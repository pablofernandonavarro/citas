<x-admin-layout
title="Turnos" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Turnos',
        'href' => route('admin.appointments.index', $appointment),
    ],
    [
        'name' => 'Consulta',
        'href' => route('admin.appointments.consultation', $appointment),
    ],



]">

    <x-wireui-card>
        @livewire('admin.Consultation-Manager', ['appointment' => $appointment],)

    </x-wireui-card>



</x-admin-layout>
