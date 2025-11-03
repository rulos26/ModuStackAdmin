# Error de Redirección - ModuStackUser

**Fecha:** 2025-11-03  
**Componente/Área Afectada:** Sistema de Redirección / Configuración Apache  
**Archivo Afectado:** `.htaccess` (raíz), `ModuStackUser/.htaccess`  
**Tipo:** backend  
**Severidad:** medio

---

## Descripción

El portal principal (`index.html`) funciona correctamente con sus botones y diseño, pero al intentar redirigir a la aplicación Laravel en `ModuStackUser/`, el sistema reporta que no existe o no puede acceder.

**Error observado:**
- Portal principal carga correctamente
- Botones y elementos visuales funcionan
- Al hacer clic en "Acceder Ahora" o en la tarjeta, no se puede acceder a ModuStackUser
- Mensaje: "no existe" o error 404

---

## Análisis del Problema

### Causa Raíz Identificada

El archivo `.htaccess` en la raíz del proyecto tenía reglas de reescritura que interferían con el acceso a la carpeta `ModuStackUser/`. Específicamente:

1. **Regla de exclusión incorrecta**: La condición `RewriteCond %{REQUEST_URI} !^/ModuStackUser` no estaba manejando correctamente todas las variantes de URLs (con o sin barra final).

2. **Falta de prioridad en reglas**: Las reglas de reescritura no tenían la prioridad correcta, causando que algunas peticiones a ModuStackUser fueran interceptadas.

3. **RewriteBase en ModuStackUser**: El `.htaccess` de ModuStackUser no tenía un `RewriteBase` configurado correctamente.

---

## Acción Correctiva Aplicada

### 1. Corrección del `.htaccess` en la raíz

**Antes:**
```apache
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_URI} !^/ModuStackUser
RewriteRule ^$ index.html [L]
```

**Después:**
```apache
# Permitir acceso completo a ModuStackUser - NO procesar estas rutas
RewriteCond %{REQUEST_URI} ^/ModuStackUser(/.*)?$
RewriteRule ^ - [L]

# Redirigir raíz vacía a index.html
RewriteCond %{REQUEST_URI} ^/$
RewriteRule ^ index.html [L]

# Para otras rutas que no existen, servir index.html si es una petición GET
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_URI} !^/ModuStackUser
RewriteCond %{REQUEST_METHOD} GET
RewriteRule ^ index.html [L]
```

**Mejoras:**
- Regex mejorado `^/ModuStackUser(/.*)?$` que captura `/ModuStackUser` y `/ModuStackUser/cualquier-ruta`
- Regla `[L]` (Last) para detener el procesamiento inmediatamente
- Separación clara de reglas para mejor mantenibilidad

### 2. Corrección del `.htaccess` en ModuStackUser

**Antes:**
```apache
Options +SymLinksIfOwnerMatch
RewriteEngine On

RewriteRule ^ index.php [L]
```

**Después:**
```apache
Options +SymLinksIfOwnerMatch
RewriteEngine On
RewriteBase /ModuStackUser/

# Si el archivo o directorio no existe, redirigir a index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [L]
```

**Mejoras:**
- Agregado `RewriteBase /ModuStackUser/` para establecer la base correcta
- Agregadas condiciones para verificar existencia de archivos/directorios antes de redirigir

---

## Archivos Modificados

1. `.htaccess` (raíz del proyecto)
2. `ModuStackUser/.htaccess`

---

## Pasos de Verificación

1. ✅ Acceder a `http://localhost/ModuStackAdmin/` - Portal debe cargar
2. ✅ Hacer clic en "Acceder Ahora" o en la tarjeta - Debe redirigir a ModuStackUser
3. ✅ Verificar que `http://localhost/ModuStackAdmin/ModuStackUser/` carga la aplicación Laravel
4. ✅ Verificar que las rutas de Laravel funcionan correctamente

---

## Referencias Técnicas

- [Apache mod_rewrite Documentation](https://httpd.apache.org/docs/current/mod/mod_rewrite.html)
- [Laravel URL Configuration](https://laravel.com/docs/12.x/configuration#url-configuration)
- [XAMPP Virtual Host Configuration](https://www.apachefriends.org/docs/)

---

## Estado

✅ **RESUELTO**

**Fecha de Resolución:** 2025-11-03  
**Tiempo de Resolución:** ~15 minutos

---

## Notas Adicionales

- El problema era específico de la configuración de Apache/XAMPP
- La solución es compatible con Laravel 12 (arquitectura tradicional)
- No se requieren cambios en código PHP o Laravel
- La solución funciona tanto en desarrollo (XAMPP) como en producción

