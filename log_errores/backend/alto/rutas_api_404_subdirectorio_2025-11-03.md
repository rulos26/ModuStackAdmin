# Error: Rutas API 404 en Subdirectorio

## Fecha: 2025-11-03
## Clasificación: Backend / Alto
## Tipo: Error crítico - API no accesible

---

## Descripción del Error

Las rutas API devuelven 404 aunque están correctamente registradas en Laravel. El servidor devuelve una página HTML 404 del hosting en lugar de procesar las peticiones con Laravel.

### Errores Detectados

```
POST https://rulossoluciones.com/ModuStackAdmin/api/auth/register 404 (Not Found)
POST https://rulossoluciones.com/ModuStackAdmin/api/auth/login 404 (Not Found)
```

**Respuesta del servidor:** Página HTML 404 del hosting (no respuesta JSON de Laravel)

---

## Módulo Afectado

- **Módulo:** Auth
- **Archivos:**
  - `Modules/Auth/Routes/api.php`
  - `Modules/Auth/Providers/AuthServiceProvider.php`
  - `routes/api.php`
  - `.htaccess` (raíz)
  - `public/.htaccess`

---

## Diagnóstico

### Rutas Registradas Correctamente ✅

```bash
php artisan route:list --path=api

POST   api/auth/login
POST   api/auth/register
GET    api/auth/profile
POST   api/auth/logout
```

**Estado:** ✅ Las rutas están registradas en Laravel

---

## Causa Raíz

El servidor no está enviando las peticiones API a `public/index.php` de Laravel.

**Flujo actual (incorrecto):**
1. Navegador: `POST /ModuStackAdmin/api/auth/register`
2. `.htaccess` raíz: Redirige a `public/api/auth/register`
3. Servidor: Busca archivo físico `public/api/auth/register` → ❌ NO existe
4. Resultado: 404 del hosting (no llega a Laravel)

**Flujo esperado (correcto):**
1. Navegador: `POST /ModuStackAdmin/api/auth/register`
2. `.htaccess` raíz: Redirige a `public/api/auth/register`
3. `.htaccess` public: Detecta que no es archivo físico, redirige a `index.php`
4. Laravel: Procesa la ruta `api/auth/register`
5. Resultado: ✅ Respuesta JSON

---

## Acciones Correctivas Aplicadas

### 1. Verificar `.htaccess` en `public/` ✅

**Archivo:** `public/.htaccess`

```apache
# Send Requests To Front Controller...
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]
```

**Estado:** ✅ Configuración correcta

---

### 2. Mejorar `.htaccess` en raíz ✅

**Archivo:** `.htaccess` (raíz)

**Actualizado para redirigir explícitamente rutas API:**

```apache
# Redirigir rutas API y web a public/index.php
RewriteCond %{REQUEST_URI} ^/ModuStackAdmin/(api|.*)$
RewriteRule ^ModuStackAdmin/(.*)$ public/$1 [L]
```

---

### 3. Crear Ruta de Prueba ✅

**Archivo:** `routes/api.php`

Agregada ruta de prueba para verificar si las rutas API funcionan:

```php
Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API funcionando correctamente',
        'timestamp' => now()->toIso8601String(),
    ]);
});
```

**URL de prueba:** `https://rulossoluciones.com/ModuStackAdmin/api/test`

---

## Verificación

### Comandos Ejecutados

```bash
# Verificar rutas registradas
php artisan route:list --path=api
# Resultado: ✅ Rutas registradas correctamente

# Limpiar cachés
php artisan route:clear
php artisan optimize:clear
# Estado: ✅ Cachés limpiados
```

### Prueba Manual Requerida

**Abrir en navegador:**
```
https://rulossoluciones.com/ModuStackAdmin/api/test
```

**Resultados posibles:**

1. **Si devuelve JSON:** ✅ Las rutas API funcionan, el problema es específico del módulo Auth
2. **Si devuelve 404:** ❌ Problema general del servidor/hosting

---

## Posibles Causas del Servidor

1. **`.htaccess` bloqueado o deshabilitado**
   - Hosting puede tener `AllowOverride None`
   - Verificar configuración del servidor

2. **`mod_rewrite` deshabilitado**
   - Apache necesita `mod_rewrite` habilitado
   - Verificar en phpinfo o configuración del servidor

3. **Configuración de DocumentRoot incorrecta**
   - Si DocumentRoot apunta directamente a `public/`, el `.htaccess` en raíz no se ejecuta

4. **Restricciones del hosting**
   - Algunos hostings bloquean peticiones a rutas `/api/` por seguridad
   - Verificar logs del servidor

---

## Próximos Pasos Recomendados

1. **Verificar ruta de prueba:**
   - Abrir `https://rulossoluciones.com/ModuStackAdmin/api/test` en navegador
   - Si funciona: problema específico del módulo Auth
   - Si no funciona: problema del servidor/hosting

2. **Verificar permisos y configuración:**
   - Revisar que `.htaccess` tenga permisos correctos
   - Verificar que el hosting permite `.htaccess`
   - Revisar logs del servidor (error_log)

3. **Contactar al proveedor de hosting:**
   - Si las rutas están registradas pero no funcionan, puede ser restricción del hosting
   - Solicitar verificación de `mod_rewrite` y `AllowOverride`

---

## Archivos Modificados

1. ✅ `.htaccess` (raíz) - Mejorado para manejar rutas API
2. ✅ `routes/api.php` - Agregada ruta de prueba
3. ✅ `bootstrap/app.php` - Ya configurado para cargar rutas API
4. ✅ `Modules/Auth/Providers/AuthServiceProvider.php` - Ya configurado correctamente

---

## Compatibilidad Verificada

- ✅ **Laravel 11:** Rutas API configuradas correctamente
- ✅ **nwidart/laravel-modules:** Módulo Auth registrado correctamente
- ✅ **Laravel Sanctum:** Middleware configurado correctamente

---

## Referencias Técnicas

- [Laravel Routing](https://laravel.com/docs/11.x/routing)
- [Apache mod_rewrite](https://httpd.apache.org/docs/current/mod/mod_rewrite.html)
- [Laravel API Routes](https://laravel.com/docs/11.x/routing#api-routes)

---

## Estado

⚠️ **PENDIENTE VERIFICACIÓN DEL SERVIDOR** - Las rutas están registradas pero el servidor no las procesa

---

**Documentado por:** Auto (Cursor AI)  
**Fecha:** 2025-11-03  
**Registro:** log_errores/backend/alto/rutas_api_404_subdirectorio_2025-11-03.md

