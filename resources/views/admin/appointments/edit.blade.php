<x-admin-layout title="Turnos" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Turnos',
        'href' => route('admin.appointments.index'),
    ],
    [
        'name' => 'Editar Turno',
        'href' => route('admin.appointments.edit', $appointment),
    ],
]">
    <x-wireui-card class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <p>
                    Editar Turno para :
                    <span class="font-semibold  text-indigo-600">
                        {{ $appointment->patient->user->name }}
                    </span>
                </p>
                <p class="text-sm text-slate-500">
                    <span> Fecha del turno:</span>
                    <span class="text-slate-900">{{ $appointment->date->format('d/m/Y') }} a las</span>
                    <span class="text-slate-900">{{ $appointment->start_time }} - {{ $appointment->end_time }}</span>
                </p>

            </div>
            <div class="">
                @php
                    $statusColor = $appointment->status->color();
                    $statusLabel = $appointment->status->label();
                @endphp
                <x-wireui-badge 
                    :flat="true" 
                    :color="$statusColor" 
                    :label="$statusLabel" 
                />
            </div>
        </div>
    </x-wireui-card>

    @livewire('admin.appointment-manager', ['appointmentEdit' => $appointment])



</x-admin-layout>
