<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Test de ejemplo para verificar el funcionamiento básico de la aplicación.
 * 
 * Este test verifica que la aplicación Laravel responde correctamente
 * en la ruta raíz.
 */
class ExampleTest extends TestCase
{
    /**
     * Test que verifica que la aplicación retorna una respuesta exitosa.
     * 
     * Este test valida:
     * - La ruta raíz está accesible
     * - La respuesta tiene código 200
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('welcome');
    }
}
