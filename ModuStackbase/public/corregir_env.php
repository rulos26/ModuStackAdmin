<?php
/**
 * Script para corregir .env - Ejecutar UNA SOLA VEZ y luego ELIMINAR
 * 
 * Este script:
 * 1. Genera APP_KEY si no existe
 * 2. Corrige APP_URL con la ruta correcta
 * 
 * ACCEDER: https://rulossoluciones.com/ModuStackAdmin/ModuStackbase/public/corregir_env.php
 * ELIMINAR después de ejecutar
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$basePath = dirname(__DIR__);
$envPath = $basePath . '/.env';

if (!file_exists($envPath)) {
    die("❌ ERROR: No se encuentra el archivo .env");
}

$envContent = file_get_contents($envPath);
$cambios = [];

// 1. Generar APP_KEY si no existe o está vacío
if (!preg_match('/^APP_KEY=base64:.+$/m', $envContent)) {
    // Generar nueva clave
    $key = 'base64:' . base64_encode(random_bytes(32));
    if (preg_match('/^APP_KEY=.*$/m', $envContent)) {
        // Reemplazar APP_KEY existente
        $envContent = preg_replace('/^APP_KEY=.*$/m', 'APP_KEY=' . $key, $envContent);
        $cambios[] = "✅ APP_KEY generado y actualizado";
    } else {
        // Agregar APP_KEY si no existe
        $envContent = preg_replace('/^(APP_NAME=.*)$/m', "$1\nAPP_KEY=$key", $envContent);
        $cambios[] = "✅ APP_KEY generado y agregado";
    }
} else {
    $cambios[] = "ℹ️ APP_KEY ya existe, no se modificó";
}

// 2. Corregir APP_URL
$appUrlCorrecta = 'https://rulossoluciones.com/ModuStackAdmin/ModuStackbase';
if (preg_match('/^APP_URL=(.+)$/m', $envContent, $matches)) {
    $appUrlActual = trim($matches[1]);
    if ($appUrlActual !== $appUrlCorrecta) {
        $envContent = preg_replace('/^APP_URL=.*$/m', 'APP_URL=' . $appUrlCorrecta, $envContent);
        $cambios[] = "✅ APP_URL corregido de '$appUrlActual' a '$appUrlCorrecta'";
    } else {
        $cambios[] = "ℹ️ APP_URL ya está correcto";
    }
} else {
    // Agregar APP_URL si no existe
    $envContent = preg_replace('/^(APP_KEY=.*)$/m', "$1\nAPP_URL=$appUrlCorrecta", $envContent);
    $cambios[] = "✅ APP_URL agregado: $appUrlCorrecta";
}

// Guardar cambios
if (file_put_contents($envPath, $envContent)) {
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Corrección .env</title>";
    echo "<style>body{font-family:Arial;max-width:600px;margin:50px auto;padding:20px;}";
    echo ".success{background:#d4edda;border:1px solid #28a745;padding:15px;border-radius:5px;margin:10px 0;}";
    echo ".info{background:#d1ecf1;border:1px solid #0c5460;padding:15px;border-radius:5px;margin:10px 0;}";
    echo "</style></head><body>";
    echo "<h1>✅ Corrección de .env Completada</h1>";
    echo "<div class='success'>";
    echo "<h2>Cambios realizados:</h2><ul>";
    foreach ($cambios as $cambio) {
        echo "<li>$cambio</li>";
    }
    echo "</ul></div>";
    echo "<div class='info'>";
    echo "<h2>⚠️ IMPORTANTE:</h2>";
    echo "<p><strong>ELIMINA este archivo ahora:</strong></p>";
    echo "<code>rm public/corregir_env.php</code>";
    echo "<p>O elimínalo desde el panel de control de tu hosting.</p>";
    echo "</div>";
    echo "</body></html>";
} else {
    die("❌ ERROR: No se pudo escribir en .env. Verifica permisos.");
}

