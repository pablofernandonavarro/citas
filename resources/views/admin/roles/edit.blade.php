<x-admin-layout 
title="Roles" 
:breadcrumbs="[
    ['name' => 'Dashboard',
     'href' => route('admin.dashboard')
    ],
    [
        'name' => 'Roles',
        
    ],
    [
        'name' => 'Editar Rol'
     ],

]">

<x-wireui-card>
    <form method="POST" action="{{ route('admin.roles.update', $role) }}">
        @csrf
        @method('PUT')

        <x-wireui-input 
            label="Nombre del Rol" 
            name="name" 
            placeholder="Ingrese el nombre del rol" 
            class="w-full mb-4" 
            value="{{ old('name', $role->name) }}"
            required
            autofocus
            

        />

        <x-wireui-button primary type="submit">
         Actualizar Rol
        </x-wireui-button>
    </form>
</x-wireui-card>
    
</x-admin-layout>