<?php

namespace App\Livewire\Admin;

use App\Models\Doctor;
use App\Models\Schedule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;
use Livewire\Attributes\Computed;



class ScheduleManager extends Component
{
    public Doctor $doctor;
    
    public $schedules = [];
    public $days = [];
  
    public $appointments_duration;
    public $intervals = [];
    public $schedule = [];
    public $start_time;
    public $end_time;

#[computed()]    
   public function hourBlocks()
   {
       return CarbonPeriod::create(
               Carbon::createFromTimeString($this->start_time),
               "1 hour",
               Carbon::createFromTimeString($this->end_time)
           );
   }
   public function mount()
   {
       $this->days = config('schedule.days');
       $this->start_time = config('schedule.start_time');
       $this->end_time = config('schedule.end_time');
       $this->appointments_duration = config('schedule.appointments_duration');
       $this->intervals = 60 / $this->appointments_duration;
       $this->initializeSchedules();
   }

   public function initializeSchedules()
   {
       $schedules = $this->doctor->schedules;
       $maxTime = Carbon::createFromTimeString("18:00:00");

       foreach($this->hourBlocks as $hourBlock){
           $period = CarbonPeriod::create(
               $hourBlock->copy(),
               $this->appointments_duration . " minutes",
                $hourBlock->copy()->addHour()
           );
            foreach($period as $time) {
                // No generar intervalos después de las 18:00
                if ($time->greaterThanOrEqualTo($maxTime)) {
                    break;
                }

                foreach($this->days as $index => $day) {
                $this->schedule[$index][$time->format('H:i:s')] = $schedules->contains(function($schedule) use ($index, $time) {
                    return $schedule->day_of_week == $index && $schedule->start_time == $time->format('H:i:s');
                  
                 });
                }
            }
   
        }
       
    }

    public function save()
    {
        // Eliminar todos los horarios existentes del doctor
        $this->doctor->schedules()->delete();

        // Recorrer el schedule y crear los nuevos horarios
        foreach ($this->schedule as $day_of_week => $intervals) {
            foreach ($intervals as $start_time => $isChecked) {
                if($isChecked) {
                   // Saltar si no está seleccionado
               
                Schedule::create([
                    'doctor_id' => $this->doctor->id,
                    'day_of_week' => $day_of_week,
                    'start_time' => $start_time,
                    // 'end_time' => Carbon::createFromFormat('H:i:s', $start_time)
                    //                 ->addMinutes($this->appointments_duration)
                    //                 ->format('H:i:s'),
                ]);
                 }
            }
        }
       $this->dispatch('swal', [
           'title' => 'Horarios guardados',
           'text' => 'Los horarios del doctor han sido actualizados correctamente.',
           'icon' => 'success',
       ]);
      
    }

    public function render()
 {
        return view('livewire.admin.schedule-manager');
    }
}
