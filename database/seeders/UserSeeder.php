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
        User::factory()->create([
            'name' => 'Veronica Navarro',
            'email' => 'pablofernandonavarr@gmail.com',
            'password' => bcrypt('12345678'),
            'dni' => '12345678A',
            'phone' => '123456789',
            'address' => '123 Main St',

        ])->assignRole('Doctor');
    }
}
