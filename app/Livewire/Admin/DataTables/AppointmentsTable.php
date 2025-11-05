<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;

class AppointmentsTable extends DataTableComponent
{
     public function builder(): Builder
     {
             return Appointment::query()
                  ->with('patient.user', 'doctor.user');
     }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Patient", "patient.user.name")
                ->sortable()
                ->searchable(),
            Column::make("Doctor", "doctor.user.name")
                ->sortable(),
            Column::make("Fecha", "date")
                ->format(fn ($value) =>
                    $value->format('d/m/Y')
                )
                ->sortable(),
            Column::make("Hora", "start_time") 
                ->sortable(),
            Column::make("Hora de fin", "end_time")
                ->sortable(),
             Column::make("Acciones")
                ->label(function($row) {
                    return view('admin.appointments.actions', ['appointment' => $row]);
                }),
           
        ];
    }
}
