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

                <x-wireui-input label="Teléfono" name="phone" placeholder="Ingrese el número de teléfono"
                    class="w-full mb-4" value="{{ old('phone') }}" required />



                <x-wireui-input label="DNI" name="dni" placeholder="Ingrese el dni del usuario"
                    class="w-full mb-4" value="{{ old('dni') }}" required />

            </div>
            <x-wireui-input label="Dirección" name="address" placeholder="Ingrese la dirección" class="w-full mb-4"
                value="{{ old('address') }}" required />

            <x-wireui-native-select name="role_id" >
              
                <option value="">Seleccione un rol</option>

                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </x-wireui-native-select>

            <x-wireui-button primary type="submit" class="mt-4">
                Crear Usuario
            </x-wireui-button>
        </form>
    </x-wireui-card>

</x-admin-layout>
