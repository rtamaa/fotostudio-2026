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
        // reset cache permission
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        /*
        |----------------------------------------
        | PERMISSIONS (Shield akan generate juga)
        |----------------------------------------
        */

        $permissions = [
            // booking
            'view_booking',
            'view_any_booking',
            'create_booking',

            // package (user butuh ini)
            'view_package',
            'view_any_package',

            // admin access (optional kalau kamu tetap mau)
            'access_admin',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'web',
            ]);
        }

        /*
        |----------------------------------------
        | ROLES
        |----------------------------------------
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
        |----------------------------------------
        | ADMIN = ALL PERMISSIONS
        |----------------------------------------
        */

        $admin->syncPermissions(Permission::all());

        /*
        |----------------------------------------
        | USER = LIMITED PERMISSIONS
        |----------------------------------------
        */

        $user->syncPermissions([
            'view_booking',
            'view_any_booking',
            'create_booking',
            'view_package',
            'view_any_package',
        ]);
    }
}