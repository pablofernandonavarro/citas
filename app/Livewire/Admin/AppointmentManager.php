<?php

namespace App\Livewire\Admin;

use App\Models\Speciality;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

class AppointmentManager extends Component
{
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
        'rason' => '',

    ];

    public function mount()
    {
        $this->specialities = Speciality::all();
        $this->search['date'] = now()->hour >= 12
         ? now()->addDay()->format('Y-m-d')
         : now()->format('Y-m-d');
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
                Rule::when($this->search['date'] === now()->format('Y-m-d'),
                    ['after_or_equal:'.now()->format('H:i:s')]
                ),
            ],
            'search.speciality_id' => 'nullable|exists:specialities,id',
        ]);
        $this->appointment['date'] = $this->search['date'];
        // buscar disponibilidad de turnos
        $this->availabilities = $Service->searchAvailability(...$this->search);

    }
}
