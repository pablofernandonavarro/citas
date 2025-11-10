<?php 
  return  [
        [   
            'type' => 'link',
            'title' => 'Dashboard',
            'icon' => 'fa-solid fa-gauge',
            'route' => 'admin.dashboard',
            'active' => 'admin.dashboard',
        ],
        [    
            'type' => 'header',
            'title' => 'Gestión',
        ],
        [   
            'type' => 'link',
            'title' => 'Roles y permisos',
            'icon' => 'fa-solid fa-shield-halved',
            'route' => 'admin.roles.index',
            'active' => 'admin.roles.*',


        ],

        [
            'type' => 'link',
            'title' => 'Usuarios',
            'icon' => 'fa-solid fa-users',
            'route' => 'admin.users.index',
            'active' =>'admin.users.*',
        ],
        [
            'type' => 'link',
            'title' => 'Pacientes',
            'icon' => 'fa-solid fa-user-injured',
            'route' => 'admin.patients.index',
            'active' => 'admin.patients.*',
        ],
         [
            'type' => 'link',
             'title' => 'obras sociales',
             'icon' => 'fa-solid fa-hand-holding-medical',
             'route' => 'admin.socialworks.index',
             'active' => 'admin.socialworks.*',
        ],
            [
                'type' => 'link',
                'title' => 'Doctores',
                'icon' => 'fa-solid fa-user-doctor',
                'route' => 'admin.doctors.index',
                'active' =>'admin.doctors.*',
            ],
        [
            'type' => 'link',
            'title' => 'Turnos',
            'icon' => 'fa-solid fa-calendar-check',
            'route' => 'admin.appointments.index',
            'active' => 'admin.appointments.*',
        ],
        [
            'type' => 'link',
            'title' => 'Calendario',
            'icon' => 'fa-solid fa-calendar-days',
            'route' => 'admin.calendar.index',
            'active' =>'admin.calendar.*',
        ],
    ];
