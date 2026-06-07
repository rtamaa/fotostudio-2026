<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@studiophoto.com'], ['name' => 'Admin', 'password' => Hash::make('password'), 'role' => UserRole::ADMIN]);
        User::updateOrCreate(['email' => 'user@example.com'], ['name' => 'User Biasa', 'password' => Hash::make('password'), 'role' => UserRole::USER]);
    }
}