# Documentación del Pipeline CI/CD

## Fecha de Configuración
**Fecha:** 2025-11-03  
**Laravel:** 11.46.1  
**PHP:** 8.2+  
**nwidart/laravel-modules:** v11.1.10

---

## Resumen Ejecutivo

Se ha configurado un pipeline completo de CI/CD (Integración Continua / Despliegue Continuo) que cumple con las reglas establecidas en `rules.yml`. El pipeline incluye:

- ✅ Ejecución de pruebas automatizadas con PHPUnit
- ✅ Generación de reportes de cobertura de código
- ✅ Análisis de calidad de código con Laravel Pint
- ✅ Verificación de seguridad de dependencias
- ✅ Pruebas específicas para módulos con nwidart/laravel-modules
- ✅ Compatibilidad verificada con Laravel 11

---

## Plataformas Configuradas

### 1. GitHub Actions

**Archivo:** `.github/workflows/ci.yml`

**Jobs configurados:**

#### a) Tests PHPUnit (`tests`)
- **Propósito:** Ejecutar todas las pruebas unitarias y de feature
- **Versiones PHP:** 8.2, 8.3
- **Base de datos:** MySQL 8.0
- **Cobertura:** Xdebug habilitado
- **Reportes generados:**
  - `documentacion/logs_de_pruebas/coverage.xml` (Clover format)
  - `documentacion/logs_de_pruebas/junit.xml` (JUnit format)
  - Upload automático a Codecov
- **Acciones:**
  1. Instalación de dependencias
  2. Verificación de compatibilidad Laravel 11
  3. Preparación del entorno de pruebas
  4. Ejecución de migraciones
  5. Ejecución de pruebas con cobertura

#### b) Análisis de Calidad de Código (`code-quality`)
- **Propósito:** Verificar estándares de código PSR-12
- **Herramientas:**
  - Laravel Pint
  - Verificación de sintaxis PHP
- **Continúa en caso de error:** Sí (no bloquea el pipeline)

#### c) Análisis de Seguridad (`security`)
- **Propósito:** Detectar vulnerabilidades en dependencias
- **Herramienta:** `composer audit`
- **Reporte:** `documentacion/logs_de_pruebas/security-audit.json`
- **Retención:** 90 días

#### d) Pruebas de Módulos (`modules-test`)
- **Propósito:** Verificar funcionamiento de módulos
- **Acciones:**
  - Listado de módulos con `php artisan module:list`
  - Verificación de rutas de módulos
  - Caché de configuración y rutas
- **Depende de:** `tests`

#### e) Build y Verificación Final (`build`)
- **Propósito:** Construcción para producción
- **Trigger:** Solo en push a `main` o `master`
- **Acciones:**
  - Instalación de dependencias sin dev
  - Build de assets con npm
  - Optimización de aplicación (config, route, view cache)
  - Generación de archivo técnico del proyecto

**Triggers del Pipeline:**
- Push a ramas: `main`, `develop`, `master`
- Pull requests a: `main`, `develop`, `master`
- Manual (workflow_dispatch)

---

### 2. GitLab CI

**Archivo:** `.gitlab-ci.yml`

**Stages configurados:**

1. **tests:** Ejecuta pruebas en PHP 8.2 y 8.3
2. **code-quality:** Análisis de calidad de código
3. **security:** Auditoría de seguridad
4. **build:** Construcción para producción

**Características:**
- Uso de servicios Docker (MySQL)
- Cache de `vendor/` y `node_modules/`
- Artifacts con reportes de pruebas
- Coverage tracking

---

## Configuración de PHPUnit

**Archivo:** `phpunit.xml`

### Cambios Realizados:

1. **Cobertura de código extendida:**
   ```xml
   <source>
       <include>
           <directory>app</directory>
           <directory>Modules</directory>
       </include>
   </source>
   ```

2. **Reportes de cobertura:**
   - Clover XML: `documentacion/logs_de_pruebas/coverage.xml`
   - HTML: `documentacion/logs_de_pruebas/coverage-html/`
   - Texto: `documentacion/logs_de_pruebas/coverage.txt`

---

## Estructura de Carpetas Creadas

```
documentacion/
└── logs_de_pruebas/
    ├── coverage.xml
    ├── coverage.txt
    ├── coverage-html/
    ├── junit.xml
    ├── security-audit.json
    └── history/
```

**Propósito según `rules.yml`:**
- Generar reportes con resultados y cobertura
- Almacenar historial para auditoría
- Clasificar errores automáticamente

---

## Comandos de Prueba Local

Para ejecutar las mismas pruebas localmente que en el pipeline:

```bash
# Instalar dependencias
composer install

# Ejecutar pruebas con cobertura
php artisan test --coverage

# Ejecutar análisis de calidad
vendor/bin/pint --test

# Verificar seguridad
composer audit

# Verificar módulos
php artisan module:list
php artisan route:list
```

---

## Integración con Codecov

El pipeline está configurado para subir reportes de cobertura automáticamente a Codecov.

**Para habilitar:**
1. Crear cuenta en https://codecov.io
2. Conectar el repositorio
3. Agregar token como secret en GitHub (si es necesario)

---

## Cumplimiento con `rules.yml`

### ✅ Requisitos Cumplidos:

1. **Pruebas Automatizadas:**
   - ✅ PHPUnit configurado
   - ✅ Validación de rutas y comportamiento
   - ✅ Reportes en `documentacion/logs_de_pruebas/`

2. **Compatibilidad Laravel 11:**
   - ✅ Verificación de versión en cada ejecución
   - ✅ Compatibilidad con nwidart/laravel-modules verificada

3. **Documentación:**
   - ✅ Este documento cumple con las reglas
   - ✅ Archivo técnico generado en build
   - ✅ Reportes almacenados según especificación

4. **Automatización:**
   - ✅ Ejecución automática en push/PR
   - ✅ Ejecución manual disponible
   - ✅ Historial de reportes guardado

---

## Variables de Entorno Requeridas

### Para GitHub Actions:
Las variables se configuran automáticamente en el workflow. No se requieren secrets adicionales para las pruebas básicas.

### Para GitLab CI:
Configurar en Settings > CI/CD > Variables si es necesario:
- `DB_HOST`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

---

## Troubleshooting

### Error: "Database connection failed"
**Solución:** Verificar que el servicio MySQL esté corriendo y las credenciales sean correctas.

### Error: "Coverage not generated"
**Solución:** Asegurarse de que Xdebug esté instalado en el entorno de CI.

### Error: "Module not found"
**Solución:** Verificar que los módulos estén correctamente registrados en `bootstrap/providers.php`.

---

## Próximos Pasos Recomendados

1. **Configurar Codecov** para tracking de cobertura histórica
2. **Agregar tests E2E** con Laravel Dusk (opcional)
3. **Configurar notificaciones** (Slack, Email, etc.)
4. **Agregar deployment automático** después de tests exitosos
5. **Configurar linters adicionales** (PHPStan, Psalm) si se requiere

---

## Referencias

- **Laravel 11 Testing:** https://laravel.com/docs/11.x/testing
- **PHPUnit Documentation:** https://phpunit.de/documentation.html
- **GitHub Actions:** https://docs.github.com/en/actions
- **GitLab CI:** https://docs.gitlab.com/ee/ci/
- **Laravel Pint:** https://laravel.com/docs/11.x/pint
- **nwidart/laravel-modules:** https://github.com/nWidart/laravel-modules

---

## Estado Actual

✅ **Pipeline Completamente Configurado**

- ✅ GitHub Actions workflow creado
- ✅ GitLab CI configurado
- ✅ PHPUnit configurado con cobertura
- ✅ Estructura de carpetas creada
- ✅ Documentación completa

**El pipeline está listo para ser utilizado en el repositorio.**

---

**Documentado por:** Auto (Cursor AI)  
**Estado:** ✅ Pipeline Configurado y Listo

