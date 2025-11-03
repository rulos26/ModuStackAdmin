<?php

namespace Modules\Core\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CoreTest extends TestCase
{
    /**
     * Test que verifica que la ruta /core está disponible y retorna el JSON esperado
     */
    public function test_core_route_returns_success_response(): void
    {
        $response = $this->get('/core');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'module',
            'version',
        ]);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Core funcionando',
            'module' => 'Core',
        ]);
    }

    /**
     * Test que verifica que los helpers de Core están disponibles
     */
    public function test_core_helpers_are_available(): void
    {
        $this->assertTrue(function_exists('core_version'));
        $this->assertTrue(function_exists('core_config'));
        $this->assertTrue(function_exists('core_log'));
        
        $version = core_version();
        $this->assertIsString($version);
        $this->assertEquals('1.0.0', $version);
    }

    /**
     * Test que verifica la configuración del módulo Core
     */
    public function test_core_config_is_accessible(): void
    {
        $config = core_config('name');
        $this->assertEquals('Core', $config);
        
        $version = core_config('version');
        $this->assertEquals('1.0.0', $version);
    }
}

