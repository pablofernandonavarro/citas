<div>
    <x-wireui-card class="mb-4">
        <div class="text-center">
            <p class="text-2xl font-semibold text-gray-800">
                Bienvenido, {{ auth()->user()->name }}
            </p>
            <p class="text-sm text-gray-600 mt-2">
                Aqui tienes un resumen de tu panel de control
            </p>
            <div class="flex justify-center mt-4">
                <x-wireui-button
                    href="{{ route('admin.appointments.create') }}"
                    primary
                >
                    Reservar un Turno
                </x-wireui-button>   
            </div>
        </div>
    </x-wireui-card>

    {{-- Próximo Turno --}}
    @if(isset($data['next_appointment']) && $data['next_appointment'])
    <x-wireui-card class="mb-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            📅 Tu Próximo Turno
        </h3>
        <div class="border-l-4 border-blue-500 pl-4 py-2 mb-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xl font-semibold text-gray-900">
                        {{ $data['next_appointment']->date->format('d/m/Y') }}
                    </p>
                    <p class="text-gray-600">
                        🕒 {{ $data['next_appointment']->start_time }} - {{ $data['next_appointment']->end_time }}
                    </p>
                    <p class="text-gray-700 mt-2">
                        <strong>Médico:</strong> {{ $data['next_appointment']->doctor->user->name }}
                    </p>
                    @if($data['next_appointment']->doctor->speciality)
                    <p class="text-sm text-gray-600">
                        {{ $data['next_appointment']->doctor->speciality->name }}
                    </p>
                    @endif
                    @if($data['next_appointment']->reason)
                    <p class="text-sm text-gray-600 mt-1">
                        <strong>Motivo:</strong> {{ $data['next_appointment']->reason }}
                    </p>
                    @endif
                </div>
                <div>
                    <x-wireui-badge color="blue" label="Programado" />
                </div>
            </div>
        </div>
        <div class="flex justify-end">
            <x-wireui-button 
                primary 
                href="{{ route('admin.appointments.show', $data['next_appointment']) }}"
                sm
            >
                Ver Detalle del Turno
            </x-wireui-button>
        </div>
    </x-wireui-card>
    @else
    <x-wireui-card class="mb-4">
        <div class="text-center py-6">
            <p class="text-gray-600">
                No tienes turnos próximos programados
            </p>
            <x-wireui-button
                href="{{ route('admin.appointments.create') }}"
                primary
                class="mt-3"
                xs
            >
                Reservar un Turno
            </x-wireui-button>
        </div>
    </x-wireui-card>
    @endif

    {{-- Turnos Pasados --}}
    @if(isset($data['past_appointments']) && $data['past_appointments']->count() > 0)
    <x-wireui-card>
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
            📋 Historial de Turnos (Últimos 10)
        </h3>
        <div class="space-y-3">
            @foreach($data['past_appointments'] as $appointment)
            <div class="border rounded-lg p-4 bg-gray-50 hover:bg-gray-100 transition">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <p class="font-semibold text-gray-900">
                                {{ $appointment->date->format('d/m/Y') }}
                            </p>
                            <span class="text-gray-400">•</span>
                            <p class="text-gray-700">
                                {{ $appointment->start_time }} - {{ $appointment->end_time }}
                            </p>
                        </div>
                        <p class="text-sm text-gray-600">
                            <strong>Médico:</strong> Dr. {{ $appointment->doctor->user->name }}
                        </p>
                        @if($appointment->doctor->speciality)
                        <p class="text-xs text-gray-500">
                            {{ $appointment->doctor->speciality->name }}
                        </p>
                        @endif
                        @if($appointment->reason)
                        <p class="text-xs text-gray-600 mt-1">
                            <strong>Motivo:</strong> {{ Str::limit($appointment->reason, 50) }}
                        </p>
                        @endif
                    </div>
                    <div class="ml-4">
                        @php
                            $statusColor = $appointment->status->color();
                            $statusLabel = $appointment->status->label();
                        @endphp
                        <x-wireui-badge :color="$statusColor" :label="$statusLabel" xs />
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </x-wireui-card>
    @else
    <x-wireui-card>
        <div class="text-center py-6">
            <p class="text-gray-500 text-sm">
                No tienes turnos pasados registrados
            </p>
        </div>
    </x-wireui-card>
    @endif

</div>
