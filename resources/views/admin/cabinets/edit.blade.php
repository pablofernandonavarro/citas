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
        'name' => 'Editar Gabinete'
     ],

]">

<x-wireui-card>
    <div class="mb-4">
        <h2 class="text-xl font-semibold text-gray-800">
            <i class="fas fa-door-open text-blue-500 mr-2"></i>
            {{ $cabinet->name }}
        </h2>
    </div>

    <form method="POST" action="{{ route('admin.cabinets.update', $cabinet) }}">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            <x-wireui-input 
                label="Nombre del Gabinete" 
                name="name" 
                placeholder="Ej: Gabinete 1, Sala A, etc." 
                class="w-full" 
                value="{{ old('name', $cabinet->name) }}"
                required
                autofocus
            />

            <x-wireui-textarea 
                label="Descripción" 
                name="description" 
                placeholder="Ingrese una descripción del gabinete (opcional)" 
                class="w-full"
                rows="3"
            >{{ old('description', $cabinet->description) }}</x-wireui-textarea>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Estado
                </label>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="is_active" value="1" {{ old('is_active', $cabinet->is_active) == '1' ? 'checked' : '' }} class="form-radio text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Activo</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="is_active" value="0" {{ old('is_active', $cabinet->is_active) == '0' ? 'checked' : '' }} class="form-radio text-red-600">
                        <span class="ml-2 text-sm text-gray-700">Inactivo</span>
                    </label>
                </div>
            </div>

            @if($cabinet->doctors->count() > 0)
                <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm font-semibold text-blue-800 mb-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Doctores Asignados ({{ $cabinet->doctors->count() }})
                    </p>
                    <ul class="text-sm text-blue-700 space-y-1">
                        @foreach($cabinet->doctors as $doctor)
                            <li>
                                <i class="fas fa-user-md text-xs mr-1"></i>
                                {{ $doctor->user->name }}
                                @if($doctor->speciality)
                                    <span class="text-blue-500">- {{ $doctor->speciality->name }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="flex gap-2 mt-6">
            <x-wireui-button primary type="submit" green>
                <i class="fas fa-save mr-2"></i>
                Actualizar Gabinete
            </x-wireui-button>
            
            <x-wireui-button 
                href="{{ route('admin.cabinets.index') }}" 
                flat
            >
                Cancelar
            </x-wireui-button>

            @can('delete_cabinet')
            <form action="{{ route('admin.cabinets.destroy', $cabinet) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de eliminar este gabinete? Esta acción no se puede deshacer.');">
                @csrf
                @method('DELETE')
                <x-wireui-button type="submit" red flat>
                    <i class="fas fa-trash mr-2"></i>
                    Eliminar
                </x-wireui-button>
            </form>
            @endcan
        </div>
    </form>
</x-wireui-card>
    
</x-admin-layout>
