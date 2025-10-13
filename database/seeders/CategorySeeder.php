<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Furniture'],
            ['name' => 'IT'],
            ['name' => 'Elektronik'],
            ['name' => 'Vehicle / Kendaraan'],
            ['name' => 'Peralatan Kantor'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
