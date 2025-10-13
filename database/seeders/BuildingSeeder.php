<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Building;

class BuildingSeeder extends Seeder
{
    public function run(): void
    {
        $buildings = [
            ['name' => 'Gedung Office'],
            ['name' => 'Gedung Store'],
            ['name' => 'Gedung SC'],
        ];

        foreach ($buildings as $building) {
            Building::create($building);
        }
    }
}
