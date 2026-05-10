<x-admin-layout
    title="Configuración de la Empresa"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Configuración de la Empresa'],
    ]">

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <x-wireui-card>
        <form method="POST" action="{{ route('admin.company-settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-wireui-input
                    label="Nombre del negocio"
                    name="business_name"
                    value="{{ old('business_name', $settings->business_name) }}"
                    required
                />

                <x-wireui-input
                    label="Especialidad"
                    name="specialty"
                    placeholder="Ej: Medicina General, Odontología"
                    value="{{ old('specialty', $settings->specialty) }}"
                />

                <x-wireui-input
                    label="Eslogan (tagline)"
                    name="tagline"
                    placeholder="Ej: Tu salud, nuestra prioridad"
                    value="{{ old('tagline', $settings->tagline) }}"
                />

                <x-wireui-input
                    label="Teléfono"
                    name="phone"
                    placeholder="Ej: +54 351 123-4567"
                    value="{{ old('phone', $settings->phone) }}"
                />

                <x-wireui-input
                    label="Dirección"
                    name="address"
                    placeholder="Ej: Av. Colón 1234, Córdoba"
                    value="{{ old('address', $settings->address) }}"
                />

                <x-wireui-input
                    label="WhatsApp"
                    name="whatsapp"
                    placeholder="Ej: 5493511234567"
                    value="{{ old('whatsapp', $settings->whatsapp) }}"
                />

                <x-wireui-input
                    label="Instagram"
                    name="instagram"
                    placeholder="Ej: @clinica_demo"
                    value="{{ old('instagram', $settings->instagram) }}"
                />

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                    @if($settings->logo_path)
                        <img src="{{ url('storage-' . tenant('id') . '/' . $settings->logo_path) }}" alt="Logo actual" class="h-20 mb-2 rounded">
                    @endif
                    <input type="file" name="logo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('logo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Imagen de fondo (hero)</label>
                    @if($settings->hero_image_path)
                        <img src="{{ url('storage-' . tenant('id') . '/' . $settings->hero_image_path) }}" alt="Fondo actual" class="h-32 w-full object-cover mb-2 rounded">
                    @endif
                    <input type="file" name="hero_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500 mt-1">Recomendado: 1920×1080px, máx 5MB. Esta imagen se muestra como fondo en tu página de inicio.</p>
                    @error('hero_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6">
                <x-wireui-button primary type="submit">
                    Guardar cambios
                </x-wireui-button>
            </div>
        </form>
    </x-wireui-card>

</x-admin-layout>
