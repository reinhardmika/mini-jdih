<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin JDIH',
            'email' => 'admin@jdih.test',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);
    }
}