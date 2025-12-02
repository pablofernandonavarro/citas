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
            'Cardiología',
            'Dermatología',
            'Neurología',
            'Pediatría',
            'Psiquiatría',
            'Oncología',
            'Ginecología',
            'Ortopedia',
            'Radiología',
            'Urología',
            'Kinesiología'
        ];

        foreach ($especilities as $especiality) {
           Speciality::create([
            'name' => $especiality]);
        }
    }
}
