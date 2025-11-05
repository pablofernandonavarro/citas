<x-admin-layout title="Doctores" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Doctores',
        'href' => route('admin.doctors.index'),
    ],
    [
        'name' => 'Editar Doctor',
    ],
]">
    <x-slot name="action">
        <x-wireui-button primary type="button" blue>
            <a href="{{ route('admin.doctors.index') }}">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver a Doctores
            </a>
        </x-wireui-button>
    </x-slot>
    <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST">
        @csrf
        @method('PUT')

        <x-wireui-card class="mb-8">

            <div class="lg:flex lg:justify-between items-center">
                <div class="flex items-center space-x-5 mt-6 lg:mt-0">
                    <img src="{{ $doctor->user->profile_photo_url }}" alt="Profile Photo"
                        class="h-20 w-20 rounded-full object-cover object-center">
                    <div>
                        <p class="text-2xl font-semibold text-gray-800 mb-1">{{ $doctor->user->name }}</p>
                        <p class="text-sm font-semibold text-gray-500">Licencia:
                            {{ $doctor->medical_license_number ?? 'No disponible' }}</p>
                    </div>
                </div>
                <div class="mt-6 lg:mt-0">
                    <x-wireui-button primary type="button" gray>
                        <a href="{{ route('admin.doctors.schedules', $doctor) }}">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Horarios Disponibles
                        </a>
                    </x-wireui-button>
                    <x-wireui-button primary type="submit" blue>

                        <i class="fas fa-save mr-2"></i>

                        Guardar Cambios
                    </x-wireui-button>

                </div>
            </div>
        </x-wireui-card>

        <x-wireui-card>
            <div class="space-y-4">
                <x-wireui-native-select label="Especialidad" name="speciality_id"
                    placeholder="Seleccione una especialidad">
                    <option value="" disabled selected>Seleccione una especialidad</option>
                    @foreach ($specialities as $speciality)
                        <option value="{{ $speciality->id }}"
                            {{ $doctor->speciality_id == $speciality->id ? 'selected' : '' }}>
                            {{ $speciality->name }}
                        </option>
                    @endforeach
                </x-wireui-native-select>

            </div>
            <div class="space-y-4">
                <x-wireui-input label="Número de Licencia Médica" name="medical_license_number" type="text"
                    placeholder="Ingrese el número de licencia médica"
                    value="{{ old('medical_license_number', $doctor->medical_license_number) }}" />
            </div>
            <div class="space-y-4">
                <x-wireui-textarea label="Biografia" name="biography"
                    placeholder="Ingrese la biografía del Doctor">{{ old('biography', $doctor->biography) }}
                </x-wireui-textarea>

            </div>
            <div>
                <x-wireui-native-select label="Estado" name="active" placeholder="Seleccione un estado">
                    <option value="1" {{ old('active', $doctor->active) == '1' ? 'selected' : '' }}>Activo
                    </option>
                    <option value="0" {{ old('active', $doctor->active) == '0' ? 'selected' : '' }}>Inactivo
                    </option>
                </x-wireui-native-select>

            </div>

        </x-wireui-card>




    </form>

</x-admin-layout>
