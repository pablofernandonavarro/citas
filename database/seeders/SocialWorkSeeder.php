<?php

namespace Database\Seeders;

use App\Models\SocialWork;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class SocialWorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $socialWorks = [
          'Pami',
          'Osde',
          'opdea',
          'Swiss Medical',
      ];
        foreach ($socialWorks as $name) {
            SocialWork::create([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
