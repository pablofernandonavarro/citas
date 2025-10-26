<x-admin-layout title="Usuarios" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    [
        'name' => 'Usuarios',
        'href' => route('admin.users.index'),
    ],
    [
        'name' => 'Editar Usuario',
    ],
]">
    <x-slot name="action">
        <x-wireui-button primary type="button" blue >
            <a href="{{ route('admin.users.index') }}">
                <i class="fas fa-arrow-left mr-2"></i>
               Volver a Usuarios
            </a>
        </x-wireui-button>
    </x-slot>
    <x-wireui-card>
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="grid lg:grid-cols-2 gap-4">
                <x-wireui-input label="Nombre del Usuario" name="name" placeholder="Ingrese el nombre del usuario"
                    class="w-full mb-4" value="{{ old('name', $user->name) }}" required autofocus />
                <x-wireui-input label="Correo Electrónico" name="email" type="email"
                    placeholder="Ingrese el correo electrónico" class="w-full mb-4" value="{{ old('email', $user->email) }}"
                    required />
                <x-wireui-input label="Contraseña" name="password" type="password" placeholder="Dejar en blanco para mantener la contraseña actual"
                    class="w-full mb-4" />
                <x-wireui-input label="Confirmar Contraseña" name="password_confirmation" type="password"
                    placeholder="Confirme la contraseña" class="w-full mb-4" />
            </div>
            
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Nota:</strong> Los campos de contraseña son opcionales. Si desea mantener la contraseña actual del usuario, déjelos en blanco. Solo ingrese una nueva contraseña si desea cambiarla.
                        </p>
                    </div>
                </div>
            </div>


            <div class="grid lg:grid-cols-2 gap-4">

                <x-wireui-input label="Teléfono" name="phone" placeholder="Ingrese el número de teléfono"
                    class="w-full mb-4" value="{{ old('phone', $user->phone) }}" required />



                <x-wireui-input label="DNI" name="dni" placeholder="Ingrese el dni del usuario"
                    class="w-full mb-4" value="{{ old('dni', $user->dni) }}" required />

            </div>
            <x-wireui-input label="Dirección" name="address" placeholder="Ingrese la dirección" class="w-full mb-4"
                value="{{ old('address', $user->address) }}" required />

            <x-wireui-native-select name="role_id" >
              
                <option value="">Seleccione un rol</option>

                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id', $user->roles->first()?->id) == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </x-wireui-native-select>

            <x-wireui-button primary type="submit" class="mt-4">
                Actualizar Usuario
            </x-wireui-button>
        </form>
    </x-wireui-card>
    
</x-admin-layout>
