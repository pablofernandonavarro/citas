<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Speciality;

class SpecialityTable extends DataTableComponent
{
    protected $model = Speciality::class;

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
            Column::make("Nombre", "name")
                ->searchable()
                ->sortable()
                ->excludeFromColumnSelect(),
            Column::make("Creado en", "created_at")
                ->sortable()
                ->excludeFromColumnSelect(),
            Column::make("Actualizado en", "updated_at")
                ->sortable()
                ->excludeFromColumnSelect(),
           Column::make('Acciones')
                ->label(function($row) {
                    return view('admin.specialities.actions',
                     ['speciality' => $row]);
                })
                ->excludeFromColumnSelect(),
        ];
    }
}
