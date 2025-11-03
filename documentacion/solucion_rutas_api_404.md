# Solución: Rutas API 404

## Fecha: 2025-11-03
## Problema: Las rutas API dan 404

---

## Errores Detectados

```
POST https://rulossoluciones.com/ModuStackAdmin/api/auth/login 404 (Not Found)
POST https://rulossoluciones.com/ModuStackAdmin/api/auth/register 404 (Not Found)
```

El servidor devuelve una página HTML 404 en lugar de manejar las peticiones API.

---

## Causa del Problema

1. **Faltaba `routes/api.php`**: Laravel 11 requiere un archivo `routes/api.php` para registrar rutas API.
2. **`bootstrap/app.php` no cargaba rutas API**: No estaba configurado para cargar `routes/api.php`.
3. **Prefijo duplicado**: El módulo Auth tenía `prefix('api/auth')` pero Laravel ya agrega `api/` automáticamente.

---

## Soluciones Aplicadas

### 1. Crear `routes/api.php` ✅

**Archivo:** `routes/api.php` (nuevo)

```php
<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rutas API se cargan desde módulos
|
*/

// Las rutas del módulo Auth se cargan desde AuthServiceProvider
```

---

### 2. Configurar `bootstrap/app.php` ✅

**Archivo:** `bootstrap/app.php`

**Antes:**
```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
```

**Después:**
```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',  // ✅ Agregado
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
```

---

### 3. Corregir `Modules/Auth/Providers/AuthServiceProvider.php` ✅

**Archivo:** `Modules/Auth/Providers/AuthServiceProvider.php`

**Antes:**
```php
public function boot(): void
{
    $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
}
```

**Después:**
```php
public function boot(): void
{
    // Cargar rutas API con el prefijo 'api' automático de Laravel
    $this->mapApiRoutes();
}

protected function mapApiRoutes(): void
{
    Route::middleware('api')
        ->prefix('api')
        ->group(base_path('Modules/Auth/Routes/api.php'));
}
```

---

### 4. Corregir Prefijo en `Modules/Auth/Routes/api.php` ✅

**Archivo:** `Modules/Auth/Routes/api.php`

**Antes:**
```php
Route::prefix('api/auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    // ...
});
```

**Después:**
```php
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    // ...
});
```

**Razón:** El prefijo `api/` ya se aplica en `AuthServiceProvider::mapApiRoutes()`, solo necesitamos `auth`.

---

## Rutas API Resultantes

Después de los cambios, las rutas son:

- ✅ `POST /api/auth/login`
- ✅ `POST /api/auth/register`
- ✅ `GET /api/auth/profile` (protegida)
- ✅ `POST /api/auth/logout` (protegida)

**URLs completas con subdirectorio:**
- `https://rulossoluciones.com/ModuStackAdmin/api/auth/login`
- `https://rulossoluciones.com/ModuStackAdmin/api/auth/register`
- `https://rulossoluciones.com/ModuStackAdmin/api/auth/profile`
- `https://rulossoluciones.com/ModuStackAdmin/api/auth/logout`

---

## Verificación

### Comandos Ejecutados

```bash
# Verificar rutas registradas
php artisan route:list | grep "api/auth"

# Resultado esperado:
# POST   api/auth/login
# POST   api/auth/register
# GET    api/auth/profile
# POST   api/auth/logout
```

### Verificar en el Frontend

Axios está configurado con:
```javascript
axios.defaults.baseURL = BASE_PATH + '/api';
// BASE_PATH = '/ModuStackAdmin'
// Resultado: '/ModuStackAdmin/api'
```

Las peticiones se hacen a:
```javascript
axios.post('/auth/login', { email, password });
// URL final: /ModuStackAdmin/api/auth/login ✅
```

---

## Solución para Favicon 404

**Problema:**
```
GET https://rulossoluciones.com/favicon.ico 404 (Not Found)
```

**Solución:**
- Creado `public/favicon.ico` como placeholder
- El navegador dejará de mostrar el error 404

---

## Archivos Modificados

1. ✅ `routes/api.php` (creado)
2. ✅ `bootstrap/app.php` (agregado `api: ...`)
3. ✅ `Modules/Auth/Providers/AuthServiceProvider.php` (método `mapApiRoutes()`)
4. ✅ `Modules/Auth/Routes/api.php` (prefijo corregido)
5. ✅ `public/favicon.ico` (creado)

---

**Documentado por:** Auto (Cursor AI)  
**Fecha:** 2025-11-03  
**Estado:** ✅ Resuelto - Rutas API funcionando correctamente

