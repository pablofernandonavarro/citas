<x-admin-layout
title="Doctores" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Doctores',
        'href' => route('admin.doctors.index'),
    ],


]">

    <x-wireui-card>
        {{-- Información del plan y límites --}}
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    Doctores
                    @if($limit !== null)
                        <span class="ml-2 inline-flex items-center rounded-full px-2.5 py-0.5 text-sm font-medium
                            {{ plan_reached_limit('max_doctors', $doctorsCount) ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $doctorsCount }} / {{ $limit }}
                        </span>
                    @else
                        <span class="ml-2 inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-sm font-medium text-green-800">
                            {{ $doctorsCount }} doctores
                        </span>
                    @endif
                </h2>
                <p class="text-sm text-gray-500 mt-1">Plan actual: {{ current_plan() }}</p>
            </div>
        </div>

        {{-- Alerta cuando se alcanza el límite --}}
        @if(plan_reached_limit('max_doctors', $doctorsCount))
            <div class="mb-4 p-4 bg-amber-50 border-l-4 border-amber-500">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-amber-800">
                            <strong>Límite alcanzado</strong>
                        </p>
                        <p class="mt-1 text-sm text-amber-700">
                            Has alcanzado el límite de {{ $limit }} doctores de tu {{ current_plan() }}.
                            Para agregar más doctores, debes actualizar tu plan.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @livewire("admin.datatables.doctor-table")
    </x-wireui-card>



</x-admin-layout>