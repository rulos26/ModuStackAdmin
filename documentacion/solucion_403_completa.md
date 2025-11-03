# Solución Completa Error 403 Forbidden

## Fecha: 2025-11-03
## Problema: Error 403 al acceder a rulossoluciones.com

---

## Error

```
403 Forbidden
Access to this resource on the server is denied!
```

---

## Causas y Soluciones

### 1. DocumentRoot no apunta a `public/` ⚠️

**Problema más común en hosting compartido.**

**Solución A: Cambiar DocumentRoot en Apache**

Si tienes acceso a configuración de Apache:

```apache
<VirtualHost *:80>
    ServerName rulossoluciones.com
    DocumentRoot /ruta/al/proyecto/ModuStackAdmin/public
    
    <Directory /ruta/al/proyecto/ModuStackAdmin/public>
        AllowOverride All
        Require all granted
        Options -Indexes +FollowSymLinks
    </Directory>
</VirtualHost>
```

**Solución B: .htaccess en raíz (Ya aplicado)**

He actualizado el `.htaccess` en la raíz para redirigir automáticamente a `public/`.

---

### 2. Permisos de archivos

**Verificar permisos:**

```bash
# Directorios
chmod 755 public
chmod 755 public/js
chmod 755 public/css

# Archivos
chmod 644 public/index.php
chmod 644 public/js/auth-app.js
chmod 644 public/css/auth-styles.css
chmod 644 public/.htaccess
chmod 644 .htaccess
```

---

### 3. AllowOverride en Apache

**Problema:** Apache puede estar bloqueando .htaccess

**Solución:** Verificar en configuración de Apache que AllowOverride esté en "All":

```apache
<Directory /ruta/al/proyecto>
    AllowOverride All
    Require all granted
</Directory>
```

---

### 4. Verificar que index.php existe

El archivo `public/index.php` debe existir y ser legible.

**Verificación:**
```
https://rulossoluciones.com/ModuStackAdmin/public/index.php
```

Si esto funciona, el problema es la configuración de rutas.

---

## .htaccess Actualizado

**Archivo:** `.htaccess` (raíz del proyecto)

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Permitir acceso directo a public/
    RewriteCond %{REQUEST_URI} ^/public/
    RewriteRule ^(.*)$ - [L]
    
    # Permitir archivos existentes (js, css, imágenes)
    RewriteCond %{REQUEST_FILENAME} -f
    RewriteRule ^(.*)$ - [L]
    
    # Permitir directorios existentes
    RewriteCond %{REQUEST_FILENAME} -d
    RewriteRule ^(.*)$ - [L]
    
    # Redirigir todo lo demás a public/
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

---

## URLs de Prueba

### 1. Probar acceso directo a archivos:

```
https://rulossoluciones.com/ModuStackAdmin/public/index.php
https://rulossoluciones.com/ModuStackAdmin/public/js/auth-app.js
https://rulossoluciones.com/ModuStackAdmin/public/css/auth-styles.css
```

### 2. Si estos URLs funcionan:

El problema es la configuración de redirección.

### 3. Si estos URLs también dan 403:

Problema de permisos o configuración del servidor.

---

## Soluciones Adicionales

### Opción 1: Verificar en Panel de Hosting

Si usas cPanel o similar:
1. Verificar que DocumentRoot apunta a `public_html/ModuStackAdmin/public`
2. Verificar permisos de archivos
3. Verificar que mod_rewrite está habilitado

### Opción 2: Crear enlace simbólico

Si no puedes cambiar DocumentRoot:

```bash
# En el directorio public_html
ln -s /ruta/completa/ModuStackAdmin/public modustack11
```

### Opción 3: Mover archivos a public_html

Si el servidor requiere que esté en `public_html/`:

1. Copiar contenido de `public/` a `public_html/`
2. Ajustar rutas en `index.php` si es necesario

---

## Diagnóstico Paso a Paso

### Paso 1: Verificar estructura

```
ModuStackAdmin/
├── public/
│   ├── index.php ✅
│   ├── .htaccess ✅
│   ├── js/
│   │   └── auth-app.js ✅
│   └── css/
│       └── auth-styles.css ✅
└── .htaccess ✅
```

### Paso 2: Probar URLs

1. `https://rulossoluciones.com/ModuStackAdmin/public/index.php`
2. `https://rulossoluciones.com/ModuStackAdmin/public/js/auth-app.js`

### Paso 3: Verificar logs

Revisar logs de Apache para ver qué error específico causa el 403.

---

## Solución Temporal

Si no puedes cambiar configuración del servidor, crear una redirección:

**Archivo:** `index.php` (en la raíz, si el servidor lo busca ahí)

```php
<?php
header('Location: /ModuStackAdmin/public/index.php');
exit;
```

---

**Documentado por:** Auto (Cursor AI)  
**Fecha:** 2025-11-03  
**Estado:** ⚠️ Requiere verificación del servidor

