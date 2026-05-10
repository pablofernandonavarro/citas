@php
    $settings = \App\Models\CompanySetting::get();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <title>{{ $settings->business_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css?family=BenchNine:400,700,300,900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'BenchNine', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .hero-bg {
            @if($settings->hero_image_path)
            background: linear-gradient(rgba(37, 37, 37, 0.59), rgba(37, 37, 37, 0.59)), url('{{ url('storage-' . tenant('id') . '/' . $settings->hero_image_path) }}');
            @else
            background: linear-gradient(135deg, #1e3a5f 0%, #2d6a9f 50%, #1a2e4a 100%);
            @endif
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-white">
    <!-- Hero Section -->
    <section class="hero-bg relative min-h-screen flex items-center justify-center text-white">
        <div class="absolute top-8 right-8 flex gap-3">
            <a href="{{ route('login') }}" class="px-6 py-2 bg-white text-gray-700 rounded-full hover:bg-gray-100 font-bold transition">
                Iniciar Sesión
            </a>
        </div>

        <div class="container mx-auto px-6 text-center">
            <div class="mb-4">
                <div class="w-32 h-32 mx-auto mb-8 flex items-center justify-center">
                    @if($settings->logo_path)
                        <img src="{{ url('storage-' . tenant('id') . '/' . $settings->logo_path) }}" alt="{{ $settings->business_name }}" class="w-full h-full object-contain">
                    @else
                        <div class="w-32 h-32 bg-white/20 rounded-full flex items-center justify-center">
                            <span class="text-4xl font-bold">{{ strtoupper(substr($settings->business_name, 0, 2)) }}</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="border-b border-white w-64 mx-auto mb-4"></div>
            @if($settings->specialty)
                <p class="text-xl mb-4 tracking-wide">{{ strtoupper($settings->specialty) }}</p>
            @endif
            <div class="border-b border-white w-64 mx-auto mb-8"></div>

            <h1 class="text-6xl md:text-8xl font-bold mb-12 leading-tight">
                @if($settings->tagline)
                    {{ strtoupper($settings->tagline) }}
                @else
                    {{ strtoupper($settings->business_name) }}
                @endif
            </h1>

            <div class="flex flex-col md:flex-row gap-4 justify-center items-center mb-4">
                <a href="{{ route('register') }}" class="px-8 py-3 bg-white text-gray-700 rounded-full hover:bg-blue-100 font-bold text-xl transition shadow-lg">
                    SOLICITAR TURNO
                </a>
                @if($settings->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $settings->whatsapp) }}" target="_blank" class="px-8 py-3 border-2 border-white text-white rounded-full hover:bg-white hover:text-gray-700 font-bold text-xl transition">
                        WHATSAPP
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Donde Estamos -->
    @if($settings->address)
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <div class="inline-block px-12 py-4 bg-gray-600 text-white rounded-full shadow-lg">
                    <h2 class="text-3xl font-bold">DONDE ESTAMOS</h2>
                </div>
                @if($settings->whatsapp)
                    <p class="text-gray-600 text-lg mt-6">Hacé click y envianos un WhatsApp para ponerte en contacto con nosotros.</p>
                @endif
            </div>
            <div class="text-center mb-8">
                <p class="text-xl font-bold text-gray-800">{{ $settings->address }}</p>
            </div>
        </div>
    </section>
    @endif

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="container mx-auto px-6">
            <div class="text-center">
                <p class="font-bold text-lg mb-2">{{ $settings->business_name }}</p>
                @if($settings->address)
                    <p class="text-gray-400 mb-2">{{ $settings->address }}</p>
                @endif
                @if($settings->phone)
                    <p class="text-gray-400 mb-4">{{ $settings->phone }}</p>
                @endif
                @if($settings->instagram)
                    <a href="https://instagram.com/{{ ltrim($settings->instagram, '@') }}" target="_blank" class="text-gray-400 hover:text-white transition">
                        @{{ $settings->instagram }}
                    </a>
                @endif
                <p class="text-gray-500 text-sm mt-4">&copy; {{ date('Y') }} {{ $settings->business_name }}. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>
