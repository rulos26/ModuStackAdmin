#!/bin/bash

# Script de instalación para ModuStackUser
# Este script instalará todas las dependencias necesarias

echo "=========================================="
echo "Instalación de ModuStackUser"
echo "=========================================="
echo ""

# Verificar si composer está instalado
if ! command -v composer &> /dev/null
then
    echo "❌ Composer no está instalado"
    echo "Por favor instala Composer desde https://getcomposer.org/"
    exit 1
fi

echo "✅ Composer encontrado"
echo ""

# Navegar al directorio
cd "$(dirname "$0")"
echo "Directorio actual: $(pwd)"
echo ""

# Instalar dependencias de Composer
echo "📦 Instalando dependencias de Composer..."
composer install --no-dev --optimize-autoloader

if [ $? -eq 0 ]; then
    echo "✅ Dependencias de Composer instaladas correctamente"
else
    echo "❌ Error al instalar dependencias de Composer"
    exit 1
fi
echo ""

# Verificar si .env existe
if [ ! -f .env ]; then
    echo "⚠️  Archivo .env no encontrado, copiando desde .env.example..."
    if [ -f .env.example ]; then
        cp .env.example .env
        echo "✅ Archivo .env creado"
        echo "⚠️  IMPORTANTE: Debes configurar APP_KEY y otras variables"
    else
        echo "❌ No se encontró .env.example"
    fi
fi
echo ""

# Generar APP_KEY si no existe
php artisan key:generate --force

# Limpiar caché
echo "🧹 Limpiando caché..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo "✅ Caché limpiado"
echo ""

# Verificar permisos de storage
echo "🔐 Verificando permisos..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
echo "✅ Permisos configurados"
echo ""

# Optimizar aplicación
echo "⚡ Optimizando aplicación..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✅ Aplicación optimizada"
echo ""

echo "=========================================="
echo "✅ Instalación completada exitosamente"
echo "=========================================="
echo ""
echo "Próximos pasos:"
echo "1. Configura las variables en .env"
echo "2. Ejecuta las migraciones: php artisan migrate"
echo "3. Accede a tu aplicación"
echo ""

