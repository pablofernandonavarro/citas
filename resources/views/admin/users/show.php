<x-admin-layout title="Usuario" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Usuarios',
        'href' => route('admin.users.index'),
    ],
    [
        'name' => 'Ver Usuario',
    ],
]">
    <x-slot name="action">
        <x-wireui-button primary type="button" blue >
            <a href="{{ route('admin.users.create') }}">
                <i class="fas fa-plus mr-2"></i>
                Ver Usuario
            </a>
        </x-wireui-button>
    </x-slot>

    
</x-admin-layout>