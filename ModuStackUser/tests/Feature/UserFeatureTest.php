<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que verifica el acceso a la ruta raíz.
     */
    public function test_home_route_returns_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test que verifica que la ruta home existe y es accesible.
     */
    public function test_home_route_is_accessible(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewIs('welcome');
    }

    /**
     * Test que verifica la creación de usuario a través de factory.
     */
    public function test_user_factory_creates_valid_user(): void
    {
        $user = User::factory()->make();

        $this->assertNotEmpty($user->name);
        $this->assertNotEmpty($user->email);
        $this->assertNotEmpty($user->password);
    }

    /**
     * Test que verifica que se pueden crear múltiples usuarios.
     */
    public function test_can_create_multiple_users(): void
    {
        $users = User::factory()->count(5)->create();

        $this->assertCount(5, $users);
        $this->assertEquals(5, User::count());
    }
}

