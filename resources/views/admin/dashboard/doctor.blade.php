<div>
    <div class="grid lg:grid-cols-4 gap-6">
        <div class="lg:col-span-2 flex">
            <x-wireui-card class="w-full">
                <p class="text-2xl fond-bold text-gray-800">
                    ¡Buen dia Dr./Dra.: {{ auth()->user()->name }}!
                </p>
                <p class="text-sm text-gray-500">
                    Aqui esta resumen de su jornada.
                </p>
            </x-wireui-card>


        </div>
        <div class="flex">
            <x-wireui-card class="w-full">
                <p class= "text-sm font-semibold text-gray-500">
                    Citas de Hoy
                </p>
                <p class = "mt-2 text-3xl font-semibold text-gray-600">
                    {{ $data['appointment_today_count'] }} Turnos.
                </p>
            </x-wireui-card>

        </div>
        <div class="flex">
            <x-wireui-card class="w-full">
                <p class= "text-sm font-semibold text-gray-500">
                    Citas de la Semana
                </p>
                <p class = "mt-2 text-3xl font-semibold text-gray-600">
                    {{ $data['appointment_week_count'] }} Turnos.
                </p>
            </x-wireui-card>
        </div>


    </div>
    <div class="grid lg:grid-cols-3 gap-6 mt-8 ">
        <div>
            <x-wireui-card>
                <p>
                    Proximo Turno :
                </p>
                <p>
                    @if ($data['next_appointment'])
                        <p class="mt-4 font-semibold text-xl text-gray-800">
                            {{ $data['next_appointment']->patient->user->name }}
                        </p>
                        <p class="font-semibold text-xl text-gray-500">
                            {{ \Carbon\Carbon::parse($data['next_appointment']['date'])->format('d-m-Y') }}
                        </p>
                        <p>
                            {{ \Carbon\Carbon::parse($data['next_appointment']['start_time'])->format('H:i') }} hs
                        </p>
                    @else
                        <p>NO hay citas para hoy</p>
                    @endif

                </p>
                @if ($data['next_appointment'])
                    <x-wireui-button class="w-full mt-4"
                        href="{{ route('admin.appointments.consultation', $data['next_appointment']) }}">
                        Gestionar turno
                    </x-wireui-button>
                @endif

            </x-wireui-card>
        </div>
        <div class="col-span-2">

            <x-wireui-card>
                <p class="text-lg font-semibold text-gray-900">
                    Agenda para Hoy:

                </p>
                <ul class="mt-4 divide-y divide-gray-500">
                    @forelse ($data['appointment_today'] as $appointment)
                        <li class="py-3 flex justify-between items-center">
                            <div>
                           <p>
                            {{ $appointment->patient->user->name }}
                           </p>
                        <p>

                            {{ \Carbon\Carbon::parse($appointment->date)->format('d-m-Y') }} a las {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                        </p>
                           </p>
                            </div>
                            <a href="{{ route('admin.appointments.consultation',$appointment )}}">
                                Gestionar Turno
                            </a>


                        </li>
                    @empty
                    @endforelse
                </ul>

            </x-wireui-card>

        </div>
    </div>

</div>
