<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions
        $this->call([
            RolePermissionSeeder::class,
        ]);

        // Create root user with Super Usuario role
        $rootUser = User::firstOrCreate(
            ['email' => 'root@example.com'],
            [
                'name' => 'root',
                'password' => bcrypt('Root@12345'),
                'email_verified_at' => now(),
            ]
        );
        $rootUser->syncRoles(['Super Usuario']);

        // Create a SuperAdmin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('Admin@12345'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->syncRoles(['SuperAdmin']);

        // Create test users with different roles
        User::factory(5)->create()->each(function (User $user) {
            if (! $user->hasRole('User')) {
                $user->assignRole('User');
            }
        });
    }
}
