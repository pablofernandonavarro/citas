<x-admin-layout
title="Turnos Disponibles" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Turnos Disponibles',
        'href' => route('admin.appointments.available'),
    ],
]">
    <x-wireui-card>
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800">
                Turnos Liberados Disponibles para Asignar
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                Estos turnos fueron cancelados y están disponibles para ser asignados a otros pacientes
            </p>
        </div>

        @if($appointments->count() > 0)
            <div class="space-y-4">
                @foreach($appointments as $appointment)
                <div class="border rounded-lg p-4 bg-white shadow-sm hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-lg font-semibold text-gray-900">
                                    📅 {{ $appointment->date->format('d/m/Y') }}
                                </span>
                                <span class="text-gray-600">
                                    🕐 {{ $appointment->start_time }} - {{ $appointment->end_time }}
                                </span>
                                @php
                                    $statusColor = $appointment->status->color();
                                    $statusLabel = $appointment->status->label();
                                @endphp
                                <x-wireui-badge :flat="true" :color="$statusColor" :label="$statusLabel" />
                            </div>
                            
                            <div class="text-sm text-gray-700">
                                <p><strong>Médico:</strong> {{ $appointment->doctor->user->name }}</p>
                                <p><strong>Especialidad:</strong> {{ $appointment->doctor->speciality->name ?? 'N/A' }}</p>
                                <p><strong>Duración:</strong> {{ $appointment->duration }} minutos</p>
                            </div>
                        </div>
                        
                        <div>
                            <form action="{{ route('admin.appointments.assign', $appointment) }}" method="POST" class="inline">
                                @csrf
                                <div class="mb-3">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        Seleccionar Paciente:
                                    </label>
                                    <select 
                                        name="patient_id" 
                                        class="w-64 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                        required
                                    >
                                        <option value="">-- Seleccione --</option>
                                        @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}">
                                                {{ $patient->user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <x-wireui-button primary type="submit" xs>
                                    Asignar Paciente
                                </x-wireui-button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-lg">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No hay turnos disponibles</h3>
                <p class="mt-2 text-sm text-gray-500">
                    No existen turnos liberados en este momento.
                </p>
            </div>
        @endif
    </x-wireui-card>
</x-admin-layout>
