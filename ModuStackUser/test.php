<?php
/**
 * Script de diagnóstico para ModuStackUser
 * Este archivo ayuda a identificar problemas de configuración
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Diagnóstico ModuStackUser</h1>";
echo "<hr>";

// 1. Verificar PHP
echo "<h2>1. Versión de PHP</h2>";
echo "Versión: " . phpversion() . "<br>";
echo "Requiere: >= 8.2<br>";
echo phpversion() >= '8.2' ? "✅ Compatible" : "❌ No compatible";
echo "<br><br>";

// 2. Verificar extensiones necesarias
echo "<h2>2. Extensiones PHP</h2>";
$required = ['mbstring', 'openssl', 'pdo', 'tokenizer', 'xml', 'ctype', 'json', 'fileinfo'];
foreach ($required as $ext) {
    $loaded = extension_loaded($ext);
    echo $ext . ": " . ($loaded ? "✅" : "❌") . "<br>";
}
echo "<br>";

// 3. Verificar archivos críticos
echo "<h2>3. Archivos Críticos</h2>";
$critical_files = [
    'vendor/autoload.php',
    'bootstrap/app.php',
    '.env',
    'index.php',
];
foreach ($critical_files as $file) {
    $exists = file_exists(__DIR__ . '/' . $file);
    echo $file . ": " . ($exists ? "✅" : "❌") . "<br>";
}
echo "<br>";

// 4. Verificar permisos de storage
echo "<h2>4. Permisos de Storage</h2>";
$storage_path = __DIR__ . '/storage';
if (is_dir($storage_path)) {
    echo "Directorio storage existe: ✅<br>";
    $writable = is_writable($storage_path);
    echo "Storage escribible: " . ($writable ? "✅" : "❌") . "<br>";
    
    $framework_path = $storage_path . '/framework';
    if (is_dir($framework_path)) {
        $fw_writable = is_writable($framework_path);
        echo "Framework escribible: " . ($fw_writable ? "✅" : "❌") . "<br>";
    }
} else {
    echo "Directorio storage: ❌ No existe<br>";
}
echo "<br>";

// 5. Intentar cargar autoload
echo "<h2>5. Autoload de Composer</h2>";
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    try {
        require __DIR__ . '/vendor/autoload.php';
        echo "✅ Autoload cargado correctamente<br>";
    } catch (Exception $e) {
        echo "❌ Error al cargar autoload: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ vendor/autoload.php no existe<br>";
}
echo "<br>";

// 6. Verificar .env
echo "<h2>6. Variables de Entorno (.env)</h2>";
if (file_exists(__DIR__ . '/.env')) {
    echo "✅ .env existe<br>";
    $env_content = file_get_contents(__DIR__ . '/.env');
    if (strpos($env_content, 'APP_KEY=') !== false) {
        preg_match('/APP_KEY=(.*)/', $env_content, $matches);
        $key = isset($matches[1]) ? trim($matches[1]) : '';
        if (!empty($key) && strlen($key) > 10) {
            echo "✅ APP_KEY configurada<br>";
        } else {
            echo "❌ APP_KEY vacía o inválida<br>";
        }
    } else {
        echo "❌ APP_KEY no encontrada en .env<br>";
    }
} else {
    echo "❌ .env no existe<br>";
}
echo "<br>";

// 7. Intentar inicializar Laravel
echo "<h2>7. Inicialización de Laravel</h2>";
if (file_exists(__DIR__ . '/bootstrap/app.php')) {
    try {
        define('LARAVEL_START', microtime(true));
        require __DIR__ . '/vendor/autoload.php';
        $app = require_once __DIR__ . '/bootstrap/app.php';
        echo "✅ Laravel inicializado correctamente<br>";
    } catch (Throwable $e) {
        echo "❌ Error al inicializar Laravel:<br>";
        echo "<pre>" . $e->getMessage() . "\n" . $e->getTraceAsString() . "</pre>";
    }
} else {
    echo "❌ bootstrap/app.php no existe<br>";
}

echo "<hr>";
echo "<p><strong>Si todos los checks muestran ✅, el problema puede estar en el .htaccess o configuración del servidor.</strong></p>";

