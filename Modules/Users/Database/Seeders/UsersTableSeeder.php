<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario administrador
        User::updateOrCreate(
            ['email' => 'admin@modustack.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Usuario demo
        User::updateOrCreate(
            ['email' => 'demo@modustack.com'],
            [
                'name' => 'Usuario Demo',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Crear usuarios adicionales usando factory
        User::factory()->count(10)->create();

        $this->command->info('✅ Usuarios creados exitosamente');
        $this->command->info('   - admin@modustack.com / password');
        $this->command->info('   - demo@modustack.com / password');
        $this->command->info('   - 10 usuarios adicionales generados');
    }
}

