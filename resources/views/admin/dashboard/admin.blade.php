 <div class="grid grid-cols-3 gap-6">
        <x-wireui-card>
            <p class="text-sm font-semibold text-gray-500">Total de Pacientes :</p>
            <p class="mt-2 font-bold text-grey-800 text-3xl">{{ $data['total_patients'] }}</p>
        </x-wireui-card>

        <x-wireui-card>
            <p class="text-sm font-semibold text-gray-500">Total de Doctores :</p>
            <p class="mt-2 font-bold text-grey-800 text-3xl">{{ $data['total_doctors'] }}</p>
        </x-wireui-card>

        <x-wireui-card>
            <p class="text-sm font-semibold text-gray-500">Turnos para Hoy :</p>
            <p class="mt-2 font-bold text-grey-800 text-3xl">{{ $data['appointment_today'] }}</p>
        </x-wireui-card>
    </div>
    <div class=" mt-2 grid grid-cols-3 gap-6">
        <div class="col-span-2">
            <x-wireui-card>
                <p class="text-lg font-semibold">Usuarios registrados recientemente :</p>
                @foreach ($data['recent_user'] as $user)
                    <li class ="py-3 flex justify-between items-center">
                        <div>
                            <p class ="text-sm font-semibold text-gray-900">
                                {{ $user->name }}
                            </p>
                            <p class ="text-sm font-semibold text-gray-500">
                                {{ $user->email }}
                            </p>
                        </div>
                        <span class="text-xs text-gray-500 mt-0">
                            {{ $user->created_at->diffForHumans() }}
                        </span>
                    </li>
                    <hr>
                @endforeach
            </x-wireui-card>


        </div>
        <div class="grid-cols-1">
            <div>
                <x-wireui-card>
                    <p class="text-lg font-semibold text-gary-800">Acciones rápidas</p>
                    <div class="mt-4 space-y-2">
                        <x-wireui-button  class="w-full" href="{{ route('admin.patients.index') }}">
                            Gestionar Pacientes
                        </x-wireui-button>
                        <x-wireui-button class="w-full" href="{{ route('admin.doctors.index') }}">
                            Gestionar Doctores
                        </x-wireui-button>
                        <x-wireui-button class="w-full" href="{{ route('admin.appointments.index') }}">
                            Gestionar Turnos
                        </x-wireui-button>
                         <x-wireui-button class="w-full" href="{{ route('admin.socialworks.index') }}">
                            Gestionar Obras Sociales
                        </x-wireui-button>
                        <x-wireui-button class="w-full" href="{{ route('admin.socialworks.index') }}">
                            Gestionar Especialidades
                        </x-wireui-button>

                    </div>

                </x-wireui-card>
            </div>


        </div>

    </div>