<x-admin-layout
title="Turnos" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Turnos',
        'href' => route('admin.appointments.index'),
    ],
    [
        'name' => 'Crear',
        'href' => route('admin.appointments.create'),
    ],
    
    
   
]">
   
    <x-wireui-card>
     @livewire("admin.AppointmentManager")
       
    </x-wireui-card>



</x-admin-layout>