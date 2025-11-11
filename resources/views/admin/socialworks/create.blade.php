<x-admin-layout 
title="Obras Sociales" 
:breadcrumbs="[
    ['name' => 'Dashboard',
     'href' => route('admin.dashboard')
    ],
    [
        'name' => 'Obras Sociales',
        'href' => route('admin.socialworks.index')
    ],
    [
        'name' => 'Nueva Obra Social'
     ],

]">

<x-wireui-card>
    <form method="POST" action="{{ route('admin.socialworks.store') }}">
        @csrf

        <x-wireui-input 
            label="Nombre de la Obra Social" 
            name="name" 
            placeholder="Ingrese el nombre de la obra social" 
            class="w-full mb-4" 
            value="{{ old('name') }}"
            required
            autofocus
        />

        <div class="flex gap-2">
            <x-wireui-button primary type="submit">
                Crear Obra Social
            </x-wireui-button>
            
            <x-wireui-button 
                href="{{ route('admin.socialworks.index') }}" 
                flat
            >
                Cancelar
            </x-wireui-button>
        </div>
    </form>
</x-wireui-card>
    
</x-admin-layout>
