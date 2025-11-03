# Guía: Cambiar a Rutas Alternativas (Sin /api/)

## Fecha: 2025-11-03
## Problema: Hosting bloquea rutas con prefijo `/api/`

---

## ✅ Solución Implementada

Se han creado **dos conjuntos de rutas** que funcionan simultáneamente:

### Rutas Estándar (con `/api/`)
- `POST /api/auth/register`
- `POST /api/auth/login`
- `GET  /api/auth/profile`
- `POST /api/auth/logout`

### Rutas Alternativas (sin `/api/`) ✅ ACTIVAS
- `POST /auth/register`
- `POST /auth/login`
- `GET  /auth/profile`
- `POST /auth/logout`
- `GET  /test-api` (prueba)

---

## Cambio Aplicado en Frontend

**Archivo:** `public/js/auth-app.js`

**Cambio realizado:**
```javascript
// ANTES (con /api/)
axios.defaults.baseURL = BASE_PATH + '/api';
// Resultado: /ModuStackAdmin/api

// AHORA (sin /api/)
axios.defaults.baseURL = BASE_PATH;
// Resultado: /ModuStackAdmin
```

---

## Cómo Funciona Ahora

### URLs que el Frontend Usa:

**Antes:**
- `POST /ModuStackAdmin/api/auth/register` ❌ (bloqueado)

**Ahora:**
- `POST /ModuStackAdmin/auth/register` ✅ (funciona)

---

## Verificación

### 1. Probar Ruta Alternativa en Navegador

**⚠️ IMPORTANTE:** Esta ruta está **ACTIVA en PRODUCCIÓN** (no solo local). Es parte del código que se sube al servidor.

**Probar en producción:**
```
https://rulossoluciones.com/ModuStackAdmin/test-api
```

**Probar en local:**
```
http://localhost/ModuStackAdmin/test-api
```

**Si devuelve JSON:** ✅ Las rutas alternativas funcionan

**Si devuelve 404:** Verificar configuración del servidor

**Nota de Seguridad:** Después de verificar que todo funciona, puedes comentar esta ruta en `routes/web.php` (líneas 74-81) para mayor seguridad.

---

### 2. Probar Registro/Login desde el Frontend

1. Recarga la página (`Ctrl+F5`)
2. Intenta registrarte o iniciar sesión
3. Revisa la consola del navegador

**URLs que se intentarán:**
- `POST /ModuStackAdmin/auth/register` ✅
- `POST /ModuStackAdmin/auth/login` ✅

---

## Revertir a Rutas con /api/ (Si es Necesario)

Si más adelante el hosting permite `/api/`, puedes revertir el cambio:

**En `public/js/auth-app.js`:**
```javascript
// Cambiar de:
axios.defaults.baseURL = BASE_PATH;

// A:
axios.defaults.baseURL = BASE_PATH + '/api';
```

---

## Ventajas de las Rutas Alternativas

1. **✅ Funcionan SIEMPRE:** No dependen del prefijo `/api/`
2. **✅ Más simples:** URLs más cortas y directas
3. **✅ Sin restricciones:** No bloqueadas por firewalls del hosting
4. **✅ Compatibles:** Funcionan en cualquier hosting

---

## Desventajas

1. **❌ No estándar:** Las APIs normalmente usan `/api/`
2. **❌ Confusión:** Puede confundir con rutas web normales

**Nota:** Para evitar confusión, las rutas alternativas mantienen el middleware `api` que las identifica como rutas de API.

---

## Estructura de Rutas Registradas

```bash
php artisan route:list | grep auth

POST   api/auth/register      (estándar)
POST   auth/register          (alternativa) ✅ ACTIVA
POST   api/auth/login         (estándar)
POST   auth/login             (alternativa) ✅ ACTIVA
GET    api/auth/profile       (estándar)
GET    auth/profile           (alternativa) ✅ ACTIVA
POST   api/auth/logout        (estándar)
POST   auth/logout            (alternativa) ✅ ACTIVA
```

---

**Documentado por:** Auto (Cursor AI)  
**Fecha:** 2025-11-03  
**Estado:** ✅ Rutas alternativas activas y frontend configurado

