# Sistema de Logging de Errores

Esta carpeta contiene el sistema de registro automático de errores del proyecto ModuStackUser.

## Estructura

```
log_errores/
├── backend/
│   ├── bajo/      # Errores menores o de interfaz
│   ├── medio/     # Errores funcionales o de lógica de negocio
│   └── alto/      # Errores críticos, de seguridad o pérdida de datos
└── frontend/
    ├── bajo/      # Errores menores o de interfaz
    ├── medio/     # Errores funcionales o de lógica de negocio
    └── alto/      # Errores críticos, de seguridad o pérdida de datos
```

## Clasificación de Errores

### Por Tipo

**Backend (`backend/`):**
- Controladores
- Servicios
- Modelos
- Comandos Artisan
- Migraciones
- Seeders

**Frontend (`frontend/`):**
- Vistas Blade
- Componentes JavaScript
- Assets públicos (CSS, imágenes, etc.)
- Validación de formularios en cliente

### Por Severidad

**Bajo (`bajo/`):**
- Errores menores de interfaz
- Advertencias no críticas
- Problemas de visualización
- Errores de validación menores

**Medio (`medio/`):**
- Errores funcionales
- Problemas de lógica de negocio
- Validaciones fallidas importantes
- Problemas de integración entre componentes

**Alto (`alto/`):**
- Errores críticos del sistema
- Problemas de seguridad
- Pérdida de datos
- Errores de autenticación/autorización
- Errores de base de datos críticos

## Formato de Archivos de Log

Cada archivo de log debe contener la siguiente información:

```json
{
  "fecha": "YYYY-MM-DD HH:MM:SS",
  "componente": "Nombre del componente/área afectada",
  "archivo": "ruta/del/archivo.php",
  "descripcion": "Descripción detallada del error",
  "accion_correctiva": "Acción tomada para resolver el error",
  "stack_trace": "Traza del error (opcional para errores altos)",
  "usuario_id": "ID del usuario afectado (si aplica)",
  "request_data": "Datos de la petición (si aplica)"
}
```

## Ejemplo de Implementación

El sistema de logging debe implementarse en:
- `app/Exceptions/Handler.php` - Para captura global de excepciones
- Middleware personalizado - Para captura de errores específicos
- Logs manuales - Para errores detectados manualmente

---

**Última actualización:** 2025-11-03

