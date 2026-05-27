<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    \App\Models\User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        'role' => 'admin',
        // HUWAG maglagay ng email_verified_at dito
    ]);
    
    \App\Models\User::create([
        'name' => 'Customer User',
        'email' => 'customer@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password1234'),
        'role' => 'customer',
    ]);
}
}
