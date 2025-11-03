# Configuración para Subdirectorio ModuStackAdmin

## Fecha: 2025-11-03
## URL Base: `https://rulossoluciones.com/ModuStackAdmin/`

---

## Cambios Aplicados

### 1. Router de Vue con Base Path ✅

El router de Vue ahora detecta automáticamente el subdirectorio y configura el base path:

```javascript
const BASE_PATH = getBasePath(); // Detecta /ModuStackAdmin
const router = createRouter({
    history: createWebHistory(BASE_PATH),
    routes,
});
```

**Rutas resultantes:**
- `/ModuStackAdmin/` → Redirige a `/ModuStackAdmin/login`
- `/ModuStackAdmin/login` → Página de login
- `/ModuStackAdmin/register` → Página de registro
- `/ModuStackAdmin/dashboard` → Dashboard protegido

---

### 2. Axios BaseURL Configurado ✅

Axios ahora usa el subdirectorio en todas las peticiones API:

```javascript
axios.defaults.baseURL = BASE_PATH + '/api';
// Resultado: /ModuStackAdmin/api
```

**Endpoints API resultantes:**
- `/ModuStackAdmin/api/auth/login`
- `/ModuStackAdmin/api/auth/register`
- `/ModuStackAdmin/api/auth/profile`
- `/ModuStackAdmin/api/auth/logout`

---

### 3. APP_URL en .env ✅

Actualizado en `.env`:
```env
APP_URL=https://rulossoluciones.com/ModuStackAdmin
```

---

### 4. .htaccess Configurado ✅

El `.htaccess` en la raíz ahora maneja correctamente el subdirectorio:

```apache
# Redirigir requests en /ModuStackAdmin/ a public/
RewriteCond %{REQUEST_URI} ^/ModuStackAdmin/
RewriteRule ^ModuStackAdmin/(.*)$ public/$1 [L]
```

---

## Detección Automática del Subdirectorio

La función `getBasePath()` detecta automáticamente el subdirectorio desde:

1. **Pathname de la URL actual:**
   - `/ModuStackAdmin/` → Detecta `/ModuStackAdmin`
   - `/ModuStackAdmin/public/index.php` → Detecta `/ModuStackAdmin`

2. **Estructura de URL completa:**
   - `https://rulossoluciones.com/ModuStackAdmin/` → Detecta `/ModuStackAdmin`

3. **Fallback:**
   - Si no se detecta subdirectorio, usa raíz `/`

---

## URLs de Acceso

### URLs Principales

| URL | Descripción |
|-----|-------------|
| `https://rulossoluciones.com/ModuStackAdmin/` | Redirige a login |
| `https://rulossoluciones.com/ModuStackAdmin/login` | Página de login |
| `https://rulossoluciones.com/ModuStackAdmin/register` | Página de registro |
| `https://rulossoluciones.com/ModuStackAdmin/dashboard` | Dashboard (protegido) |

### URLs de Recursos Estáticos

| URL | Archivo |
|-----|---------|
| `https://rulossoluciones.com/ModuStackAdmin/css/auth-styles.css` | Estilos CSS |
| `https://rulossoluciones.com/ModuStackAdmin/js/auth-app.js` | JavaScript principal |

### URLs de API

| URL | Método | Descripción |
|-----|--------|-------------|
| `https://rulossoluciones.com/ModuStackAdmin/api/auth/login` | POST | Login |
| `https://rulossoluciones.com/ModuStackAdmin/api/auth/register` | POST | Registro |
| `https://rulossoluciones.com/ModuStackAdmin/api/auth/profile` | GET | Perfil (protegido) |
| `https://rulossoluciones.com/ModuStackAdmin/api/auth/logout` | POST | Logout (protegido) |

---

## Verificación

### 1. Verificar que APP_URL está correcto:

```bash
php artisan tinker
>>> config('app.url')
=> "https://rulossoluciones.com/ModuStackAdmin"
```

### 2. Verificar rutas de Laravel:

```bash
php artisan route:list | grep -E "GET|POST"
```

### 3. Verificar en navegador:

1. Abrir consola del navegador (F12)
2. Buscar logs que empiecen con:
   - `📁 Subdirectorio detectado: /ModuStackAdmin`
   - `📍 Base path configurado: /ModuStackAdmin`
   - `✅ Axios configurado con baseURL: /ModuStackAdmin/api`

---

## Debug en Consola

El código JavaScript incluye logs detallados para verificar la configuración:

```
🚀 Inicializando aplicación de autenticación...
✅ Vue y Vue Router cargados
🔍 Detectando base path...
   Pathname: /ModuStackAdmin/
   Href: https://rulossoluciones.com/ModuStackAdmin/
📁 Subdirectorio detectado: /ModuStackAdmin
📍 Base path configurado: /ModuStackAdmin
✅ Axios configurado con baseURL: /ModuStackAdmin/api
```

---

## Troubleshooting

### Problema: Rutas no funcionan correctamente

**Solución:** Verificar en consola del navegador:
- ¿Se detecta correctamente el subdirectorio?
- ¿El baseURL de Axios incluye `/ModuStackAdmin/api`?

### Problema: Recursos estáticos (CSS/JS) no cargan

**Solución:** Verificar que `.htaccess` permite acceso a:
- `/ModuStackAdmin/public/css/`
- `/ModuStackAdmin/public/js/`

### Problema: API devuelve 404

**Solución:** Verificar:
1. APP_URL en `.env` incluye `/ModuStackAdmin`
2. Rutas de API están registradas correctamente
3. El baseURL de Axios incluye el subdirectorio

---

**Documentado por:** Auto (Cursor AI)  
**Fecha:** 2025-11-03  
**Estado:** ✅ Configuración completa para subdirectorio

