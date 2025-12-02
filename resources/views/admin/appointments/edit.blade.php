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
        <div class="flex gap-2">
            @if($appointment->status->value === 3)
                {{-- Si el turno está cancelado, mostrar botón para liberar --}}
                <form action="{{ route('admin.appointments.release', $appointment) }}" method="post">
                    @csrf
                    <x-wireui-button yellow type="submit" sm>
                        Liberar Turno
                    </x-wireui-button>
                </form>
            @endif
            
            @if($appointment->status->isEditable())
                <form action="{{ route('admin.appointments.destroy',$appointment) }}" method="post">
                    @csrf
                    @method('delete')
                    <x-wireui-button red type="submit" sm>
                        Cancelar Turno
                    </x-wireui-button>
                </form>
            @endif
        </div>
    </x-slot>

    <x-wireui-card class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                @if($appointment->patient)
                    <p>
                        Editar Turno para :
                        <span class="font-semibold  text-indigo-600">
                            {{ $appointment->patient->user->name }}
                        </span>
                    </p>
                @else
                    <p>
                        <span class="font-semibold text-yellow-600">
                            Turno Disponible (Sin paciente asignado)
                        </span>
                    </p>
                @endif
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

    {{-- Sección de Gabinete (solo si el doctor tiene gabinetes asignados) --}}
    @if($appointment->doctor->cabinets->count() > 0)
        <x-wireui-card class="mb-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        <i class="fas fa-door-open mr-2"></i>
                        Gabinete
                    </h3>
                    @if($appointment->cabinet)
                        <p class="text-sm text-gray-600">
                            Gabinete asignado: 
                            <span class="font-semibold text-green-600">{{ $appointment->cabinet->name }}</span>
                        </p>
                        @if($appointment->cabinet->description)
                            <p class="text-xs text-gray-500 mt-1">{{ $appointment->cabinet->description }}</p>
                        @endif
                    @else
                        <p class="text-sm text-yellow-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            Sin gabinete asignado
                        </p>
                    @endif
                </div>
                
                @if($appointment->status->isEditable())
                    <div class="ml-4">
                        <form action="{{ route('admin.appointments.assign.cabinet', $appointment) }}" method="POST" class="flex gap-2">
                            @csrf
                            <select name="cabinet_id" required
                                class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="" disabled {{ !$appointment->cabinet_id ? 'selected' : '' }}>
                                    Seleccionar gabinete
                                </option>
                                @foreach($appointment->doctor->cabinets as $cabinet)
                                    <option value="{{ $cabinet->id }}" 
                                        {{ $appointment->cabinet_id == $cabinet->id ? 'selected' : '' }}>
                                        {{ $cabinet->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-wireui-button primary type="submit" sm>
                                <i class="fas fa-check mr-1"></i>
                                Asignar
                            </x-wireui-button>
                        </form>
                    </div>
                @endif
            </div>
        </x-wireui-card>
    @endif

    @if ($appointment->status->isEditable())
        @livewire('admin.appointment-manager', ['appointmentEdit' => $appointment])
    @elseif($appointment->status->isAvailable())
        <x-wireui-card>
            <div class="text-center py-6">
                <p class="text-lg font-semibold text-yellow-600 mb-2">Este turno está disponible</p>
                <p class="text-sm text-gray-600 mb-4">Puede ser asignado a un nuevo paciente desde la lista de turnos disponibles</p>
                <a href="{{ route('admin.appointments.available') }}">
                    <x-wireui-button primary>
                        Ver Turnos Disponibles
                    </x-wireui-button>
                </a>
            </div>
        </x-wireui-card>
    @else
        <x-wireui-card>
            <p class="text-center">Este Turno ha sido completado o cancelado</p>
        </x-wireui-card>
    @endif



</x-admin-layout>
