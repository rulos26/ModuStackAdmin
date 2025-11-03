# Solución: Hosting Bloquea Rutas /api/

## Fecha: 2025-11-03
## Problema: Hosting bloquea rutas con prefijo `/api/`

---

## Diagnóstico

Si el hosting está bloqueando las rutas `/api/`, puede ser por:

1. **Restricciones de seguridad del hosting**
   - Algunos hostings bloquean automáticamente rutas que contienen `/api/`
   - Protección contra ataques comunes

2. **Configuración de `.htaccess`**
   - `mod_rewrite` deshabilitado
   - `AllowOverride None` en configuración del servidor

3. **Reglas de firewall del hosting**
   - Bloqueo de rutas específicas por seguridad

---

## Soluciones Aplicadas

### Solución 1: Rutas Alternativas Sin Prefijo `/api/` ✅

Se han creado rutas alternativas que NO usan el prefijo `/api/`:

**Rutas estándar (con /api/):**
- `POST /api/auth/register`
- `POST /api/auth/login`

**Rutas alternativas (sin /api/):**
- `POST /auth/register`
- `POST /auth/login`
- `POST /auth/profile`
- `POST /auth/logout`

**Ambas funcionan simultáneamente**, así que si el hosting bloquea `/api/`, las alternativas funcionarán.

---

### Solución 2: Configurar Frontend para Usar Rutas Alternativas

**Archivo:** `public/js/auth-app.js`

El frontend puede cambiar fácilmente de `/api/` a `/`:

```javascript
// ANTES (con /api/)
axios.defaults.baseURL = BASE_PATH + '/api';

// DESPUÉS (sin /api/)
axios.defaults.baseURL = BASE_PATH;
```

---

## Cambios Realizados

### 1. `routes/web.php` ✅

Agregadas rutas alternativas sin prefijo `/api/`:

```php
// Rutas alternativas sin prefijo /api/
Route::middleware(['api'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register'])->name('auth.register');
        Route::post('login', [AuthController::class, 'login'])->name('auth.login');
        // ...
    });
});
```

**Ventaja:** Estas rutas funcionan SIEMPRE porque no usan `/api/`.

---

## Opciones de Configuración

### Opción A: Usar Rutas Estándar (con /api/)

**Frontend:**
```javascript
axios.defaults.baseURL = BASE_PATH + '/api';
```

**Rutas:**
- `POST /ModuStackAdmin/api/auth/register`

---

### Opción B: Usar Rutas Alternativas (sin /api/)

**Frontend:**
```javascript
axios.defaults.baseURL = BASE_PATH;
```

**Rutas:**
- `POST /ModuStackAdmin/auth/register`

---

## Cómo Cambiar

### Paso 1: Elegir qué rutas usar

Si `/api/` está bloqueado, usar rutas alternativas.

### Paso 2: Actualizar Frontend

**Archivo:** `public/js/auth-app.js`

**Cambiar de:**
```javascript
axios.defaults.baseURL = BASE_PATH + '/api';
```

**A:**
```javascript
axios.defaults.baseURL = BASE_PATH;
```

**Y cambiar las peticiones de:**
```javascript
axios.post('/auth/login', ...)  // Ya está correcto, solo cambia baseURL
```

---

## Verificación

### Probar Rutas Estándar (con /api/):

```bash
# Desde navegador o Postman
POST https://rulossoluciones.com/ModuStackAdmin/api/auth/test
GET https://rulossoluciones.com/ModuStackAdmin/api/test
```

### Probar Rutas Alternativas (sin /api/):

```bash
# Desde navegador o Postman
POST https://rulossoluciones.com/ModuStackAdmin/auth/test-api
GET https://rulossoluciones.com/ModuStackAdmin/test-api
```

**Si las alternativas funcionan pero las estándar no:** El hosting está bloqueando `/api/`.

---

## Otras Soluciones Si Nada Funciona

### Solución 3: Usar Query Parameters

Si TODO está bloqueado, usar query parameters:

```php
Route::get('auth-action', function (Request $request) {
    $action = $request->get('action');
    
    if ($action === 'register') {
        return app(AuthController::class)->register($request);
    }
    // ...
});
```

**URL:** `/ModuStackAdmin/auth-action?action=register`

### Solución 4: Contactar al Proveedor de Hosting

Si nada funciona, contactar al hosting para:
1. Habilitar `mod_rewrite`
2. Permitir `AllowOverride All`
3. Desbloquear rutas `/api/` (si es necesario)

---

## Recomendación Final

1. **Primero:** Probar rutas alternativas (sin `/api/`)
2. **Si funcionan:** Cambiar `baseURL` en frontend
3. **Si no funcionan:** Verificar logs del servidor y contactar hosting

---

**Documentado por:** Auto (Cursor AI)  
**Fecha:** 2025-11-03  
**Estado:** ✅ Soluciones alternativas implementadas

