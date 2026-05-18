<x-admin-layout title="Usuarios" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Usuarios',
        'href' => route('admin.users.index'),
    ],
    [
        'name' => 'Nuevo Usuario',
        'href' => route('admin.users.create'),
    ],
]">

    <x-wireui-card>
        {{-- Mostrar alertas de límites del plan --}}
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Información del plan actual --}}
        <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-800">Plan actual: {{ current_plan() }}</p>
                    <div class="mt-2 text-sm text-blue-700">
                        <span class="inline-block mr-4">
                            <strong>Doctores:</strong> {{ $doctorsCount }}
                            @if($doctorsLimit !== null)
                                / {{ $doctorsLimit }}
                            @else
                                / Ilimitados
                            @endif
                        </span>
                        <span class="inline-block">
                            <strong>Pacientes:</strong> {{ $patientsCount }}
                            @if($patientsLimit !== null)
                                / {{ $patientsLimit }}
                            @else
                                / Ilimitados
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="grid lg:grid-cols-2 gap-4">
                <x-wireui-input label="Nombre del Usuario" name="name" placeholder="Ingrese el nombre del usuario"
                    class="w-full mb-4" value="{{ old('name') }}" required autofocus />
                <x-wireui-input label="Correo Electrónico" name="email" type="email"
                    placeholder="Ingrese el correo electrónico" class="w-full mb-4" value="{{ old('email') }}"
                    required />
                <x-wireui-input label="Contraseña" name="password" type="password" placeholder="Ingrese la contraseña"
                    class="w-full mb-4" required />
                <x-wireui-input label="Confirmar Contraseña" name="password_confirmation" type="password"
                    placeholder="Confirme la contraseña" class="w-full mb-4" required />
            </div>


            <div class="grid lg:grid-cols-2 gap-4">

                <div class="w-full mb-4">
                    <x-wireui-input label="Teléfono" name="phone" placeholder="Ej: 1169975132"
                        class="w-full" value="{{ old('phone') }}" required />
                    <p class="text-xs text-gray-500 mt-1">
                        <strong>ℹ️ Formato correcto:</strong> 10 dígitos (código de área + número)<br>
                        <strong>✓</strong> Buenos Aires: <code>1169975132</code><br>
                        <strong>✓</strong> Córdoba: <code>3514567890</code><br>
                        <strong>✗</strong> No usar: 0, 15, +54, espacios ni guiones
                    </p>
                </div>



                <x-wireui-input label="DNI" name="dni" placeholder="Ingrese el dni del usuario"
                    class="w-full mb-4" value="{{ old('dni') }}" required />

            </div>
            <x-wireui-input label="Dirección" name="address" placeholder="Ingrese la dirección" class="w-full mb-4"
                value="{{ old('address') }}" required />

            <div class="mb-4">
                <x-wireui-native-select name="role_id" label="Rol del Usuario">
                    <option value="">Seleccione un rol</option>

                    @foreach ($roles as $role)
                        @php
                            $disabled = false;
                            $warningText = '';

                            if ($role->name === 'Doctor' && plan_reached_limit('max_doctors', $doctorsCount)) {
                                $disabled = true;
                                $warningText = ' (Límite alcanzado: ' . $doctorsLimit . ')';
                            }

                            if ($role->name === 'Paciente' && plan_reached_limit('max_patients', $patientsCount)) {
                                $disabled = true;
                                $warningText = ' (Límite alcanzado: ' . $patientsLimit . ')';
                            }
                        @endphp

                        <option value="{{ $role->id }}"
                                {{ old('role_id') == $role->id ? 'selected' : '' }}
                                {{ $disabled ? 'disabled' : '' }}>
                            {{ $role->name }}{{ $warningText }}
                        </option>
                    @endforeach
                </x-wireui-native-select>

                @if(plan_reached_limit('max_doctors', $doctorsCount) || plan_reached_limit('max_patients', $patientsCount))
                    <p class="mt-2 text-sm text-amber-600">
                        <strong>⚠️ Límite alcanzado:</strong>
                        Algunos roles están deshabilitados porque has alcanzado el límite de tu {{ current_plan() }}.
                    </p>
                @endif
            </div>

            <x-wireui-button primary type="submit" class="mt-4">
                Crear Usuario
            </x-wireui-button>
        </form>
    </x-wireui-card>

</x-admin-layout>
