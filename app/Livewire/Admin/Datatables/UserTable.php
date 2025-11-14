<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class UserTable extends DataTableComponent
{
    // protected $model = User::class;
    public function builder(): Builder
    {
        return User::query()->with('roles');
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
            Column::make('Nombre', 'name')
                ->searchable()
                ->sortable(),
            Column::make('Correo Electrónico', 'email')
                ->sortable(),
            Column::make('Teléfono', 'phone')
                ->sortable(),
            Column::make('DNI', 'dni')
                ->searchable()
                ->sortable(),
            Column::make('Rol', 'roles')
                ->label(
                    function ($row) {
                        return $row->roles->first()->name ?? 'Sin Rol';
                    }
                ),
            Column::make('Creado en', 'created_at')
                ->sortable(),
            Column::make('Actualizado en', 'updated_at')
                ->sortable(),
            Column::make('Acciones')
                ->label(function ($row) {
                    return view('admin.users.actions',
                        ['user' => $row]);
                }),
        ];
    }
}
