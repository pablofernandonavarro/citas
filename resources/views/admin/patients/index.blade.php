<x-admin-layout title="Pacientes" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Pacientes',
    ],
]">
    <x-slot name="action">
       
    </x-slot>

   @livewire('Admin.Datatables.Patient-Table')
</x-admin-layout>
