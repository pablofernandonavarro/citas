<x-admin-layout
title="Turnos" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Turnos',
        'href' => route('admin.appointments.index'),
    ],
    
   
]">
  <x-slot name="action">
        <x-wireui-button primary type="button" blue >
            <a href="{{ route('admin.appointments.create') }}">
                <i class="fas fa-plus mr-2"></i>
                Nuevo Turno
            </a>
        </x-wireui-button>
    </x-slot>
   
    <x-wireui-card>
        @livewire('admin.dataTables.appointments-table')
       
    </x-wireui-card>



</x-admin-layout>