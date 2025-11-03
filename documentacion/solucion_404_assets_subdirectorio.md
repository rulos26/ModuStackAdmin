# Solución Error 404 en Assets en Subdirectorio

## Fecha: 2025-11-03
## Problema: Assets CSS y JS dan 404 en subdirectorio

---

## Error Detectado

```
GET https://rulossoluciones.com/ModuStackAdmin/css/auth-styles.css 
net::ERR_ABORTED 404 (Not Found)

GET https://rulossoluciones.com/ModuStackAdmin/js/auth-app.js 
net::ERR_ABORTED 404 (Not Found)
```

**Ruta buscada:** `/ModuStackAdmin/css/auth-styles.css`  
**Ruta real del archivo:** `public/css/auth-styles.css`

---

## Causa del Problema

El `.htaccess` no estaba redirigiendo correctamente los requests de archivos estáticos (CSS, JS) desde `/ModuStackAdmin/css/` y `/ModuStackAdmin/js/` hacia `public/css/` y `public/js/`.

---

## Solución Aplicada

### 1. Actualización del `.htaccess` en la raíz

Se agregaron reglas específicas para archivos estáticos:

```apache
# Permitir acceso directo a archivos estáticos en public/
# CSS, JS, imágenes, etc.
RewriteCond %{REQUEST_URI} ^/ModuStackAdmin/(css|js|images|build|storage)/
RewriteCond %{REQUEST_FILENAME} -f
RewriteRule ^ModuStackAdmin/(.*)$ public/$1 [L]
```

**Explicación:**
- Detecta requests a `/ModuStackAdmin/css/`, `/ModuStackAdmin/js/`, etc.
- Verifica que el archivo existe en `public/`
- Redirige `/ModuStackAdmin/css/auth-styles.css` → `public/css/auth-styles.css`

---

## Rutas Configuradas

### Rutas en `welcome.blade.php`

```html
<!-- CSS -->
<link rel="stylesheet" href="/ModuStackAdmin/css/auth-styles.css">

<!-- JS -->
<script src="/ModuStackAdmin/js/auth-app.js"></script>
```

### Flujo de Redirección

1. Usuario solicita: `/ModuStackAdmin/css/auth-styles.css`
2. `.htaccess` detecta que es un archivo estático
3. Redirige a: `public/css/auth-styles.css`
4. Servidor sirve el archivo

---

## Estructura de Archivos

```
ModuStackAdmin/
├── public/
│   ├── css/
│   │   └── auth-styles.css ✅
│   ├── js/
│   │   └── auth-app.js ✅
│   └── .htaccess (maneja rutas de Laravel)
│
└── .htaccess (redirige a public/ y archivos estáticos)
```

---

## Verificación

### URLs que deberían funcionar:

1. **CSS:**
   ```
   https://rulossoluciones.com/ModuStackAdmin/css/auth-styles.css
   ```

2. **JS:**
   ```
   https://rulossoluciones.com/ModuStackAdmin/js/auth-app.js
   ```

3. **Página principal:**
   ```
   https://rulossoluciones.com/ModuStackAdmin/
   ```

---

## Si el Problema Persiste

### Opción 1: Verificar permisos del servidor

El DocumentRoot puede estar configurado directamente en `public/`. En ese caso, las rutas deberían ser:

```html
<!-- Sin /ModuStackAdmin/ si DocumentRoot es public/ -->
<link rel="stylesheet" href="/css/auth-styles.css">
<script src="/js/auth-app.js"></script>
```

### Opción 2: Usar rutas relativas desde Laravel

Si `asset()` funciona correctamente con el subdirectorio configurado:

```blade
<link rel="stylesheet" href="{{ asset('css/auth-styles.css') }}">
<script src="{{ asset('js/auth-app.js') }}"></script>
```

**Requiere configuración en `config/app.php`:**
```php
'url' => env('APP_URL', 'https://rulossoluciones.com/ModuStackAdmin'),
```

---

## Debug

### Verificar que los archivos existen:

```bash
# Windows
dir public\css\auth-styles.css
dir public\js\auth-app.js

# Linux/Mac
ls -la public/css/auth-styles.css
ls -la public/js/auth-app.js
```

### Probar acceso directo:

Abrir en navegador:
- `https://rulossoluciones.com/ModuStackAdmin/css/auth-styles.css`
- `https://rulossoluciones.com/ModuStackAdmin/js/auth-app.js`

Si estos URLs funcionan, el problema está en el `.htaccess`.  
Si no funcionan, el problema está en la configuración del servidor.

---

**Documentado por:** Auto (Cursor AI)  
**Fecha:** 2025-11-03  
**Estado:** ⚠️ Requiere verificación del servidor

