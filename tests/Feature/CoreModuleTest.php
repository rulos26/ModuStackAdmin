<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CoreModuleTest extends TestCase
{
    /**
     * Test que verifica que la ruta /core está disponible
     */
    public function test_core_route_is_accessible(): void
    {
        // Verificar que la ruta existe
        $this->assertTrue(
            \Illuminate\Support\Facades\Route::has('core.index'),
            'La ruta core.index debe estar registrada'
        );
        
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
    }
}

