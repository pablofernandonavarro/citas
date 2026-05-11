<div x-data="data()">

    {{-- Períodos de bloqueo activos y próximos --}}
    @if($this->upcomingBlocks->isNotEmpty())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-ban text-red-500"></i>
                    <span class="text-sm font-semibold text-red-700">Períodos de no disponibilidad activos o próximos</span>
                </div>
                <a href="{{ route('admin.unavailability.create') }}" class="text-xs text-red-600 hover:text-red-800 underline">
                    + Agregar bloqueo
                </a>
            </div>
            <div class="space-y-2">
                @foreach($this->upcomingBlocks as $block)
                    @php $isActive = $block->start_date->lte(now()) && $block->end_date->gte(now()); @endphp
                    <div class="flex items-center justify-between rounded-md bg-white border border-red-100 px-3 py-2">
                        <div class="flex items-center gap-3">
                            @if($isActive)
                                <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">
                                    <span class="relative flex h-1.5 w-1.5">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-red-500"></span>
                                    </span>
                                    Activo ahora
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-orange-100 px-2 py-0.5 text-xs font-medium text-orange-700">
                                    Próximo
                                </span>
                            @endif
                            <div>
                                <span class="text-sm font-medium text-gray-800">
                                    @if($block->all_day)
                                        {{ $block->start_date->format('d/m/Y') }}@if(!$block->start_date->eq($block->end_date)) — {{ $block->end_date->format('d/m/Y') }}@endif
                                        <span class="text-xs text-gray-400 ml-1">(todo el día)</span>
                                    @else
                                        {{ $block->start_date->format('d/m/Y') }} {{ substr($block->start_time, 0, 5) }} — {{ $block->end_date->format('d/m/Y') }} {{ substr($block->end_time, 0, 5) }}
                                    @endif
                                </span>
                                @if($block->reason)
                                    <span class="text-xs text-gray-500 ml-2">· {{ $block->reason }}</span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('admin.unavailability.edit', $block) }}" class="text-gray-400 hover:text-gray-600 ml-4">
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="mb-4 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                Sin períodos de bloqueo activos o próximos
            </div>
            <a href="{{ route('admin.unavailability.create') }}" class="text-xs text-gray-400 hover:text-gray-600 underline">
                Agregar bloqueo
            </a>
        </div>
    @endif

    <x-wireui-card>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-xl font-semibold mb-4">
                Gestionar Horarios del Dr./Dra. : {{ $doctor->user->name }}
            </h1>
            <x-wireui-button wire:click="save">
                Guardar Horarios
            </x-wireui-button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Día/Hora

                        </th>
                        @foreach ($days as $day)
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $day }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="px-6 py-3 bg-gray-50 text-left text-xs font-medium ">
                    @foreach ($this->hourBlocks as $hourBlock)
                        @php
                            $hour = $hourBlock->format('H:i:s');

                        @endphp
                        <tr>
                            <td class="border px-6 py-4 whitespace-nowrap ">
                                <label>
                                    <input type="checkbox" 
                                        x-on:click="toggleAllDaysForHour('{{ $hour }}', $el.checked)"
                                        :checked="isAllDaysForHourChecked('{{ $hour }}')"
                                        class="rounded text-red-600 border-gray-300">
                                    <span class="font-bold">
                                        {{ $hour }}
                                    </span>
                                </label>
                            </td>
                            @foreach ($days as $indexDay => $day)
                                <td class="border px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-2">
                                        <label>
                                            <input type="checkbox"
                                                x-on:click="toggleHourBlock('{{ $indexDay }}', '{{ $hour }}', $el.checked)"
                                                :checked="isHourBlockChecked('{{ $indexDay }}', '{{ $hour }}')"
                                                class="h-4 w-4 border-gray-200 text-blue-800">
                                            <span class="ml-2 text-sm text-gray-900">
                                                Todos
                                            </span>
                                        </label>
                                        @for ($i = 0; $i < $this->intervals; $i++)
                                            @php
                                                $startTime = $hourBlock
                                                    ->copy()
                                                    ->addMinutes($this->appointments_duration * $i);
                                                $endTime = $startTime->copy()->addMinutes($this->appointments_duration);

                                            @endphp
                                            <label>
                                                <input type="checkbox"
                                                    x-model="schedule['{{ $indexDay }}']['{{ $startTime->format('H:i:s') }}']"
                                                    class="h-4 w-4 border-gray-200 text-blue-800 bordered-checkbox focus:ring-blue-500 rounded">
                                                <span class="ml-2 text-sm text-gray-900">
                                                    {{ $startTime->format('H:i') }}-{{ $endTime->format('H:i') }} mins
                                                </span>
                                            </label>
                                        @endfor
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-wireui-card>
    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('swal', (event) => {
                    Swal.fire({
                        title: event[0].title,
                        text: event[0].text,
                        icon: event[0].icon
                    });
                });
            });

            function data() {
                return {
                    appointments_duration: @entangle('appointments_duration'),
                    intervals: @entangle('intervals'),
                    schedule: @entangle('schedule').live,
                    toggleHourBlock(dayIndex, hourBlock, checked) {
                        let hour = new Date(`1970-01-01T${hourBlock}`);

                        if (!this.schedule[dayIndex]) {
                            this.schedule[dayIndex] = {};
                        }

                        for (let i = 0; i < this.intervals; i++) {
                            let startTime = new Date(hour.getTime() + (i * this.appointments_duration * 60000));
                            let formattedStartTime = startTime.toTimeString().split(' ')[0];
                            this.schedule[dayIndex][formattedStartTime] = checked;
                        }
                    },
                    isHourBlockChecked(dayIndex, hourBlock) {
                        if (!this.schedule[dayIndex]) {
                            return false;
                        }

                        let hour = new Date(`1970-01-01T${hourBlock}`);

                        for (let i = 0; i < this.intervals; i++) {
                            let startTime = new Date(hour.getTime() + (i * this.appointments_duration * 60000));
                            let formattedStartTime = startTime.toTimeString().split(' ')[0];
                            if (!this.schedule[dayIndex][formattedStartTime]) {
                                return false;
                            }
                        }
                        return true;
                    },
                    toggleAllDaysForHour(hourBlock, checked) {
                        let hour = new Date(`1970-01-01T${hourBlock}`);

                        // Iterar sobre todos los días
                        Object.keys(this.schedule).forEach(dayIndex => {
                            if (!this.schedule[dayIndex]) {
                                this.schedule[dayIndex] = {};
                            }

                            // Marcar todos los intervalos de esa hora para cada día
                            for (let i = 0; i < this.intervals; i++) {
                                let startTime = new Date(hour.getTime() + (i * this.appointments_duration * 60000));
                                let formattedStartTime = startTime.toTimeString().split(' ')[0];
                                this.schedule[dayIndex][formattedStartTime] = checked;
                            }
                        });
                    },
                    isAllDaysForHourChecked(hourBlock) {
                        let hour = new Date(`1970-01-01T${hourBlock}`);

                        // Verificar si todos los días tienen marcada esa hora
                        for (let dayIndex of Object.keys(this.schedule)) {
                            if (!this.schedule[dayIndex]) {
                                return false;
                            }

                            for (let i = 0; i < this.intervals; i++) {
                                let startTime = new Date(hour.getTime() + (i * this.appointments_duration * 60000));
                                let formattedStartTime = startTime.toTimeString().split(' ')[0];
                                if (!this.schedule[dayIndex][formattedStartTime]) {
                                    return false;
                                }
                            }
                        }
                        return true;
                    }
                }
            }
            
        </script>
    @endpush
</div>
