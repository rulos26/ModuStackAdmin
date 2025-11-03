# Documentación del Componente User

**Componente:** Modelo User  
**Fecha de creación:** 2025-11-03  
**Versión Laravel:** 12.0

---

## Descripción General

El modelo `User` es el modelo principal de autenticación y usuarios del sistema ModuStackUser. Extiende de `Authenticatable` de Laravel y utiliza los traits `HasFactory` y `Notifiable` para proporcionar funcionalidades de factory y notificaciones.

---

## Archivos Modificados o Creados

### Archivos del Componente
- **Modelo:** `app/Models/User.php`
- **Factory:** `database/factories/UserFactory.php` (ya existente)
- **Migration:** `database/migrations/0001_01_01_000000_create_users_table.php` (ya existente)
- **Seeder:** `database/seeders/DatabaseSeeder.php` (ya existente, puede usar User)

### Tests Creados
- **Unit Test:** `tests/Unit/UserTest.php` ✅ NUEVO
- **Feature Test:** `tests/Feature/UserFeatureTest.php` ✅ NUEVO

### Documentación
- **Este archivo:** `documentacion/documentacion_user/documentacion_user.md` ✅ NUEVO

---

## Dependencias Involucradas

### Dependencias de Laravel
- `Illuminate\Foundation\Auth\User as Authenticatable` - Clase base para autenticación
- `Illuminate\Database\Eloquent\Factories\HasFactory` - Trait para factories
- `Illuminate\Notifications\Notifiable` - Trait para notificaciones

### Dependencias de Testing
- `Tests\TestCase` - Clase base para tests
- `Illuminate\Foundation\Testing\RefreshDatabase` - Trait para refrescar BD en tests
- `Illuminate\Support\Facades\Hash` - Para verificar hashing de passwords

---

## Estructura del Modelo

### Traits Utilizados
- `HasFactory` - Permite crear usuarios usando factories
- `Notifiable` - Permite enviar notificaciones al usuario

### Atributos Fillable
```php
protected $fillable = [
    'name',
    'email',
    'password',
];
```

### Atributos Hidden
```php
protected $hidden = [
    'password',
    'remember_token',
];
```

### Casts
```php
protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
```

---

## Funcionalidades Implementadas

### 1. Creación de Usuarios
- ✅ Creación mediante factory
- ✅ Creación mediante Eloquent
- ✅ Password automáticamente hasheado

### 2. Autenticación
- ✅ Integración con sistema de autenticación de Laravel
- ✅ Soporte para remember token
- ✅ Verificación de email (estructura preparada)

### 3. Seguridad
- ✅ Password hasheado automáticamente
- ✅ Password oculto en serialización
- ✅ Remember token oculto

---

## Tests Implementados

### Tests Unitarios (`tests/Unit/UserTest.php`)

1. **test_user_can_be_created()**
   - Verifica que se puede crear un usuario
   - Valida que se guarda en la base de datos

2. **test_user_password_is_hashed()**
   - Verifica que el password se hashea automáticamente
   - Valida que el hash es correcto

3. **test_user_has_fillable_attributes()**
   - Verifica que los atributos fillable están correctamente configurados

4. **test_user_has_hidden_attributes()**
   - Verifica que los atributos hidden están correctamente configurados

5. **test_user_has_correct_casts()**
   - Verifica que los casts funcionan correctamente

6. **test_user_email_must_be_unique()**
   - Verifica que el email debe ser único

7. **test_user_can_be_updated()**
   - Verifica que se puede actualizar un usuario

8. **test_user_can_be_deleted()**
   - Verifica que se puede eliminar un usuario

### Tests de Funcionalidad (`tests/Feature/UserFeatureTest.php`)

1. **test_home_route_returns_successful_response()**
   - Verifica que la ruta raíz responde correctamente

2. **test_home_route_is_accessible()**
   - Verifica que la ruta home es accesible y retorna la vista correcta

3. **test_user_factory_creates_valid_user()**
   - Verifica que la factory crea usuarios válidos

4. **test_can_create_multiple_users()**
   - Verifica que se pueden crear múltiples usuarios

---

## Pasos de Prueba o Despliegue

### Ejecutar Tests

```bash
# Ejecutar todos los tests del modelo User
php artisan test --filter UserTest

# Ejecutar tests unitarios del User
php artisan test tests/Unit/UserTest.php

# Ejecutar tests de funcionalidad del User
php artisan test tests/Feature/UserFeatureTest.php

# Ejecutar todos los tests con cobertura
php artisan test --coverage
```

### Crear Usuario Manualmente

```php
// Usando factory
$user = User::factory()->create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
]);

// Usando Eloquent directamente
$user = User::create([
    'name' => 'Jane Doe',
    'email' => 'jane@example.com',
    'password' => 'password123',
]);
```

### Verificar Funcionalidad

1. Ejecutar migraciones:
   ```bash
   php artisan migrate
   ```

2. Ejecutar tests:
   ```bash
   php artisan test
   ```

3. Verificar que todos los tests pasan:
   - ✅ 8 tests unitarios del User
   - ✅ 4 tests de funcionalidad del User
   - ✅ Tests de ejemplo existentes

---

## Enlaces o Referencias Externas Consultadas

1. **Laravel 12 Documentation - Eloquent Models:**
   - https://laravel.com/docs/12.x/eloquent
   - Consultado: 2025-11-03

2. **Laravel 12 Documentation - Authentication:**
   - https://laravel.com/docs/12.x/authentication
   - Consultado: 2025-11-03

3. **Laravel 12 Documentation - Testing:**
   - https://laravel.com/docs/12.x/testing
   - Consultado: 2025-11-03

4. **PHPUnit Documentation:**
   - https://phpunit.de/documentation.html
   - Consultado: 2025-11-03

---

## Notas Adicionales

- El modelo está preparado para verificación de email (atributo `email_verified_at` presente)
- El password se hashea automáticamente gracias al cast `hashed`
- El modelo puede extenderse fácilmente agregando relaciones cuando sea necesario
- Todos los tests están implementados y pasando según las reglas del proyecto

---

## Compatibilidad con Laravel 12

✅ **Verificado:** El modelo User es completamente compatible con Laravel 12:
- Usa la sintaxis actualizada de Laravel 12
- El método `casts()` usa el nuevo formato de array
- Los tipos de datos están correctamente definidos
- No se usan métodos deprecados

---

**Última actualización:** 2025-11-03  
**Estado:** ✅ Implementado y probado

