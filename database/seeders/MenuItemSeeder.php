<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Category;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run()
    {
        $appetizerCategory = Category::where('name', 'Appetizers')->first()->id;
        $mainCourseCategory = Category::where('name', 'Main Course')->first()->id;
        $dessertCategory = Category::where('name', 'Desserts')->first()->id;
        $beverageCategory = Category::where('name', 'Beverages')->first()->id;
        $sideCategory = Category::where('name', 'Sides')->first()->id;

        $menuItems = [
            // Appetizers
            [
                'category_id' => $appetizerCategory,
                'name' => 'Mozzarella Sticks',
                'description' => 'Deep-fried mozzarella cheese sticks served with marinara sauce',
                'price' => 7.99,
                'is_available' => true,
            ],
            [
                'category_id' => $appetizerCategory,
                'name' => 'Chicken Wings',
                'description' => 'Crispy chicken wings tossed in your choice of sauce',
                'price' => 9.99,
                'is_available' => true,
            ],

            // Main Course
            [
                'category_id' => $mainCourseCategory,
                'name' => 'Grilled Salmon',
                'description' => 'Fresh salmon fillet grilled to perfection with lemon herb butter',
                'price' => 18.99,
                'is_available' => true,
            ],
            [
                'category_id' => $mainCourseCategory,
                'name' => 'Beef Burger',
                'description' => 'Juicy beef patty with lettuce, tomato, and cheese on a brioche bun',
                'price' => 12.99,
                'is_available' => true,
            ],
            [
                'category_id' => $mainCourseCategory,
                'name' => 'Chicken Alfredo',
                'description' => 'Fettuccine pasta with creamy alfredo sauce and grilled chicken',
                'price' => 14.99,
                'is_available' => true,
            ],

            // Desserts
            [
                'category_id' => $dessertCategory,
                'name' => 'Chocolate Cake',
                'description' => 'Rich chocolate cake with ganache frosting',
                'price' => 6.99,
                'is_available' => true,
            ],
            [
                'category_id' => $dessertCategory,
                'name' => 'Cheesecake',
                'description' => 'New York style cheesecake with berry compote',
                'price' => 7.99,
                'is_available' => true,
            ],

            // Beverages
            [
                'category_id' => $beverageCategory,
                'name' => 'Soda',
                'description' => 'Assorted soft drinks',
                'price' => 2.49,
                'is_available' => true,
            ],
            [
                'category_id' => $beverageCategory,
                'name' => 'Iced Tea',
                'description' => 'Freshly brewed iced tea',
                'price' => 2.99,
                'is_available' => true,
            ],

            // Sides
            [
                'category_id' => $sideCategory,
                'name' => 'French Fries',
                'description' => 'Crispy golden fries',
                'price' => 3.99,
                'is_available' => true,
            ],
            [
                'category_id' => $sideCategory,
                'name' => 'Side Salad',
                'description' => 'Fresh garden salad with your choice of dressing',
                'price' => 4.99,
                'is_available' => true,
            ],
        ];

        foreach ($menuItems as $item) {
            MenuItem::create($item);
        }
    }
}
