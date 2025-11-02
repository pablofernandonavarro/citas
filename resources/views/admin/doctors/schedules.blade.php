<x-admin-layout title="Doctores" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Doctores',
        'href' => route('admin.doctors.index'),
    ],
    [
        'name' => 'Horarios del Doctor',
        'href' => route('admin.doctors.schedules', $doctor),
    ],
]">



    
 @livewire('admin.schedule-manager', ['doctor' => $doctor]) 


</x-admin-layout>

