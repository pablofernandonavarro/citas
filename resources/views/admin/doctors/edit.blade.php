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

            <div>
                <x-wireui-native-select label="Duración de Turnos" name="appointment_duration" placeholder="Seleccione la duración">
                    <option value="" {{ old('appointment_duration', $doctor->appointment_duration) === null ? 'selected' : '' }}>
                        Por defecto ({{ config('schedule.appointments_duration') }} minutos)
                    </option>
                    <option value="15" {{ old('appointment_duration', $doctor->appointment_duration) == '15' ? 'selected' : '' }}>15 minutos</option>
                    <option value="30" {{ old('appointment_duration', $doctor->appointment_duration) == '30' ? 'selected' : '' }}>30 minutos</option>
                    <option value="45" {{ old('appointment_duration', $doctor->appointment_duration) == '45' ? 'selected' : '' }}>45 minutos</option>
                    <option value="60" {{ old('appointment_duration', $doctor->appointment_duration) == '60' ? 'selected' : '' }}>60 minutos</option>
                    <option value="90" {{ old('appointment_duration', $doctor->appointment_duration) == '90' ? 'selected' : '' }}>90 minutos</option>
                </x-wireui-native-select>
                <p class="text-xs text-gray-500 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    Define cuánto tiempo dura cada turno de este doctor. Si se deja por defecto, usará {{ config('schedule.appointments_duration') }} minutos.
                </p>
            </div>

        </x-wireui-card>
    </form>

    {{-- Sección de Gabinetes --}}
    <x-wireui-card class="mt-6" x-data="{ showCabinets: {{ $doctor->cabinets->count() > 0 ? 'true' : 'false' }} }">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-door-open mr-2"></i>
            Gabinetes y Modalidad de Atención
        </h3>
        
        <div class="mb-6">
            <p class="text-sm font-semibold text-gray-700 mb-3">¿Cómo atiende este doctor a sus pacientes?</p>
            
            <div class="space-y-3">
                <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition" 
                       :class="!showCabinets ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'"
                       @click="showCabinets = false">
                    <input type="radio" name="attendance_mode" value="single" 
                           :checked="!showCabinets" 
                           class="mt-1 text-blue-600 focus:ring-blue-500">
                    <div class="ml-3">
                        <p class="font-semibold text-gray-800">
                            <i class="fas fa-user mr-2 text-blue-600"></i>
                            Un paciente por vez
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            Modalidad tradicional - El doctor atiende un solo paciente en cada horario (médico general, cardiólogo, etc.)
                        </p>
                    </div>
                </label>
                
                <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition" 
                       :class="showCabinets ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-gray-300'"
                       @click="showCabinets = true">
                    <input type="radio" name="attendance_mode" value="multiple" 
                           :checked="showCabinets" 
                           class="mt-1 text-green-600 focus:ring-green-500">
                    <div class="ml-3">
                        <p class="font-semibold text-gray-800">
                            <i class="fas fa-users mr-2 text-green-600"></i>
                            Múltiples pacientes simultáneamente
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            Requiere gabinetes - El doctor puede atender varios pacientes al mismo tiempo (kinesiólogos, fisioterapeutas, etc.)
                        </p>
                    </div>
                </label>
            </div>
        </div>

        <div x-show="showCabinets" x-transition class="border-t pt-6 mt-6">
            <h4 class="text-md font-semibold text-gray-800 mb-3">
                <i class="fas fa-door-open mr-2 text-green-600"></i>
                Selecciona los gabinetes disponibles
            </h4>
            
            <form action="{{ route('admin.doctors.cabinets.assign', $doctor) }}" method="POST">
                @csrf
                
                @if($cabinets->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    @foreach($cabinets as $cabinet)
                        <label class="flex items-center space-x-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer
                            {{ $doctor->cabinets->contains($cabinet->id) ? 'bg-blue-50 border-blue-300' : 'border-gray-200' }}">
                            <input 
                                type="checkbox" 
                                name="cabinet_ids[]" 
                                value="{{ $cabinet->id }}"
                                {{ $doctor->cabinets->contains($cabinet->id) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            >
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $cabinet->name }}</p>
                                @if($cabinet->description)
                                    <p class="text-xs text-gray-500">{{ Str::limit($cabinet->description, 50) }}</p>
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="flex justify-end">
                    <x-wireui-button primary type="submit" green>
                        <i class="fas fa-save mr-2"></i>
                        Guardar Gabinetes
                    </x-wireui-button>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-yellow-800">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        No hay gabinetes disponibles. 
                        <a href="{{ route('admin.cabinets.create') }}" class="underline font-semibold">Crear gabinete</a>
                    </p>
                </div>
                @endif
            </form>
        </div>
    </x-wireui-card>

</x-admin-layout>
