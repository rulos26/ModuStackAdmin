# Registro de Vulnerabilidades de Seguridad Detectadas

## Fecha: 2025-11-03
## Tipo: Backend - Seguridad
## Severidad: ALTO
## Módulo: Sistema Base

---

## Vulnerabilidad 1: Laravel Framework - File Validation Bypass

### Información General
- **CVE:** CVE-2025-27515
- **Severidad:** MEDIUM
- **Paquete:** laravel/framework
- **Versión Actual:** v11.46.1 ✅
- **Versión Afectada:** >=11.0.0,<11.44.1
- **Versión Corregida:** v11.46.1 (>=11.44.1)
- **URL:** https://github.com/advisories/GHSA-78fx-h6xr-vch4
- **Reportado:** 2025-03-05T19:09:39+00:00

### Descripción
Laravel tiene una vulnerabilidad de bypass de validación de archivos que puede permitir que archivos no deseados pasen las validaciones de seguridad.

### Impacto
- Permite que archivos no validados sean procesados
- Potencial riesgo de seguridad en uploads de archivos
- Afecta sistemas con validación de archivos

### Acción Correctiva Aplicada
1. ✅ Actualizado Laravel Framework de v11.37.0 a v11.46.1
2. ✅ Ejecutado `composer update laravel/framework league/commonmark`
3. ✅ Verificado que no haya vulnerabilidades restantes con `composer audit`
4. ✅ Verificado: Laravel Framework funcionando correctamente (v11.46.1)

### Estado
- [x] Detectado
- [x] En proceso de corrección
- [x] Corregido - Actualizado a v11.46.1
- [x] Verificado ✅ (Laravel funcionando correctamente, composer audit sin vulnerabilidades)

---

## Vulnerabilidad 2: league/commonmark - XSS Vulnerability

### Información General
- **CVE:** CVE-2025-46734
- **Severidad:** MEDIUM
- **Paquete:** league/commonmark
- **Versión Actual:** 2.7.1 ✅
- **Versión Afectada:** <2.7.0
- **Versión Corregida:** 2.7.1 (>=2.7.0)
- **URL:** https://github.com/advisories/GHSA-3527-qv2q-pfvx
- **Reportado:** 2025-05-05T20:36:36+00:00

### Descripción
league/commonmark contiene una vulnerabilidad XSS (Cross-Site Scripting) en la extensión Attributes que puede permitir la ejecución de código malicioso.

### Impacto
- Permite inyección de código JavaScript
- Riesgo de robo de sesiones o datos
- Afecta cualquier funcionalidad que procese Markdown con atributos

### Acción Correctiva Aplicada
1. ✅ Actualizado league/commonmark de 2.6.1 a 2.7.1
2. ✅ Ejecutado `composer update league/commonmark` junto con Laravel
3. ✅ Verificado que no haya breaking changes (51 paquetes actualizados sin conflictos)
4. ✅ Verificado: league/commonmark actualizado correctamente (2.7.1). No se detectó uso activo de Markdown en el proyecto, pero la dependencia está actualizada y segura

### Estado
- [x] Detectado
- [x] En proceso de corrección
- [x] Corregido - Actualizado a 2.7.1
- [x] Verificado ✅ (No se usa Markdown activamente en el proyecto, pero actualizado correctamente)

---

## Archivos Afectados
- `composer.json`
- `composer.lock`
- Dependencias transitivas

## Acciones Realizadas
1. ✅ Ejecutado `composer audit` para detectar vulnerabilidades
2. ✅ Documentadas vulnerabilidades encontradas
3. ✅ Actualizadas dependencias vulnerables:
   - Laravel Framework: v11.37.0 → v11.46.1
   - league/commonmark: 2.6.1 → 2.7.1
4. ✅ Verificado que no haya vulnerabilidades restantes
5. ✅ 51 paquetes actualizados en total (incluyendo dependencias transitivas)

## Referencias
- Laravel Security Advisories: https://github.com/advisories/GHSA-78fx-h6xr-vch4
- CommonMark Security Advisories: https://github.com/advisories/GHSA-3527-qv2q-pfvx
- Composer Audit: https://getcomposer.org/doc/03-cli.md#audit

---

**Registrado por:** Auto (Cursor AI)  
**Siguiente revisión:** Después de actualización de dependencias

