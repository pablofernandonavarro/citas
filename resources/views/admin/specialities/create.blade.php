<x-admin-layout 
title="Especialidades" 
:breadcrumbs="[
    ['name' => 'Dashboard',
     'href' => route('admin.dashboard')
    ],
    [
        'name' => 'Especialidades',
        'href' => route('admin.specialities.index')
    ],
    [
        'name' => 'Nueva Especialidad'
     ],

]">

<x-wireui-card>
    <form method="POST" action="{{ route('admin.specialities.store') }}">
        @csrf

        <x-wireui-input 
            label="Nombre de la Especialidad" 
            name="name" 
            placeholder="Ingrese el nombre de la especialidad" 
            class="w-full mb-4" 
            value="{{ old('name') }}"
            required
            autofocus
        />

        <div class="flex gap-2">
            <x-wireui-button primary type="submit">
                Crear Especialidad
            </x-wireui-button>
            
            <x-wireui-button 
                href="{{ route('admin.specialities.index') }}" 
                flat
            >
                Cancelar
            </x-wireui-button>
        </div>
    </form>
</x-wireui-card>
    
</x-admin-layout>
