# Error HTTP 500 - ModuStackUser

**Fecha:** 2025-11-03  
**Componente/Área Afectada:** Aplicación Laravel ModuStackUser  
**Archivo Afectado:** `ModuStackUser/index.php`, `ModuStackUser/.htaccess`, `ModuStackUser/.env`  
**Tipo:** backend  
**Severidad:** alto

---

## Descripción

Al intentar acceder a la aplicación Laravel en `ModuStackUser/` desde el portal principal, se produce un error HTTP 500 (Internal Server Error).

**Error observado:**
- URL: `rulossoluciones.com/ModuStackAdmin/ModuStackUser/`
- Error: HTTP ERROR 500
- Mensaje: "Esta página no funciona" / "rulossoluciones.com no puede procesar esta solicitud en este momento"

---

## Análisis del Problema

### Causas Identificadas

1. **Configuración incorrecta de APP_URL en .env**
   - Actual: `APP_URL=https://rulossoluciones.com/modustackadmin`
   - Problema: La URL no incluye la ruta completa a ModuStackUser
   - Impacto: Laravel puede generar URLs incorrectas y causar errores de routing

2. **Configuración de .htaccess en ModuStackUser**
   - Problema: RewriteBase estaba configurado como `/ModuStackUser/` lo cual puede causar conflictos en producción
   - Impacto: Las reglas de reescritura no funcionan correctamente

3. **Posibles problemas de permisos o configuración PHP**
   - Necesita verificación mediante script de diagnóstico

---

## Acción Correctiva Aplicada

### 1. Corrección del `.htaccess` en ModuStackUser

**Antes:**
```apache
Options +SymLinksIfOwnerMatch
RewriteEngine On
RewriteBase /ModuStackUser/

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [L]
```

**Después:**
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

**Mejoras:**
- Eliminado RewriteBase que causaba conflictos
- Usada configuración estándar de Laravel 12
- Agregado manejo de headers de autorización
- Mejor manejo de trailing slashes

### 2. Creación de Scripts de Diagnóstico

Creados archivos de diagnóstico:
- `ModuStackUser/test.php` - Diagnóstico completo
- `ModuStackUser/diagnostico.php` - Diagnóstico rápido

Estos scripts ayudan a identificar:
- Versión de PHP
- Extensiones necesarias
- Archivos críticos
- Permisos de storage
- Inicialización de Laravel

### 3. Corrección Necesaria en .env

**Archivo:** `ModuStackUser/.env`

**Cambio requerido:**
```env
# ANTES (incorrecto)
APP_URL=https://rulossoluciones.com/modustackadmin

# DESPUÉS (correcto)
APP_URL=https://rulossoluciones.com/ModuStackAdmin/ModuStackUser
```

**Nota:** Este cambio debe aplicarse manualmente en el servidor de producción.

---

## Archivos Modificados

1. ✅ `ModuStackUser/.htaccess` - Actualizado a configuración estándar Laravel
2. ✅ `ModuStackUser/test.php` - Creado (script de diagnóstico)
3. ✅ `ModuStackUser/diagnostico.php` - Creado (diagnóstico rápido)

## Archivos que Requieren Modificación Manual

1. ⚠️ `ModuStackUser/.env` - Actualizar APP_URL (requiere acceso al servidor)

---

## Pasos de Verificación

### 1. Verificar .htaccess
Acceder a: `https://rulossoluciones.com/ModuStackAdmin/ModuStackUser/test.php`
- Debe mostrar diagnóstico completo
- Todos los checks deben mostrar ✅

### 2. Verificar Diagnóstico Rápido
Acceder a: `https://rulossoluciones.com/ModuStackAdmin/ModuStackUser/diagnostico.php`
- Debe mostrar que Laravel se inicializa correctamente

### 3. Actualizar APP_URL en .env
```bash
# En el servidor, editar ModuStackUser/.env
APP_URL=https://rulossoluciones.com/ModuStackAdmin/ModuStackUser
```

### 4. Limpiar Caché de Laravel
```bash
cd ModuStackUser
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 5. Verificar Permisos
```bash
# En el servidor
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## Soluciones Adicionales Recomendadas

### Si el error persiste:

1. **Habilitar Debug Mode Temporalmente**
   ```env
   APP_DEBUG=true
   ```
   Esto mostrará el error específico en lugar del HTTP 500 genérico.

2. **Revisar Logs de Laravel**
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Revisar Logs de Apache/Nginx**
   - Verificar errores del servidor web
   - Buscar errores de PHP-FPM si aplica

4. **Verificar Versión de PHP**
   - Requiere PHP >= 8.2
   - Verificar con `php -v`

5. **Verificar Extensiones PHP Necesarias**
   - mbstring, openssl, pdo, tokenizer, xml, ctype, json, fileinfo

---

## Referencias Técnicas

- [Laravel 12 Deployment](https://laravel.com/docs/12.x/deployment)
- [Apache mod_rewrite](https://httpd.apache.org/docs/current/mod/mod_rewrite.html)
- [Laravel URL Configuration](https://laravel.com/docs/12.x/configuration#url-configuration)
- [PHP Error Handling](https://www.php.net/manual/en/errorfunc.configuration.php)

---

## Estado

⚠️ **EN PROGRESO**

**Pasos Completados:**
- ✅ Corregido .htaccess de ModuStackUser
- ✅ Creados scripts de diagnóstico
- ⚠️ Pendiente: Actualizar APP_URL en .env (requiere acceso servidor)
- ⚠️ Pendiente: Limpiar caché de Laravel
- ⚠️ Pendiente: Verificar permisos

**Fecha de Inicio:** 2025-11-03  
**Próximos Pasos:** Actualizar .env en producción y verificar

---

## Notas Adicionales

- El error HTTP 500 es genérico y puede tener múltiples causas
- Los scripts de diagnóstico ayudarán a identificar el problema específico
- La solución es compatible con Laravel 12 (arquitectura tradicional)
- Es importante verificar los logs del servidor para más detalles

