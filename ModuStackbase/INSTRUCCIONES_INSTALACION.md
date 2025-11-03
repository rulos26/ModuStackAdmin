# Instrucciones de Instalación - ModuStackUser

**Fecha:** 2025-11-03  
**Proyecto:** ModuStackUser  
**Versión:** Laravel 12

---

## ⚠️ PROBLEMA IDENTIFICADO

**El diagnóstico muestra que falta la carpeta `vendor/` con las dependencias de Composer.**

Esto es CRÍTICO para que Laravel funcione. Sin estas dependencias, la aplicación no puede ejecutarse.

---

## 🚀 SOLUCIÓN: Instalación de Dependencias

### Opción 1: Instalación Automática (Recomendada)

#### Para Windows:
```bash
cd ModuStackUser
instalar.bat
```

#### Para Linux/Mac:
```bash
cd ModuStackUser
chmod +x instalar.sh
./instalar.sh
```

### Opción 2: Instalación Manual

#### Paso 1: Instalar Composer (si no lo tienes)

**Windows:**
1. Descargar desde: https://getcomposer.org/download/
2. Ejecutar el instalador
3. Verificar instalación: `composer --version`

**Linux:**
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version
```

**Mac:**
```bash
brew install composer
composer --version
```

#### Paso 2: Instalar Dependencias

```bash
cd ModuStackUser

# Instalar dependencias de producción
composer install --no-dev --optimize-autoloader

# O si quieres incluir dependencias de desarrollo
composer install
```

#### Paso 3: Configurar Archivo .env

```bash
# Si no existe .env, copiarlo desde .env.example
cp .env.example .env

# Generar APP_KEY
php artisan key:generate
```

#### Paso 4: Limpiar y Optimizar

```bash
# Limpiar caché
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Paso 5: Configurar Permisos (Linux/Mac)

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

---

## 📋 Verificación Post-Instalación

### 1. Verificar que vendor existe

```bash
ls -la ModuStackUser/vendor/
```

Debe mostrar carpetas como:
- `autoload.php`
- `composer/`
- `laravel/`
- Y muchas otras carpetas

### 2. Ejecutar Diagnóstico Nuevamente

Acceder a: `https://rulossoluciones.com/ModuStackAdmin/ModuStackUser/test.php`

Ahora debe mostrar:
- ✅ `vendor/autoload.php: ✅`
- ✅ `Autoload cargado correctamente: ✅`
- ✅ `Laravel se inicializa correctamente: ✅`

### 3. Verificar Acceso Normal

Acceder a: `https://rulossoluciones.com/ModuStackAdmin/ModuStackUser/`

Debe cargar la aplicación Laravel sin errores.

---

## 🔧 Configuración de Producción

### Variables Importantes en .env

```env
APP_NAME=ModuStackUser
APP_ENV=production
APP_DEBUG=false
APP_URL=https://rulossoluciones.com/ModuStackAdmin/ModuStackUser

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### Base de Datos

```bash
# Ejecutar migraciones
php artisan migrate

# Si necesitas seeders
php artisan db:seed
```

---

## 🐛 Problemas Comunes

### Error: "composer command not found"

**Solución:** Instalar Composer siguiendo las instrucciones del Paso 1.

### Error: "memory limit exceeded"

**Solución:**
```bash
php -d memory_limit=512M composer install
```

### Error: "permission denied" (Linux/Mac)

**Solución:**
```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### Error: "autoload.php not found" después de instalación

**Solución:**
1. Verificar que estás en el directorio correcto
2. Ejecutar: `composer dump-autoload`
3. Verificar permisos del directorio

---

## 📚 Referencias

- [Composer Installation](https://getcomposer.org/download/)
- [Laravel Installation](https://laravel.com/docs/12.x/installation)
- [Laravel Deployment](https://laravel.com/docs/12.x/deployment)

---

## ✅ Checklist de Instalación

- [ ] Composer instalado
- [ ] Dependencias de Composer instaladas (`vendor/` existe)
- [ ] Archivo `.env` configurado
- [ ] `APP_KEY` generada
- [ ] Caché limpiado
- [ ] Aplicación optimizada
- [ ] Permisos configurados
- [ ] Diagnóstico muestra todo ✅
- [ ] Acceso a la aplicación funciona

---

**Última actualización:** 2025-11-03

