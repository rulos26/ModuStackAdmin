# Logs de Pruebas Automatizadas

Esta carpeta contiene los reportes generados por las pruebas automatizadas del proyecto.

## Estructura

```
logs_de_pruebas/
├── README.md          # Este archivo
├── coverage/          # Reportes de cobertura de código (si se implementa)
└── history/           # Historial de ejecuciones de pruebas (futuro)
```

## Tipos de Reportes

### Reportes de PHPUnit
- Resultados de tests unitarios
- Resultados de tests de funcionalidad
- Reportes de cobertura de código

### Comandos para Generar Reportes

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests con cobertura
php artisan test --coverage

# Ejecutar tests específicos
php artisan test --filter NombreTest

# Generar reporte HTML de cobertura
php artisan test --coverage-html documentacion/logs_de_pruebas/coverage/
```

## Formato de Reportes

Los reportes deben incluir:
- Fecha y hora de ejecución
- Número total de tests ejecutados
- Número de tests exitosos
- Número de tests fallidos
- Tiempo de ejecución
- Cobertura de código (si está habilitada)
- Lista de tests ejecutados con su estado

## Integración con CI/CD (Futuro)

Cuando se implemente CI/CD, los reportes deben:
- Generarse automáticamente en cada commit
- Almacenarse con timestamp para auditoría
- Incluir comparación con ejecución anterior

---

**Última actualización:** 2025-11-03

