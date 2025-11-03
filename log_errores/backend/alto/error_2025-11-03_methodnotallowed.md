# Error MethodNotAllowedHttpException - GET Method

**Fecha:** 2025-11-03  
**Componente/Área Afectada:** Aplicación Laravel ModuStackUser  
**Archivo Afectado:** `ModuStackUser/index.php`, configuración de rutas  
**Tipo:** backend  
**Severidad:** alto

---

## Descripción

Al acceder a ModuStackUser, se produce un error `MethodNotAllowedHttpException` indicando que el método GET no está soportado para la ruta `/`, solo HEAD.

**Error observado:**
```
Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException

The GET method is not supported for route /. Supported methods: HEAD.
```

**URL:** `rulossoluciones.com/ModuStackAdmin/ModuStackUser/`

---

## Análisis del Problema

### Causa Raíz Identificada

**PROBLEMA PRINCIPAL:** El proyecto está usando el archivo `index.php` incorrecto.

Laravel 12, cuando se instala correctamente, coloca el punto de entrada en `public/index.php`, NO en la raíz del proyecto.

**Estructura Incorrecta Actual:**
```
ModuStackUser/
├── index.php          ❌ Archivo incorrecto en raíz
├── public/
│   ├── index.php      ✅ Archivo correcto de Laravel
│   └── .htaccess
└── ...
```

**Impacto:**
- El `index.php` en la raíz no tiene la configuración correcta de rutas
- El `.htaccess` no está redirigiendo correctamente a `public/index.php`
- Laravel no puede resolver las rutas correctamente

### Conflicto de Punto de Entrada

El `index.php` en la raíz del proyecto ModuStackUser parece ser una copia antigua o incorrecta. El punto de entrada estándar de Laravel es `public/index.php`.

---

## Acción Correctiva Aplicada

### Solución 1: Corregir .htaccess para Redirigir a public/

**Modificar:** `ModuStackUser/.htaccess`

**Nueva configuración:**
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Redirigir todo a public/index.php
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ public/index.php [L]
</IfModule>
```

### Solución 2: Eliminar index.php de la Raíz

El archivo `ModuStackUser/index.php` debe eliminarse o renombrarse porque:
1. Laravel no lo usa en producción
2. Crea confusión sobre el punto de entrada
3. Puede causar conflictos de routing

**Acción:**
```bash
# Renombrar para backup
mv ModuStackUser/index.php ModuStackUser/index.php.backup
```

O mejor aún, eliminarlo si no se necesita.

### Solución 3: Verificar Estructura de Directorios

Asegurar que la estructura sea:
```
ModuStackUser/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/              ← Directorio web root
│   ├── index.php        ← Punto de entrada
│   ├── .htaccess
│   └── ...
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
├── composer.json
└── artisan
```

**NO DEBE EXISTIR:**
- `ModuStackUser/index.php` en la raíz

---

## Archivos Modificados o Creados

| Archivo | Estado | Descripción |
|---------|--------|-------------|
| `ModuStackUser/.htaccess` | Modificar | Agregar redirección a public/ |
| `ModuStackUser/index.php` | Eliminar/Renombrar | No debe existir en raíz |

---

## Pasos de Resolución

### En el Servidor

1. **Acceder al servidor via SSH o cPanel**

2. **Renombrar index.php problemático:**
   ```bash
   cd /home/u494150416/domains/rulossoluciones.com/public_html/ModuStackAdmin/ModuStackUser
   mv index.php index.php.backup
   ```

3. **Corregir .htaccess:**
   ```bash
   # Editar .htaccess
   nano .htaccess
   ```

   Reemplazar contenido con:
   ```apache
   <IfModule mod_rewrite.c>
       <IfModule mod_negotiation.c>
           Options -MultiViews -Indexes
       </IfModule>

       RewriteEngine On

       # Redirigir todo a public/index.php
       RewriteCond %{REQUEST_FILENAME} !-d
       RewriteCond %{REQUEST_FILENAME} !-f
       RewriteRule ^ public/index.php [L]
   </IfModule>
   ```

4. **Verificar que public/index.php existe:**
   ```bash
   ls -la public/index.php
   ```

5. **Verificar acceso:**
   - Acceder a: `https://rulossoluciones.com/ModuStackAdmin/ModuStackUser/`
   - Debe cargar correctamente

---

## Configuración Alternativa (Si Public No Funciona)

Si por alguna razón el servidor no permite usar `public/` como web root:

### Opción A: Mover Contenido de public/ a Raíz

```bash
# Mover archivos de public/ a raíz
mv public/* .
mv public/.htaccess .
rmdir public
```

**Nota:** Esta opción no es recomendada para Laravel 12.

### Opción B: Crear Script de Redirección

Crear archivo `index.php` en raíz que redirija a public:

```php
<?php
// index.php en raíz - Redirigir a public/
require __DIR__.'/public/index.php';
```

---

## Referencias Técnicas

- [Laravel 12 Directory Structure](https://laravel.com/docs/12.x/structure)
- [Laravel Deployment - Server Configuration](https://laravel.com/docs/12.x/deployment#server-configuration)
- [Apache mod_rewrite Documentation](https://httpd.apache.org/docs/current/mod/mod_rewrite.html)

---

## Estado

⚠️ **PENDIENTE RESOLUCIÓN EN SERVIDOR**

**Pasos Completados:**
- ✅ Problema identificado
- ✅ Solución documentada
- ⚠️ Pendiente: Aplicar cambios en servidor

**Acción Crítica Requerida:**
1. Eliminar `ModuStackUser/index.php` de la raíz
2. Corregir `.htaccess` para redirigir a `public/index.php`
3. Verificar que `public/index.php` esté correcto

**Fecha de Identificación:** 2025-11-03

---

## Notas Importantes

1. **Estructura Laravel:** Laravel SIEMPRE usa `public/` como directorio web root en instalaciones estándar

2. **index.php duplicado:** Tener dos archivos `index.php` causa conflictos de routing

3. **Compatibilidad:** La solución es compatible con Laravel 12 (arquitectura tradicional)

4. **URL Base:** Después de la corrección, las rutas funcionarán normalmente

