<div>
    <x-wireui-card>
        <p class= "text-xl font-semibold mb-1 text-slate-800">
            Buscar Turno
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
                        <x-wireui-select.option 
                            :label="$hourBlock->format('H:i:s') . ' - ' . $hourBlock->copy()->addHour()->format('H:i:s')" 
                            :value="$hourBlock->format('H:i:s')" 
                        />
                    @endforeach
                </x-wireui-select>
            </div>
            <div>
                <x-wireui-select label="Especialidad" placeholder="Selecciona una especialidad" wire:model="search.speciality_id">

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
</div>
