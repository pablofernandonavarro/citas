<?php

namespace App\Livewire\Admin;

use App;
use App\Models\Speciality;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\Appointment;


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

      if(auth()->user()->hasRole('Paciente')){
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
            $this->appointment['start_time'] = $schedules->first();
            $this->appointment['end_time'] = Carbon::parse($schedules->last())->addMinutes(config('schedule.appointments_duration'))->format('H:i:s');
            $this->appointment['duration'] = $schedules->count() * config('schedule.appointments_duration');
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
                    ['after_or_equal:' . now()->format('H:i:s')]
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
        if ($this->appointmentEdit) {
            $this->appointmentEdit->update($this->appointment);
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Cita actualizada con exito',
                'text' => 'La cita se ha actualizado correctamente',
            ]);
            $this->searchAvailability(new AppointmentService());
            return;

        }
        // Verificar que no exista otra cita en el mismo horario
        // Dos citas se solapan si:
        // - La nueva cita empieza antes de que termine la existente Y
        // - La nueva cita termina después de que empiece la existente
        $existingAppointment = Appointment::where('doctor_id', $this->appointment['doctor_id'])
            ->whereDate('date', $this->appointment['date'])
            ->where(function ($query) {
                $query->where('start_time', '<', $this->appointment['end_time'])
                    ->where('end_time', '>', $this->appointment['start_time']);
            })
            ->exists();

        if ($existingAppointment) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Horario no disponible',
                'text' => 'Ya existe una cita en este horario para el doctor seleccionado.',
            ]);
            return;
        }

        //guardar la cita
        Appointment::create($this->appointment)
            ->consultation()
            ->create([]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Cita creada con exito',
            'text' => 'La cita se ha creado correctamente',
        ]);
        return redirect()->route('admin.appointments.index');



    }
}
