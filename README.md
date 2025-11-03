# ModuStackAdmin

![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)
![License](https://img.shields.io/badge/License-MIT-green.svg)

Sistema modular de administración construido con Laravel 12, diseñado para ser extensible, seguro y fácil de mantener.

---

## 📋 Descripción

**ModuStackAdmin** es una plataforma de administración modular que proporciona una arquitectura base para desarrollar sistemas complejos de gestión. Caracterizado por su estructura modular, sistema de logs centralizado y documentación automatizada.

### Características Principales

- 🎯 **Arquitectura Modular**: Preparado para sistemas escalables
- 🔒 **Seguridad Integrada**: Headers HTTP, compresión y protecciones
- 📊 **Sistema de Logs**: Clasificación automática por tipo y severidad
- 📚 **Documentación Automática**: Generación automática de docs técnicas
- 🎨 **Frontend Moderno**: Bootstrap 5 con diseño glass morphism
- ⚡ **Alto Rendimiento**: Optimizaciones de caché y compresión

---

## 🚀 Requisitos

- **PHP:** >= 8.2
- **Composer:** Última versión
- **Node.js:** >= 18.x
- **Servidor Web:** Apache o Nginx
- **Base de Datos:** MySQL 8.0+ / MariaDB 10.3+ / PostgreSQL 12+

---

## 📦 Instalación

### Opción 1: Instalación en XAMPP (Local)

1. Clonar o copiar el proyecto en la carpeta `htdocs`:
```bash
cd C:\xampp\htdocs\
# Copiar el proyecto aquí
```

2. Configurar permisos (Linux/Mac):
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

3. Instalar dependencias de Laravel:
```bash
cd ModuStackUser
composer install
```

4. Configurar entorno:
```bash
copy .env.example .env
php artisan key:generate
```

5. Configurar base de datos en `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=modustack_db
DB_USERNAME=root
DB_PASSWORD=
```

6. Ejecutar migraciones:
```bash
php artisan migrate
```

7. Instalar dependencias frontend:
```bash
npm install
npm run build
```

8. Acceder al portal:
```
http://localhost/ModuStackAdmin/
```

### Opción 2: Instalación en Producción

1. Subir archivos al servidor via FTP/SFTP
2. Configurar `.env` con datos de producción
3. Ejecutar migraciones
4. Configurar permisos de almacenamiento
5. Configurar SSL/HTTPS
6. Configurar cron jobs (si aplica)

---

## 📁 Estructura del Proyecto

```
ModuStackAdmin/
├── .cursor/
│   └── rules.yml                    # Reglas de desarrollo
├── documentacion/                   # Documentación del proyecto
│   ├── archivo_tecnico_proyecto.md
│   ├── documentacion_portal_principal.md
│   └── mejoras_2025-11-03.md
├── index.html                       # Portal principal
├── .htaccess                        # Configuración Apache
├── log_errores/                     # Sistema de logs centralizado
│   ├── backend/
│   │   ├── bajo/
│   │   ├── medio/
│   │   └── alto/
│   └── frontend/
│       ├── bajo/
│       ├── medio/
│       └── alto/
├── ModuStackUser/                   # Módulo principal Laravel
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── public/
│   ├── resources/
│   ├── routes/
│   └── storage/
└── README.md                        # Este archivo
```

---

## 🔐 Seguridad

### Headers de Seguridad Implementados

- `X-Content-Type-Options: nosniff` - Previene MIME sniffing
- `X-Frame-Options: SAMEORIGIN` - Previene clickjacking
- `X-XSS-Protection: 1; mode=block` - Protección XSS
- `Referrer-Policy: strict-origin-when-cross-origin` - Control de referrer
- `Permissions-Policy` - Control de APIs del navegador

### Compresión y Optimización

- GZIP habilitado para assets estáticos
- Caché de navegador configurado
- Assets minificados en producción

---

## 📊 Sistema de Logs

El proyecto incluye un sistema centralizado de logs con clasificación automática:

### Tipos
- **Backend:** Errores de controladores, modelos, servicios
- **Frontend:** Errores de vistas, JavaScript, CSS

### Niveles de Severidad
- 🔵 **Bajo:** Problemas menores de interfaz
- 🟡 **Medio:** Errores funcionales o lógica de negocio
- 🔴 **Alto:** Errores críticos, seguridad o pérdida de datos

Ver más en: [`log_errores/README.md`](log_errores/README.md)

---

## 🧪 Testing

### Ejecutar Tests

```bash
# Todos los tests
php artisan test

# Tests unitarios
php artisan test --testsuite=Unit

# Tests de feature
php artisan test --testsuite=Feature

# Con cobertura
php artisan test --coverage
```

---

## 📚 Documentación

La documentación completa del proyecto se encuentra en la carpeta `documentacion/`:

- **[Archivo Técnico](documentacion/archivo_tecnico_proyecto.md)**: Documentación completa de la arquitectura
- **[Portal Principal](documentacion/documentacion_portal_principal.md)**: Documentación del portal
- **[Mejoras](documentacion/mejoras_2025-11-03.md)**: Recomendaciones y mejoras sugeridas

---

## 🔧 Configuración

### Variables de Entorno Principales

```env
APP_NAME=ModuStackAdmin
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=modustack_db
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
```

---

## 🛠️ Comandos Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Optimizar aplicación
php artisan optimize

# Crear nuevo módulo
php artisan module:make NombreModulo

# Generar controllers, models, etc
php artisan make:controller NombreController
php artisan make:model NombreModel -mcr
```

---

## 📝 Guía de Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/NuevaFuncionalidad`)
3. Commit tus cambios (`git commit -m 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/NuevaFuncionalidad`)
5. Abre un Pull Request

### Reglas de Desarrollo

Ver [`rules.yml`](.cursor/rules.yml) para las reglas completas de desarrollo.

Resumen:
- ✅ Documentar todos los cambios
- ✅ Probar funcionalidades
- ✅ Seguir estándares PSR
- ✅ Registrar errores en logs

---

## 🗺️ Roadmap

### Fase 1: Fundación (Actual)
- ✅ Portal principal con Bootstrap 5
- ✅ Sistema de logs centralizado
- ✅ Documentación base
- ✅ Configuración de seguridad

### Fase 2: Módulos (Próximo)
- [ ] Autenticación y autorización
- [ ] Dashboard de administración
- [ ] Sistema de roles y permisos
- [ ] API RESTful

### Fase 3: Optimización
- [ ] Caché Redis
- [ ] Colas con Horizon
- [ ] CDN para assets
- [ ] Optimización de queries

### Fase 4: Expansión
- [ ] Múltiples módulos
- [ ] Integraciones externas
- [ ] Reportes avanzados
- [ ] Multi-idioma

---

## 🐛 Reportar Bugs

Si encuentras un bug, por favor:

1. Verifica que no esté ya reportado en Issues
2. Crea un nuevo Issue con:
   - Descripción clara del problema
   - Pasos para reproducir
   - Versión de PHP/Laravel
   - Logs relevantes (si aplica)

---

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

---

## 👥 Equipo

Desarrollado con ❤️ por el equipo ModuStack

---

## 🙏 Agradecimientos

- [Laravel](https://laravel.com) - Framework PHP elegante
- [Bootstrap](https://getbootstrap.com) - Framework CSS
- [Composer](https://getcomposer.org) - Gestor de dependencias PHP
- [PHP](https://php.net) - Lenguaje de programación

---

## 📞 Soporte

Para soporte, email a: soporte@modustack.com  
o abre un Issue en GitHub.

---

**Versión:** 1.0.0  
**Última actualización:** 2025-11-03

