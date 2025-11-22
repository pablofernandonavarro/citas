<x-admin-layout title="Roles" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Roles',
    ],
]">
    <x-slot name="action">

        @can('create_role')
            <x-wireui-button primary type="button" blue>
                <a href="{{ route('admin.roles.create') }}">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Rol
                </a>
            </x-wireui-button>
        @endcan
    </x-slot>

    @livewire('admin.datatables.role-table')
</x-admin-layout>
