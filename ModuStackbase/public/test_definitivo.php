<?php
/**
 * TEST DEFINITIVO - Diagnóstico Exhaustivo del Error 403 Forbidden
 * 
 * Este script realiza pruebas completas para identificar la causa del error 403
 * Acceder desde: https://rulossoluciones.com/ModuStackAdmin/ModuStackbase/public/test_definitivo.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Función para mostrar resultados
function mostrarResultado($titulo, $resultado, $detalles = '', $tipo = 'info') {
    $icono = $resultado ? '✅' : '❌';
    $color = $resultado ? 'green' : 'red';
    echo "<div style='margin: 10px 0; padding: 10px; border-left: 4px solid $color; background: #f5f5f5;'>";
    echo "<strong style='color: $color;'>$icono $titulo:</strong> ";
    echo $resultado ? '<span style="color: green;">PASÓ</span>' : '<span style="color: red;">FALLÓ</span>';
    if ($detalles) {
        echo "<br><small style='color: #666;'>$detalles</small>";
    }
    echo "</div>";
}

// Función para verificar si un archivo existe y es legible
function verificarArchivo($ruta, $descripcion) {
    $existe = file_exists($ruta);
    $legible = $existe ? is_readable($ruta) : false;
    $detalles = $existe ? ($legible ? "Archivo existe y es legible" : "Archivo existe pero NO es legible") : "Archivo NO existe";
    mostrarResultado($descripcion, $existe && $legible, $detalles . " - Ruta: $ruta");
    return $existe && $legible;
}

// Función para verificar permisos
function verificarPermisos($ruta, $descripcion) {
    if (!file_exists($ruta)) {
        mostrarResultado($descripcion, false, "El archivo/directorio no existe: $ruta");
        return false;
    }
    $perms = fileperms($ruta);
    $permisos = substr(sprintf('%o', $perms), -4);
    $legible = is_readable($ruta);
    $escribible = is_writable($ruta);
    $detalles = "Permisos: $permisos | Legible: " . ($legible ? 'Sí' : 'No') . " | Escribible: " . ($escribible ? 'Sí' : 'No');
    mostrarResultado($descripcion, $legible, $detalles);
    return $legible;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Definitivo - Diagnóstico 403 Forbidden</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #f0f0f0;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .seccion {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .seccion h2 {
            color: #667eea;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .resumen {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .error {
            background: #f8d7da;
            border: 2px solid #dc3545;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
        }
        .success {
            background: #d4edda;
            border: 2px solid #28a745;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
        }
        pre {
            background: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
            border-left: 4px solid #667eea;
        }
        .codigo {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔍 TEST DEFINITIVO - Diagnóstico Exhaustivo</h1>
        <p><strong>Fecha:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        <p><strong>URL:</strong> <?php echo $_SERVER['REQUEST_URI'] ?? 'N/A'; ?></p>
        <p><strong>Servidor:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'N/A'; ?></p>
    </div>

    <?php
    $errores = [];
    $advertencias = [];
    $exitos = [];

    // ============================================
    // SECCIÓN 1: INFORMACIÓN DEL SERVIDOR
    // ============================================
    echo "<div class='seccion'>";
    echo "<h2>1. Información del Servidor</h2>";
    
    mostrarResultado("Versión PHP", true, "Versión: " . PHP_VERSION);
    mostrarResultado("SAPI (Server API)", true, "Tipo: " . php_sapi_name());
    mostrarResultado("Sistema Operativo", true, "OS: " . PHP_OS);
    mostrarResultado("Document Root", true, "Ruta: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A'));
    mostrarResultado("Script Filename", true, "Ruta: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'N/A'));
    mostrarResultado("Request URI", true, "URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A'));
    mostrarResultado("HTTP Host", true, "Host: " . ($_SERVER['HTTP_HOST'] ?? 'N/A'));
    
    // Verificar si estamos en un subdirectorio
    $subdirectorio = strpos($_SERVER['REQUEST_URI'] ?? '', '/ModuStackAdmin/ModuStackbase') !== false;
    mostrarResultado("Detectado Subdirectorio", $subdirectorio, $subdirectorio ? "Sí, estamos en un subdirectorio" : "No, estamos en la raíz");
    
    echo "</div>";

    // ============================================
    // SECCIÓN 2: ESTRUCTURA DE DIRECTORIOS
    // ============================================
    echo "<div class='seccion'>";
    echo "<h2>2. Estructura de Directorios</h2>";
    
    $basePath = dirname(__DIR__);
    $publicPath = __DIR__;
    
    echo "<div class='codigo'>";
    echo "Ruta Base del Proyecto: $basePath<br>";
    echo "Ruta Public: $publicPath<br>";
    echo "Ruta Actual del Script: " . __FILE__ . "<br>";
    echo "</div>";
    
    // Verificar directorios principales
    verificarArchivo($basePath, "Directorio raíz del proyecto");
    verificarArchivo($publicPath, "Directorio public");
    verificarArchivo($basePath . '/app', "Directorio app");
    verificarArchivo($basePath . '/bootstrap', "Directorio bootstrap");
    verificarArchivo($basePath . '/config', "Directorio config");
    verificarArchivo($basePath . '/routes', "Directorio routes");
    verificarArchivo($basePath . '/storage', "Directorio storage");
    verificarArchivo($basePath . '/vendor', "Directorio vendor");
    
    echo "</div>";

    // ============================================
    // SECCIÓN 3: ARCHIVOS CRÍTICOS
    // ============================================
    echo "<div class='seccion'>";
    echo "<h2>3. Archivos Críticos</h2>";
    
    $archivosCriticos = [
        $publicPath . '/index.php' => 'public/index.php',
        $basePath . '/bootstrap/app.php' => 'bootstrap/app.php',
        $basePath . '/.env' => '.env',
        $basePath . '/vendor/autoload.php' => 'vendor/autoload.php',
        $basePath . '/routes/web.php' => 'routes/web.php',
        $basePath . '/.htaccess' => '.htaccess (raíz)',
        $publicPath . '/.htaccess' => 'public/.htaccess',
    ];
    
    foreach ($archivosCriticos as $ruta => $descripcion) {
        verificarArchivo($ruta, $descripcion);
    }
    
    // Verificar que NO existe index.php en la raíz (correcto)
    $indexRaiz = $basePath . '/index.php';
    $noExisteIndexRaiz = !file_exists($indexRaiz);
    mostrarResultado("index.php NO existe en raíz (correcto)", $noExisteIndexRaiz, 
        $noExisteIndexRaiz ? "Correcto: No debe existir" : "ERROR: Existe index.php en la raíz");
    
    echo "</div>";

    // ============================================
    // SECCIÓN 4: PERMISOS DE ARCHIVOS
    // ============================================
    echo "<div class='seccion'>";
    echo "<h2>4. Permisos de Archivos y Directorios</h2>";
    
    verificarPermisos($basePath, "Directorio raíz");
    verificarPermisos($publicPath, "Directorio public");
    verificarPermisos($publicPath . '/index.php', "public/index.php");
    verificarPermisos($basePath . '/storage', "Directorio storage");
    verificarPermisos($basePath . '/bootstrap/cache', "Directorio bootstrap/cache");
    verificarPermisos($basePath . '/.htaccess', "Archivo .htaccess (raíz)");
    verificarPermisos($publicPath . '/.htaccess', "Archivo public/.htaccess");
    
    echo "</div>";

    // ============================================
    // SECCIÓN 5: CONFIGURACIÓN .HTACCESS
    // ============================================
    echo "<div class='seccion'>";
    echo "<h2>5. Configuración .htaccess</h2>";
    
    // Leer .htaccess de la raíz
    $htaccessRaiz = $basePath . '/.htaccess';
    if (file_exists($htaccessRaiz)) {
        $contenidoRaiz = file_get_contents($htaccessRaiz);
        mostrarResultado(".htaccess existe en raíz", true, "Tamaño: " . filesize($htaccessRaiz) . " bytes");
        
        // Verificar contenido
        $tieneRewriteEngine = strpos($contenidoRaiz, 'RewriteEngine On') !== false;
        $tieneRewriteBase = strpos($contenidoRaiz, 'RewriteBase') !== false;
        $tienePublicIndex = strpos($contenidoRaiz, 'public/index.php') !== false;
        
        mostrarResultado("Contiene RewriteEngine On", $tieneRewriteEngine);
        mostrarResultado("Contiene RewriteBase", $tieneRewriteBase, $tieneRewriteBase ? "RewriteBase está configurado" : "RewriteBase NO está configurado (puede ser correcto)");
        mostrarResultado("Redirige a public/index.php", $tienePublicIndex);
        
        echo "<div class='codigo'><strong>Contenido del .htaccess (raíz):</strong><br>";
        echo "<pre>" . htmlspecialchars($contenidoRaiz) . "</pre>";
        echo "</div>";
    } else {
        mostrarResultado(".htaccess NO existe en raíz", false, "Esto puede ser correcto si el servidor apunta a public/");
    }
    
    // Leer .htaccess de public
    $htaccessPublic = $publicPath . '/.htaccess';
    if (file_exists($htaccessPublic)) {
        $contenidoPublic = file_get_contents($htaccessPublic);
        mostrarResultado(".htaccess existe en public/", true);
        
        echo "<div class='codigo'><strong>Contenido del public/.htaccess:</strong><br>";
        echo "<pre>" . htmlspecialchars($contenidoPublic) . "</pre>";
        echo "</div>";
    } else {
        mostrarResultado(".htaccess NO existe en public/", false, "ERROR: Debe existir");
    }
    
    echo "</div>";

    // ============================================
    // SECCIÓN 6: CONFIGURACIÓN DE LARAVEL
    // ============================================
    echo "<div class='seccion'>";
    echo "<h2>6. Configuración de Laravel</h2>";
    
    // Verificar autoload
    if (file_exists($basePath . '/vendor/autoload.php')) {
        require_once $basePath . '/vendor/autoload.php';
        mostrarResultado("Autoload de Composer", true, "Cargado correctamente");
    } else {
        mostrarResultado("Autoload de Composer", false, "No se pudo cargar");
    }
    
    // Verificar .env
    $envPath = $basePath . '/.env';
    if (file_exists($envPath)) {
        mostrarResultado("Archivo .env existe", true);
        $envContent = file_get_contents($envPath);
        $tieneAppKey = strpos($envContent, 'APP_KEY=') !== false && strpos($envContent, 'APP_KEY=') !== strpos($envContent, 'APP_KEY=');
        $tieneAppUrl = strpos($envContent, 'APP_URL=') !== false;
        mostrarResultado(".env contiene APP_KEY", $tieneAppKey);
        mostrarResultado(".env contiene APP_URL", $tieneAppUrl);
        
        // Extraer APP_URL
        if (preg_match('/APP_URL=(.+)/', $envContent, $matches)) {
            $appUrl = trim($matches[1]);
            mostrarResultado("APP_URL configurado", true, "Valor: $appUrl");
        }
    } else {
        mostrarResultado("Archivo .env existe", false, "ERROR: Debe existir");
    }
    
    // Intentar cargar Laravel
    try {
        if (file_exists($basePath . '/bootstrap/app.php')) {
            $app = require_once $basePath . '/bootstrap/app.php';
            mostrarResultado("Laravel se puede inicializar", true, "Aplicación cargada correctamente");
        }
    } catch (Exception $e) {
        mostrarResultado("Laravel se puede inicializar", false, "Error: " . $e->getMessage());
        $errores[] = "Error al inicializar Laravel: " . $e->getMessage();
    }
    
    echo "</div>";

    // ============================================
    // SECCIÓN 7: EXTENSIONES PHP
    // ============================================
    echo "<div class='seccion'>";
    echo "<h2>7. Extensiones PHP Requeridas</h2>";
    
    $extensiones = [
        'mbstring' => 'mbstring',
        'openssl' => 'openssl',
        'pdo' => 'pdo',
        'tokenizer' => 'tokenizer',
        'xml' => 'xml',
        'ctype' => 'ctype',
        'json' => 'json',
        'fileinfo' => 'fileinfo',
        'curl' => 'curl',
    ];
    
    foreach ($extensiones as $nombre => $extension) {
        $existe = extension_loaded($extension);
        mostrarResultado("Extensión $nombre", $existe, $existe ? "Cargada" : "NO cargada");
        if (!$existe) {
            $errores[] = "Extensión PHP faltante: $nombre";
        }
    }
    
    echo "</div>";

    // ============================================
    // SECCIÓN 8: PRUEBAS DE ACCESO
    // ============================================
    echo "<div class='seccion'>";
    echo "<h2>8. Pruebas de Acceso</h2>";
    
    // Probar acceso a index.php
    $indexPath = $publicPath . '/index.php';
    if (file_exists($indexPath)) {
        $puedeLeer = is_readable($indexPath);
        mostrarResultado("Se puede leer public/index.php", $puedeLeer);
        
        // Intentar incluir (sin ejecutar)
        ob_start();
        $errorOcurrido = false;
        try {
            // Solo leer el contenido, no ejecutar
            $contenidoIndex = file_get_contents($indexPath);
            mostrarResultado("Se puede leer contenido de index.php", !empty($contenidoIndex), 
                "Tamaño: " . strlen($contenidoIndex) . " bytes");
        } catch (Exception $e) {
            $errorOcurrido = true;
            mostrarResultado("Error al leer index.php", false, $e->getMessage());
        }
        ob_end_clean();
    }
    
    // Verificar variables de servidor importantes
    $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    
    mostrarResultado("DOCUMENT_ROOT configurado", !empty($documentRoot), "Valor: $documentRoot");
    mostrarResultado("SCRIPT_NAME configurado", !empty($scriptName), "Valor: $scriptName");
    mostrarResultado("REQUEST_URI configurado", !empty($requestUri), "Valor: $requestUri");
    
    // Verificar si mod_rewrite está habilitado (si es Apache)
    if (function_exists('apache_get_modules')) {
        $modRewrite = in_array('mod_rewrite', apache_get_modules());
        mostrarResultado("mod_rewrite habilitado", $modRewrite, $modRewrite ? "Sí" : "No (puede causar problemas)");
    } else {
        mostrarResultado("mod_rewrite habilitado", null, "No se puede verificar (no es Apache o función no disponible)");
    }
    
    echo "</div>";

    // ============================================
    // SECCIÓN 9: LOGS Y ERRORES
    // ============================================
    echo "<div class='seccion'>";
    echo "<h2>9. Logs y Errores</h2>";
    
    $logPath = $basePath . '/storage/logs/laravel.log';
    if (file_exists($logPath)) {
        mostrarResultado("Log de Laravel existe", true, "Ruta: $logPath");
        $logSize = filesize($logPath);
        mostrarResultado("Tamaño del log", true, "Tamaño: " . number_format($logSize / 1024, 2) . " KB");
        
        // Leer últimas líneas del log
        if ($logSize > 0) {
            $ultimasLineas = file_get_contents($logPath);
            $lineas = explode("\n", $ultimasLineas);
            $ultimas10 = array_slice($lineas, -10);
            echo "<div class='codigo'><strong>Últimas 10 líneas del log:</strong><br>";
            echo "<pre>" . htmlspecialchars(implode("\n", $ultimas10)) . "</pre>";
            echo "</div>";
        }
    } else {
        mostrarResultado("Log de Laravel existe", false, "No existe aún (puede ser normal si no hay errores)");
    }
    
    // Verificar errores de PHP
    $phpErrors = error_get_last();
    if ($phpErrors) {
        mostrarResultado("Errores de PHP recientes", false, 
            "Tipo: {$phpErrors['type']} | Mensaje: {$phpErrors['message']} | Archivo: {$phpErrors['file']}");
    } else {
        mostrarResultado("Errores de PHP recientes", true, "No hay errores recientes");
    }
    
    echo "</div>";

    // ============================================
    // SECCIÓN 10: DIAGNÓSTICO FINAL
    // ============================================
    echo "<div class='seccion'>";
    echo "<h2>10. Diagnóstico Final y Recomendaciones</h2>";
    
    // Analizar posibles causas del 403
    $posiblesCausas = [];
    
    if (!file_exists($publicPath . '/index.php')) {
        $posiblesCausas[] = "❌ CRÍTICO: public/index.php no existe";
    }
    
    if (!file_exists($publicPath . '/.htaccess')) {
        $posiblesCausas[] = "❌ CRÍTICO: public/.htaccess no existe";
    }
    
    if (!is_readable($publicPath . '/index.php')) {
        $posiblesCausas[] = "❌ CRÍTICO: public/index.php no es legible (problema de permisos)";
    }
    
    if (file_exists($basePath . '/index.php')) {
        $posiblesCausas[] = "⚠️ ADVERTENCIA: Existe index.php en la raíz (debe eliminarse)";
    }
    
    $htaccessRaiz = file_get_contents($basePath . '/.htaccess');
    if ($htaccessRaiz && strpos($htaccessRaiz, 'RewriteBase') === false && $subdirectorio) {
        $posiblesCausas[] = "⚠️ ADVERTENCIA: Proyecto en subdirectorio pero RewriteBase no está configurado";
    }
    
    if (empty($posiblesCausas)) {
        echo "<div class='success'>";
        echo "<h3>✅ No se encontraron problemas obvios en la configuración local</h3>";
        echo "<p>El problema del 403 Forbidden puede deberse a:</p>";
        echo "<ul>";
        echo "<li>Configuración del servidor web (Apache/Nginx)</li>";
        echo "<li>Restricciones de acceso en el servidor</li>";
        echo "<li>Configuración de hosting compartido</li>";
        echo "<li>Problemas con el DocumentRoot del servidor</li>";
        echo "</ul>";
        echo "</div>";
    } else {
        echo "<div class='error'>";
        echo "<h3>⚠️ Problemas Detectados:</h3>";
        echo "<ul>";
        foreach ($posiblesCausas as $causa) {
            echo "<li>$causa</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
    
    // Recomendaciones
    echo "<div class='resumen'>";
    echo "<h3>📋 Recomendaciones:</h3>";
    echo "<ol>";
    echo "<li><strong>Verificar configuración del servidor:</strong> Asegúrate de que el servidor apunte al directorio <code>public/</code> o que el <code>.htaccess</code> de la raíz esté configurado correctamente.</li>";
    echo "<li><strong>Verificar permisos:</strong> Los directorios deben tener permisos 755 y los archivos 644 (en Linux).</li>";
    echo "<li><strong>Revisar logs del servidor:</strong> Consulta los logs de Apache/Nginx para ver el error específico.</li>";
    echo "<li><strong>Contactar al proveedor de hosting:</strong> Si usas hosting compartido, puede haber restricciones específicas.</li>";
    echo "<li><strong>Probar sin .htaccess en raíz:</strong> Si el servidor apunta a <code>public/</code>, elimina el <code>.htaccess</code> de la raíz.</li>";
    echo "</ol>";
    echo "</div>";
    
    echo "</div>";

    // ============================================
    // RESUMEN FINAL
    // ============================================
    echo "<div class='seccion'>";
    echo "<h2>📊 Resumen de la Ejecución</h2>";
    echo "<p><strong>Fecha:</strong> " . date('Y-m-d H:i:s') . "</p>";
    echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
    echo "<p><strong>Servidor:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "</p>";
    echo "<p><strong>Ruta Base:</strong> $basePath</p>";
    echo "<p><strong>Ruta Public:</strong> $publicPath</p>";
    echo "</div>";
    ?>

    <div style="text-align: center; margin: 30px 0; padding: 20px; background: #e9ecef; border-radius: 8px;">
        <p><strong>Test completado</strong></p>
        <p>Revisa los resultados arriba para identificar el problema del 403 Forbidden</p>
    </div>

</body>
</html>

