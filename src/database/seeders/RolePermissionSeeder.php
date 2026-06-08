<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        /*
        |----------------------------------
        | FORCE GUARD CONSISTENCY
        |----------------------------------
        */
        Permission::query()->update(['guard_name' => 'web']);
        Role::query()->update(['guard_name' => 'web']);

        /*
        |----------------------------------
        | ROLES
        |----------------------------------
        */

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user = Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'web',
        ]);

        /*
        |----------------------------------
        | ADMIN = ALL PERMISSIONS (SAFE SHIELD WAY)
        |----------------------------------
        */

        $admin->syncPermissions(Permission::all());

        /*
        |----------------------------------
        | USER = LIMITED BUT SAFE ACCESS
        |----------------------------------
        */

        $userPermissions = Permission::whereIn('name', [

            // Booking
            'view_booking',
            'view_any_booking',
            'create_booking',

            // Package (wajib untuk pilih paket)
            'view_package',
            'view_any_package',

            // Payment (kalau user bisa lihat pembayaran sendiri)
            // kalau tidak perlu, hapus ini
            'view_payment',
            'view_any_payment',

        ])->pluck('name');

        $user->syncPermissions($userPermissions);
    }
}