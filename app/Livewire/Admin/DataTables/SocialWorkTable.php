<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\SocialWork;

class SocialWorkTable extends DataTableComponent
{
    protected $model = SocialWork::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Name", "name")
                ->searchable()
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
           Column::make('Acciones')
                ->label(function($row) {
                    return view('admin.socialworks.actions',
                     ['socialwork' => $row]);
                }),
        ];
    }
}
