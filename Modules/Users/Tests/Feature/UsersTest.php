<?php

namespace Modules\Users\Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{
    // use RefreshDatabase; // Comentado: la tabla users ya existe en la base de datos

    /**
     * Test que verifica que la ruta /users está disponible
     */
    public function test_users_index_route_returns_success(): void
    {
        // Crear algunos usuarios de prueba
        User::factory()->count(3)->create();

        $response = $this->get('/users');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data',
            'count',
        ]);
        $response->assertJson([
            'status' => 'success',
        ]);
        $this->assertEquals(3, $response->json('count'));
    }

    /**
     * Test que verifica que la ruta /users/{id} retorna un usuario específico
     */
    public function test_users_show_route_returns_user(): void
    {
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
                'created_at',
                'updated_at',
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

    /**
     * Test que verifica que /users retorna array vacío cuando no hay usuarios
     */
    public function test_users_index_returns_empty_when_no_users(): void
    {
        $response = $this->get('/users');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'data' => [],
            'count' => 0,
        ]);
    }
}

