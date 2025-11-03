# Sistema de Logs de Errores - ModuStackAdmin

Este directorio contiene el sistema centralizado de logs de errores del proyecto ModuStackAdmin, clasificado automáticamente según el tipo y nivel de severidad.

---

## 📁 Estructura de Directorios

```
log_errores/
├── backend/              # Errores de backend
│   ├── bajo/            # Errores menores o de interfaz
│   ├── medio/           # Errores funcionales o de lógica de negocio
│   └── alto/            # Errores críticos, de seguridad o pérdida de datos
├── frontend/            # Errores de frontend
│   ├── bajo/            # Errores menores o de interfaz
│   ├── medio/           # Errores funcionales o de lógica de negocio
│   └── alto/            # Errores críticos, de seguridad o pérdida de datos
└── README.md            # Este archivo
```

---

## 📋 Clasificación de Errores

### Tipos de Error

#### Backend
Errores que provienen de:
- Controladores (`app/Http/Controllers/`)
- Servicios (`app/Services/`)
- Modelos (`app/Models/`)
- Comandos Artisan (`app/Console/Commands/`)
- Middleware (`app/Http/Middleware/`)

#### Frontend
Errores que provienen de:
- Vistas Blade (`resources/views/`)
- Componentes JavaScript (`resources/js/`)
- Assets públicos (`public/`)
- Estilos CSS (`resources/css/`)

---

### Niveles de Severidad

#### 🔵 Bajo (Minor/Interface)
- Problemas de visualización menor
- Estilos que no afectan funcionalidad
- Warnings de consola no críticos
- Sugerencias de optimización

**Ejemplos:**
- Imagen no encontrada pero no afecta el flujo
- Tooltip que no aparece
- Warning de deprecación menor

---

#### 🟡 Medio (Functional/Business Logic)
- Errores que afectan funcionalidad específica
- Validaciones de formularios fallidas
- Lógica de negocio incorrecta
- Problemas de integración entre componentes

**Ejemplos:**
- Error al guardar un registro
- Validación incorrecta de datos
- API response inesperada
- Problema con relaciones de modelos

---

#### 🔴 Alto (Critical/Security/Data Loss)
- Errores que comprometen la seguridad
- Pérdida de datos o incapacidad para recuperarlos
- Errores que detienen completamente la aplicación
- Vulnerabilidades detectadas

**Ejemplos:**
- Inyección SQL detectada
- Acceso no autorizado
- Pérdida de datos sin backup
- Exception fatal que detiene el servidor

---

## 📝 Formato de Archivos de Log

Cada archivo de log debe seguir este formato:

```yaml
Fecha: YYYY-MM-DD HH:MM:SS
Módulo: nombre_del_modulo
Archivo Afectado: ruta/relativa/del/archivo.php
Tipo: backend/frontend
Severidad: bajo/medio/alto

Descripción:
[Descripción detallada del error, incluyendo contexto y datos relevantes]

Stack Trace:
[Si aplica, stack trace del error]

Acción Correctiva:
[Descripción de la acción tomada para resolver el error]

Estado: [resuelto/pendiente/en_investigacion]
Usuario Afectado: [opcional, si aplica]
```

---

## 🔧 Integración con Laravel

### Ejemplo de Registro Automático

```php
// app/Providers/AppServiceProvider.php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

// En el método register() o boot()

try {
    // Tu código aquí
} catch (\Exception $e) {
    // Clasificar automáticamente el error
    $severity = $this->classifyError($e);
    $type = str_contains(get_class($e), 'Frontend') ? 'frontend' : 'backend';
    
    // Escribir en el archivo correspondiente
    $this->writeErrorLog($e, $type, $severity);
}

/**
 * Clasifica el error según su severidad
 */
private function classifyError($exception) {
    if ($exception instanceof SecurityException || 
        $exception instanceof CriticalException) {
        return 'alto';
    } elseif ($exception instanceof BusinessLogicException ||
              $exception instanceof ValidationException) {
        return 'medio';
    }
    return 'bajo';
}

/**
 * Escribe el log en el archivo correspondiente
 */
private function writeErrorLog($exception, $type, $severity) {
    $logPath = base_path("../log_errores/{$type}/{$severity}/");
    $filename = 'error_' . date('Y-m-d') . '.log';
    
    $logContent = sprintf(
        "Fecha: %s\nMódulo: %s\nArchivo Afectado: %s\nTipo: %s\nSeveridad: %s\n\nDescripción:\n%s\n\nStack Trace:\n%s\n\nEstado: pendiente\n\n---\n\n",
        date('Y-m-d H:i:s'),
        config('app.name'),
        $exception->getFile(),
        $type,
        $severity,
        $exception->getMessage(),
        $exception->getTraceAsString()
    );
    
    File::append($logPath . $filename, $logContent);
    
    // También registrar en logs de Laravel
    Log::error($exception->getMessage(), [
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
    ]);
}
```

---

## 🔍 Consulta y Análisis de Logs

### Comando Artisan (Pendiente de Implementación)

```bash
# Ver errores de hoy
php artisan logs:show --today

# Ver errores por severidad
php artisan logs:show --severity=alto

# Ver errores por tipo
php artisan logs:show --type=backend

# Ver errores de un módulo específico
php artisan logs:show --module=ModuStackUser

# Buscar en logs
php artisan logs:search "query string"

# Generar reporte
php artisan logs:report --format=pdf
```

---

## 📊 Mantenimiento

### Rotación de Logs
- Los logs deben rotarse automáticamente diariamente
- Mantener logs de los últimos 30 días
- Archivar logs más antiguos

### Limpieza
- Ejecutar limpieza semanal
- Eliminar logs resueltos con más de 90 días
- Comprimir logs antiguos

### Backups
- Backup diario de logs críticos (alto)
- Backup semanal de logs medios
- Backup mensual de todos los logs

---

## 🚨 Alertas Automáticas

### Configurar Alertas (Pendiente)

- **Email:** Enviar email para errores de severidad ALTA
- **Slack/Teams:** Notificar errores críticos en canales
- **Dashboard:** Mostrar métricas de errores en tiempo real

---

## 📚 Referencias

- [Laravel Logging Documentation](https://laravel.com/docs/logging)
- [Monolog Documentation](https://github.com/Seldaek/monolog)
- [PHP Error Handling](https://www.php.net/manual/en/errorfunc.examples.php)

---

**Generado:** 2025-11-03  
**Versión:** 1.0.0

