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
                <x-wireui-button wire:click="searchAvailability"
                 label="Buscar Disponibilidad"
                 class="w-full"
                 :disabled="$appointmentEdit && !$appointmentEdit->status->isEditable()"
                />
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
                                <img src="{{ $availability['doctor']->user->profile_photo_url }}"
                                    alt="{{ $availability['doctor']->user->name }}"
                                    class="h-16 w-16 rounded-full object-cover">
                                <div>
                                    <p class="text-xl font-semibold text-slate-800 ">
                                        Dr./Dra. {{ $availability['doctor']->user->name }}
                                    </p>
                                    <p class="text-sm text-indigo-500">
                                        Especialidad:
                                        {{ $availability['doctor']->speciality?->name ?? 'sin especialidad' }}
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
                                            <x-wireui-button
                                                :disabled="$schedule['disabled']"
                                                :color="$schedule['disabled'] ? 'gray' : 'primary'"
                                                class="w-full"
                                                x-on:click="selectSchedule({{ $availability['doctor']->id }}, '{{ $schedule['start_time'] }}')"
                                                x-bind:class="selectedSchedules.doctor_id === {{ $availability['doctor']->id }} &&
                                                selectedSchedules.schedule.includes(
                                                '{{ $schedule['start_time'] }}') ? 'opacity-50' : ''"
                                                >

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
                    {{-- @json($selectedSchedules) --}}

                    <x-wireui-card>
                        <p class="text-xl font-semibold mb-4 text-slate-800">
                            Resumen de Turno
                        </p>

                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between">
                                <span class="font-semibold text-slate-500">Dr./Dra.:</span>
                                <span class="font-semibold text-slate-800">{{ $this->doctorName }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold text-slate-500">Fecha:</span>
                                <span
                                    class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($this->appointment['date'])->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold text-slate-500">Horario:</span>
                                @if ($appointment['duration'])
                                    <span class="font-semibold text-slate-800">
                                        {{ $this->appointment['start_time'] }} -
                                        {{ \Carbon\Carbon::parse($this->appointment['start_time'])->addMinutes($appointment['duration'])->format('H:i:s') }}
                                    </span>
                                @else
                                    <span class="font-semibold text-slate-800">{{ 'Por Definir' }}</span>
                                @endif
                            </div>

                            <div class="flex justify-between">
                                <span class="font-semibold text-slate-500">Duracion:</span>
                                @if ($appointment['duration'])
                                    <span class="font-semibold text-slate-800">
                                        {{ $appointment['duration'] ?: "sin duración" }} minutos
                                    </span>
                                @else
                                    <span class="font-semibold text-slate-800">
                                        {{ 'Por Definir' }}
                                    </span>
                                @endif
                            </div>
                            <hr class="my-4">
                             <div class="space-y-6">
                             <x-wireui-select
                                label="Paciente"
                                placeholder="Selecciona un paciente"
                                wire:model="appointment.patient_id"
                                :async-data="route('api.patient')"
                                option-label="name"
                                option-value="id"
                                :disabled="$appointmentEdit !== null"
                              />
                              <x-wireui-textarea
                                label="Motivo de la consulta"
                                wire:model="appointment.reason"
                                placeholder="Describe el motivo de la consulta"
                                />

                                <x-wireui-button
                                    wire:click="save"
                                    label="Agendar Turno"
                                    class="w-full"
                                    spinner="save"
                                    >
                                     <span class="font-semibold">Agendar Turno</span>
                             </x-wireui-button>


                            </div>
                        </div>
                    </x-wireui-card>
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
</div>

@push('js')
    <script>
        function data() {
            return {
                selectedSchedules: @entangle('selectedSchedules').live,
                availabilities: @entangle('availabilities').live,

                getAvailableSchedules(doctorId) {
                    // Acceder directamente usando doctorId como clave
                    const doctorData = this.availabilities[doctorId];

                    if (!doctorData || !doctorData.schedules) return [];
                    return doctorData.schedules.map(s => s.start_time).sort();
                },

                isContiguous(schedules, newSchedule, doctorId) {
                    if (schedules.length === 0) return true;

                    const allSchedules = [...schedules, newSchedule].sort();
                    const availableSchedules = this.getAvailableSchedules(doctorId);

                    // Encontrar el rango de horarios seleccionados en los disponibles
                    for (let i = 1; i < allSchedules.length; i++) {
                        const prevSchedule = allSchedules[i - 1];
                        const currSchedule = allSchedules[i];

                        const prevIndex = availableSchedules.indexOf(prevSchedule);
                        const currIndex = availableSchedules.indexOf(currSchedule);

                        // Si no son consecutivos en el array de disponibles
                        if (currIndex - prevIndex !== 1) {
                            return false;
                        }
                    }

                    return true;
                },

                selectSchedule(doctorId, schedule) {
                    if (this.selectedSchedules.doctor_id && this.selectedSchedules.doctor_id !== doctorId) {
                        // si se selecciono un doctor diferente, reiniciar la seleccion
                        this.selectedSchedules = {
                            doctor_id: doctorId,
                            schedule: [schedule]
                        };
                        return;
                    }

                    let currentSchedules = Array.isArray(this.selectedSchedules.schedule) ?
                        this.selectedSchedules.schedule :
                        [];

                    let newSchedules = [];
                    if (currentSchedules.includes(schedule)) {
                        // quitar de la seleccion
                        newSchedules = currentSchedules.filter(s => s !== schedule);

                        // Validar que los restantes sigan siendo contiguos
                        if (newSchedules.length > 1) {
                            const availableSchedules = this.getAvailableSchedules(doctorId);
                            const sorted = newSchedules.sort();

                            for (let i = 1; i < sorted.length; i++) {
                                const prevIdx = availableSchedules.indexOf(sorted[i - 1]);
                                const currIdx = availableSchedules.indexOf(sorted[i]);

                                if (currIdx - prevIdx !== 1) {
                                    Swal.fire({
                                        title: 'Horarios no contiguos',
                                        text: 'Al quitar este horario, los restantes dejan de ser contiguos. Se reiniciará la selección.',
                                        icon: 'warning',
                                        confirmButtonText: 'Entendido'
                                    });
                                    newSchedules = [];
                                    break;
                                }
                            }
                        }
                    } else {
                        // Validar que sea contiguo
                        const isContig = this.isContiguous(currentSchedules, schedule, doctorId);

                        if (!isContig) {
                            Swal.fire({
                                title: 'Horarios no contiguos',
                                text: 'Solo puedes seleccionar horarios contiguos',
                                icon: 'warning',
                                confirmButtonText: 'Entendido'
                            });
                            return;
                        }
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
