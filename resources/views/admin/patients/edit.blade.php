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
            <div class="p-6" x-data="{ tab: 'contacto-emergencia' }">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                        <li class="me-2">
                            <a href="#" x-on:click.prevent="tab = 'datos-personales'" <a href="#"
                                x-on:click.prevent="tab = 'datos-personales'"
                                class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group"
                                :class="{ 'border-blue-500 text-blue-600': tab === 'datos-personales' }"
                                :aria-current="tab === 'datos-personales' ? 'page' : false">
                                <i class="fas fa-user mr-2"></i>
                                Datos personales
                            </a>
                        </li>
                        <li class="me-2">
                            <a href="#" x-on:click.prevent="tab = 'antecedentes-medicos'"
                                class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group"
                                :class="{ 'border-blue-500 text-blue-600': tab === 'antecedentes-medicos' }"
                                aria-current="page">
                                <i class="fas fa-file-medical mr-2"></i>
                                Antecedentes médicos
                            </a>
                        </li>
                        <li class="me-2">
                            <a href="#" x-on:click.prevent="tab = 'informacion-general'"
                                class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group"
                                :class="{ 'border-blue-500 text-blue-600': tab === 'informacion-general' }"
                                aria-current="page">
                                <i class="fas fa-file-alt mr-2"></i>
                                informacion general
                            </a>
                        </li>
                        <li class="me-2">
                            <a href="#" x-on:click.prevent="tab = 'contacto-emergencia'"
                                class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group"
                                :class="{ 'border-blue-500 text-blue-600': tab === 'contacto-emergencia' }"
                                aria-current="page">
                                <i class="fas fa-user-md mr-2"></i>
                                contacto de emergencia
                            </a>
                        </li>

                    </ul>

                </div>
                <div class="px-4 mt-4">

                    {{-- Datos personales --}}
                    <div x-show="tab === 'datos-personales'">

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

                    </div>

                    {{-- Antecedentes médicos --}}
                    <div x-show="tab === 'antecedentes-medicos'">
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
                    </div>

                    {{-- informacion general --}}
                    <div x-show="tab === 'informacion-general'">
                        

                            <div>
                                <x-wireui-native-select label="obra social"
                                    name="social_work_id"
                                    value="{{ old('social_work_id', $patient->social_work_id) }}">
                                    <option value=""
                                    class="mb-6">
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
                        
                    </div>

                    {{-- contacto de emergencia --}}
                    <div x-show="tab === 'contacto-emergencia'">
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
                    </div>
                </div>
            </div>

        </x-wireui-card>
    </form>
</x-admin-layout>
