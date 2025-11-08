<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user=User::factory()->create([
            'name' => 'Veronica Navarro',
            'email' => 'vero-nav@hotmail.com',
            'password' => bcrypt('12345678'),
            'dni' => '12345678A',
            'phone' => '123456789',
            'address' => '123 Main St',

        ]);
        $user->assignRole('Doctor');
        $user->doctor()->create([
            
            'medical_license_number' => '123456789',
            'biography' => 'Especialista en cardiología con 10 años de experiencia.',
        ]);

         $user=User::factory()->create([
            'name' => 'Navarro Pablo',
            'email' => 'pablofernandonavarro@gmail.com',
            'password' => bcrypt('12345678'),
            'dni' => '24448030',
            'phone' => '1569975132',
            'address' => 'moseñor larumbe 3151 dto 1102',

        ]);
        $user->assignRole('Admin');

          $user=User::factory()->create([
            'name' => 'olga Aranda',
            'email' => 'olga@gmail.com',
            'password' => bcrypt('12345678'),
            'dni' => '24448030',
            'phone' => '1569975132',
            'address' => 'moseñor larumbe 3151 dto 1102',

        ]);
        $user->assignRole('Paciente');


    }
}
