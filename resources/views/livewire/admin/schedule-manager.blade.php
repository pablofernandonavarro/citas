<div x-data="data()">
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
