<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Carbon\CarbonPeriod;
use Livewire\Attributes\Computed;
use Carbon\Carbon;
use App\Models\Speciality;
use Illuminate\Validation\Rule;
use App\Services\AppointmentService;

use function Livewire\Volt\rules;

class AppointmentManager extends Component
{  
    public $search = [
        'date' => null,
        'hour' => null,
        'speciality_id' => null,
    ];
    public $specialities = [];

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
               "1 hour",
               Carbon::createFromTimeString(config('schedule.end_time'))
           );
   }
    public function render()
    {
        return view('livewire.admin.appointment-manager');
    }
    public function searchAvailability(AppointmentService $appointmentService)

  
    {
    
      //  dd($this->search);
      $this->validate([
          'search.date' => 'required|date|after_or_equal:today',
          'search.hour' => [
            'required',
            'date_format:H:i:s',
            Rule::when($this->search['date'] === now()->format('Y-m-d'),
                ['after_or_equal:' . now()->format('H:i:s')]
            ),
          ],
          'search.speciality_id' => 'nullable|exists:specialities,id',
      ]);
        $appointmentService->searchAvailability(
          $this->search['date'],
          $this->search['hour'],
          $this->search['speciality_id']
      );

    }
}
