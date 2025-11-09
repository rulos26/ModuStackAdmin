#!/usr/bin/env bash

set -euo pipefail

echo "======================================="
echo " Inicializando entorno de ModuStackUser"
echo "======================================="

if [[ ! -f ".env" ]]; then
    echo "Archivo .env no encontrado. Copiando .env.example..."
    cp .env.example .env
    php artisan key:generate --ansi
fi

echo "Limpiando y regenerando cachés..."
php artisan optimize:clear

echo "Ejecutando migraciones..."
php artisan migrate --force

echo "Ejecutando seeders (incluye usuario root y roles)..."
php artisan db:seed --force

echo "Inicialización completada."

