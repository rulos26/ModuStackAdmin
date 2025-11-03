<?php

namespace Modules\Auth\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    // use RefreshDatabase; // Comentado: la tabla users ya existe en la base de datos

    /**
     * Test de registro de usuario
     */
    public function test_user_can_register(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'status',
            'message',
            'token',
            'user' => [
                'id',
                'name',
                'email',
                'created_at',
            ],
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
        ]);
    }

    /**
     * Test de login exitoso
     */
    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'login@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'token',
            'user' => [
                'id',
                'name',
                'email',
            ],
        ]);

        $this->assertNotEmpty($response->json('token'));
    }

    /**
     * Test de login con credenciales incorrectas
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'invalid@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'invalid@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Credenciales incorrectas',
        ]);
    }

    /**
     * Test de obtener perfil autenticado
     */
    public function test_user_can_get_profile(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/auth/profile');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'user' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    /**
     * Test de logout
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Sesión cerrada exitosamente',
        ]);

        // Verificar que el token fue revocado
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'name' => 'test-token',
        ]);
    }

    /**
     * Test de validación en registro
     */
    public function test_register_requires_valid_data(): void
    {
        $response = $this->postJson('/api/auth/register', []);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'status',
            'message',
            'errors',
        ]);
    }

    /**
     * Test de validación en login
     */
    public function test_login_requires_valid_data(): void
    {
        $response = $this->postJson('/api/auth/login', []);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'status',
            'message',
            'errors',
        ]);
    }
}

