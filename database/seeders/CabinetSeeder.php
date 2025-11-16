<?php

namespace Database\Seeders;

use App\Models\Cabinet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CabinetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabinets = [
            [
                'name' => 'Gabinete 1',
                'description' => 'Gabinete de kinesiología equipado con camilla y equipos de rehabilitación',
                'is_active' => true,
            ],
            [
                'name' => 'Gabinete 2',
                'description' => 'Gabinete de kinesiología con equipamiento especializado',
                'is_active' => true,
            ],
            [
                'name' => 'Gabinete 3',
                'description' => 'Gabinete de kinesiología para terapias físicas',
                'is_active' => true,
            ],
        ];

        foreach ($cabinets as $cabinet) {
            Cabinet::create($cabinet);
        }
    }
}
