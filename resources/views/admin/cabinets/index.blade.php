<x-admin-layout title="Gabinetes" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Gabinetes',
    ],
]">
    <x-slot name="action">
        @can('create_cabinet')
        <x-wireui-button primary type="button" blue>
            <a href="{{ route('admin.cabinets.create') }}">
                <i class="fas fa-plus mr-2"></i>
                Crear Gabinete
            </a>
        </x-wireui-button>
        @endcan
    </x-slot>

    <x-wireui-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Descripción
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Doctores Asignados
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($cabinets as $cabinet)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <i class="fas fa-door-open text-blue-500 mr-3"></i>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $cabinet->name }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500">
                                    {{ Str::limit($cabinet->description, 50) ?? 'Sin descripción' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($cabinet->doctors->count() > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $cabinet->doctors->count() }} {{ Str::plural('doctor', $cabinet->doctors->count()) }}
                                        </span>
                                        <div class="mt-1 text-xs text-gray-500">
                                            @foreach($cabinet->doctors->take(2) as $doctor)
                                                {{ $doctor->user->name }}@if(!$loop->last), @endif
                                            @endforeach
                                            @if($cabinet->doctors->count() > 2)
                                                <span class="text-gray-400">y {{ $cabinet->doctors->count() - 2 }} más</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs">Sin asignar</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($cabinet->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Activo
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    @can('read_cabinet')
                                    <a href="{{ route('admin.cabinets.show', $cabinet) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endcan
                                    
                                    @can('update_cabinet')
                                    <a href="{{ route('admin.cabinets.edit', $cabinet) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    
                                    @can('delete_cabinet')
                                    <form action="{{ route('admin.cabinets.destroy', $cabinet) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de eliminar este gabinete?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center py-8">
                                    <i class="fas fa-door-open text-gray-300 text-5xl mb-4"></i>
                                    <p class="text-lg font-semibold mb-2">No hay gabinetes registrados</p>
                                    <p class="text-sm">Crea el primer gabinete para comenzar</p>
                                    @can('create_cabinet')
                                    <a href="{{ route('admin.cabinets.create') }}" class="mt-4">
                                        <x-wireui-button primary blue>
                                            <i class="fas fa-plus mr-2"></i>
                                            Crear Gabinete
                                        </x-wireui-button>
                                    </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-wireui-card>

</x-admin-layout>
