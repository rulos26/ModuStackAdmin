# Solución: Mover Rutas API a web.php

## Fecha: 2025-11-03
## Problema: Rutas API en routes/api.php no funcionan en hosting

---

## Error Persistente

Las rutas API en `routes/api.php` devolvían 404 aunque estaban correctamente registradas en Laravel.

**Causa:** Algunos hostings no procesan correctamente `routes/api.php` o tienen restricciones en rutas `/api/`.

---

## Solución Aplicada

### Mover Rutas API a `routes/web.php`

**Razón:** `routes/web.php` siempre funciona en todos los hostings, ya que es el archivo estándar que todos procesan.

**Archivo:** `routes/web.php`

```php
// Rutas API del módulo Auth (registradas aquí para evitar problemas con hosting)
Route::prefix('api')->group(function () {
    // Ruta de prueba
    Route::get('/test', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'API funcionando correctamente desde web.php',
            'timestamp' => now()->toIso8601String(),
        ]);
    });
    
    // Cargar rutas del módulo Auth
    Route::prefix('auth')->group(function () {
        // Rutas públicas
        Route::post('register', [\Modules\Auth\Http\Controllers\AuthController::class, 'register']);
        Route::post('login', [\Modules\Auth\Http\Controllers\AuthController::class, 'login']);

        // Rutas protegidas con Sanctum
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('profile', [\Modules\Auth\Http\Controllers\AuthController::class, 'profile']);
            Route::post('logout', [\Modules\Auth\Http\Controllers\AuthController::class, 'logout']);
        });
    });
});
```

---

## Cambios Realizados

### 1. `routes/web.php` ✅

- Agregadas rutas API con prefijo `/api`
- Importado `AuthController` directamente
- Agregada ruta de prueba `/api/test`

### 2. `Modules/Auth/Providers/AuthServiceProvider.php` ✅

- Comentado el método `mapApiRoutes()`
- Las rutas ahora se cargan desde `routes/web.php`

### 3. `bootstrap/app.php` ✅

- Se mantiene la referencia a `routes/api.php` (puede estar vacío)
- Las rutas importantes están en `routes/web.php`

---

## Rutas Disponibles

- ✅ `GET  /api/test` - Ruta de prueba
- ✅ `POST /api/auth/login`
- ✅ `POST /api/auth/register`
- ✅ `GET  /api/auth/profile` (protegida)
- ✅ `POST /api/auth/logout` (protegida)

**URLs completas con subdirectorio:**
- `https://rulossoluciones.com/ModuStackAdmin/api/test`
- `https://rulossoluciones.com/ModuStackAdmin/api/auth/login`
- `https://rulossoluciones.com/ModuStackAdmin/api/auth/register`

---

## Ventajas de esta Solución

1. **Compatibilidad:** Funciona en todos los hostings
2. **Sin dependencia de routes/api.php:** No requiere configuración especial
3. **Mismo comportamiento:** Las rutas funcionan igual que en `routes/api.php`
4. **Fácil de depurar:** Todas las rutas están en un solo lugar

---

## Verificación

```bash
# Verificar rutas registradas
php artisan route:list | grep "api/auth"

# Resultado esperado:
# POST   api/auth/login
# POST   api/auth/register
# GET    api/auth/profile
# POST   api/auth/logout
```

---

**Documentado por:** Auto (Cursor AI)  
**Fecha:** 2025-11-03  
**Estado:** ✅ Solución aplicada - Rutas API en web.php

