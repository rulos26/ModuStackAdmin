# Instalación y despliegue de Laravel 12 en Hostinger

Este paquete contiene instrucciones certificadas y un script de despliegue adaptado para Hostinger (shared hosting / VPS).  
Las fuentes principales usadas: documentación oficial de Laravel y guías de Hostinger. (Ver enlaces en el chat).

---

## Resumen rápido (requisitos mínimos)
- **PHP >= 8.2** (Laravel 12 requiere PHP 8.2+).  
- **Composer** (necesario para instalar dependencias).  
- Extensiones PHP requeridas: `BCMath`, `Ctype`, `cURL`, `DOM`, `Fileinfo`, `JSON`, `Mbstring`, `OpenSSL`, `PCRE`, `PDO`, `Tokenizer`, `XML` y `zip`.  
- Acceso **SSH** al hosting (altamente recomendado). Si no tienes SSH, Hostinger permite subir archivos y ejecutar pasos manuales desde el panel, pero SSH facilita composer y migraciones.  
- Base de datos MySQL (o la que uses) creada en hPanel.

---

## Pasos detallados (flujo recomendado)
1. **Verificar PHP y extensiones**  
   - En hPanel > Alojamiento, revisa la versión de PHP y cambia a 8.2+ si es necesario. Activa las extensiones listadas en el apartado anterior.  
   *(Fuente: Laravel docs y Hostinger guides).*  

2. **Subir el proyecto**  
   - Opción A (recomendado): subir el código al servidor vía **Git / SSH** y clonar el repo en tu cuenta.  
   - Opción B: subir un ZIP por el Administrador de Archivos y extraerlo en la carpeta del proyecto.  

3. **Instalar dependencias con Composer (por SSH)**  
   - Desde la carpeta del proyecto (donde está `composer.json`):  
     ```bash
     composer install --optimize-autoloader --no-dev
     ```
   - Si no tienes composer global, Hostinger permite instalar `composer.phar` y usar `php composer.phar` o configurar un alias.  
   *(Fuente: Hostinger - How to use Composer).*  

4. **Configurar entorno**  
   - Copia `.env.example` a `.env` y actualiza DB, MAIL y APP_URL.  
   - Genera la key: `php artisan key:generate`  

5. **Mover `public` a `public_html` (si usas shared hosting)**  
   - En hosting compartido normalmente el `DOCUMENT_ROOT` es `public_html`.  
   - Mueve el contenido de `project/public` a `public_html` y deja el resto de archivos en una carpeta por encima (ej. `project`).  
   - Ajusta `index.php` dentro de `public_html` para requerir el `../project/vendor/autoload.php` y apuntar a `../project/bootstrap/app.php` (corregir rutas relativas).  
   - Alternativa en VPS: configurar VirtualHost con `public` como DocumentRoot.  
   *(Fuente: Hostinger deployment guides).*  

6. **Permisos y enlaces**  
   - Permisos: `storage` y `bootstrap/cache` deben ser escribibles por el servidor web.  
     ```bash
     chmod -R 775 storage bootstrap/cache
     chown -R :www-data storage bootstrap/cache  # si tienes control de usuario/grupo
     ```
   - Crear enlace de almacenamiento público:
     ```bash
     php artisan storage:link
     ```

7. **Migraciones y optimizaciones**  
   ```bash
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan optimize
   ```

8. **Configuración adicional en Hostinger**  
   - Asegura que `.env` `APP_ENV=production`, `APP_DEBUG=false`.  
   - Configura los certificados SSL desde hPanel (Let’s Encrypt) y fuerza HTTPS en `.htaccess` o con redirecciones.  

9. **Si no tienes SSH (opción limitada)**  
   - Genera `vendor` localmente en tu máquina (`composer install --no-dev`) y sube la carpeta `vendor`.  
   - Sube el resto del proyecto, ajusta `.env` y migraciones manualmente (si no puedes ejecutar `php artisan migrate` en servidor, ejecuta migraciones localmente y exporta la base de datos).  
   - Nota: este flujo es menos flexible y puede requerir correcciones de permisos y rutas.

---

## Archivos incluidos en este paquete
- `README.md` (este archivo).
- `deploy_template.sh` — script **de plantilla** que automatiza pasos comunes asumiendo SSH y privilegios adecuados. **Revisa y adapta antes de ejecutar.**

---

## Advertencias importantes
- **Haz backup** de la base de datos y archivos antes de mover o ejecutar migraciones.  
- Revisa la versión de PHP del hosting y prueba en un entorno de staging antes de producción.  
- El script es **plantilla**; personalízalo a tu estructura de proyecto y usuarios del servidor.

---

## Comandos de verificación útiles

Antes y después del despliegue, puedes verificar el estado con estos comandos:

```bash
# Verificar versión de PHP
php -v

# Verificar extensiones PHP instaladas
php -m

# Verificar que Composer funciona
composer --version
# o si usas composer.phar:
php composer.phar --version

# Verificar que Laravel está funcionando
cd /ruta/a/tu/proyecto
php artisan --version

# Verificar configuración de Laravel
php artisan config:show

# Verificar conexión a base de datos
php artisan db:show

# Verificar rutas disponibles
php artisan route:list

# Verificar permisos de directorios críticos
ls -la storage/
ls -la bootstrap/cache/
```

---

## Troubleshooting (Solución de problemas)

### Error: "PHP version not supported"
- **Causa**: PHP < 8.2 instalado
- **Solución**: Cambia la versión de PHP en hPanel > Alojamiento > Versión de PHP a 8.2 o superior

### Error: "Composer not found"
- **Causa**: Composer no está instalado o no está en el PATH
- **Solución**: 
  - Instala Composer globalmente: `curl -sS https://getcomposer.org/installer | php`
  - O descarga `composer.phar` y colócalo en `$HOME/composer.phar`
  - O colócalo en la raíz del proyecto como `composer.phar`

### Error: "Extension X not found"
- **Causa**: Extensión PHP faltante
- **Solución**: Activa la extensión en hPanel > Alojamiento > Extensiones PHP

### Error: "Permission denied" en storage o bootstrap/cache
- **Causa**: Permisos incorrectos
- **Solución**: 
  ```bash
  chmod -R 775 storage bootstrap/cache
  # Si tienes control de usuario/grupo:
  chown -R usuario:www-data storage bootstrap/cache
  ```

### Error: "Class not found" o errores de autoload
- **Causa**: Dependencias no instaladas o autoloader desactualizado
- **Solución**: 
  ```bash
  composer install --optimize-autoloader
  composer dump-autoload
  ```

### Error: "APP_KEY not set"
- **Causa**: Falta la clave de aplicación en `.env`
- **Solución**: 
  ```bash
  php artisan key:generate
  ```

### Error: "Database connection failed"
- **Causa**: Credenciales incorrectas en `.env`
- **Solución**: Verifica y actualiza en `.env`:
  - `DB_CONNECTION`
  - `DB_HOST`
  - `DB_PORT`
  - `DB_DATABASE`
  - `DB_USERNAME`
  - `DB_PASSWORD`

### Error: "Route cache failed" o "Config cache failed"
- **Causa**: Cache corrupto o permisos insuficientes
- **Solución**: 
  ```bash
  php artisan config:clear
  php artisan route:clear
  php artisan view:clear
  php artisan cache:clear
  # Luego regenera:
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```

### La aplicación muestra "500 Internal Server Error"
- **Causa**: Varias posibles (permisos, configuración, errores PHP)
- **Solución**: 
  1. Revisa los logs: `storage/logs/laravel.log`
  2. Verifica permisos de `storage` y `bootstrap/cache`
  3. Verifica que `.env` esté configurado correctamente
  4. Asegúrate de que `APP_DEBUG=false` en producción (o `true` temporalmente para ver errores)

### Los assets (CSS/JS) no cargan
- **Causa**: Rutas incorrectas o archivos no copiados a `public_html`
- **Solución**: 
  1. Verifica que los archivos estén en `public_html`
  2. Verifica que `APP_URL` en `.env` sea correcta
  3. Ejecuta: `php artisan config:clear && php artisan config:cache`

---

## Variables de entorno importantes en `.env`

Asegúrate de configurar estas variables críticas:

```env
APP_NAME="Tu Aplicación"
APP_ENV=production
APP_KEY=base64:...  # Generado con php artisan key:generate
APP_DEBUG=false
APP_URL=https://tudominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_base_datos
DB_USERNAME=usuario_db
DB_PASSWORD=contraseña_db

# Configuración de sesión (importante para producción)
SESSION_DRIVER=database  # o file
SESSION_LIFETIME=120

# Cache (recomendado: redis o database en producción)
CACHE_DRIVER=file  # o redis/database según disponibilidad

# Queue (si usas colas)
QUEUE_CONNECTION=database  # o redis/sync
```

---

## Checklist post-despliegue

Después de ejecutar el script, verifica:

- [ ] La aplicación carga correctamente en el navegador
- [ ] No hay errores en `storage/logs/laravel.log`
- [ ] Los assets (CSS/JS/imágenes) cargan correctamente
- [ ] Las rutas funcionan (prueba navegar por la aplicación)
- [ ] La base de datos está conectada (prueba login o consultas)
- [ ] Los permisos de `storage` y `bootstrap/cache` son correctos (775)
- [ ] El enlace simbólico de storage existe: `public/storage -> storage/app/public`
- [ ] `APP_DEBUG=false` en producción
- [ ] SSL/HTTPS está configurado y funciona
- [ ] Las migraciones se ejecutaron correctamente

--- 
