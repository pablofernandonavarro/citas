<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
USE Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'paciente',
            'doctor',
            'recepcionista',
            'administrador'];

        foreach ($roles as $role) {
            Role::create([
                'name' => $role,
                
            ]);
        }
    }
}


