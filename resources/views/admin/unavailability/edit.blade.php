<x-admin-layout
title="Editar Período de Bloqueo" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Períodos de Bloqueo',
        'href' => route('admin.unavailability.index'),
    ],
    [
        'name' => 'Editar',
        'href' => route('admin.unavailability.edit', $unavailability),
    ],
]">
   
    <x-wireui-card>
        <form action="{{ route('admin.unavailability.update', $unavailability) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Doctor -->
                <div class="col-span-2">
                    <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Doctor *
                    </label>
                    <select name="doctor_id" id="doctor_id" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Seleccione un doctor</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}" 
                                {{ old('doctor_id', $unavailability->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->user->name }} - {{ $doctor->speciality->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha Inicio -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha Inicio *
                    </label>
                    <input type="date" name="start_date" id="start_date" 
                        value="{{ old('start_date', $unavailability->start_date->format('Y-m-d')) }}" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha Fin -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha Fin *
                    </label>
                    <input type="date" name="end_date" id="end_date" 
                        value="{{ old('end_date', $unavailability->end_date->format('Y-m-d')) }}" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Motivo -->
                <div class="col-span-2">
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Motivo
                    </label>
                    <input type="text" name="reason" id="reason" value="{{ old('reason', $unavailability->reason) }}" 
                        placeholder="Ej: Vacaciones, Congreso médico, etc."
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('reason')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Todo el día -->
                <div class="col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="all_day" id="all_day" value="1" 
                            {{ old('all_day', $unavailability->all_day) ? 'checked' : '' }}
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                            onchange="toggleTimeFields()">
                        <label for="all_day" class="ml-2 block text-sm text-gray-900">
                            Bloquear todo el día
                        </label>
                    </div>
                </div>

                <!-- Hora Inicio -->
                <div id="start_time_field" class="{{ old('all_day', $unavailability->all_day) ? 'hidden' : '' }}">
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                        Hora Inicio
                    </label>
                    <input type="time" name="start_time" id="start_time" 
                        value="{{ old('start_time', $unavailability->start_time) }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('start_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hora Fin -->
                <div id="end_time_field" class="{{ old('all_day', $unavailability->all_day) ? 'hidden' : '' }}">
                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                        Hora Fin
                    </label>
                    <input type="time" name="end_time" id="end_time" 
                        value="{{ old('end_time', $unavailability->end_time) }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('end_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.unavailability.index') }}" 
                    class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Cancelar
                </a>
                <button type="submit" 
                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Actualizar Período de Bloqueo
                </button>
            </div>
        </form>
    </x-wireui-card>

    <script>
        function toggleTimeFields() {
            const allDay = document.getElementById('all_day').checked;
            const startTimeField = document.getElementById('start_time_field');
            const endTimeField = document.getElementById('end_time_field');
            
            if (allDay) {
                startTimeField.classList.add('hidden');
                endTimeField.classList.add('hidden');
                document.getElementById('start_time').value = '';
                document.getElementById('end_time').value = '';
            } else {
                startTimeField.classList.remove('hidden');
                endTimeField.classList.remove('hidden');
            }
        }
    </script>

</x-admin-layout>
