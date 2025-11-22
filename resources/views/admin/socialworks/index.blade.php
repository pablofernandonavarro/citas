<x-admin-layout title="Obras Sociales" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Obras Sociales',
    ],
]">
    <x-slot name="action">

        @can('create_socialwork')
        <x-wireui-button primary type="button" blue >
            <a href="{{ route('admin.socialworks.create') }}">
                <i class="fas fa-plus mr-2"></i>
                Crear Obra Social
            </a>
        </x-wireui-button>
        @endcan
    </x-slot>

     @livewire('admin.datatables.social-work-table')

</x-admin-layout>
