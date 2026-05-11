<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DoctorTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Doctor::query()->with(['user', 'unavailabilities' => fn ($q) => $q->where('end_date', '>=', now())]);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable(),
            Column::make('Nombre', 'user.name')
                ->searchable()
                ->sortable(),
            Column::make('DNI', 'user.dni')
                ->sortable(),
            Column::make('Teléfono', 'user.phone')
                ->format(fn ($value) => format_phone_compact($value))
                ->sortable(),
            Column::make('Especialidad', 'speciality.name')
                ->searchable()
                ->format(function ($value) {
                    return $value ?: 'N/A';
                })
                ->sortable(),
            Column::make('Bloqueos')
                ->label(function (Doctor $row) {
                    $activeBlocks = $row->unavailabilities->filter(
                        fn ($u) => $u->start_date->lte(now()) && $u->end_date->gte(now())
                    );
                    $upcomingCount = $row->unavailabilities->count();

                    if ($activeBlocks->isNotEmpty()) {
                        return '<span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">
                            <span class="relative flex h-1.5 w-1.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-red-500"></span>
                            </span>
                            Bloqueado
                        </span>';
                    }

                    if ($upcomingCount > 0) {
                        return '<span class="inline-flex items-center rounded-full bg-orange-100 px-2 py-0.5 text-xs font-medium text-orange-700">'
                            .$upcomingCount.' próximo'.($upcomingCount > 1 ? 's' : '').'</span>';
                    }

                    return '<span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Disponible</span>';
                })
                ->html(),
            Column::make('Acciones')
                ->label(function ($row) {
                    return view('admin.doctors.actions', ['doctor' => $row]);
                }),
        ];
    }
}
