<x-admin-layout
title="Turnos Disponibles" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Turnos Disponibles',
        'href' => route('admin.appointments.available'),
    ],
]">
    <x-wireui-card>
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-gray-800">
                Turnos Liberados Disponibles para Asignar
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                Estos turnos fueron cancelados y están disponibles para ser asignados a otros pacientes
            </p>
        </div>

        @if($appointments->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha y Hora
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Médico
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Especialidad
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Duración
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($appointments as $appointment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $appointment->date->format('d/m/Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $appointment->start_time }} - {{ $appointment->end_time }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $appointment->doctor->user->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $appointment->doctor->speciality->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $appointment->duration }} min
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColor = $appointment->status->color();
                                    $statusLabel = $appointment->status->label();
                                @endphp
                                <x-wireui-badge :flat="true" :color="$statusColor" :label="$statusLabel" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <x-wireui-button 
                                    xs 
                                    primary 
                                    x-on:click="$openModal('assign-modal-{{ $appointment->id }}')"
                                >
                                    Asignar Paciente
                                </x-wireui-button>
                            </td>
                        </tr>

                        {{-- Modal para asignar paciente --}}
                        <x-wireui-modal name="assign-modal-{{ $appointment->id }}" align="center">
                            <x-wireui-card title="Asignar Turno a Paciente">
                                <p class="mb-4 text-sm text-gray-600">
                                    Turno: {{ $appointment->date->format('d/m/Y') }} a las {{ $appointment->start_time }}
                                    <br>
                                    Médico: {{ $appointment->doctor->user->name }}
                                </p>

                                <form action="{{ route('admin.appointments.assign', $appointment) }}" method="POST">
                                    @csrf
                                    
                                    <div class="mb-4">
                                        <label for="patient_id_{{ $appointment->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                            Seleccionar Paciente
                                        </label>
                                        <select 
                                            name="patient_id" 
                                            id="patient_id_{{ $appointment->id }}"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            required
                                        >
                                            <option value="">-- Seleccione un paciente --</option>
                                            @foreach($patients as $patient)
                                                <option value="{{ $patient->id }}">
                                                    {{ $patient->user->name }} 
                                                    @if($patient->user->email)
                                                        ({{ $patient->user->email }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <x-slot name="footer">
                                        <div class="flex justify-end gap-x-4">
                                            <x-wireui-button flat label="Cancelar" x-on:click="close" />
                                            <x-wireui-button primary type="submit" label="Asignar Turno" />
                                        </div>
                                    </x-slot>
                                </form>
                            </x-wireui-card>
                        </x-wireui-modal>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay turnos disponibles</h3>
                <p class="mt-1 text-sm text-gray-500">
                    No existen turnos liberados en este momento.
                </p>
            </div>
        @endif
    </x-wireui-card>
</x-admin-layout>
