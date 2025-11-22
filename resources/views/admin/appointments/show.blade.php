<x-admin-layout title="Turnos" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Turnos',
        'href' => route('admin.appointments.index'),
    ],
    [
        'name' => 'Detalle',
        'href' => route('admin.appointments.show', $appointment),
    ],
]">

    <x-wireui-card>
        <x-slot name="title">
            <h1 class="text-2xl font-bold text-gray-800">
                Detalle del Turno
            </h1>
            <p class="text-sm text-gray-500">
                fecha : {{ $appointment->date->format('d/m/y') }}
            </p>
        </x-slot>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <h2 class="font-semibold text-gray-500 text-xs mb-2 uppercase ">
                    Paciente :
                </h2>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $appointment->patient->user->name }}
                </p>
                <p class="text-lg font-semibold text-gray-500">
                    {{ $appointment->patient->user->email }}
                </p>
            </div>
            <div>
                <h2 class="font-semibold text-gray-500 text-xs mb-2 uppercase ">
                    Médico :
                </h2>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $appointment->doctor->user->name }}
                </p>
                <p class="text-lg font-semibold text-gray-500">
                    {{ $appointment->doctor->speciality->name ?? 'Sin especialidad' }}
                </p>

            </div>
        </div>
        <hr class="my-6">
        <div>
            <h3 class="font-semibold text-gray-500 mb-2">
                Diagnostico :
            </h3>
            <p>
                {{ $appointment->consultation->diagnosis ?? 'no disponible' }}
            </p>
        </div>
        <div>
            <h3 class="font-semibold text-gray-500 mb-2 mt-3">
                Plan del Tratamiento :
            </h3>
            <p>
                {{ $appointment->consultation->treatment ?? 'no disponible' }}
            </p>
        </div>
        <hr class="my-6">
        <div>
            <h3 class="font-semibold text-gray-500 mb-2">
                Receta medica :
            </h3>
            @forelse ($appointment->consultation->prescriptions ?? [] as $prescription)
                <li>
                    <p>
                        <strong>Medicamento:</strong> {{ $prescription['medicine'] ?? 'N/A' }}<br>
                        <strong>Dosis:</strong> {{ $prescription['dosage'] ?? 'N/A' }}<br>
                        <strong>Frecuencia:</strong> {{ $prescription['frequency'] ?? 'N/A' }}<br>
                        <strong>Duración:</strong> {{ $prescription['duration'] ?? 'N/A' }}
                    </p>
                </li>
            @empty
                <p class="text-gray-500">No hay recetas registradas</p>
            @endforelse

        </div>
        @role('Doctor')
            <hr class="my-6">
            <div>
                <h3 class="font-semibold text-gray-500 mb-2">
                    Nota del medico:
                </h3>
                <p>
                     {{ $appointment->consultation->notes ?? 'no disponible' }}
                </p>
            </div>
        @endrole

    </x-wireui-card>

</x-admin-layout>
