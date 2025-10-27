<x-admin-layout title="Pacientes" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Pacientes',
        'href' => route('admin.patients.index'),
    ],
    [
        'name' => 'Editar Paciente',
    ],
]">
    <x-slot name="action">
        <x-wireui-button primary type="button" blue >
            <a href="{{ route('admin.patients.index') }}">
                <i class="fas fa-arrow-left mr-2"></i>
               Volver a Pacientes
            </a>
        </x-wireui-button>
    </x-slot>

    <x-wireui-card>
        <div class="mb-6">
            <h3 class="text-lg font-semibold">Paciente: {{ $patient->user->name }}</h3>
            <p class="text-gray-600">DNI: {{ $patient->user->dni }}</p>
        </div>

        <form action="{{ route('admin.patients.update', $patient) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid lg:grid-cols-2 gap-4">
                <x-wireui-native-select 
                    label="Obra Social"
                    name="social_work_id"
                    class="w-full mb-4"
                >
                    <option value="">Seleccione una obra social</option>
                    @foreach ($socialWorks as $socialWork)
                        <option value="{{ $socialWork->id }}" {{ old('social_work_id', $patient->social_work_id) == $socialWork->id ? 'selected' : '' }}>
                            {{ $socialWork->name }}
                        </option>
                    @endforeach
                </x-wireui-native-select>

                <x-wireui-input 
                    label="Número de Afiliado" 
                    name="affiliate_number" 
                    placeholder="Número de afiliado a la obra social"
                    class="w-full mb-4"
                    value="{{ old('affiliate_number', $patient->affiliate_number) }}"
                />
            </div>

            <div class="grid lg:grid-cols-2 gap-4">
                <x-wireui-input 
                    label="Alergias" 
                    name="allergies" 
                    placeholder="Ingrese alergias"
                    class="w-full mb-4"
                    value="{{ old('allergies', $patient->allergies) }}"
                />

                <x-wireui-input 
                    label="Número de Historia Clínica" 
                    name="medical_record_number" 
                    placeholder="Número de historia clínica"
                    class="w-full mb-4"
                    value="{{ old('medical_record_number', $patient->medical_record_number) }}"
                />
            </div>

            <div class="grid lg:grid-cols-2 gap-4">
                <x-wireui-input 
                    label="Condiciones Crónicas" 
                    name="chronic_conditions" 
                    placeholder="Condiciones crónicas"
                    class="w-full mb-4"
                    value="{{ old('chronic_conditions', $patient->chronic_conditions) }}"
                />

                <x-wireui-input 
                    label="Historial de Cirugías" 
                    name="surgeries_history" 
                    placeholder="Historial de cirugías"
                    class="w-full mb-4"
                    value="{{ old('surgeries_history', $patient->surgeries_history) }}"
                />
            </div>

            <div class="grid lg:grid-cols-2 gap-4">
                <x-wireui-input 
                    label="Historial Familiar" 
                    name="family_history" 
                    placeholder="Historial familiar"
                    class="w-full mb-4"
                    value="{{ old('family_history', $patient->family_history) }}"
                />

                <x-wireui-input 
                    label="Condiciones Genéticas" 
                    name="genetic_conditions" 
                    placeholder="Condiciones genéticas"
                    class="w-full mb-4"
                    value="{{ old('genetic_conditions', $patient->genetic_conditions) }}"
                />
            </div>

            <div class="grid lg:grid-cols-2 gap-4">
                <x-wireui-input 
                    label="Otras Condiciones" 
                    name="other_conditions" 
                    placeholder="Otras condiciones"
                    class="w-full mb-4"
                    value="{{ old('other_conditions', $patient->other_conditions) }}"
                />

                <x-wireui-input 
                    type="date"
                    label="Fecha de Nacimiento" 
                    name="date_of_birth"
                    class="w-full mb-4"
                    value="{{ old('date_of_birth', $patient->date_of_birth) }}"
                />
            </div>

            <h3 class="text-lg font-semibold mb-4 mt-4">Contacto de Emergencia</h3>

            <div class="grid lg:grid-cols-2 gap-4">
                <x-wireui-input 
                    label="Nombre" 
                    name="emergency_contact_name" 
                    placeholder="Nombre del contacto"
                    class="w-full mb-4"
                    value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}"
                />

                <x-wireui-input 
                    label="Teléfono" 
                    name="emergency_contact_phone" 
                    placeholder="Teléfono del contacto"
                    class="w-full mb-4"
                    value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}"
                />
            </div>

            <x-wireui-input 
                label="Relación" 
                name="emergency_contact_relationship" 
                placeholder="Relación con el paciente"
                class="w-full mb-4"
                value="{{ old('emergency_contact_relationship', $patient->emergency_contact_relationship) }}"
            />

            <x-wireui-button primary type="submit" class="mt-4">
                Actualizar Paciente
            </x-wireui-button>
        </form>
    </x-wireui-card>
    
</x-admin-layout>
