<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions =[
            'access_dashboard',


            'create_role',
            'read_role',
            'update_role',
            'delete_role',

            'create_user',
            'read_user',
            'update_user',
            'delete_user',

            'read_patient',
            'update_patient',

            'access_socialwork',
            'read_socialwork',
            'create_socialwork',
            'update_socialwork',
            'delete_socialwork',

            'read_doctor',
            'update_doctor',

            'create_appointment',
            'read_appointment',
            'update_appointment',
            'delete_appointment',

            'read_calendar',





        ];

        foreach($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
            ]);
        }
    }
}
