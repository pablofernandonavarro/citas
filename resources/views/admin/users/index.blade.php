<x-admin-layout title="Usuarios" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Usuarios',
    ],
]">
    <x-slot name="action">
        <x-wireui-button primary type="button" blue >
            <a href="{{ route('admin.users.create') }}">
                <i class="fas fa-plus mr-2"></i>
                Crear Usuario
            </a>
        </x-wireui-button>
    </x-slot>

    @livewire('Admin.Datatables.User-Table')
</x-admin-layout>
