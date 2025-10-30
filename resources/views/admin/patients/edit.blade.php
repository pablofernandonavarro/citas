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
        <x-wireui-button primary type="button" blue>
            <a href="{{ route('admin.patients.index') }}">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver a Pacientes
            </a>
        </x-wireui-button>
    </x-slot>
    <form action="{{ route('admin.patients.update', $patient) }}" method="POST">
        @csrf
        @method('PUT')
        <x-wireui-card class="mb-6 bg-white">

            <div class="lg:flex lg:justify-between items-center">
                <div class="flex items-center space-x-5 mt-6 lg:mt-0">
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile Photo"
                        class="h-20 w-20 rounded-full object-cover object-center">

                    <div>
                        <p class="text-2xl font-semibold text-gray-800">{{ $patient->user->name }}</p>

                    </div>
                </div>
                <div class="mt-6 lg:mt-0">
                    <x-wireui-button primary type="submit" blue>
                        <i class="fas fa-edit mr-2"></i>
                        Editar Paciente
                    </x-wireui-button>
                </div>
            </div>
        </x-wireui-card>
        {{-- seccionn de tabs --}}
        <x-wireui-card>
            <x-tabs active="datos-personales">
                 <x-slot name="header">
                    <x-tab-link tab="datos-personales">
                        Datos personales
                    </x-tab-link>
                    <x-tab-link tab="antecedentes-medicos">
                        Antecedentes médicos
                    </x-tab-link>
                    <x-tab-link tab="informacion-general">
                        Informacion general
                    </x-tab-link>
                    <x-tab-link tab="contacto-emergencia">
                        Contacto de emergencia
                    </x-tab-link>
                </x-slot> 

                {{-- Datos personales --}}
                <x-tab-content tab="datos-personales">
                    <x-wireui-alert type="info" class="mb-4">
                        <x-slot name="title">Edicion de Usuario</x-slot>
                        <div>
                            <p> Para editar esta informacion dirígete a la sección de
                                <a href="{{ route('admin.users.edit', $patient->user) }}"
                                    class="text-blue-600 underline hover:text-blue-800" target="_blank">
                                    perfil de usuario
                                </a>
                                asociado a este paciente.
                            </p>
                        </div>
                    </x-wireui-alert>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <span class="text-gray-500 font-semibold">Telefono:</span>
                            <span class="text-gray-900 text-sm ml-1">{{ $patient->user->phone }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 font-semibold">Email:</span>
                            <span class="text-gray-900 text-sm ml-1">{{ $patient->user->email }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 font-semibold">Direccion:</span>
                            <span class="text-gray-900 text-sm ml-1">{{ $patient->user->address }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 font-semibold">DNI:</span>
                            <span class="text-gray-900 text-sm ml-1">{{ $patient->user->dni }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 font-semibold">Numero obra Social:</span>
                            <span class="text-gray-900 text-sm ml-1">{{ $patient->medical_record_number }}</span>
                        </div>
                    </div>
                </x-tab-content>

                {{-- Antecedentes médicos --}}
                <x-tab-content tab="antecedentes-medicos">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-wireui-textarea label="Alergias conocidas"
                                name="allergies">{{ old('allergies', $patient->allergies) }}</x-wireui-textarea>
                        </div>
                        <div>
                            <x-wireui-textarea label="Enfermedades cronicas"
                                name="chronic_conditions">{{ old('chronic_conditions', $patient->chronic_conditions) }}</x-wireui-textarea>
                        </div>
                        <div>
                            <x-wireui-textarea label="Antecedentes quirurgicos"
                                name="surgeries_history">{{ old('surgeries_history', $patient->surgeries_history) }}</x-wireui-textarea>
                        </div>
                        <div>
                            <x-wireui-textarea label="Antecedentes familiares"
                                name="family_history">{{ old('family_history', $patient->family_history) }}</x-wireui-textarea>
                        </div>
                    </div>
                </x-tab-content>

                {{-- informacion general --}}
                <x-tab-content tab="informacion-general">
                    <div>
                        <x-wireui-native-select label="obra social"
                            name="social_work_id"
                            value="{{ old('social_work_id', $patient->social_work_id) }}">
                            <option value="" class="mb-6">
                                Seleccione una obra social
                            </option>
                            @foreach ($socialWorks as $socialWork)
                                <option value="{{ $socialWork->id }}" @selected($socialWork->id == old('social_work_id', $patient->social_work_id))>
                                    {{ $socialWork->name }}
                                </option>
                            @endforeach
                        </x-wireui-native-select>
                    </div>

                    <div>
                        <x-wireui-textarea label="Otras observaciones"
                            name="other_conditions">{{ old('other_conditions', $patient->other_conditions) }}</x-wireui-textarea>
                    </div>
                </x-tab-content>

                {{-- contacto de emergencia --}}
                <x-tab-content tab="contacto-emergencia">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-wireui-input label="Nombre del contacto de emergencia" name="emergency_contact_name"
                                value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}" />
                        </div>
                        <div>
                            <x-wireui-input label="Telefono del contacto de emergencia"
                                name="emergency_contact_phone"
                                value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}" />
                        </div>
                        <div>
                            <x-wireui-input label="Relacion con el contacto de emergencia"
                                name="emergency_contact_relationship"
                                value="{{ old('emergency_contact_relationship', $patient->emergency_contact_relationship) }}" />
                        </div>
                    </div>
                </x-tab-content>
            </x-tabs>

        </x-wireui-card>
    </form>
</x-admin-layout>
