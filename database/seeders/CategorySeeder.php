<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Appetizers', 'description' => 'Starters and small dishes'],
            ['name' => 'Main Course', 'description' => 'Main dishes'],
            ['name' => 'Desserts', 'description' => 'Sweet treats'],
            ['name' => 'Beverages', 'description' => 'Drinks and refreshments'],
            ['name' => 'Sides', 'description' => 'Side dishes'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
