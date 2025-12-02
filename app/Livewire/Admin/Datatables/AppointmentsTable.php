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
       return  Appointment::query()
            ->with('patient.user', 'doctor.user', 'cabinet');

    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setColumnSelectStatus(true);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->excludeFromColumnSelect(),
            Column::make("Paciente", "patient.user.name")
                ->sortable()
                ->searchable(),
            Column::make("Doctor", "doctor.user.name")
                ->sortable(),
            Column::make("Fecha", "date")
                ->format(
                    fn($value) =>
                    $value->format('d/m/Y')
                )
                ->sortable(),
            Column::make("Hora", "start_time")
                ->sortable(),
            Column::make("Gabinete", "cabinet_id")
                ->format(function ($value, $row) {
                    if ($row->cabinet) {
                        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">' . 
                               '<i class="fas fa-door-open mr-1"></i> ' . 
                               htmlspecialchars($row->cabinet->name) . 
                               '</span>';
                    }
                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">' . 
                           'Sin asignar' . 
                           '</span>';
                })
                ->html(),
            Column::make("Estado", "status")
                ->format(function ($value, $row) {
                    $statusColors = [
                        1 => 'bg-blue-100 text-blue-800',    // Programado
                        2 => 'bg-green-100 text-green-800',  // Completado
                        3 => 'bg-red-100 text-red-800',      // Cancelado
                        4 => 'bg-yellow-100 text-yellow-800', // Disponible
                    ];
                    
                    $color = $statusColors[$row->status->value] ?? 'bg-gray-100 text-gray-800';
                    
                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $color . '">' . 
                           htmlspecialchars($row->status->label()) . 
                           '</span>';
                })
                ->html()
                ->searchable(function (Builder $query, $search) {
                    $statusMap = [
                        'programado' => 1,
                        'completado' => 2,
                        'cancelado' => 3,
                        'disponible' => 4,
                    ];
                    
                    $searchLower = mb_strtolower($search);
                    
                    foreach ($statusMap as $label => $value) {
                        if (str_contains($label, $searchLower)) {
                            return $query->orWhere('status', $value);
                        }
                    }
                    
                    return $query;
                }),
            Column::make("Acciones")
                ->label(function ($row) {
                    return view('admin.appointments.actions', ['appointment' => $row]);
                })
                ->excludeFromColumnSelect(),

        ];
    }
}
