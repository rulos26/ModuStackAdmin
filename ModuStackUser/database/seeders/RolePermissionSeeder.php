<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Users
            'view users',
            'create users',
            'update users',
            'delete users',
            'restore users',
            'force delete users',

            // Roles
            'view roles',
            'create roles',
            'update roles',
            'delete roles',
            'restore roles',
            'force delete roles',

            // Activity Logs
            'view activity logs',
            'export activity logs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $allPermissions = Permission::all();

        $superUsuario = Role::firstOrCreate(['name' => 'Super Usuario']);
        $superUsuario->syncPermissions($allPermissions);

        $superAdmin = Role::firstOrCreate(['name' => 'SuperAdmin']);
        $superAdmin->syncPermissions($allPermissions);

        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions([
            'view users',
            'create users',
            'update users',
            'delete users',
            'view roles',
            'view activity logs',
            'export activity logs',
        ]);

        $editor = Role::firstOrCreate(['name' => 'Editor']);
        $editor->syncPermissions([
            'view users',
            'view activity logs',
        ]);

        Role::firstOrCreate(['name' => 'User']);
    }
}
