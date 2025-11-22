@props([
    'title' => config('app.name', 'Laravel'),
    'breadcrumbs' => [],
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/616056bedb.js" crossorigin="anonymous"></script>



    <!-- WireUI Styles & Scripts -->
    <wireui:scripts />


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    @stack('css')

</head>

<body class="font-sans antialiased bg-gray-50">


    @include('layouts.includes.admin.navegation')
    @include('layouts.includes.admin.siderbar')





    <div class="p-4 sm:ml-64">
        <div class="mt-14 overflow-x-hidden">

            <div class="flex items-center justify-between mb-4">
                <div>
                    @include('layouts.includes.admin.breadcrumb')
                </div>
                @isset($action)
                    <div>{{ $action }}</div>
                @endisset
            </div>

            {{ $slot }}
        </div>

    </div>

    @stack('modals')

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    @if (session('swal'))
        <script>
            Swal.fire(@json(session('swal')));
        </script>
    @endif

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('swal', data => {
                Swal.fire(data[0]);
            });
        });
    </script>

    <script>
        const forms = document.querySelectorAll('.delete-form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¡No podrás revertir esto!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

    @stack('js')

</body>

</html>
