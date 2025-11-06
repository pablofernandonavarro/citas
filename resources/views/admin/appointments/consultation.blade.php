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

    @livewire('admin.Consultation-Manager', ['appointment' => $appointment],)

</x-admin-layout>
