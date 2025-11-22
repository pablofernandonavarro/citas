<x-admin-layout 
title="Roles" 
:breadcrumbs="[
    ['name' => 'Dashboard',
     'href' => route('admin.dashboard')
    ],
    [
        'name' => 'Roles',
        
    ],

]">

<x-wireui-card>
    <form method="POST" action="{{ route('admin.roles.store') }}">
        @csrf

        <x-wireui-input 
            label="Nombre del Rol" 
            name="name" 
            placeholder="Ingrese el nombre del rol" 
            class="w-full mb-4" 
            value="{{ old('name') }}"
            required
            autofocus
            

        />

        <x-wireui-button primary type="submit">
            Crear Rol
        </x-wireui-button>
    </form>
</x-wireui-card>
    
</x-admin-layout>