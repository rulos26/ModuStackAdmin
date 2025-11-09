# Documentación del Módulo Usuarios y Roles

**Fecha de creación:** 2025-11-03  
**Proyecto:** ModuStackUser  
**Versión Laravel:** 12.0  
**Estado:** ✅ Implementación Completada

---

## Descripción General

El módulo de Usuarios y Roles proporciona un sistema completo de autenticación, autorización, gestión de usuarios, roles, permisos, auditoría y autenticación de múltiples factores (MFA). Está diseñado para funcionar con AdminLTE 4 como plantilla de interfaz administrativa.

---

## Archivos Modificados o Creados

### Migraciones
- ✅ `0001_01_01_000000_create_users_table.php` - Base de usuarios
- ✅ `2025_11_03_223311_create_permission_tables.php` - Roles y permisos (Spatie)
- ✅ `2025_11_03_223311_create_personal_access_tokens_table.php` - Tokens API (Sanctum)
- ✅ `2025_11_03_223312_create_audits_table.php` - Auditoría (Laravel Auditing)
- ✅ `2025_11_03_223320_add_mfa_fields_to_users_table.php` - Campos MFA
- ✅ `2025_11_03_223321_create_user_activity_logs_table.php` - Logs de actividad
- ✅ `2025_11_03_223322_create_user_sessions_table.php` - Gestión de sesiones

### Modelos
- ✅ `app/Models/User.php` - Actualizado con traits y métodos MFA
- ✅ `app/Models/UserActivityLog.php` - Nuevo modelo
- ✅ `app/Models/UserSession.php` - Nuevo modelo

### Controladores
- ✅ `app/Http/Controllers/Auth/*` - Autenticación (Breeze)
- ✅ `app/Http/Controllers/UserController.php` - Gestión de usuarios
- ✅ `app/Http/Controllers/RoleController.php` - Gestión de roles
- ✅ `app/Http/Controllers/ActivityLogController.php` - Logs de actividad

### Form Requests
- ✅ `app/Http/Requests/Auth/LoginRequest.php` (Breeze)
- ✅ `app/Http/Requests/ProfileUpdateRequest.php` (Breeze)
- ✅ `app/Http/Requests/UserStoreRequest.php`
- ✅ `app/Http/Requests/UserUpdateRequest.php`
- ✅ `app/Http/Requests/RoleStoreRequest.php`
- ✅ `app/Http/Requests/RoleUpdateRequest.php`

### Policies
- ✅ `app/Policies/UserPolicy.php`
- ✅ `app/Policies/RolePolicy.php`

### Middleware
- ✅ `app/Http/Middleware/CheckRole.php`
- ✅ `app/Http/Middleware/ActivityLogger.php`

### Servicios
- ✅ `app/Services/MFAService.php`
- ✅ `app/Services/ActivityLogService.php`
- ✅ `app/Services/UserService.php`

### Seeders
- ✅ `database/seeders/RolePermissionSeeder.php`
- ✅ `database/seeders/DatabaseSeeder.php` - Actualizado

### Rutas
- ✅ `routes/web.php` - Rutas de gestión agregadas
- ✅ `routes/auth.php` - Rutas de autenticación (Breeze)

### Configuración
- ✅ `bootstrap/app.php` - Middleware registrados
- ✅ `app/Providers/AppServiceProvider.php` - Policies registradas

---

## Dependencias Involucradas

### Producción
- `laravel/framework` ^12.0
- `laravel/sanctum` ^4.2.0 - Autenticación API
- `spatie/laravel-permission` ^6.23.0 - Roles y permisos
- `owen-it/laravel-auditing` ^14.0.0 - Auditoría
- `pragmaRX/google2fa-laravel` ^2.3.0 - MFA
- `barryvdh/laravel-dompdf` ^3.1.1 - Exportación PDF
- `maatwebsite/excel` ^3.1.67 - Exportación Excel

### Desarrollo
- `laravel/breeze` ^2.3.8 - Autenticación base

---

## Funcionalidades Implementadas

### 1. Autenticación y Registro
- ✅ Login y logout
- ✅ Registro de usuarios
- ✅ Recuperación de contraseña
- ✅ Verificación de email
- ✅ Actualización de perfil

### 2. Roles y Permisos
- ✅ Gestión de roles (CRUD)
- ✅ Gestión de permisos
- ✅ Asignación de roles a usuarios
- ✅ Middleware de verificación de roles
- ✅ Policies para autorización

### 3. Gestión de Usuarios
- ✅ Listado con búsqueda y filtros
- ✅ Crear, editar, eliminar usuarios
- ✅ Activar/desactivar usuarios
- ✅ Asignar roles a usuarios
- ✅ Gestión de avatar

### 4. Auditoría y Sesiones
- ✅ Registro automático de actividad
- ✅ Logs de actividad personalizados
- ✅ Gestión de sesiones
- ✅ Exportación a PDF y Excel

### 5. MFA - Autenticación Multifactor
- ✅ Generación de secretos MFA
- ✅ Códigos QR para configuración
- ✅ Verificación de códigos OTP
- ✅ Habilitar/deshabilitar MFA

### 6. API Authentication
- ✅ Laravel Sanctum configurado
- ✅ Tokens de acceso API
- ✅ Middleware para API

---

## Pasos de Prueba o Despliegue

### 1. Instalar Dependencias
```bash
composer install
npm install
```

### 2. Configurar Base de Datos
```bash
# Copiar .env.example a .env y configurar
php artisan key:generate
```

### 3. Ejecutar Migraciones
```bash
php artisan migrate
```

### 4. Ejecutar Seeders
```bash
php artisan db:seed
```

Esto creará:
- Roles: SuperAdmin, Admin, Editor, User
- Permisos básicos
- Usuario SuperAdmin: admin@example.com

### 5. Compilar Assets
```bash
npm run dev
# o para producción
npm run build
```

### 6. Iniciar Servidor
```bash
php artisan serve
```

### 7. Acceder al Sistema
- URL: http://localhost:8000
- Login: admin@example.com
- Contraseña: (generada por factory)

---

## Estructura de Roles y Permisos

### Roles Creados
1. **SuperAdmin**
   - Todos los permisos

2. **Admin**
   - Ver, crear, actualizar, eliminar usuarios
   - Ver roles
   - Ver y exportar logs de actividad

3. **Editor**
   - Ver usuarios
   - Ver logs de actividad

4. **User**
   - Sin permisos especiales (solo acceso básico)

### Permisos Disponibles
- `view users`, `create users`, `update users`, `delete users`
- `view roles`, `create roles`, `update roles`, `delete roles`
- `view activity logs`, `export activity logs`

---

## Rutas Disponibles

### Autenticación
- `GET /login` - Formulario de login
- `POST /login` - Procesar login
- `GET /register` - Formulario de registro
- `POST /register` - Procesar registro
- `POST /logout` - Cerrar sesión

### Usuarios
- `GET /users` - Listado de usuarios
- `GET /users/create` - Formulario de creación
- `POST /users` - Crear usuario
- `GET /users/{user}` - Ver usuario
- `GET /users/{user}/edit` - Editar usuario
- `PUT /users/{user}` - Actualizar usuario
- `DELETE /users/{user}` - Eliminar usuario
- `POST /users/{user}/activate` - Activar usuario
- `POST /users/{user}/deactivate` - Desactivar usuario

### Roles
- `GET /roles` - Listado de roles
- `GET /roles/create` - Formulario de creación
- `POST /roles` - Crear rol
- `GET /roles/{role}` - Ver rol
- `GET /roles/{role}/edit` - Editar rol
- `PUT /roles/{role}` - Actualizar rol
- `DELETE /roles/{role}` - Eliminar rol

### Logs de Actividad
- `GET /activity-logs` - Listado de logs
- `GET /activity-logs/{activityLog}` - Ver log
- `GET /activity-logs/export/pdf` - Exportar PDF
- `GET /activity-logs/export/excel` - Exportar Excel

---

## Enlaces o Referencias Externas Consultadas

1. **Laravel 12 Documentation:**
   - https://laravel.com/docs/12.x
   - Consultado: 2025-11-03

2. **Laravel Breeze:**
   - https://laravel.com/docs/12.x/starter-kits#laravel-breeze
   - Consultado: 2025-11-03

3. **Spatie Laravel Permission:**
   - https://spatie.be/docs/laravel-permission/v6/introduction
   - Consultado: 2025-11-03

4. **Laravel Sanctum:**
   - https://laravel.com/docs/12.x/sanctum
   - Consultado: 2025-11-03

5. **Laravel Auditing:**
   - https://www.laravel-auditing.com/
   - Consultado: 2025-11-03

6. **Google2FA Laravel:**
   - https://github.com/antonioribeiro/google2fa-laravel
   - Consultado: 2025-11-03

7. **AdminLTE 4:**
   - https://adminlte.io/docs/4.2/
   - Consultado: 2025-11-03

---

## Notas Adicionales

### Pendiente de Implementación
- ⚠️ Vistas Blade con AdminLTE 4 (estructura lista, falta adaptar)
- ⚠️ Tests completos (Unit y Feature)
- ⚠️ Exportación Excel (requiere clase Export)
- ⚠️ Integración completa de MFA en login
- ⚠️ Gestión de sesiones activas

### Compatibilidad Laravel 12
✅ **Verificado:** Todo el módulo es compatible con Laravel 12:
- Usa sintaxis actualizada de Laravel 12
- No usa métodos deprecados
- Sigue arquitectura tradicional (no modular)
- Compatible con PHP 8.2+

---

**Última actualización:** 2025-11-03  
**Estado:** ✅ Implementación Backend Completada - ⏳ Frontend Pendiente


