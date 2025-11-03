# Creación de ModuStackbase - Copia de ModuStackUser

**Fecha:** 2025-01-27  
**Operación:** Duplicación completa de ModuStackUser a ModuStackbase  
**Versión Laravel:** 12.12.0

---

## 📋 Resumen

Se creó una copia exacta del proyecto `ModuStackUser` con el nombre `ModuStackbase` para permitir tener dos instancias independientes de Laravel en el mismo servidor.

---

## ✅ Acciones Realizadas

### 1. Copia Completa del Proyecto

Se realizó una copia recursiva de toda la estructura de `ModuStackUser` a `ModuStackbase`:

```powershell
Copy-Item -Path "ModuStackUser" -Destination "ModuStackbase" -Recurse -Force
```

**Estructura copiada:**
- ✅ Todos los archivos PHP
- ✅ Configuraciones (config/)
- ✅ Rutas (routes/)
- ✅ Controladores y Modelos (app/)
- ✅ Vistas (resources/views/)
- ✅ Base de datos (database/)
- ✅ Storage (storage/)
- ✅ Bootstrap (bootstrap/)
- ✅ Archivos de configuración (composer.json, package.json, etc.)

**Nota:** La carpeta `vendor/` no se copió (está en .gitignore). Se debe ejecutar `composer install` en ModuStackbase si es necesario.

### 2. Configuración de .htaccess Principal

Se actualizó el archivo `.htaccess` en la raíz de `ModuStackAdmin` para permitir acceso a `ModuStackbase`:

```8:14:.htaccess
    # Permitir acceso completo a ModuStackUser - NO procesar estas rutas
    RewriteCond %{REQUEST_URI} ^/ModuStackUser(/.*)?$
    RewriteRule ^ - [L]
    
    # Permitir acceso completo a ModuStackbase - NO procesar estas rutas
    RewriteCond %{REQUEST_URI} ^/ModuStackbase(/.*)?$
    RewriteRule ^ - [L]
```

También se actualizó la regla para excluir `ModuStackbase` del procesamiento de rutas no encontradas:

```20:26:.htaccess
    # Para otras rutas que no existen, servir index.html si es una petición GET
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} !^/ModuStackUser
    RewriteCond %{REQUEST_URI} !^/ModuStackbase
    RewriteCond %{REQUEST_METHOD} GET
    RewriteRule ^ index.html [L]
```

---

## 🌐 URLs de Acceso

### ModuStackUser
- URL Local: `http://localhost/ModuStackAdmin/ModuStackUser/`
- URL Producción: `https://rulossoluciones.com/ModuStackAdmin/ModuStackUser/`

### ModuStackbase
- URL Local: `http://localhost/ModuStackAdmin/ModuStackbase/`
- URL Producción: `https://rulossoluciones.com/ModuStackAdmin/ModuStackbase/`

---

## 🔧 Configuración Incluida

`ModuStackbase` incluye todas las correcciones y mejoras aplicadas a `ModuStackUser`:

1. ✅ Corrección de detección de método HTTP (GET/HEAD) en `index.php`
2. ✅ Ruta `/` configurada correctamente en `routes/web.php`
3. ✅ `.htaccess` configurado para routing correcto
4. ✅ Bootstrap configurado sin health checks innecesarios

---

## 📝 Archivos Modificados

| Archivo | Cambio | Estado |
|---------|--------|--------|
| `.htaccess` (raíz) | Agregadas reglas para ModuStackbase | ✅ |
| `ModuStackbase/` | Carpeta completa creada | ✅ |

---

## ⚠️ Consideraciones Importantes

### 1. Base de Datos

Si `ModuStackbase` necesita una base de datos separada, deberás:

1. Crear una nueva base de datos
2. Actualizar el archivo `.env` en `ModuStackbase/`
3. Ejecutar las migraciones:
   ```bash
   cd ModuStackbase
   php artisan migrate
   ```

### 2. Variables de Entorno

El archivo `.env` de `ModuStackbase` debe configurarse independientemente:

```bash
cd ModuStackbase
cp .env.example .env
php artisan key:generate
```

**Asegúrate de cambiar:**
- `APP_NAME` (si deseas un nombre diferente)
- `DB_DATABASE` (si usas base de datos separada)
- `APP_URL` (si es necesario)

### 3. Dependencias

Si necesitas instalar dependencias en `ModuStackbase`:

```bash
cd ModuStackbase
composer install --no-dev --optimize-autoloader
```

### 4. Caché

Después de configurar, limpia los cachés:

```bash
cd ModuStackbase
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## 🧪 Verificación

### 1. Verificar Rutas

```bash
cd ModuStackbase
php artisan route:list
```

**Resultado esperado:**
```
GET|HEAD       / ...................................................... home
GET|HEAD       storage/{path} ................................ storage.local
```

### 2. Acceso Web

- Navega a `http://localhost/ModuStackAdmin/ModuStackbase/`
- Deberías ver la página de bienvenida de Laravel

---

## 🔄 Sincronización Futura

Si necesitas mantener ambos proyectos sincronizados en el futuro:

1. **Modificar solo ModuStackUser:**
   - Haz cambios en `ModuStackUser`
   - Prueba y verifica
   - Copia cambios específicos a `ModuStackbase` si es necesario

2. **Modificar ambos:**
   - Haz cambios en ambos proyectos
   - O considera usar symlinks para archivos compartidos

3. **Independientes:**
   - Cada proyecto puede evolucionar de forma independiente
   - No hay dependencias entre ellos

---

## 📊 Estructura Final

```
ModuStackAdmin/
├── .htaccess                    # Configurado para ModuStackUser y ModuStackbase
├── index.html                   # Portal principal
├── ModuStackUser/               # Proyecto original
│   ├── index.php
│   ├── routes/
│   ├── app/
│   └── ...
└── ModuStackbase/               # Proyecto duplicado
    ├── index.php
    ├── routes/
    ├── app/
    └── ...
```

---

## ✅ Estado Final

- ✅ `ModuStackbase` creado como copia exacta de `ModuStackUser`
- ✅ `.htaccess` configurado para permitir acceso a ambos proyectos
- ✅ Rutas funcionando correctamente
- ✅ Configuración de detección HTTP aplicada
- ✅ Listo para uso independiente

---

**Última actualización:** 2025-01-27

