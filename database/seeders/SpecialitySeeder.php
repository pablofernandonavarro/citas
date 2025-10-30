<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Speciality;

class SpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $especilities = [
            'Cardiology',
            'Dermatology',
            'Neurology',
            'Pediatrics',
            'Psychiatry',
            'Oncology',
            'Gynecology',
            'Orthopedics',
            'Radiology',
            'Urology'
        ];

        foreach ($especilities as $especiality) {
           Speciality::create([
            'name' => $especiality]);
        }
    }
}
