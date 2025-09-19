<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function run()
    {
        $tables = [
            ['name' => 'Table 1', 'capacity' => 2, 'status' => 'available'],
            ['name' => 'Table 2', 'capacity' => 2, 'status' => 'available'],
            ['name' => 'Table 3', 'capacity' => 4, 'status' => 'available'],
            ['name' => 'Table 4', 'capacity' => 4, 'status' => 'available'],
            ['name' => 'Table 5', 'capacity' => 6, 'status' => 'available'],
            ['name' => 'Table 6', 'capacity' => 6, 'status' => 'available'],
            ['name' => 'Table 7', 'capacity' => 8, 'status' => 'available'],
            ['name' => 'Table 8', 'capacity' => 8, 'status' => 'available'],
            ['name' => 'Table 9', 'capacity' => 2, 'status' => 'available'],
            ['name' => 'Table 10', 'capacity' => 2, 'status' => 'available'],
            ['name' => 'Table 11', 'capacity' => 2, 'status' => 'available'],
            ['name' => 'Table 12', 'capacity' => 2, 'status' => 'available'],
            ['name' => 'Table 13', 'capacity' => 2, 'status' => 'available'],
            ['name' => 'Table 14', 'capacity' => 2, 'status' => 'available'],
            ['name' => 'Table 15', 'capacity' => 2, 'status' => 'available'],
            ['name' => 'Table 16', 'capacity' => 2, 'status' => 'available'],
            ['name' => 'Table 17', 'capacity' => 2, 'status' => 'available'],
            ['name' => 'Table 18', 'capacity' => 2, 'status' => 'available'],
        ];

        foreach ($tables as $table) {
            Table::create($table);
        }
    }
}
