<x-admin-layout title="Pacientes" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Pacientes',
        'href' => route('admin.patients.index'),
    ],
    [
        'name' => 'Crear Paciente',
    ],
]">

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('admin.patients.store') }}" method="POST">
                @csrf
                
                <input type="hidden" name="user_id" value="{{ $user_id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-wireui-native-select
                            label="Obra Social"
                            name="social_work_id"
                        >
                            <option value="">Seleccione una obra social</option>
                            @foreach ($socialWorks as $socialWork)
                                <option value="{{ $socialWork->id }}">
                                    {{ $socialWork->name }}
                                </option>
                            @endforeach
                        </x-wireui-native-select>
                    </div>

                    <div>
                        <x-wireui-input 
                            label="Número de Afiliado" 
                            name="affiliate_number" 
                            placeholder="Número de afiliado a la obra social"
                        />
                    </div>

                    <div>
                        <x-wireui-input 
                            label="Alergias" 
                            name="allergies" 
                            placeholder="Ingrese alergias"
                        />
                    </div>

                    <div>
                        <x-wireui-input 
                            label="Número de Historia Clínica" 
                            name="medical_record_number" 
                            placeholder="Número de historia clínica"
                        />
                    </div>

                    <div>
                        <x-wireui-input 
                            label="Condiciones Crónicas" 
                            name="chronic_conditions" 
                            placeholder="Condiciones crónicas"
                        />
                    </div>

                    <div>
                        <x-wireui-input 
                            label="Historial de Cirugías" 
                            name="surgeries_history" 
                            placeholder="Historial de cirugías"
                        />
                    </div>

                    <div>
                        <x-wireui-input 
                            label="Historial Familiar" 
                            name="family_history" 
                            placeholder="Historial familiar"
                        />
                    </div>

                    <div>
                        <x-wireui-input 
                            label="Condiciones Genéticas" 
                            name="genetic_conditions" 
                            placeholder="Condiciones genéticas"
                        />
                    </div>

                    <div>
                        <x-wireui-input 
                            label="Otras Condiciones" 
                            name="other_conditions" 
                            placeholder="Otras condiciones"
                        />
                    </div>

                    <div>
                        <x-wireui-input 
                            type="date"
                            label="Fecha de Nacimiento" 
                            name="date_of_birth"
                        />
                    </div>

                    <div class="md:col-span-2">
                        <h3 class="text-lg font-semibold mb-4">Contacto de Emergencia</h3>
                    </div>

                    <div>
                        <x-wireui-input 
                            label="Nombre" 
                            name="emergency_contact_name" 
                            placeholder="Nombre del contacto"
                        />
                    </div>

                    <div>
                        <x-wireui-input 
                            label="Teléfono" 
                            name="emergency_contact_phone" 
                            placeholder="Ej: +54 9 11 1234-5678 o 1112345678"
                        />
                        <p class="text-xs text-gray-500 mt-1">Formato: código de país + código de área + número</p>
                    </div>

                    <div>
                        <x-wireui-input 
                            label="Relación" 
                            name="emergency_contact_relationship" 
                            placeholder="Relación con el paciente"
                        />
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <x-wireui-button 
                        href="{{ route('admin.patients.index') }}" 
                        flat 
                        secondary
                    >
                        Cancelar
                    </x-wireui-button>
                    
                    <x-wireui-button 
                        type="submit" 
                        primary
                    >
                        Crear Paciente
                    </x-wireui-button>
                </div>
            </form>
        </div>
    </div>

</x-admin-layout>
