# Solución Error 403 Forbidden

## Fecha: 2025-11-03
## Problema: Error 403 al acceder al sitio

---

## Error Detectado

```
403 Forbidden
Access to this resource on the server is denied!
```

**URL:** `rulossoluciones.com` o `rulossoluciones.com/ModuStackAdmin`

---

## Causas Posibles

1. **DocumentRoot no apunta a `public/`**
   - Laravel debe servirse desde `public/` como document root
   - Si apunta a la raíz del proyecto, causa 403

2. **Permisos de archivos/directorios**
   - Archivos sin permisos de lectura
   - Directorios sin permisos de ejecución

3. **.htaccess bloqueado**
   - Apache puede estar bloqueando .htaccess
   - Configuración de AllowOverride incorrecta

4. **Configuración de servidor**
   - PHP no configurado correctamente
   - Módulos de Apache faltantes

---

## Soluciones

### Solución 1: Verificar DocumentRoot (Apache)

El DocumentRoot debe apuntar a `public/`:

```apache
<VirtualHost *:80>
    ServerName rulossoluciones.com
    DocumentRoot /ruta/completa/al/proyecto/ModuStackAdmin/public
    
    <Directory /ruta/completa/al/proyecto/ModuStackAdmin/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Para subdirectorio:**
Si el proyecto está en `/modustack11/` o `/ModuStackAdmin/`, el DocumentRoot debe ser:
```
DocumentRoot /ruta/completa/al/proyecto/ModuStackAdmin/public
```

---

### Solución 2: Verificar .htaccess en raíz

Si el DocumentRoot no puede cambiar, crear `.htaccess` en la raíz que redirija a `public/`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

**Archivo:** `.htaccess` (en la raíz del proyecto)

---

### Solución 3: Verificar permisos

**En servidor Linux:**
```bash
# Permisos de directorios
chmod 755 public
chmod 755 public/js
chmod 755 public/css

# Permisos de archivos
chmod 644 public/js/auth-app.js
chmod 644 public/css/auth-styles.css
chmod 644 public/index.php

# Permisos de .htaccess
chmod 644 public/.htaccess
```

---

### Solución 4: Verificar AllowOverride (Apache)

En configuración de Apache:

```apache
<Directory /ruta/al/proyecto/ModuStackAdmin>
    AllowOverride All
    Require all granted
</Directory>
```

---

### Solución 5: Crear .htaccess mejorado en raíz

Si el proyecto está en un subdirectorio, crear `.htaccess` en la raíz:

**Archivo:** `.htaccess` (raíz del proyecto)

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirigir todo a public/
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

---

## Verificación de Archivos

### Archivos que deben existir y ser accesibles:

- ✅ `public/index.php` - Punto de entrada de Laravel
- ✅ `public/.htaccess` - Configuración de Apache
- ✅ `public/js/auth-app.js` - JavaScript de autenticación
- ✅ `public/css/auth-styles.css` - Estilos CSS

### Verificar acceso directo:

```
https://rulossoluciones.com/ModuStackAdmin/public/js/auth-app.js
https://rulossoluciones.com/ModuStackAdmin/public/css/auth-styles.css
```

Si estos URLs funcionan, el problema es la configuración del DocumentRoot.

---

## Configuración Recomendada

### Para Apache con subdirectorio

**1. DocumentRoot debe ser `public/`:**

Si el proyecto está en `/home/usuario/public_html/ModuStackAdmin/`:

```apache
DocumentRoot /home/usuario/public_html/ModuStackAdmin/public
```

**2. O usar .htaccess en raíz:**

Si no puedes cambiar DocumentRoot, crear `.htaccess` en la raíz que redirija todo a `public/`.

---

## Diagnóstico Rápido

### Probar URLs directamente:

1. **CSS:**
   ```
   https://rulossoluciones.com/ModuStackAdmin/public/css/auth-styles.css
   ```

2. **JS:**
   ```
   https://rulossoluciones.com/ModuStackAdmin/public/js/auth-app.js
   ```

3. **Index:**
   ```
   https://rulossoluciones.com/ModuStackAdmin/public/index.php
   ```

**Si estos URLs funcionan:** El problema es la configuración del servidor.

**Si estos URLs también dan 403:** Problema de permisos o configuración de Apache.

---

## Solución Inmediata

### Opción A: Cambiar DocumentRoot (Recomendado)

En configuración de Apache/Nginx, cambiar DocumentRoot a `public/`.

### Opción B: Crear .htaccess en raíz

Crear `.htaccess` en la raíz del proyecto que redirija a `public/`.

---

**Documentado por:** Auto (Cursor AI)  
**Fecha:** 2025-11-03  
**Estado:** ⚠️ Error 403 - Requiere configuración del servidor

