<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Patient;
use Livewire\Component;

class ConsultationManager extends Component
{

    public Appointment $appointment;
    public Consultation $consultation;
    public ?Consultation $lastConsultation;
    public Patient $patient;
    public $form = [
        'diagnosis' => '',
        'treatment' => '',
        'notes' => '',
        'prescriptions' => [],
    ];


    public function mount(Appointment $appointment)
    {
        $appointment->load(['doctor.cabinets', 'cabinet']);
        
        $this->consultation = $appointment->consultation ?? new Consultation();
        $this->form = [
            'diagnosis' => $this->consultation->diagnosis ?? '',
            'treatment' => $this->consultation->treatment ?? '',
            'notes' => $this->consultation->notes ?? '',
            'prescriptions' => $this->consultation->prescriptions ?? [],
        ];
        $this->patient = $appointment->patient;
        $this->lastConsultation = Consultation::whereHas('appointment', function ($query) use ($appointment) {
            $query->where('patient_id', $appointment->patient_id)
                  ->where('id', '!=', $this->consultation->id ?? 0);
        })->latest()->first();
    }

    public function addPrescription()
    {
        $this->form['prescriptions'][] = ['medicine' => '', 'dosage' => '', 'frequency' => ''];
    }
    public function removePrescription($index)
    {
        unset($this->form['prescriptions'][$index]);
        $this->form['prescriptions'] = array_values($this->form['prescriptions']);
    }

    public function save()
    {
        $this->validate([
            'form.diagnosis' => 'required|string|max:5000',
            'form.treatment' => 'required|string|max:5000',
            'form.notes' => 'nullable|string|max:5000',
            'form.prescriptions' => 'nullable|array',
            'form.prescriptions.*.medicine' => 'required|string|max:255',
            'form.prescriptions.*.dosage' => 'required|string|max:255',
            'form.prescriptions.*.frequency' => 'required|string|max:255',
        ]);

        if ($this->consultation->exists) {
            $this->consultation->update([
                'diagnosis' => $this->form['diagnosis'],
                'treatment' => $this->form['treatment'],
                'notes' => $this->form['notes'],
                'prescriptions' => $this->form['prescriptions'],
            ]);
        } else {
            $this->appointment->consultation()->create([
                'diagnosis' => $this->form['diagnosis'],
                'treatment' => $this->form['treatment'],
                'notes' => $this->form['notes'],
                'prescriptions' => $this->form['prescriptions'],
            ]);
        }

        $this->appointment->update([
            'status' => \App\Enums\AppointmentEnum::COMPLETED,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Consulta guardada',
            'text' => 'La consulta se ha guardado correctamente.',
        ]);

        return redirect()->route('admin.appointments.consultation', $this->appointment);
    }


    public function render()
    {
        return view('livewire.admin.consultation-manager');
    }
}
