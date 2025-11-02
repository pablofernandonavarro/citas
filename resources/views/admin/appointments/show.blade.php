<x-admin-layout
title="Turnos" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Turnos',
        'href' => route('admin.appointments.index'),
    ],
    [
        'name' => 'Detalle',
        'href' => route('admin.appointments.show', $appointment),
    ],  
    
   
]">
   
    <x-wireui-card>
        {{-- Tabla de doctores --}}
       
    </x-wireui-card>



</x-admin-layout>