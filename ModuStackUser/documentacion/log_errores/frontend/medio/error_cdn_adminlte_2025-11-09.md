## Error: Estilos de login sin aplicar por fallo de CDN

- **Fecha:** 2025-11-09
- **Ambiente:** Producción (`https://rulossoluciones.com/ModuStackAdmin/ModuStackUser`)
- **Componente afectado:** Frontend - Login (`resources/views/layouts/guest.blade.php`)
- **Descripción:** La pantalla de inicio de sesión se renderizaba sin estilos. Las hojas de estilo/JS de AdminLTE servidas desde jsDelivr respondían `HTTP/2 404`, dejando el formulario sin apariencia y rompiendo la experiencia de usuario.
- **Causa raíz:** URL de CDN apuntaba a `admin-lte@4.2`, versión que ya no está disponible públicamente en jsDelivr.
- **Acción correctiva aplicada:** Actualizar los enlaces de CSS/JS para usar `https://cdn.jsdelivr.net/npm/admin-lte@latest/...`, asegurando que el recurso siempre exista. Se replicó el ajuste también en `layouts/admin.blade.php` y se validó con `curl -I`.
- **Resultado:** `curl -I https://cdn.jsdelivr.net/npm/admin-lte@latest/dist/css/adminlte.min.css` devuelve `HTTP/2 200`. Tras limpiar cachés y refrescar el navegador, el login carga con estilo AdminLTE.
- **Recomendaciones:** Como prioritario, fijar explícitamente una versión publicada (ej. `admin-lte@4.0.0-rc3`) y monitorear futuras rotaciones del CDN. Registrar pruebas visuales y automatizadas en `documentacion/logs_de_pruebas/` tras completar ajustes.

