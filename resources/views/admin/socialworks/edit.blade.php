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
        'name' => 'Editar Obra Social'
     ],

]">

<x-wireui-card>
    <form method="POST" action="{{ route('admin.socialworks.update', $socialwork) }}">
        @csrf
        @method('PUT')

        <x-wireui-input 
            label="Nombre de la Obra Social" 
            name="name" 
            placeholder="Ingrese el nombre de la obra social" 
            class="w-full mb-4" 
            value="{{ old('name', $socialwork->name) }}"
            required
            autofocus
            

        />

        <x-wireui-button primary type="submit">
            Actualizar Obra Social
        </x-wireui-button>
    </form>
</x-wireui-card>
    
</x-admin-layout>