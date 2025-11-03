# Solución Error 404 en Assets de Vite

## Fecha: 2025-11-03
## Problema: Assets de Vite no se cargan (Error 404)

---

## Error Detectado

```
Failed to load resource: the server responded with a status of 404
- app-BXU3OPy7.css
- app-DMQ01B9M.js
```

**Ruta buscada:** `ModuStackAdmin/build/assets/` (incorrecta)  
**Ruta correcta:** `public/build/assets/`

---

## Causas Posibles

1. **Servidor no sirve desde `public/`**
   - Laravel debe servirse desde `public/` como document root
   - Si se sirve desde raíz, los assets no se encuentran

2. **Vite no detecta archivos compilados**
   - Assets no compilados correctamente
   - Manifest.json no actualizado

3. **Rutas relativas incorrectas**
   - Configuración de APP_URL incorrecta
   - Base path de Vite mal configurado

---

## Soluciones Aplicadas

### 1. Verificar que los assets existen

Los archivos compilados están en:
- ✅ `public/build/assets/app-DMQ01B9M.js`
- ✅ `public/build/assets/app-BXU3OPy7.css`
- ✅ `public/build/manifest.json`

**Estado:** ✅ Archivos presentes

### 2. Limpiar cachés

```bash
php artisan config:clear
php artisan view:clear
php artisan cache:clear
```

**Estado:** ✅ Ejecutado

### 3. Recompilar assets

```bash
npm run build
```

**Estado:** ✅ Build exitoso

---

## Soluciones Adicionales

### Opción 1: Verificar que el servidor sirve desde `public/`

**Si usas `php artisan serve`:**
- ✅ El servidor automáticamente sirve desde `public/`
- No requiere configuración adicional

**Si usas Apache/Nginx:**
- Verificar que el DocumentRoot apunta a `public/`
- Verificar `.htaccess` en `public/` (para Apache)

### Opción 2: Usar Vite Dev Server (Desarrollo)

Para desarrollo con hot reload:

```bash
# Terminal 1: Servidor Laravel
php artisan serve

# Terminal 2: Vite Dev Server
npm run dev
```

**Ventajas:**
- Hot reload automático
- Sin necesidad de recompilar
- Detección automática de cambios

### Opción 3: Verificar configuración de APP_URL

En `.env`:
```env
APP_URL=http://127.0.0.1:8000
```

**Importante:** Si el proyecto está en una subcarpeta, ajustar según necesidad.

---

## Comandos de Verificación

### 1. Verificar archivos compilados
```bash
# Windows
dir public\build\assets

# Linux/Mac
ls -la public/build/assets
```

### 2. Verificar rutas generadas
```bash
php artisan tinker --execute="echo asset('build/assets/app-DMQ01B9M.js');"
```

### 3. Recompilar assets
```bash
npm run build
```

### 4. Limpiar y reconstruir
```bash
# Limpiar
php artisan config:clear
php artisan view:clear

# Recompilar
npm run build
```

---

## Estado Actual

### Archivos Verificados
- ✅ `public/build/assets/app-DMQ01B9M.js` existe
- ✅ `public/build/assets/app-BXU3OPy7.css` existe
- ✅ `public/build/manifest.json` existe

### Build Verificado
- ✅ `npm run build` ejecutado exitosamente
- ✅ Assets generados correctamente

### Cachés Limpiados
- ✅ Config cache limpiado
- ✅ View cache limpiado

---

## Solución Recomendada

### Para Desarrollo (Recomendado)

Usar Vite Dev Server:

1. **Terminal 1 - Laravel:**
   ```bash
   php artisan serve
   ```

2. **Terminal 2 - Vite:**
   ```bash
   npm run dev
   ```

3. **Acceder a:**
   ```
   http://127.0.0.1:8000
   ```

**Ventajas:**
- Hot reload automático
- Sin necesidad de rebuilds manuales
- Mejor experiencia de desarrollo

### Para Producción

1. **Compilar assets:**
   ```bash
   npm run build
   ```

2. **Verificar servidor:**
   - Asegurar que DocumentRoot apunta a `public/`
   - Verificar permisos de archivos

3. **Limpiar cachés:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

## Verificación Final

### Checklist

- [ ] Assets compilados en `public/build/assets/`
- [ ] Manifest.json actualizado
- [ ] Cachés de Laravel limpiados
- [ ] Servidor sirve desde `public/`
- [ ] APP_URL configurado correctamente
- [ ] Build ejecutado sin errores

---

## Si el Problema Persiste

### 1. Verificar permisos
```bash
# Asegurar que los archivos son legibles
chmod 644 public/build/assets/*
```

### 2. Verificar .htaccess (Apache)
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### 3. Verificar Nginx (si aplica)
```nginx
root /ruta/al/proyecto/public;
index index.php;
```

---

**Documentado por:** Auto (Cursor AI)  
**Fecha:** 2025-11-03  
**Estado:** ✅ Soluciones aplicadas

