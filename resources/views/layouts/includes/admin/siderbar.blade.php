@php
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
             'ico' => 'fa-solid fa-calendar-check',
             'href' => route('admin.socialworks.index'),
             'active' => request()->routeIs('admin.socialworks.*'),
        ],

    ];
@endphp

<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            @foreach ($links as $link)
                <li>
                    @isset($link['Headers'])
                        <h1 class="px-3 mt-4 mb-2 text-gray-500 uppercase dark:text-gray-400 text-sm">{{ $link['Headers'] }}
                        </h1>
                    @else
                        @isset($link['submenu'])
                            <button type="button"
                                class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ $link['active'] ? 'bg-gray-100 dark:bg-gray-700' : '' }}"
                                aria-controls="dropdown-{{ $loop->index }}" data-collapse-toggle="dropdown-{{ $loop->index }}">
                                <span class="inline-flex justify-center items-center text-gray-500">
                                    <i class="{{ $link['ico'] }}"></i>
                                </span>
                                <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">{{ $link['name'] }}</span>
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m1 1 4 4 4-4" />
                                </svg>
                            </button>
                            <ul id="dropdown-{{ $loop->index }}" class="hidden py-2 space-y-2">
                                @foreach($link['submenu'] as $sublink)
                                    <li>
                                        <a href="{{ $sublink['href'] }}"
                                            class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ $sublink['active'] ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                            {{ $sublink['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <a href="{{ $link['href'] }}"
                                class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ $link['active'] ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                <span class="inline-flex justify-center items-center text-gray-500">
                                    <i class="{{ $link['ico'] }}"></i>
                                </span>
                                <span class="ms-3">{{ $link['name'] }}</span>
                            </a>
                        @endisset
                @endisset
                </li>
            @endforeach


        </ul>
    </div>
</aside>
