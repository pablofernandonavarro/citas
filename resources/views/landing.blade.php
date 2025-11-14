<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <title>Cetrip - {{ config('app.name') }}</title>
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
            background: linear-gradient(rgba(37, 37, 37, 0.59), rgba(37, 37, 37, 0.59)), url('');
            background-size: cover;
            background-position: 57% 76%;
        }
    </style>
</head>

<body class="bg-white">
    <!-- Hero Section -->
<section class="hero-bg relative min-h-screen flex items-center justify-center text-white px-4 sm:px-6">
    <!-- Botones login/register -->
    <div class="absolute top-6 right-4 flex flex-row gap-3 items-center">
        <a href="{{ route('login') }}"
            class="px-6 py-3 bg-white text-gray-700 rounded-full hover:bg-gray-100 font-bold transition text-center">
            Iniciar Sesión
        </a>

        <a href="{{ route('register') }}"
            class="px-6 py-3 bg-white text-gray-700 rounded-full hover:bg-gray-100 font-bold transition text-center">
            Registrarse
        </a>
    </div>

    <!-- Contenido central -->
    <div class="container mx-auto px-4 sm:px-6 text-center">
        <!-- Logo -->
        <div class="w-16 h-16 sm:w-32 sm:h-32 mx-auto mb-6 sm:mb-8 flex items-center justify-center">
            <img src="{{ asset('images/obrassociales/cetrip.png') }}" alt="Cetrip Logo"
                class="w-full h-full object-contain">
        </div>

        <div class="border-b border-white w-48 sm:w-64 mx-auto mb-4"></div>

        <p class="text-lg sm:text-xl mb-4 tracking-wide">CENTRO MEDICO ESPECIALIZADO</p>

        <!-- Botón principal -->
        <div class="flex flex-col md:flex-row gap-4 justify-center items-center mb-6">
            <a href="{{ route('register') }}"
                class="px-6 sm:px-8 py-3 bg-white text-gray-700 rounded-full hover:bg-blue-100 font-bold text-lg sm:text-xl transition shadow-lg w-full sm:w-auto text-center">
                SOLICITAR TURNO
            </a>
        </div>

        <div class="border-b border-white w-48 sm:w-64 mx-auto mb-8"></div>

        <!-- Título -->
        <h1 class="text-3xl sm:text-6xl md:text-8xl font-bold mb-8 sm:mb-12 leading-snug sm:leading-tight">
            RECUPERA TU BIENESTAR.<br>
            ESPECIALISTAS EN DOLOR LABORAL Y CALIDAD DE VIDA.
        </h1>
    </div>
</section>





    <!-- Coberturas -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <div class="inline-block px-12 py-4 bg-gray-600 text-white rounded-full shadow-lg">
                    <h2 class="text-3xl font-bold">COBERTURAS</h2>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 items-center justify-items-center">
                <div class="h-20 flex items-center justify-center">
                    <img src="{{ asset('images/obrassociales/osde.webp') }}" alt="OSDE"
                        class="max-h-16 max-w-full object-contain">
                </div>
                <div class="h-20 flex items-center justify-center">
                    <img src="{{ asset('images/obrassociales/swissmedical.webp') }}" alt="Swiss Medical"
                        class="max-h-16 max-w-full object-contain">
                </div>
                <div class="h-20 flex items-center justify-center">
                    <span class="text-gray-600 font-bold text-2xl">IOMA</span>
                </div>
                <div class="h-20 flex items-center justify-center">
                    <img src="{{ asset('images/obrassociales/ospjn.webp') }}" alt="Poder Judicial"
                        class="max-h-16 max-w-full object-contain">
                </div>
                <div class="h-20 flex items-center justify-center">
                    <img src="{{ asset('images/obrassociales/medife.webp') }}" alt="Medife"
                        class="max-h-16 max-w-full object-contain">
                </div>
            </div>
        </div>
    </section>

    <!-- Lesiones Frecuentes -->
    <section class="py-20 bg-gray-900 text-white"
        style="background: linear-gradient(rgba(0, 0, 0, 0.59), rgba(0, 0, 0, 0.59)), url(''); background-size: cover;">
        <div class="container mx-auto px-6">
            <h2 class="text-5xl md:text-7xl font-bold mb-8">LESIONES FRECUENTES</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="text-2xl leading-relaxed">
                    <p class="mb-2"><strong>Desgarros y distensiones musculares</strong></p>
                    <p class="mb-2">Tendinopatias</p>
                    <p class="mb-2"><strong>Post quirúrgicos traumatológicos</strong></p>
                    <p class="mb-2">Alteraciones posturales</p>
                    <p class="mb-2"><strong>Dolores agudos y crónicos de columna</strong></p>
                    <p class="mb-2">Esguinces</p>
                    <p><strong>Alteraciones vestibulares</strong></p>
                </div>
                <div class="flex items-center justify-center">
                    <div class="w-64 h-64 bg-white/10 rounded-full"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tratamientos -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <div class="inline-block px-12 py-4 bg-gray-600 text-white rounded-full shadow-lg">
                    <h2 class="text-3xl font-bold">TRATAMIENTOS</h2>
                </div>
                <p class="text-gray-600 text-xl mt-6">Profesionales especializados en deporte a disposición de nuestros
                    pacientes</p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 max-w-6xl mx-auto">
                <!-- Traumatología -->
                <div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-4">Traumatologia</h3>
                    <p class="text-gray-600 text-lg mb-4">Contamos con destacados especialistas médicos de vasta
                        experiencia de distintas ramas dentro de la traumatología que incluyen miembro superior y
                        miembro inferior, como también procedimientos exploratorios artroscopicos y tratamientos
                        mínimamente invasivos como tratatamiento rico en plaquetas.</p>
                </div>

                <!-- Neurocirugía -->
                <div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-4">Neurocirugia</h3>
                    <p class="text-gray-600 text-lg mb-4">Contamos con profesionales de excelencia especializados en
                        columna para atención de dolores tanto crónicos como agudos de distintas patologías, como así
                        también la aplicación de procedimientos como bloqueos y/o terapéuticas menos invasivas para del
                        tratamiento del dolor.</p>
                </div>

                <!-- RPG -->
                <div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-4">RPG</h3>
                    <p class="text-gray-600 text-lg mb-4">RPG es un método científico de evaluación, diagnostico y
                        tratamiento de patologías que afectan al sistema locomotor. La RPG aborda al paciente como una
                        unidad funcional, utilizando posturas de tratamiento, en forma global y progresiva.</p>
                </div>

                <!-- Kinesiología -->
                <div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-4">Kinesiologia y Rehabilitación</h3>
                    <p class="text-gray-600 text-lg mb-4">La rehabilitación cumple un rol fundamental en la etapa de
                        recuperación del paciente, ya que permite alcanzar el más completo potencial físico e intenta
                        restablecer o restaurar la salud y la calidad de vida.</p>
                </div>

                <!-- MEP -->
                <div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-4">MEP (Microelectrólisis Percutánea)</h3>
                    <p class="text-gray-600 text-lg mb-4">Consiste en la aplicación de una corriente galvánica en el
                        orden de microamperios, en forma percutánea con el fin de generar: Analgesia / Regeneración del
                        tejido / Normalización del pH local.</p>
                </div>

                <!-- Osteopatía -->
                <div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-4">Osteopatía</h3>
                    <p class="text-gray-600 text-lg mb-4">La Osteopatía es una metodología terapéutica que trata al ser
                        humano de forma global, restableciendo el equilibrio perturbado mediante técnicas manuales
                        dirigidas a cualesquiera de los tejidos afectados.</p>
                </div>

                <!-- Rehabilitación vestibular -->
                <div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-4">Rehabilitación vestibular</h3>
                    <p class="text-gray-600 text-lg mb-4">Terapia diseñada para tratar los problemas relacionados con el
                        sistema vestibular, que es responsable del equilibrio y la orientación en el espacio.</p>
                </div>

                <!-- Nutrición -->
                <div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-4">Nutrición y antropometria</h3>
                    <p class="text-gray-600 text-lg mb-4">Evaluación del estado nutricional de las personas,
                        proporcionando información y asesoría sobre alimentación con el objetivo de promover hábitos
                        alimenticios saludables y mejorar la calidad de vida.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gray-900 text-white"
        style="background: linear-gradient(rgba(0, 0, 0, 0.59), rgba(0, 0, 0, 0.59)), url('https://v.fastcdn.co/u/13a2027a/64481490-0-running-6252827-1280.jpg'); background-size: cover;">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-5xl md:text-7xl font-bold leading-tight">
                CENTRO MEDICO INTEGRAL.<br>
                TE AYUDAMOS A LLEGAR LEJOS.
            </h2>
        </div>
    </section>

    <!-- Instalaciones -->
    <section class="py-20 bg-gray-100">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <div class="inline-block px-12 py-4 bg-gray-600 text-white rounded-full shadow-lg">
                    <h2 class="text-3xl font-bold">INSTALACIONES DE PRIMERA</h2>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-300 h-64 rounded-lg"></div>
                <div class="bg-gray-300 h-64 rounded-lg"></div>
                <div class="bg-gray-300 h-64 rounded-lg"></div>
                <div class="bg-gray-300 h-64 rounded-lg"></div>
                <div class="bg-gray-300 h-64 rounded-lg"></div>
                <div class="bg-gray-300 h-64 rounded-lg"></div>
            </div>
        </div>
    </section>

    <!-- Donde Estamos -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <div class="inline-block px-12 py-4 bg-gray-600 text-white rounded-full shadow-lg">
                    <h2 class="text-3xl font-bold">DONDE ESTAMOS</h2>
                </div>
                <p class="text-gray-600 text-lg mt-6">Hacé click y envianos un Whatsapp para ponerte en contacto con
                    nosotros.</p>
            </div>
            <div class="text-center mb-8">
                <p class="text-xl font-bold text-gray-800">Jose C Paz 5723, San Martín</p>
                <p class="text-xl text-gray-600">Buenos Aires, Argentina</p>
            </div>
            <div class="max-w-2xl mx-auto">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3283.4359767892695!2d-58.54001492422988!3d-34.619282257296365!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bcb7b0e5e5e5e5%3A0x5e5e5e5e5e5e5e5e!2sJose%20C%20Paz%205723%2C%20San%20Mart%C3%ADn%2C%20Provincia%20de%20Buenos%20Aires!5e0!3m2!1ses-419!2sar!4v1699999999999!5m2!1ses-419!2sar"
                    width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade" class="rounded-lg shadow-lg"></iframe>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-100 py-6">
        <div class="container mx-auto px-6">
            <div class="text-center text-gray-600">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>

</html>
