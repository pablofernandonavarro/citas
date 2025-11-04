<div x-data="data()">
    <x-wireui-card class='mb-8'>
        <p class= "text-xl font-semibold mb-1 text-slate-800">
            Buscar disponibilidad de turnos
        </p>
        <p>
            encuentra el horario perfecto para tu turno aquí.
        </p>

        <div class="grid grid-cols-1 lg:grid-cols-4 md:grid-cols-4 gap-4 mt-4">
            <div>
                <x-wireui-input label="Fecha" type="date" wire:model="search.date" class="w-full"
                    placeholder="Selecciona una fecha">
                </x-wireui-input>
            </div>

            <div>
                <x-wireui-select label="Hora" placeholder="Selecciona una hora" wire:model="search.hour">
                    @foreach ($this->hourBlocks as $hourBlock)
                        <x-wireui-select.option :label="$hourBlock->format('H:i:s') . ' - ' . $hourBlock->copy()->addHour()->format('H:i:s')" :value="$hourBlock->format('H:i:s')" />
                    @endforeach
                </x-wireui-select>
            </div>
            <div>
                <x-wireui-select label="Especialidad" placeholder="Selecciona una especialidad"
                    wire:model="search.speciality_id">

                    @foreach ($this->specialities as $speciality)
                        <x-wireui-select.option :label="$speciality->name" :value="$speciality->id" />
                    @endforeach
                </x-wireui-select>
            </div>
            <div class="lg:pt-6.5">
                <x-wireui-button wire:click="searchAvailability" label="Buscar Disponibilidad" class="w-full" />
            </div>
        </div>
    </x-wireui-card>
    @if ($appointment['date'])

        @if (count($availabilities))

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-8">
                <div class="lg:col-span-2 col-span-1 space-y-6">
                    @foreach ($availabilities as $availability)
                   
                        <x-wireui-card class="mb-4">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $availability['doctor']->user->profile_photo_url }}" alt="{{ $availability['doctor']->user->name }}"
                                    class="h-16 w-16 rounded-full object-cover">
                                <div>
                                    <p class="text-xl font-semibold text-slate-800 ">
                                        Dr./Dra. {{ $availability['doctor']->user->name }}
                                    </p>
                                    <p class="text-sm text-indigo-500">
                                        Especialidad: {{ $availability['doctor']->speciality?->name ?? 'sin especialidad' }}
                                    </p>
                                </div>
                            </div>
                            <hr class="my-4">
                            <div>
                                <p>
                                    Horarios disponibles :
                                </p>

                                <ul class="grid grid-col-1 md:grid-cols-2 lg:grid-cols-4 gap-2 ">
                                    @foreach ($availability['schedules'] as $schedule)
                                    
                                        <li>
                                            <x-wireui-button  class="w-full"
                                                x-on:click="selectSchedule({{ $availability['doctor']->id }}, '{{ $schedule['start_time'] }}')"
                                                x-bind:class="selectedSchedules.doctor_id === {{ $availability['doctor']->id }} && selectedSchedules.schedule.includes('{{ $schedule['start_time'] }}') ? 'opacity-50' : ''  ">
                          
                                         {{ $schedule['start_time'] }}
                                            </x-wireui-button>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>

                        </x-wireui-card>
                    @endforeach

                </div>
                <div class="col-span-1">
                    @json($selectedSchedules)

                </div>


            </div>
        @else
            <x-wireui-card>
                <p class="text-center text-gray-500">
                    NO hay disponibilidad de turnos para la fecha y hora seleccionadas.
                </p>
            </x-wireui-card>
        @endif
    @endif

@push('js')
    <script>
        function data() {
            return {
                selectedSchedules: @entangle('selectedSchedules').live,
                selectSchedule(doctorId, schedule) {
                    if(this.selectedSchedules.doctor_id && this.selectedSchedules.doctor_id !== doctorId) {
                        // si se selecciono un doctor diferente, reiniciar la seleccion
                        this.selectedSchedules = {
                            doctor_id: doctorId,
                            schedule: [schedule]
                        };
                        return;
                    }

                   let currentSchedules = Array.isArray(this.selectedSchedules.schedule) 
                       ? this.selectedSchedules.schedule 
                       : [];
                   
                   let newSchedules = [];
                   if (currentSchedules.includes(schedule)) {
                       // quitar de la seleccion
                       newSchedules = currentSchedules.filter(s => s !== schedule);
                   } else {
                       // agregar a la seleccion
                       newSchedules = [...currentSchedules, schedule];
                   }
                   
                   this.selectedSchedules = {
                       doctor_id: doctorId,
                       schedule: newSchedules
                   };
                }
            }
        }
    </script>

@endpush


</div>
