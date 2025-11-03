# README de Instalación Rápida - ModuStackUser

**Fecha:** 2025-11-03

---

## ⚠️ PROBLEMA PRINCIPAL: Falta carpeta vendor/

**Error:** `vendor/autoload.php no existe`

**Solución:** Ejecutar composer install

---

## 🚀 Instalación Rápida

### 1. Instalar Dependencias

```bash
cd ModuStackUser
composer install --no-dev --optimize-autoloader
```

### 2. Configurar .env

Si no existe .env, copiarlo:
```bash
cp .env.example .env
```

Generar APP_KEY:
```bash
php artisan key:generate
```

### 3. Limpiar Caché

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 4. Optimizar

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## ✅ Verificación

**URL:** `http://localhost/ModuStackAdmin/ModuStackUser/`

**Resultado esperado:** Página de bienvenida de Laravel

---

## 📝 Archivos Importantes

- `ModuStackUser/index.php` - Punto de entrada ✅
- `ModuStackUser/.htaccess` - Routing configurado ✅
- `ModuStackUser/bootstrap/app.php` - Sin health check ✅

---

**Listo para producción después de ejecutar `composer install`** 🎉

