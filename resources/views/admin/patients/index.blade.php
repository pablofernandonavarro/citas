<x-admin-layout title="Pacientes" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Pacientes',
    ],
]">
    <x-slot name="action">
        {{-- <x-wireui-button primary type="button" blue >
            <a href="{{ route('admin.patients.create') }}">
                <i class="fas fa-plus mr-2"></i>
                Crear Paciente
            </a>
        </x-wireui-button> --}}
    </x-slot>

   @livewire('Admin.Datatables.Patient-Table')
</x-admin-layout>
