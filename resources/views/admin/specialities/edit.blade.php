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
        'name' => 'Editar Especialidad'
     ],

]">

<x-wireui-card>
    <form method="POST" action="{{ route('admin.specialities.update', $speciality) }}">
        @csrf
        @method('PUT')

        <x-wireui-input 
            label="Nombre de la Especialidad" 
            name="name" 
            placeholder="Ingrese el nombre de la especialidad" 
            class="w-full mb-4" 
            value="{{ old('name', $speciality->name) }}"
            required
            autofocus
            

        />

        <x-wireui-button primary type="submit">
            Actualizar Especialidad
        </x-wireui-button>
    </form>
</x-wireui-card>
    
</x-admin-layout>
