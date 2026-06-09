<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // CREATE ROLES FIRST
        // =========================
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admin']);
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // =========================
        // ADMIN USER
        // =========================
        $admin = User::updateOrCreate(
            ['email' => 'admin@studiophoto.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        $admin->assignRole('admin');

        // =========================
        // NORMAL USER
        // =========================
        $user = User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User Biasa',
                'password' => Hash::make('password'),
            ]
        );

        $user->assignRole('user');
    }
}