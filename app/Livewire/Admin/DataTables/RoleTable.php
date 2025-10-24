<?php

namespace App\Livewire\Admin\DataTables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Spatie\Permission\Models\Role;

class RoleTable extends DataTableComponent
{
    protected $model = Role::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Nombre", "name")
                ->sortable()
                ->searchable(),
            Column::make("Creado en", "created_at")
                ->sortable(),
            Column::make("Actualizado en", "updated_at")
                ->sortable()
                ->format(function($value) {
                    return $value->format('d/m/Y H:i:s');
                }),
            Column::make('Acciones')
                ->label(function($row) {
                    return view('admin.roles.actions',
                     ['role' => $row]);
                }),
        ];
    }
}
