#!/usr/bin/env bash
# deploy_template.sh
# Plantilla de despliegue para Laravel 12 en Hostinger (requiere SSH)
# REVISAR y ADAPTAR antes de ejecutar.
set -euo pipefail

# Modo debug (activar con DEBUG=1 ./deploy_template.sh)
DEBUG="${DEBUG:-0}"
if [ "$DEBUG" = "1" ]; then
    set -x  # Mostrar cada comando antes de ejecutarlo
fi

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configurables - editar según tu entorno
# IMPORTANTE: Personaliza estos valores antes de ejecutar el script

# URL del repositorio Git
REPO_URL="https://github.com/rulos26/ModuStackAdmin.git"

# Carpeta donde se clonará el repositorio completo
# En Hostinger con dominio: normalmente es $HOME/domains/tudominio.com/private
REPO_DIR="$HOME/domains/rulossoluciones.com/private/ModuStackAdmin"

# Subcarpeta Laravel dentro del repositorio (ModuStackBases, ModuStackbase, ModuStackUser, etc.)
# Cambia esto según la carpeta que contenga tu aplicación Laravel
LARAVEL_SUBDIR="ModuStackBases"

# Carpeta donde se trabajará con la aplicación Laravel (REPO_DIR + LARAVEL_SUBDIR)
APP_DIR="$REPO_DIR/$LARAVEL_SUBDIR"

# Carpeta pública donde se servirán los archivos web
# En Hostinger con dominio: normalmente es $HOME/domains/tudominio.com/public_html
PUBLIC_DIR="$HOME/domains/rulossoluciones.com/public_html"

# Rama de Git a desplegar (main, master, production, etc.)
BRANCH="main"

# Directorio para backups del .env (opcional)
BACKUP_DIR="$HOME/backups"

# Archivo de log para auditoría
LOG_FILE="$HOME/deploy_$(date +%Y%m%d_%H%M%S).log"

# Variable para rastrear si el despliegue fue exitoso
DEPLOY_SUCCESS=false

# Función de limpieza al salir (éxito o error)
cleanup() {
    local exit_code=$?
    
    if [ $exit_code -eq 0 ] && [ "$DEPLOY_SUCCESS" = true ]; then
        log_info "Despliegue completado exitosamente"
    elif [ $exit_code -ne 0 ]; then
        log_error "Despliegue falló con código de salida: $exit_code"
        log_error "Revisa el log en: $LOG_FILE"
    fi
    
    # Limpiar archivos temporales si existen
    if [ -n "${TEMP_FILES:-}" ]; then
        for temp_file in $TEMP_FILES; do
            [ -f "$temp_file" ] && rm -f "$temp_file" 2>/dev/null || true
        done
    fi
    
    return $exit_code
}

# Configurar trap para ejecutar cleanup al salir
trap cleanup EXIT INT TERM

# Función de logging (con archivo)
log_info() {
    local msg="[$(date '+%Y-%m-%d %H:%M:%S')] [INFO] $1"
    echo -e "${GREEN}[INFO]${NC} $1"
    echo "$msg" >> "$LOG_FILE" 2>/dev/null || true
}

log_warn() {
    local msg="[$(date '+%Y-%m-%d %H:%M:%S')] [WARN] $1"
    echo -e "${YELLOW}[WARN]${NC} $1"
    echo "$msg" >> "$LOG_FILE" 2>/dev/null || true
}

log_error() {
    local msg="[$(date '+%Y-%m-%d %H:%M:%S')] [ERROR] $1"
    echo -e "${RED}[ERROR]${NC} $1"
    echo "$msg" >> "$LOG_FILE" 2>/dev/null || true
}

# Función para detectar ruta de PHP (específico para Hostinger)
detect_php_path() {
    local php_paths=(
        "/usr/bin/php"
        "$(which php 2>/dev/null)"
        "php"
    )
    
    for path in "${php_paths[@]}"; do
        if [ -n "$path" ] && command -v "$path" >/dev/null 2>&1; then
            if "$path" -v >/dev/null 2>&1; then
                echo "$path"
                return 0
            fi
        fi
    done
    
    return 1
}

# Función de validación (específica para Hostinger)
check_requirements() {
    log_info "Verificando requisitos del sistema (Hostinger)..."
    
    # Detectar ruta de PHP (Hostinger puede tener rutas específicas)
    PHP_CMD=$(detect_php_path)
    if [ -z "$PHP_CMD" ]; then
        log_error "PHP no está instalado o no está en el PATH"
        log_error "En Hostinger, verifica que PHP esté habilitado en hPanel"
        exit 1
    fi
    log_info "PHP encontrado en: $PHP_CMD"
    
    # Verificar PHP >= 8.2
    PHP_VERSION=$($PHP_CMD -r "echo PHP_VERSION;" 2>/dev/null | cut -d. -f1,2)
    if [ -z "$PHP_VERSION" ]; then
        log_error "No se pudo obtener la versión de PHP"
        exit 1
    fi
    
    REQUIRED_VERSION="8.2"
    if [ "$(printf '%s\n' "$REQUIRED_VERSION" "$PHP_VERSION" | sort -V | head -n1)" != "$REQUIRED_VERSION" ]; then
        log_error "PHP $PHP_VERSION detectado. Se requiere PHP >= $REQUIRED_VERSION"
        log_error "En Hostinger: Ve a hPanel > Alojamiento > Versión de PHP y cambia a 8.2+"
        exit 1
    fi
    log_info "PHP $PHP_VERSION detectado ✓"
    
    # Verificar extensiones PHP críticas (usar PHP_CMD detectado)
    REQUIRED_EXTENSIONS=("pdo" "mbstring" "openssl" "json" "tokenizer" "xml" "ctype" "fileinfo" "bcmath")
    MISSING_EXTENSIONS=()
    for ext in "${REQUIRED_EXTENSIONS[@]}"; do
        if ! $PHP_CMD -m 2>/dev/null | grep -qi "^${ext}$"; then
            MISSING_EXTENSIONS+=("$ext")
        fi
    done
    
    if [ ${#MISSING_EXTENSIONS[@]} -gt 0 ]; then
        log_error "Extensiones PHP faltantes: ${MISSING_EXTENSIONS[*]}"
        log_error "En Hostinger: Ve a hPanel > Alojamiento > Extensiones PHP y actívalas"
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

# Función para verificar que la subcarpeta Laravel existe
check_laravel_subdir() {
    if [ ! -d "$APP_DIR" ]; then
        log_error "La subcarpeta Laravel '$LARAVEL_SUBDIR' no existe en $REPO_DIR"
        log_error "Carpetas disponibles en el repositorio:"
        # Optimización: usar find en lugar de múltiples pipes
        find "$REPO_DIR" -maxdepth 1 -type d ! -path "$REPO_DIR" -printf '%f\n' 2>/dev/null || \
        ls -1 "$REPO_DIR" 2>/dev/null | grep -v "^\.$" | grep -v "^\.\.$" || true
        log_error "Ajusta la variable LARAVEL_SUBDIR en el script (línea ~25)"
        exit 1
    fi
    
    if [ ! -f "$APP_DIR/composer.json" ]; then
        log_error "No se encontró composer.json en $APP_DIR"
        log_error "Verifica que $LARAVEL_SUBDIR sea una aplicación Laravel válida"
        exit 1
    fi
    
    if [ ! -f "$APP_DIR/artisan" ]; then
        log_error "No se encontró artisan en $APP_DIR"
        log_error "Esto no parece ser una aplicación Laravel válida"
        exit 1
    fi
    
    # Verificar integridad básica de composer.json
    if ! php -r "json_decode(file_get_contents('$APP_DIR/composer.json'));" 2>/dev/null; then
        log_warn "composer.json podría estar corrupto o tener formato inválido"
    fi
}

# Función para verificar integridad de archivos críticos después de copiar
verify_critical_files() {
    log_info "Verificando integridad de archivos críticos..."
    
    local critical_files=(
        "$PUBLIC_DIR/index.php"
        "$APP_DIR/vendor/autoload.php"
        "$APP_DIR/bootstrap/app.php"
    )
    
    local missing_files=0
    for file in "${critical_files[@]}"; do
        if [ ! -f "$file" ]; then
            log_error "Archivo crítico no encontrado: $file"
            missing_files=$((missing_files + 1))
        elif [ ! -s "$file" ]; then
            log_error "Archivo crítico está vacío: $file"
            missing_files=$((missing_files + 1))
        fi
    done
    
    if [ $missing_files -gt 0 ]; then
        log_error "Faltan $missing_files archivo(s) crítico(s). El despliegue puede fallar."
        return 1
    fi
    
    log_info "Todos los archivos críticos verificados ✓"
    return 0
}

# Función para verificar espacio en disco
check_disk_space() {
    log_info "Verificando espacio en disco..."
    local available_space
    local df_output
    
    # Optimización: usar una sola llamada a df
    df_output=$(df -BG "$(dirname "$APP_DIR")" 2>/dev/null | tail -1)
    available_space=$(echo "$df_output" | awk '{print $4}' | sed 's/G//')
    
    if [ -z "$available_space" ]; then
        log_warn "No se pudo verificar el espacio en disco"
        return 0
    fi
    
    if [ "$available_space" -lt 1 ]; then
        log_error "Espacio en disco insuficiente. Disponible: ${available_space}GB (mínimo recomendado: 1GB)"
        exit 1
    fi
    log_info "Espacio en disco: ${available_space}GB disponible ✓"
}

# Función para verificar conexión a base de datos con timeout (Hostinger)
check_database_connection() {
    log_info "Verificando conexión a base de datos..."
    
    # En Hostinger, DB_HOST suele ser localhost
    if [ -f .env ]; then
        DB_HOST=$(grep "^DB_HOST=" .env 2>/dev/null | cut -d'=' -f2 | tr -d ' "' || echo "localhost")
        if [ "$DB_HOST" != "localhost" ] && [ "$DB_HOST" != "127.0.0.1" ]; then
            log_warn "DB_HOST en .env es '$DB_HOST'. En Hostinger normalmente es 'localhost'"
        fi
    fi
    
    # Usar PHP_CMD y timeout para evitar que se quede colgado
    if command -v timeout >/dev/null 2>&1; then
        if timeout 10 $PHP_CMD artisan db:show >/dev/null 2>&1; then
            log_info "Conexión a base de datos verificada ✓"
            return 0
        else
            log_warn "No se pudo verificar la conexión a la base de datos (timeout o error)"
            log_warn "En Hostinger: Verifica que DB_HOST=localhost en .env"
            log_warn "Asegúrate de que las credenciales en .env sean correctas"
            return 1
        fi
    else
        # Fallback sin timeout
        if $PHP_CMD artisan db:show >/dev/null 2>&1; then
            log_info "Conexión a base de datos verificada ✓"
            return 0
        else
            log_warn "No se pudo verificar la conexión a la base de datos"
            log_warn "En Hostinger: Verifica que DB_HOST=localhost en .env"
            log_warn "Asegúrate de que las credenciales en .env sean correctas"
            return 1
        fi
    fi
}

# Función para verificar configuración de producción
check_production_config() {
    log_info "Verificando configuración de producción..."
    if [ -f .env ]; then
        if grep -q "APP_ENV=production" .env 2>/dev/null; then
            log_info "APP_ENV=production ✓"
        else
            log_warn "APP_ENV no está configurado como 'production' en .env"
        fi
        
        if grep -q "APP_DEBUG=false" .env 2>/dev/null; then
            log_info "APP_DEBUG=false ✓"
        else
            log_warn "APP_DEBUG no está configurado como 'false' en .env (recomendado para producción)"
        fi
    fi
}

# Función para ajustar rutas en index.php después de copiar a public_html (Hostinger)
adjust_index_php_paths() {
    local index_file="$PUBLIC_DIR/index.php"
    if [ ! -f "$index_file" ]; then
        log_warn "index.php no encontrado en $PUBLIC_DIR"
        return 1
    fi
    
    log_info "Ajustando rutas en index.php para apuntar a la aplicación Laravel..."
    
    # Calcular ruta relativa desde public_html a APP_DIR
    # En Hostinger: public_html está en domains/tudominio.com/public_html
    # y la app está en domains/tudominio.com/private/ModuStackAdmin/ModuStackBases
    local relative_path
    relative_path=$(realpath --relative-to="$PUBLIC_DIR" "$APP_DIR" 2>/dev/null || echo "../private/ModuStackAdmin/$LARAVEL_SUBDIR")
    
    # Backup del index.php original
    cp "$index_file" "${index_file}.backup.$(date +%Y%m%d_%H%M%S)" 2>/dev/null || true
    
    # Ajustar rutas en index.php (compatibilidad con Laravel 11+ y 12)
    # Buscar y reemplazar las rutas típicas de Laravel
    if grep -q "require __DIR__.'/../vendor/autoload.php'" "$index_file" 2>/dev/null; then
        sed -i "s|require __DIR__.'/../vendor/autoload.php'|require __DIR__.'/$relative_path/vendor/autoload.php'|g" "$index_file" 2>/dev/null || true
    fi
    
    if grep -q "\$app = require_once __DIR__.'/../bootstrap/app.php'" "$index_file" 2>/dev/null; then
        sed -i "s|\$app = require_once __DIR__.'/../bootstrap/app.php'|\$app = require_once __DIR__.'/$relative_path/bootstrap/app.php'|g" "$index_file" 2>/dev/null || true
    fi
    
    # Para Laravel 11+ y 12 con bootstrap/app.php
    if grep -q "require __DIR__.'/../bootstrap/app.php'" "$index_file" 2>/dev/null; then
        sed -i "s|require __DIR__.'/../bootstrap/app.php'|require __DIR__.'/$relative_path/bootstrap/app.php'|g" "$index_file" 2>/dev/null || true
    fi
    
    log_info "Rutas en index.php ajustadas ✓"
    log_info "Ruta relativa calculada: $relative_path"
}

# Función para crear .htaccess específico de Hostinger
create_hostinger_htaccess() {
    local htaccess_file="$PUBLIC_DIR/.htaccess"
    
    log_info "Creando/verificando .htaccess para Hostinger..."
    
    # Si ya existe, hacer backup
    if [ -f "$htaccess_file" ]; then
        cp "$htaccess_file" "${htaccess_file}.backup.$(date +%Y%m%d_%H%M%S)" 2>/dev/null || true
    fi
    
    # Crear .htaccess optimizado para Hostinger
    cat > "$htaccess_file" << 'HTACCESS_EOF'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Protección de archivos sensibles (Hostinger)
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>

# Compresión GZIP (Hostinger)
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
</IfModule>

# Cache de navegador (Hostinger)
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
HTACCESS_EOF

    log_info ".htaccess creado/actualizado para Hostinger ✓"
}

# Validar variables críticas antes de comenzar
validate_critical_variables() {
    local errors=0
    
    if [ -z "$REPO_URL" ] || [ "$REPO_URL" = "git@github.com:usuario/tu-proyecto.git" ]; then
        log_error "REPO_URL no está configurado correctamente"
        errors=$((errors + 1))
    fi
    
    if [ -z "$APP_DIR" ]; then
        log_error "APP_DIR no está configurado"
        errors=$((errors + 1))
    fi
    
    if [ -z "$PUBLIC_DIR" ]; then
        log_error "PUBLIC_DIR no está configurado"
        errors=$((errors + 1))
    fi
    
    if [ -z "$LARAVEL_SUBDIR" ]; then
        log_error "LARAVEL_SUBDIR no está configurado"
        errors=$((errors + 1))
    fi
    
    if [ $errors -gt 0 ]; then
        log_error "Corrige las variables de configuración antes de continuar"
        exit 1
    fi
}

log_info "=== Iniciando despliegue Laravel 12 (plantilla) ==="
log_info "Repositorio: $REPO_URL"
log_info "Rama: $BRANCH"
log_info "Subcarpeta Laravel: $LARAVEL_SUBDIR"
log_info "Directorio de trabajo: $APP_DIR"
log_info "Log de despliegue: $LOG_FILE"
[ "$DEBUG" = "1" ] && log_info "Modo DEBUG activado"

# Validar variables críticas
validate_critical_variables

# Guardar tiempo de inicio
SCRIPT_START_TIME=$(date +%s)

# Validar requisitos
check_requirements

# Verificar espacio en disco
check_disk_space

# Crear backup si existe instalación previa
create_backup

# 1. Clonar o actualizar repo completo
if [ -d "$REPO_DIR/.git" ]; then
  log_info "Actualizando repositorio completo en $REPO_DIR"
  cd "$REPO_DIR"
  
  # Verificar si hay cambios locales no commiteados
  if ! git diff-index --quiet HEAD -- 2>/dev/null; then
    log_warn "Hay cambios locales no commiteados. Se hará reset --hard"
    log_warn "Esto eliminará cualquier cambio local. Presiona Ctrl+C para cancelar (5 segundos)..."
    sleep 5 || true
  fi
  
  git fetch origin || { log_error "Error al hacer fetch del repositorio"; exit 1; }
  
  # Verificar que la rama existe
  if ! git ls-remote --heads origin "$BRANCH" >/dev/null 2>&1; then
    log_error "La rama '$BRANCH' no existe en el repositorio remoto"
    exit 1
  fi
  
  git reset --hard origin/$BRANCH || { log_error "Error al resetear a $BRANCH"; exit 1; }
  git clean -fd || true  # Limpiar archivos no rastreados
  log_info "Repositorio actualizado ✓"
else
  log_info "Clonando repositorio completo en $REPO_DIR"
  if [ ! -d "$(dirname "$REPO_DIR")" ]; then
    mkdir -p "$(dirname "$REPO_DIR")" || { log_error "No se pudo crear directorio padre"; exit 1; }
  fi
  
  # Verificar que la rama existe antes de clonar
  if ! git ls-remote --heads "$REPO_URL" "$BRANCH" >/dev/null 2>&1; then
    log_error "La rama '$BRANCH' no existe en el repositorio remoto"
    log_error "Ramas disponibles:"
    git ls-remote --heads "$REPO_URL" 2>/dev/null | sed 's|.*refs/heads/||' || true
    exit 1
  fi
  
  git clone -b $BRANCH "$REPO_URL" "$REPO_DIR" || { log_error "Error al clonar repositorio"; exit 1; }
  log_info "Repositorio clonado ✓"
fi

# Verificar que la subcarpeta Laravel existe
check_laravel_subdir

# Cambiar al directorio de la aplicación Laravel
log_info "Trabajando con la aplicación Laravel en: $APP_DIR"
cd "$APP_DIR" || { log_error "No se pudo acceder a $APP_DIR"; exit 1; }

# 2. Instalar dependencias via composer (específico para Hostinger)
log_info "Instalando dependencias con Composer..."
COMPOSER_CMD=""

# En Hostinger, composer puede estar en diferentes ubicaciones
if command -v composer >/dev/null 2>&1; then
  COMPOSER_CMD="composer"
  log_info "Composer encontrado globalmente"
elif [ -f "$HOME/composer.phar" ]; then
  COMPOSER_CMD="$PHP_CMD $HOME/composer.phar"
  log_info "Usando composer.phar en $HOME"
elif [ -f "./composer.phar" ]; then
  COMPOSER_CMD="$PHP_CMD ./composer.phar"
  log_info "Usando composer.phar en el proyecto"
elif [ -f "$REPO_DIR/composer.phar" ]; then
  COMPOSER_CMD="$PHP_CMD $REPO_DIR/composer.phar"
  log_info "Usando composer.phar en el repositorio"
else
  log_error "Composer no está disponible"
  log_error "En Hostinger: Descarga composer.phar y colócalo en $HOME o en el proyecto"
  log_error "Comando: curl -sS https://getcomposer.org/installer | $PHP_CMD"
  exit 1
fi

# Instalar dependencias con timeout si está disponible
# En Hostinger, composer puede tardar más debido a limitaciones de recursos
log_info "Instalando dependencias (esto puede tardar varios minutos en Hostinger)..."
if command -v timeout >/dev/null 2>&1; then
    timeout 900 $COMPOSER_CMD install --no-dev --optimize-autoloader --no-interaction --no-scripts || {
        log_warn "Composer install con --no-scripts falló, intentando sin optimización..."
        timeout 900 $COMPOSER_CMD install --no-dev --no-interaction || {
            log_error "Error al instalar dependencias de Composer (timeout o error)"
            log_error "En Hostinger: Verifica que tienes suficiente espacio y permisos"
            exit 1
        }
    }
else
    $COMPOSER_CMD install --no-dev --optimize-autoloader --no-interaction --no-scripts || {
        log_warn "Composer install con --no-scripts falló, intentando sin optimización..."
        $COMPOSER_CMD install --no-dev --no-interaction || {
            log_error "Error al instalar dependencias de Composer"
            exit 1
        }
    }
fi
log_info "Dependencias instaladas ✓"

# 3. Configurar .env
if [ ! -f .env ]; then
  if [ ! -f .env.example ]; then
    log_error ".env.example no encontrado. Asegúrate de tener este archivo en el repositorio"
    exit 1
  fi
  log_info "Creando archivo .env desde .env.example"
  cp .env.example .env
  
  # Configurar valores por defecto para Hostinger
  log_info "Configurando valores por defecto para Hostinger..."
  sed -i "s|APP_ENV=.*|APP_ENV=production|g" .env 2>/dev/null || true
  sed -i "s|APP_DEBUG=.*|APP_DEBUG=false|g" .env 2>/dev/null || true
  sed -i "s|DB_HOST=.*|DB_HOST=localhost|g" .env 2>/dev/null || true
  
  $PHP_CMD artisan key:generate --force || { log_error "Error al generar APP_KEY"; exit 1; }
  log_warn "IMPORTANTE: Edita el archivo .env en $APP_DIR con tus credenciales antes de continuar"
  log_warn "Ejecuta este script nuevamente después de configurar .env"
  exit 0
else
  log_info "Archivo .env encontrado ✓"
fi

# Verificar que .env tenga configuración básica
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
  log_warn "APP_KEY no encontrado en .env, generando..."
  $PHP_CMD artisan key:generate --force || { log_error "No se pudo generar APP_KEY"; exit 1; }
fi

# Asegurar que DB_HOST sea localhost para Hostinger
if grep -q "^DB_HOST=" .env 2>/dev/null; then
    DB_HOST_CURRENT=$(grep "^DB_HOST=" .env | cut -d'=' -f2 | tr -d ' "')
    if [ "$DB_HOST_CURRENT" != "localhost" ] && [ "$DB_HOST_CURRENT" != "127.0.0.1" ]; then
        log_warn "DB_HOST está configurado como '$DB_HOST_CURRENT'"
        log_warn "En Hostinger, normalmente debe ser 'localhost'. Verifica en hPanel si es diferente."
    fi
fi

# Verificar configuración de producción
check_production_config

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
$PHP_CMD artisan config:clear || true
$PHP_CMD artisan route:clear || true
$PHP_CMD artisan view:clear || true
$PHP_CMD artisan cache:clear || true

# 6. Verificar conexión a BD y ejecutar migraciones
if check_database_connection; then
  log_info "Ejecutando migraciones..."
  $PHP_CMD artisan migrate --force || {
    log_error "Las migraciones fallaron. Revisa los logs y la configuración de BD"
    log_error "En Hostinger: Verifica las credenciales de BD en hPanel > Bases de datos"
    log_error "No se continuará con el despliegue hasta resolver este problema"
    exit 1
  }
  log_info "Migraciones ejecutadas correctamente ✓"
else
  log_warn "No se pudo verificar la conexión a BD. Las migraciones se omitirán"
  log_warn "IMPORTANTE: Ejecuta las migraciones manualmente después de verificar la conexión"
  log_warn "En Hostinger: Verifica las credenciales en hPanel > Bases de datos"
fi

log_info "Optimizando aplicación..."
$PHP_CMD artisan config:cache || { log_warn "Error al cachear configuración"; }
$PHP_CMD artisan route:cache || { log_warn "Error al cachear rutas"; }
$PHP_CMD artisan view:cache || { log_warn "Error al cachear vistas"; }
$PHP_CMD artisan optimize || { log_warn "Error al optimizar"; }
log_info "Optimización completada ✓"

# 7. Mover/public link (solo si necesitas copiar public a public_html)
if [ -d "public" ] && [ -n "$PUBLIC_DIR" ]; then
  log_info "Sincronizando archivos públicos a $PUBLIC_DIR..."
  
  if [ ! -d "$PUBLIC_DIR" ]; then
    mkdir -p "$PUBLIC_DIR" || { log_error "No se pudo crear $PUBLIC_DIR"; exit 1; }
  fi
  
  # Verificar que index.php existe antes de copiar
  if [ ! -f "public/index.php" ]; then
    log_error "public/index.php no encontrado. Esto es crítico para Laravel"
    exit 1
  fi
  
  if command -v rsync >/dev/null 2>&1; then
    rsync -a --delete public/ "$PUBLIC_DIR/" || {
      log_warn "Error al sincronizar con rsync. Intentando con cp..."
      cp -r public/* "$PUBLIC_DIR/" 2>/dev/null || { log_error "Error al copiar archivos públicos"; exit 1; }
    }
    log_info "Archivos públicos sincronizados con rsync ✓"
  else
    log_warn "rsync no disponible. Copiando archivos con cp..."
    cp -r public/* "$PUBLIC_DIR/" 2>/dev/null || { log_error "Error al copiar archivos públicos"; exit 1; }
    log_info "Archivos públicos copiados ✓"
  fi
  
  # Ajustar rutas en index.php después de copiar
  adjust_index_php_paths
  
  # Crear/actualizar .htaccess en public_html (específico para Hostinger)
  create_hostinger_htaccess
  
  # Verificar integridad de archivos críticos
  verify_critical_files || log_warn "Algunos archivos críticos no pasaron la verificación"
  
else
  log_warn "Directorio public no encontrado o PUBLIC_DIR no configurado. Saltando sincronización."
fi

# 8. Crear enlace de storage (Hostinger)
log_info "Creando enlace simbólico de storage..."
if $PHP_CMD artisan storage:link 2>/dev/null; then
  log_info "Enlace de storage creado ✓"
  
  # Copiar el enlace a public_html si es necesario
  if [ -L "public/storage" ] && [ ! -L "$PUBLIC_DIR/storage" ]; then
    ln -sfn "$(readlink -f public/storage)" "$PUBLIC_DIR/storage" 2>/dev/null || true
  fi
else
  # Verificar si ya existe
  if [ -L "public/storage" ] || [ -L "$PUBLIC_DIR/storage" ]; then
    log_info "Enlace de storage ya existe ✓"
  else
    log_warn "No se pudo crear el enlace de storage. Verifica permisos"
    # Intentar crear manualmente
    if [ -d "storage/app/public" ]; then
      ln -sfn "$(pwd)/storage/app/public" "public/storage" 2>/dev/null && log_info "Enlace creado manualmente ✓" || log_warn "No se pudo crear enlace manualmente"
      # También crear en public_html
      if [ -d "$PUBLIC_DIR" ]; then
        ln -sfn "$APP_DIR/storage/app/public" "$PUBLIC_DIR/storage" 2>/dev/null || true
      fi
    fi
  fi
fi

# 9. Verificación final exhaustiva
log_info "=== Verificación final ==="

# Verificar Laravel
if $PHP_CMD artisan --version >/dev/null 2>&1; then
  LARAVEL_VERSION=$($PHP_CMD artisan --version)
  log_info "Laravel funcionando: $LARAVEL_VERSION ✓"
else
  log_error "Laravel no está funcionando correctamente"
fi

# Verificar que index.php existe y tiene contenido
if [ -f "$PUBLIC_DIR/index.php" ]; then
  if [ -s "$PUBLIC_DIR/index.php" ]; then
    log_info "index.php existe y tiene contenido ✓"
  else
    log_error "index.php está vacío"
  fi
else
  log_error "index.php no existe en $PUBLIC_DIR"
fi

# Verificar permisos críticos
if [ -d "storage" ] && [ -w "storage" ]; then
  log_info "Permisos de storage correctos ✓"
else
  log_warn "Problemas con permisos de storage"
fi

if [ -d "bootstrap/cache" ] && [ -w "bootstrap/cache" ]; then
  log_info "Permisos de bootstrap/cache correctos ✓"
else
  log_warn "Problemas con permisos de bootstrap/cache"
fi

# Resumen final
DEPLOY_SUCCESS=true
log_info "=== Despliegue completado ==="
log_info "Log guardado en: $LOG_FILE"
log_info "Tiempo total: $(($(date +%s) - ${SCRIPT_START_TIME:-$(date +%s)})) segundos"
log_warn "IMPORTANTE: Revisa los siguientes puntos:"
log_warn "1. Verifica que la aplicación carga en el navegador"
log_warn "2. Revisa los logs: storage/logs/laravel.log"
log_warn "3. Verifica permisos de storage y bootstrap/cache"
log_warn "4. Confirma que APP_DEBUG=false en producción"
log_warn "5. Verifica que las rutas en $PUBLIC_DIR/index.php apuntan correctamente a $APP_DIR"
log_warn ""
log_info "=== Configuración adicional para Hostinger ==="
log_info "Si usas tareas programadas (scheduled tasks), configura un cron job en hPanel:"
log_info "Ruta del cron: $PHP_CMD $APP_DIR/artisan schedule:run"
log_info "Frecuencia: * * * * * (cada minuto)"
log_info ""
log_info "Para configurar el cron en Hostinger:"
log_info "1. Ve a hPanel > Cron Jobs"
log_info "2. Agrega: * * * * * $PHP_CMD $APP_DIR/artisan schedule:run >> /dev/null 2>&1"
