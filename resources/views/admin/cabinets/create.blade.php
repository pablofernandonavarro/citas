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
        'name' => 'Nuevo Gabinete'
     ],

]">

<x-wireui-card>
    <form method="POST" action="{{ route('admin.cabinets.store') }}">
        @csrf

        <div class="space-y-4">
            <x-wireui-input 
                label="Nombre del Gabinete" 
                name="name" 
                placeholder="Ej: Gabinete 1, Sala A, etc." 
                class="w-full" 
                value="{{ old('name') }}"
                required
                autofocus
            />

            <x-wireui-textarea 
                label="Descripción" 
                name="description" 
                placeholder="Ingrese una descripción del gabinete (opcional)" 
                class="w-full"
                rows="3"
            >{{ old('description') }}</x-wireui-textarea>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Estado
                </label>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="form-radio text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Activo</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="is_active" value="0" {{ old('is_active') == '0' ? 'checked' : '' }} class="form-radio text-red-600">
                        <span class="ml-2 text-sm text-gray-700">Inactivo</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex gap-2 mt-6">
            <x-wireui-button primary type="submit" blue>
                <i class="fas fa-save mr-2"></i>
                Crear Gabinete
            </x-wireui-button>
            
            <x-wireui-button 
                href="{{ route('admin.cabinets.index') }}" 
                flat
            >
                Cancelar
            </x-wireui-button>
        </div>
    </form>
</x-wireui-card>
    
</x-admin-layout>
