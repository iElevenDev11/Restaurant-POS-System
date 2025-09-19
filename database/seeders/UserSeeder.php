<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Create cashier user
        User::create([
            'name' => 'Cashier User',
            'email' => 'cashier@example.com',
            'password' => Hash::make('password'),
            'role' => 'cashier',
            'status' => 'active',
        ]);
    }
}
