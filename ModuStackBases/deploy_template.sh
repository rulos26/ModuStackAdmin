#!/usr/bin/env bash
# deploy_template.sh
# Plantilla de despliegue para Laravel 12 en Hostinger (requiere SSH)
# REVISAR y ADAPTAR antes de ejecutar.
set -euo pipefail

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configurables - editar según tu entorno
REPO_URL="git@github.com:usuario/tu-proyecto.git"
APP_DIR="$HOME/laravel_app"      # carpeta donde se ubicará el proyecto
PUBLIC_DIR="$HOME/public_html"   # ruta típica en Hostinger (ajusta si es distinto)
BRANCH="main"
BACKUP_DIR="$HOME/backups"       # directorio para backups (opcional)

# Función de logging
log_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

log_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Función de validación
check_requirements() {
    log_info "Verificando requisitos del sistema..."
    
    # Verificar PHP >= 8.2
    if ! command -v php >/dev/null 2>&1; then
        log_error "PHP no está instalado o no está en el PATH"
        exit 1
    fi
    
    PHP_VERSION=$(php -r "echo PHP_VERSION;" | cut -d. -f1,2)
    REQUIRED_VERSION="8.2"
    if [ "$(printf '%s\n' "$REQUIRED_VERSION" "$PHP_VERSION" | sort -V | head -n1)" != "$REQUIRED_VERSION" ]; then
        log_error "PHP $PHP_VERSION detectado. Se requiere PHP >= $REQUIRED_VERSION"
        exit 1
    fi
    log_info "PHP $PHP_VERSION detectado ✓"
    
    # Verificar extensiones PHP críticas
    REQUIRED_EXTENSIONS=("pdo" "mbstring" "openssl" "json" "tokenizer" "xml" "ctype" "fileinfo")
    MISSING_EXTENSIONS=()
    for ext in "${REQUIRED_EXTENSIONS[@]}"; do
        if ! php -m | grep -qi "^${ext}$"; then
            MISSING_EXTENSIONS+=("$ext")
        fi
    done
    
    if [ ${#MISSING_EXTENSIONS[@]} -gt 0 ]; then
        log_error "Extensiones PHP faltantes: ${MISSING_EXTENSIONS[*]}"
        exit 1
    fi
    log_info "Extensiones PHP requeridas verificadas ✓"
    
    # Verificar Git
    if ! command -v git >/dev/null 2>&1; then
        log_error "Git no está instalado o no está en el PATH"
        exit 1
    fi
    log_info "Git detectado ✓"
}

# Función para backup (opcional)
create_backup() {
    if [ -d "$APP_DIR" ] && [ -f "$APP_DIR/.env" ]; then
        log_info "Creando backup del .env..."
        mkdir -p "$BACKUP_DIR" 2>/dev/null || true
        BACKUP_FILE="$BACKUP_DIR/.env.backup.$(date +%Y%m%d_%H%M%S)"
        cp "$APP_DIR/.env" "$BACKUP_FILE" 2>/dev/null && log_info "Backup creado: $BACKUP_FILE" || log_warn "No se pudo crear backup"
    fi
}

log_info "=== Iniciando despliegue Laravel 12 (plantilla) ==="

# Validar requisitos
check_requirements

# Crear backup si existe instalación previa
create_backup

# 1. Clonar o actualizar repo
if [ -d "$APP_DIR/.git" ]; then
  log_info "Actualizando repo en $APP_DIR"
  cd "$APP_DIR"
  git fetch origin || { log_error "Error al hacer fetch del repositorio"; exit 1; }
  git reset --hard origin/$BRANCH || { log_error "Error al resetear a $BRANCH"; exit 1; }
  log_info "Repositorio actualizado ✓"
else
  log_info "Clonando repo en $APP_DIR"
  if [ ! -d "$(dirname "$APP_DIR")" ]; then
    mkdir -p "$(dirname "$APP_DIR")" || { log_error "No se pudo crear directorio padre"; exit 1; }
  fi
  git clone -b $BRANCH "$REPO_URL" "$APP_DIR" || { log_error "Error al clonar repositorio"; exit 1; }
  log_info "Repositorio clonado ✓"
fi

cd "$APP_DIR" || { log_error "No se pudo acceder a $APP_DIR"; exit 1; }

# 2. Instalar dependencias via composer
log_info "Instalando dependencias con Composer..."
COMPOSER_CMD=""
if command -v composer >/dev/null 2>&1; then
  COMPOSER_CMD="composer"
elif [ -f "$HOME/composer.phar" ]; then
  COMPOSER_CMD="php $HOME/composer.phar"
  log_info "Usando composer.phar local"
elif [ -f "./composer.phar" ]; then
  COMPOSER_CMD="php ./composer.phar"
  log_info "Usando composer.phar en el proyecto"
else
  log_error "Composer no está disponible. Instala composer o sube composer.phar"
  exit 1
fi

$COMPOSER_CMD install --no-dev --optimize-autoloader --no-interaction || {
  log_error "Error al instalar dependencias de Composer"
  exit 1
}
log_info "Dependencias instaladas ✓"

# 3. Configurar .env
if [ ! -f .env ]; then
  if [ ! -f .env.example ]; then
    log_error ".env.example no encontrado. Asegúrate de tener este archivo en el repositorio"
    exit 1
  fi
  log_info "Creando archivo .env desde .env.example"
  cp .env.example .env
  php artisan key:generate --force || { log_error "Error al generar APP_KEY"; exit 1; }
  log_warn "IMPORTANTE: Edita el archivo .env en $APP_DIR con tus credenciales antes de continuar"
  log_warn "Ejecuta este script nuevamente después de configurar .env"
  exit 0
else
  log_info "Archivo .env encontrado ✓"
fi

# Verificar que .env tenga configuración básica
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
  log_warn "APP_KEY no encontrado en .env, generando..."
  php artisan key:generate --force || log_warn "No se pudo generar APP_KEY"
fi

# 4. Ajustes de permisos
log_info "Ajustando permisos..."
if [ -d "storage" ]; then
  chmod -R 775 storage || log_warn "No se pudieron ajustar permisos de storage"
  log_info "Permisos de storage ajustados ✓"
fi
if [ -d "bootstrap/cache" ]; then
  chmod -R 775 bootstrap/cache || log_warn "No se pudieron ajustar permisos de bootstrap/cache"
  log_info "Permisos de bootstrap/cache ajustados ✓"
fi

# 5. Limpiar cache antiguo antes de crear nuevos
log_info "Limpiando cache antiguo..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# 6. Migrate & cache (FORCE para producción)
log_info "Ejecutando migraciones..."
php artisan migrate --force || {
  log_warn "Las migraciones fallaron o no son aplicables. Revisa manualmente."
}

log_info "Optimizando aplicación..."
php artisan config:cache || { log_warn "Error al cachear configuración"; }
php artisan route:cache || { log_warn "Error al cachear rutas"; }
php artisan view:cache || { log_warn "Error al cachear vistas"; }
php artisan optimize || { log_warn "Error al optimizar"; }
log_info "Optimización completada ✓"

# 7. Mover/public link (solo si necesitas copiar public a public_html)
if [ -d "public" ] && [ -n "$PUBLIC_DIR" ]; then
  log_info "Sincronizando archivos públicos a $PUBLIC_DIR..."
  if command -v rsync >/dev/null 2>&1; then
    if [ ! -d "$PUBLIC_DIR" ]; then
      mkdir -p "$PUBLIC_DIR" || { log_warn "No se pudo crear $PUBLIC_DIR"; }
    fi
    rsync -a --delete public/ "$PUBLIC_DIR/" || {
      log_warn "Error al sincronizar con rsync. Intentando con cp..."
      cp -r public/* "$PUBLIC_DIR/" 2>/dev/null || log_warn "Error al copiar archivos públicos"
    }
    log_info "Archivos públicos sincronizados ✓"
  else
    log_warn "rsync no disponible. Copiando archivos con cp..."
    if [ ! -d "$PUBLIC_DIR" ]; then
      mkdir -p "$PUBLIC_DIR" || { log_warn "No se pudo crear $PUBLIC_DIR"; }
    fi
    cp -r public/* "$PUBLIC_DIR/" 2>/dev/null || log_warn "Error al copiar archivos públicos"
  fi
else
  log_warn "Directorio public no encontrado o PUBLIC_DIR no configurado. Saltando sincronización."
fi

# 8. Crear enlace de storage
log_info "Creando enlace simbólico de storage..."
php artisan storage:link || log_warn "No se pudo crear el enlace de storage (puede que ya exista)"

# Verificación final
log_info "=== Verificación final ==="
if php artisan --version >/dev/null 2>&1; then
  LARAVEL_VERSION=$(php artisan --version)
  log_info "Laravel funcionando: $LARAVEL_VERSION ✓"
else
  log_warn "No se pudo verificar la versión de Laravel"
fi

log_info "=== Despliegue completado ==="
log_warn "Revisa los logs y permisos. Verifica que la aplicación funcione correctamente."
