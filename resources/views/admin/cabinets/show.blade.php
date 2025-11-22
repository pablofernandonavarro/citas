<x-admin-layout 
title="Gabinetes" 
:breadcrumbs="[
    ['name' => 'Dashboard',
     'href' => route('admin.dashboard')
    ],
    [
        'name' => 'Gabinetes',
        'href' => route('admin.cabinets.index')
    ],
    [
        'name' => $cabinet->name
     ],

]">

<x-slot name="action">
    @can('update_cabinet')
    <x-wireui-button primary type="button" blue>
        <a href="{{ route('admin.cabinets.edit', $cabinet) }}">
            <i class="fas fa-edit mr-2"></i>
            Editar Gabinete
        </a>
    </x-wireui-button>
    @endcan
</x-slot>

<!-- Información General -->
<x-wireui-card class="mb-6">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <div class="flex items-center mb-4">
                <div class="bg-blue-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-door-open text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $cabinet->name }}</h2>
                    <p class="text-sm text-gray-500">Gabinete ID: #{{ $cabinet->id }}</p>
                </div>
            </div>
            
            @if($cabinet->description)
                <div class="mb-4">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Descripción:</p>
                    <p class="text-gray-600">{{ $cabinet->description }}</p>
                </div>
            @endif

            <div class="flex items-center gap-4">
                <div>
                    <p class="text-sm font-semibold text-gray-700">Estado:</p>
                    @if($cabinet->is_active)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Activo
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-1"></i>
                            Inactivo
                        </span>
                    @endif
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-700">Creado:</p>
                    <p class="text-sm text-gray-600">{{ $cabinet->created_at->format('d/m/Y H:i') }}</p>
                </div>

                @if($cabinet->updated_at != $cabinet->created_at)
                <div>
                    <p class="text-sm font-semibold text-gray-700">Última actualización:</p>
                    <p class="text-sm text-gray-600">{{ $cabinet->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-wireui-card>

<!-- Doctores Asignados -->
<x-wireui-card class="mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        <i class="fas fa-user-md text-blue-500 mr-2"></i>
        Doctores Asignados ({{ $cabinet->doctors->count() }})
    </h3>

    @if($cabinet->doctors->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($cabinet->doctors as $doctor)
                <div class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                    <img src="{{ $doctor->user->profile_photo_url }}" 
                         alt="{{ $doctor->user->name }}" 
                         class="h-12 w-12 rounded-full object-cover mr-4">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">{{ $doctor->user->name }}</p>
                        @if($doctor->speciality)
                            <p class="text-sm text-gray-500">{{ $doctor->speciality->name }}</p>
                        @endif
                        @if($doctor->medical_license_number)
                            <p class="text-xs text-gray-400">Lic: {{ $doctor->medical_license_number }}</p>
                        @endif
                    </div>
                    <a href="{{ route('admin.doctors.edit', $doctor) }}" 
                       class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8 bg-gray-50 rounded-lg">
            <i class="fas fa-user-slash text-gray-300 text-4xl mb-3"></i>
            <p class="text-gray-600">No hay doctores asignados a este gabinete</p>
            <p class="text-sm text-gray-500 mt-1">Asigne doctores desde la página de edición del doctor</p>
        </div>
    @endif
</x-wireui-card>

<!-- Estadísticas de Citas -->
<x-wireui-card>
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        <i class="fas fa-calendar-check text-green-500 mr-2"></i>
        Estadísticas de Uso
    </h3>

    @php
        $totalAppointments = $cabinet->appointments->count();
        $todayAppointments = $cabinet->appointments()->whereDate('date', today())->count();
        $upcomingAppointments = $cabinet->appointments()
            ->whereDate('date', '>=', today())
            ->where('status', 2) // Agendadas
            ->count();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-blue-50 p-4 rounded-lg">
            <p class="text-sm font-semibold text-blue-800 mb-1">Total de Citas</p>
            <p class="text-3xl font-bold text-blue-600">{{ $totalAppointments }}</p>
        </div>
        
        <div class="bg-green-50 p-4 rounded-lg">
            <p class="text-sm font-semibold text-green-800 mb-1">Citas Hoy</p>
            <p class="text-3xl font-bold text-green-600">{{ $todayAppointments }}</p>
        </div>
        
        <div class="bg-purple-50 p-4 rounded-lg">
            <p class="text-sm font-semibold text-purple-800 mb-1">Próximas Citas</p>
            <p class="text-3xl font-bold text-purple-600">{{ $upcomingAppointments }}</p>
        </div>
    </div>

    @if($totalAppointments == 0)
        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600 text-center">
                <i class="fas fa-info-circle mr-1"></i>
                Este gabinete aún no tiene citas asignadas
            </p>
        </div>
    @endif
</x-wireui-card>

</x-admin-layout>
