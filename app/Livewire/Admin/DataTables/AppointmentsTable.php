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
                ->sortable()
                ->excludeFromColumnSelect(),
            Column::make("Paciente", "patient.user.name")
                ->sortable()
                ->searchable()
                ->excludeFromColumnSelect(),
            Column::make("Doctor", "doctor.user.name")
                ->sortable()
                ->excludeFromColumnSelect(),
            Column::make("Fecha", "date")
                ->format(
                    fn($value) =>
                    $value->format('d/m/Y')
                )
                ->sortable()
                ->excludeFromColumnSelect(),
            Column::make("Hora", "start_time")
                ->sortable()
                ->excludeFromColumnSelect(),

            Column::make("Hora de fin", "end_time")
                ->sortable()
                ->excludeFromColumnSelect(),
            Column::make("Estado", "status")
                ->format(function ($value, $row) {
                    return $row->status->label();
                })
                ->excludeFromColumnSelect(),
            Column::make("Acciones")
                ->label(function ($row) {
                    return view('admin.appointments.actions', ['appointment' => $row]);
                })
                ->excludeFromColumnSelect(),

        ];
    }
}
