<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Test unitario de ejemplo.
 * 
 * Este test verifica el funcionamiento básico de PHPUnit.
 */
class ExampleTest extends TestCase
{
    /**
     * Test básico que verifica una aserción simple.
     * 
     * Este test valida que PHPUnit funciona correctamente
     * ejecutando una aserción básica.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
        $this->assertFalse(false);
        $this->assertEquals(1, 1);
    }
}
