@echo off
REM Script de instalación para ModuStackUser (Windows)
REM Este script instalará todas las dependencias necesarias

echo ==========================================
echo Instalacion de ModuStackUser
echo ==========================================
echo.

cd /d "%~dp0"
echo Directorio actual: %CD%
echo.

REM Verificar si composer está instalado
where composer >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo Error: Composer no esta instalado
    echo Por favor instala Composer desde https://getcomposer.org/
    pause
    exit /b 1
)

echo Composer encontrado
echo.

REM Instalar dependencias de Composer
echo Instalando dependencias de Composer...
call composer install --no-dev --optimize-autoloader

if %ERRORLEVEL% NEQ 0 (
    echo Error al instalar dependencias de Composer
    pause
    exit /b 1
)

echo Dependencias de Composer instaladas correctamente
echo.

REM Verificar si .env existe
if not exist .env (
    echo Archivo .env no encontrado, copiando desde .env.example...
    if exist .env.example (
        copy .env.example .env
        echo Archivo .env creado
        echo IMPORTANTE: Debes configurar APP_KEY y otras variables
    ) else (
        echo No se encontro .env.example
    )
)
echo.

REM Generar APP_KEY si no existe
php artisan key:generate --force

REM Limpiar caché
echo Limpiando cache...
call php artisan config:clear
call php artisan cache:clear
call php artisan route:clear
call php artisan view:clear
echo Cache limpiado
echo.

REM Optimizar aplicación
echo Optimizando aplicacion...
call php artisan config:cache
call php artisan route:cache
call php artisan view:cache
echo Aplicacion optimizada
echo.

echo ==========================================
echo Instalacion completada exitosamente
echo ==========================================
echo.
echo Proximos pasos:
echo 1. Configura las variables en .env
echo 2. Ejecuta las migraciones: php artisan migrate
echo 3. Accede a tu aplicacion
echo.

pause

