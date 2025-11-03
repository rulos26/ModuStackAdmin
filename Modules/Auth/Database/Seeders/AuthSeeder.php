<?php

namespace Modules\Auth\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario root universal
        User::updateOrCreate(
            ['email' => 'root@system.local'],
            [
                'name' => 'root',
                'password' => Hash::make('root1234'),
                'email_verified_at' => now(),
            ]
        );

        // Crear usuario administrador de prueba
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Crear usuario de prueba adicional
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Usuario Test',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Usuarios de autenticación creados exitosamente');
        $this->command->info('   - root@system.local / root1234');
        $this->command->info('   - admin@example.com / password');
        $this->command->info('   - test@example.com / password');
    }
}

