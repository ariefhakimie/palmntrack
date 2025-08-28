<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Yusri Bin Halim',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Ahmad bin Wahab',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        User::create([
            'name' => 'Abu bin Jalil',
            'email' => 'abu@example.com',
            'password' => Hash::make('password'),
            'role' => 'worker',
        ]);
                User::create([
            'name' => 'Kamal bin Syukur',
            'email' => 'kamal@example.com',
            'password' => Hash::make('password'),
            'role' => 'worker',
        ]);
                User::create([
            'name' => 'Halimah binti Badar',
            'email' => 'halimah@example.com',
            'password' => Hash::make('password'),
            'role' => 'worker',
        ]);
    }
}
