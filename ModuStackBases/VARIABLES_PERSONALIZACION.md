# Guía de Personalización de Variables

## 📍 Ubicación de las Variables

Las variables configurables están en el archivo **`deploy_template.sh`** en las **líneas 13-18**.

---

## 🔧 Variables que DEBES Personalizar

### 1. `REPO_URL` (Línea 14)
**¿Qué es?** La URL de tu repositorio Git (GitHub, GitLab, Bitbucket, etc.)

**Valor actual:**
```bash
REPO_URL="git@github.com:usuario/tu-proyecto.git"
```

**Ejemplos de cómo personalizarlo:**
```bash
# GitHub (SSH)
REPO_URL="git@github.com:tu-usuario/tu-proyecto-laravel.git"

# GitHub (HTTPS - si no tienes SSH configurado)
REPO_URL="https://github.com:tu-usuario/tu-proyecto-laravel.git"

# GitLab
REPO_URL="git@gitlab.com:tu-usuario/tu-proyecto.git"

# Bitbucket
REPO_URL="git@bitbucket.org:tu-usuario/tu-proyecto.git"
```

**⚠️ IMPORTANTE:** 
- Si usas SSH, asegúrate de tener las claves SSH configuradas en el servidor
- Si usas HTTPS, necesitarás credenciales de acceso

---

### 2. `APP_DIR` (Línea 15)
**¿Qué es?** La carpeta donde se instalará/clonará tu proyecto Laravel en el servidor

**Valor actual:**
```bash
APP_DIR="$HOME/laravel_app"
```

**Ejemplos de cómo personalizarlo:**
```bash
# Opción 1: En el directorio home del usuario
APP_DIR="$HOME/laravel_app"

# Opción 2: Ruta absoluta específica
APP_DIR="/home/usuario/mi-proyecto"

# Opción 3: Si tienes un dominio específico
APP_DIR="$HOME/domains/tudominio.com/private"

# Opción 4: En Hostinger, podría ser algo como:
APP_DIR="$HOME/domains/tudominio.com/private/laravel"
```

**💡 TIP:** En Hostinger, normalmente:
- `$HOME` = `/home/usuario` o `/home/u12345678`
- Puedes verificar tu ruta con: `echo $HOME` en SSH

---

### 3. `PUBLIC_DIR` (Línea 16)
**¿Qué es?** La carpeta pública donde se servirán los archivos web (normalmente `public_html`)

**Valor actual:**
```bash
PUBLIC_DIR="$HOME/public_html"
```

**Ejemplos de cómo personalizarlo:**
```bash
# Hostinger compartido (típico)
PUBLIC_DIR="$HOME/public_html"

# Hostinger con dominio específico
PUBLIC_DIR="$HOME/domains/tudominio.com/public_html"

# VPS con configuración personalizada
PUBLIC_DIR="/var/www/html"

# Otra opción común
PUBLIC_DIR="$HOME/www"
```

**💡 TIP:** 
- En hosting compartido, normalmente es `public_html`
- En VPS, puede variar según tu configuración
- Verifica en tu panel de Hostinger la ruta exacta

---

### 4. `BRANCH` (Línea 17)
**¿Qué es?** La rama de Git que quieres desplegar (main, master, develop, production, etc.)

**Valor actual:**
```bash
BRANCH="main"
```

**Ejemplos de cómo personalizarlo:**
```bash
# Rama principal (GitHub moderno)
BRANCH="main"

# Rama principal (Git antiguo)
BRANCH="master"

# Rama de producción
BRANCH="production"

# Rama de desarrollo (para staging)
BRANCH="develop"
```

**⚠️ IMPORTANTE:** 
- Asegúrate de que la rama existe en tu repositorio
- Para producción, usa siempre una rama estable (main/master/production)

---

### 5. `BACKUP_DIR` (Línea 18) - OPCIONAL
**¿Qué es?** La carpeta donde se guardarán los backups del archivo `.env`

**Valor actual:**
```bash
BACKUP_DIR="$HOME/backups"
```

**Ejemplos de cómo personalizarlo:**
```bash
# Opción 1: En el home del usuario
BACKUP_DIR="$HOME/backups"

# Opción 2: Ruta absoluta
BACKUP_DIR="/home/usuario/backups_laravel"

# Opción 3: Si no quieres backups, puedes dejarlo así
BACKUP_DIR="$HOME/backups"
```

**💡 TIP:** Esta variable es opcional, pero recomendada para tener respaldos automáticos

---

## 📝 Ejemplo Completo Personalizado

Aquí tienes un ejemplo de cómo quedarían las variables personalizadas:

```bash
# Configurables - editar según tu entorno
REPO_URL="git@github.com:juanperez/mi-tienda-laravel.git"
APP_DIR="$HOME/domains/mitienda.com/private/laravel"
PUBLIC_DIR="$HOME/domains/mitienda.com/public_html"
BRANCH="main"
BACKUP_DIR="$HOME/backups"
```

---

## 🔍 Cómo Encontrar los Valores Correctos

### Para `REPO_URL`:
1. Ve a tu repositorio en GitHub/GitLab/Bitbucket
2. Haz clic en el botón verde "Code" o "Clone"
3. Copia la URL SSH o HTTPS

### Para `APP_DIR` y `PUBLIC_DIR`:
1. Conéctate por SSH a tu servidor Hostinger
2. Ejecuta: `pwd` para ver tu ubicación actual
3. Ejecuta: `ls -la` para ver las carpetas disponibles
4. En Hostinger, normalmente verás:
   - `public_html` → para archivos públicos
   - `domains/` → si tienes múltiples dominios
   - `private/` → para archivos privados

### Para `BRANCH`:
1. Ve a tu repositorio en GitHub/GitLab
2. Revisa qué ramas tienes disponibles
3. Normalmente `main` o `master` es la rama principal

---

## ✅ Checklist Antes de Ejecutar

Antes de ejecutar el script, verifica que:

- [ ] `REPO_URL` apunta a tu repositorio correcto
- [ ] `APP_DIR` es una ruta válida y tienes permisos de escritura
- [ ] `PUBLIC_DIR` es la carpeta pública correcta de tu hosting
- [ ] `BRANCH` existe en tu repositorio
- [ ] Tienes acceso SSH configurado (si usas SSH para Git)
- [ ] Tienes permisos para crear carpetas en las rutas especificadas

---

## 🚨 Errores Comunes

### Error: "Repository not found"
- **Causa:** `REPO_URL` incorrecto o no tienes acceso
- **Solución:** Verifica la URL y tus credenciales SSH/HTTPS

### Error: "Permission denied" en APP_DIR
- **Causa:** No tienes permisos en esa carpeta
- **Solución:** Cambia `APP_DIR` a una carpeta donde tengas permisos, o ajusta permisos

### Error: "Branch not found"
- **Causa:** La rama especificada en `BRANCH` no existe
- **Solución:** Verifica el nombre de la rama en tu repositorio

---

## 📞 ¿Necesitas Ayuda?

Si no estás seguro de los valores, puedes:
1. Contactar al soporte de Hostinger para confirmar rutas
2. Revisar la documentación de tu hosting
3. Probar primero en un entorno de desarrollo/staging

