# Reporte Automático de Módulos

**Fecha de generación:** 2025-11-03 04:33:28

---

## Resumen

Total de módulos: **3**

---

## Módulo: Core

### Información General

- **Nombre:** Core
- **Alias:** core
- **Descripción:** Módulo Core del sistema - Funcionalidades base y helpers
- **Prioridad:** 1
- **Versión:** N/A

### Service Providers

- ✅ `Modules\Core\Providers\CoreServiceProvider`

### Rutas Registradas (2)

| Método | URI | Nombre |
|--------|-----|--------|
| GET|HEAD | `core` | core.index |
| GET|HEAD | `core/users-count` | core.users-count |

### Tests (1)

- `Feature\CoreTest.php`

---

## Módulo: DevTools

### Información General

- **Nombre:** DevTools
- **Alias:** devtools
- **Descripción:** Herramientas de desarrollo y mantenimiento para módulos
- **Prioridad:** 99
- **Versión:** N/A

### Service Providers

- ✅ `Modules\DevTools\Providers\DevToolsServiceProvider`

---

## Módulo: Users

### Información General

- **Nombre:** Users
- **Alias:** users
- **Descripción:** Módulo de gestión de usuarios
- **Prioridad:** 2
- **Versión:** N/A

### Service Providers

- ✅ `Modules\Users\Providers\UsersServiceProvider`

### Rutas Registradas (3)

| Método | URI | Nombre |
|--------|-----|--------|
| GET|HEAD | `core/users-count` | core.users-count |
| GET|HEAD | `users` | users.index |
| GET|HEAD | `users/{id}` | users.show |

### Migraciones (1)

- `2025_11_03_000001_create_users_table.php`

### Seeders (1)

- `UsersTableSeeder.php`

### Tests (1)

- `Feature\UsersTest.php`

---

## Estado General

✅ Todos los módulos están activos y funcionando

**Generado automáticamente por:** `php artisan modules:report`
