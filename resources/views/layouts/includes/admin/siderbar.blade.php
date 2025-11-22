{{-- @php
    $links = [
        [
            'name' => 'Dashboard',
            'ico' => 'fa-solid fa-gauge',
            'href' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard'),
        ],
        [
            'Headers' => 'Gestión',
        ],
        [
            'name' => 'Roles y permisos',
            'ico' => 'fa-solid fa-shield-halved',
            'href' => route('admin.roles.index'),
            'active' => request()->routeIs('admin.roles.*'),


        ],

        [
            'name' => 'Usuarios',
            'ico' => 'fa-solid fa-users',
            'href' => route('admin.users.index'),
            'active' => request()->routeIs('admin.users.*'),
        ],
        [
            'name' => 'Pacientes',
            'ico' => 'fa-solid fa-user-injured',
            'href' => route('admin.patients.index'),
            'active' => request()->routeIs('admin.patients.*'),
        ],
         [
             'name' => 'obras sociales',
             'ico' => 'fa-solid fa-hand-holding-medical',
             'href' => route('admin.socialworks.index'),
             'active' => request()->routeIs('admin.socialworks.*'),
        ],
            [
                'name' => 'Doctores',
                'ico' => 'fa-solid fa-user-doctor',
                'href' => route('admin.doctors.index'),
                'active' => request()->routeIs('admin.doctors.*'),
            ],
        [
            'name' => 'Turnos',
            'ico' => 'fa-solid fa-calendar-check',
            'href' => route('admin.appointments.index'),
            'active' => request()->routeIs('admin.appointments.*'),
        ],
        [
            'name' => 'Calendario',
            'ico' => 'fa-solid fa-calendar-days',
            'href' => route('admin.calendar.index'),
            'active' => request()->routeIs('admin.calendar.*'),
        ],
    ];

@endphp --}}

<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            @foreach($siderbarItems as $item)
                <li>
                    {!! $item->render() !!}
                </li>
            @endforeach
          
        </ul>
    </div>
</aside>
