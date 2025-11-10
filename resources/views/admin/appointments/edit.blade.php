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



    <x-slot name="action">
        <form action="{{ route('admin.appointments.destroy',$appointment) }}" method="post">
            @csrf
            @method('delete')


            <x-wireui-button red type="submit" sm>
                Cancelar Turno
            </x-wireui-button>
        </form>
    </x-slot>

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
                <x-wireui-badge :flat="true" :color="$statusColor" :label="$statusLabel" />
            </div>
        </div>
    </x-wireui-card>

    @if ($appointment->status->isEditable())
        @livewire('admin.appointment-manager', ['appointmentEdit' => $appointment])
    @else
        <x-wireui-card>
            <p class="text-center">Este Turno a si completado o cancelado</p>
        </x-wireui-card>
    @endif



</x-admin-layout>
