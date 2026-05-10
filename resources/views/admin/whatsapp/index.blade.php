<x-admin-layout
    title="WhatsApp"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'WhatsApp'],
    ]">

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(!config('services.evolution.api_url'))
        <div class="p-6 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-800">
            <p class="font-semibold">Evolution API no configurada</p>
            <p class="text-sm mt-1">Contactá al administrador para habilitar el envío de mensajes por WhatsApp.</p>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Estado de conexión --}}
            <x-wireui-card title="Estado de conexión">
                <div id="status-container">
                    @if($connected)
                        <div class="flex items-center gap-3 p-4 bg-green-50 rounded-lg border border-green-200">
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            <div>
                                <p class="font-semibold text-green-800">WhatsApp conectado</p>
                                <p class="text-sm text-green-600">Los mensajes se enviarán desde tu número</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.whatsapp.disconnect') }}" class="mt-4">
                            @csrf
                            @method('DELETE')
                            <x-wireui-button flat negative type="submit">
                                Desconectar WhatsApp
                            </x-wireui-button>
                        </form>
                    @else
                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200 mb-4">
                            <div class="w-3 h-3 rounded-full bg-gray-400"></div>
                            <div>
                                <p class="font-semibold text-gray-700">No conectado</p>
                                <p class="text-sm text-gray-500">Escaneá el QR con tu WhatsApp</p>
                            </div>
                        </div>

                        <button
                            onclick="refreshQr()"
                            class="text-sm text-blue-600 hover:underline">
                            ↻ Actualizar QR
                        </button>
                    @endif
                </div>
            </x-wireui-card>

            {{-- QR Code --}}
            @if(!$connected)
            <x-wireui-card title="Escanear QR">
                <div id="qr-container" class="text-center">
                    @if($qr && isset($qr['base64']))
                        <img
                            id="qr-image"
                            src="{{ $qr['base64'] }}"
                            alt="QR WhatsApp"
                            class="mx-auto w-64 h-64 border rounded-lg"
                        >
                        <p class="text-sm text-gray-500 mt-3">
                            Abrí WhatsApp → Dispositivos vinculados → Vincular dispositivo
                        </p>
                    @elseif($qr && isset($qr['qrcode']))
                        <img
                            id="qr-image"
                            src="{{ $qr['qrcode'] }}"
                            alt="QR WhatsApp"
                            class="mx-auto w-64 h-64 border rounded-lg"
                        >
                        <p class="text-sm text-gray-500 mt-3">
                            Abrí WhatsApp → Dispositivos vinculados → Vincular dispositivo
                        </p>
                    @else
                        <div id="qr-loading" class="py-12 text-gray-400">
                            <p>Generando QR...</p>
                        </div>
                    @endif
                </div>

                <p class="text-xs text-gray-400 mt-4 text-center">
                    El QR se actualiza automáticamente cada 20 segundos
                </p>
            </x-wireui-card>
            @endif

        </div>

        {{-- Instrucciones --}}
        <div class="mt-6 p-4 bg-blue-50 border border-blue-100 rounded-lg">
            <h3 class="font-semibold text-blue-800 mb-2">¿Cómo funciona?</h3>
            <ol class="text-sm text-blue-700 space-y-1 list-decimal list-inside">
                <li>Abrí WhatsApp en tu celular</li>
                <li>Tocá los tres puntos → <strong>Dispositivos vinculados</strong></li>
                <li>Tocá <strong>Vincular un dispositivo</strong></li>
                <li>Escaneá el código QR de la pantalla</li>
                <li>Una vez conectado, los recordatorios de turnos se enviarán automáticamente desde tu número</li>
            </ol>
        </div>
    @endif

    @if(!$connected && config('services.evolution.api_url'))
    <script>
        let pollInterval;

        function refreshQr() {
            fetch('{{ route("admin.whatsapp.status") }}')
                .then(r => r.json())
                .then(data => {
                    if (data.connected) {
                        clearInterval(pollInterval);
                        window.location.reload();
                        return;
                    }

                    const container = document.getElementById('qr-container');
                    if (data.qr && (data.qr.base64 || data.qr.qrcode)) {
                        const src = data.qr.base64 || data.qr.qrcode;
                        container.innerHTML = `
                            <img src="${src}" alt="QR WhatsApp" class="mx-auto w-64 h-64 border rounded-lg">
                            <p class="text-sm text-gray-500 mt-3">Abrí WhatsApp → Dispositivos vinculados → Vincular dispositivo</p>
                        `;
                    }
                })
                .catch(() => {});
        }

        // Polling cada 20 segundos para verificar conexión y renovar QR
        pollInterval = setInterval(refreshQr, 20000);
    </script>
    @endif

</x-admin-layout>
