<x-admin-layout title="Especialidades" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Especialidades',
    ],
]">
    <x-slot name="action">

        @can('create_speciality')
        <x-wireui-button primary type="button" blue >
            <a href="{{ route('admin.specialities.create') }}">
                <i class="fas fa-plus mr-2"></i>
                Crear Especialidad
            </a>
        </x-wireui-button>
        @endcan
    </x-slot>

     @livewire('admin.datatables.speciality-table')

</x-admin-layout>
