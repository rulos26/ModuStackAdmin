<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersModuleTest extends TestCase
{
    // use RefreshDatabase; // Comentado porque la tabla users ya existe

    /**
     * Test que verifica que la ruta /users está disponible
     */
    public function test_users_index_route_is_accessible(): void
    {
        $response = $this->get('/users');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data',
            'count',
        ]);
    }

    /**
     * Test que verifica que la ruta /users/{id} está disponible
     */
    public function test_users_show_route_is_accessible(): void
    {
        // Crear un usuario de prueba
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $response = $this->get("/users/{$user->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'id',
                'name',
                'email',
            ],
        ]);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'name' => 'Test User',
                'email' => 'test@example.com',
            ],
        ]);
    }

    /**
     * Test que verifica que /users/{id} retorna 404 para usuario inexistente
     */
    public function test_users_show_returns_404_for_nonexistent_user(): void
    {
        $response = $this->get('/users/99999');

        $response->assertStatus(404);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Usuario no encontrado',
        ]);
    }
}

