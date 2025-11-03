<?php
/**
 * Diagnóstico rápido de errores
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Diagnóstico ModuStackUser<br><br>";

// Verificar si puede leer el index.php
if (file_exists(__DIR__ . '/index.php')) {
    echo "✅ index.php existe<br>";
} else {
    echo "❌ index.php NO existe<br>";
}

// Verificar vendor
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "✅ vendor/autoload.php existe<br>";
} else {
    echo "❌ vendor/autoload.php NO existe<br>";
}

// Intentar cargar Laravel
try {
    define('LARAVEL_START', microtime(true));
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo "✅ Laravel se inicializa correctamente<br>";
} catch (Throwable $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

