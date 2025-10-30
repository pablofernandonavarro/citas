<x-admin-layout
title="Doctores" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Doctores',
        'href' => route('admin.doctors.index'),
    ],
    
   
]">
   
    <x-wireui-card>
        {{-- Tabla de doctores --}}
        @livewire("admin.datatables.doctor-table")
    </x-wireui-card>



</x-admin-layout>