<?php

namespace App\Livewire\Admin;

use App\Mail\AppointmentCreatedDoctor;
use App\Mail\AppointmentCreatedPatient;
use App\Models\Appointment;
use App\Models\Speciality;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

class AppointmentManager extends Component
{
    public ?Appointment $appointmentEdit = null;

    public $search = [
        'date' => null,
        'hour' => null,
        'speciality_id' => null,
    ];

    public $selectedSchedules = [
        'doctor_id' => '',
        'schedule' => [],
    ];

    public $specialities = [];

    public $availabilities = [];

    public $appointment = [
        'patient_id' => '',
        'doctor_id' => '',
        'date' => '',
        'start_time' => '',
        'end_time' => '',
        'duration' => '',
        'reason' => '',

    ];

    public function mount()
    {
        $this->specialities = Speciality::all();
        $this->search['date'] = now()->hour >= 12
            ? now()->addDay()->format('Y-m-d')
            : now()->format('Y-m-d');

        if ($this->appointmentEdit) {
            $this->appointment['patient_id'] = $this->appointmentEdit->patient_id;
        }

        if (auth()->user()->hasRole('Paciente')) {
            $this->appointment['patient_id'] = auth()->user()->patient->id;
        }

    }

    public function updated($property, $value)
    {
        if ($property === 'selectedSchedules') {
            $this->fillAppointment($value);
        }
    }

    #[computed()]
    public function hourBlocks()
    {
        return CarbonPeriod::create(
            Carbon::createFromTimeString(config('schedule.start_time')),
            '1 hour',
            Carbon::createFromTimeString(config('schedule.end_time'))
        );
    }

    #[computed()]
    public function doctorName()
    {
        return $this->appointment['doctor_id']
            ? $this->availabilities->firstWhere('doctor.id', $this->appointment['doctor_id'])['doctor']['user']['name'] : 'Por Definir';
    }

    public function fillAppointment($selectedSchedules)
    {

        $schedules = collect($selectedSchedules['schedule'])
            ->sort()
            ->values();
        if ($schedules->count()) {
            $this->appointment['doctor_id'] = $selectedSchedules['doctor_id'];

            // Obtener la duración del doctor seleccionado
            $doctor = \App\Models\Doctor::find($selectedSchedules['doctor_id']);
            $appointmentDuration = $doctor ? $doctor->getAppointmentDuration() : config('schedule.appointments_duration');

            $this->appointment['start_time'] = $schedules->first();
            $this->appointment['end_time'] = Carbon::parse($schedules->last())->addMinutes($appointmentDuration)->format('H:i:s');
            $this->appointment['duration'] = $schedules->count() * $appointmentDuration;

            return;
        }
        $this->appointment['doctor_id'] = '';
        $this->appointment['start_time'] = '';
        $this->appointment['end_time'] = '';
        $this->appointment['duration'] = 0;

    }

    public function render()
    {
        return view('livewire.admin.appointment-manager');
    }

    public function searchAvailability(AppointmentService $Service)
    {

        $this->validate([
            'search.date' => 'required|date|after_or_equal:today',
            'search.hour' => [
                'required',
                'date_format:H:i:s',
                Rule::when(
                    $this->search['date'] === now()->format('Y-m-d'),
                    ['after_or_equal:'.now()->format('H:i:s')]
                ),
            ],
            'search.speciality_id' => 'nullable|exists:specialities,id',
        ]);
        $this->appointment['date'] = $this->search['date'];
        // buscar disponibilidad de turnos
        $this->availabilities = $Service->searchAvailability(...$this->search);

    }

    public function save()
    {
        $this->validate([
            'appointment.patient_id' => 'required|exists:patients,id',
            'appointment.doctor_id' => 'required|exists:doctors,id',
            'appointment.date' => 'required|date|after_or_equal:today',
            'appointment.start_time' => 'required|date_format:H:i:s',
            'appointment.end_time' => 'required|date_format:H:i:s|after:appointment.start_time',
            'appointment.duration' => 'required|integer|min:15',
            'appointment.reason' => 'nullable|string|max:500',
        ]);

        // Verificar que no exista otra cita en el mismo horario
        // Dos citas se solapan si:
        // - La nueva cita empieza antes de que termine la existente Y
        // - La nueva cita termina después de que empiece la existente

        // Contar cuántas citas ya existen en este horario
        $query = Appointment::where('doctor_id', $this->appointment['doctor_id'])
            ->whereRaw('DATE(date) = ?', [$this->appointment['date']])
            ->where(function ($query) {
                $query->where('start_time', '<', $this->appointment['end_time'])
                    ->where('end_time', '>', $this->appointment['start_time']);
            });

        // Excluir la cita actual si estamos editando
        if ($this->appointmentEdit) {
            $query->where('id', '!=', $this->appointmentEdit->id);
        }

        $existingAppointmentsCount = $query->count();

        // Obtener la cantidad de gabinetes del doctor
        $doctor = \App\Models\Doctor::with('cabinets')->find($this->appointment['doctor_id']);
        $cabinetCount = $doctor->cabinets()->count();

        // Validar según si tiene gabinetes o no
        $isTimeSlotFull = $cabinetCount > 0
            ? $existingAppointmentsCount >= $cabinetCount
            : $existingAppointmentsCount > 0;

        if ($isTimeSlotFull) {
            $message = $cabinetCount > 0
                ? "No hay gabinetes disponibles. El doctor tiene {$cabinetCount} gabinete(s) y ya hay {$existingAppointmentsCount} cita(s) en este horario."
                : 'Ya existe una cita en este horario para el doctor seleccionado.';

            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Horario no disponible',
                'text' => $message,
            ]);

            return;
        }

        if ($this->appointmentEdit) {
            $this->appointmentEdit->update($this->appointment);
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Cita actualizada con exito',
                'text' => 'La cita se ha actualizado correctamente',
            ]);
            $this->searchAvailability(new AppointmentService);

            return;
        }

        // guardar la cita
        $newAppointment = Appointment::create($this->appointment);
        $newAppointment->consultation()->create([]);

        // Cargar relaciones necesarias para los emails
        $newAppointment->load(['patient.user', 'doctor.user', 'doctor.speciality', 'cabinet']);

        // Enviar email al paciente (inmediato)
        try {
            Mail::to($newAppointment->patient->user->email)
                ->send(new AppointmentCreatedPatient($newAppointment));
        } catch (\Exception $e) {
            Log::error('Error enviando email al paciente', [
                'appointment_id' => $newAppointment->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Enviar email al doctor (inmediato)
        try {
            Mail::to($newAppointment->doctor->user->email)
                ->send(new AppointmentCreatedDoctor($newAppointment));
        } catch (\Exception $e) {
            Log::error('Error enviando email al doctor', [
                'appointment_id' => $newAppointment->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Enviar WhatsApp al paciente (inmediato)
        if ($newAppointment->patient->user->phone) {
            try {
                $whatsappSent = app(\App\Services\WhatsAppService::class)->sendAppointmentConfirmationToPatient($newAppointment);
                if (! $whatsappSent) {
                    Log::warning('WhatsApp no se pudo enviar al paciente', [
                        'appointment_id' => $newAppointment->id,
                        'phone' => $newAppointment->patient->user->phone,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Error enviando WhatsApp al paciente', [
                    'appointment_id' => $newAppointment->id,
                    'phone' => $newAppointment->patient->user->phone,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Cita creada con exito',
            'text' => 'La cita se ha creado correctamente y se han enviado las notificaciones por email y WhatsApp',
        ]);

        return redirect()->route('admin.appointments.index');

    }
}
