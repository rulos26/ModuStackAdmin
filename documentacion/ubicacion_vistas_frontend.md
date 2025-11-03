# Ubicación de las Vistas del Frontend

## Fecha: 2025-11-03

---

## Estado Actual

**Versión ACTIVA:** CDN (JavaScript inline)  
**Archivo:** `public/js/auth-app.js`

---

## Vistas del Frontend

### ✅ Versión CDN (ACTIVA - En uso)

**Ubicación:** `public/js/auth-app.js`

Todas las vistas están definidas como componentes Vue dentro de este archivo JavaScript:

#### 1. **LoginView** 
- **Línea:** 273
- **Ruta:** `/login` o `/ModuStackAdmin/login`
- **Template:** Formulario de login con email y password
- **Funcionalidad:** Autenticación, validación, manejo de errores

#### 2. **RegisterView**
- **Línea:** 388
- **Ruta:** `/register` o `/ModuStackAdmin/register`
- **Template:** Formulario de registro completo
- **Funcionalidad:** Registro de usuarios, validación de contraseñas

#### 3. **DashboardView**
- **Línea:** 543
- **Ruta:** `/dashboard` o `/ModuStackAdmin/dashboard` (protegida)
- **Template:** Vista del dashboard del usuario autenticado
- **Funcionalidad:** Muestra perfil, información del usuario, logout

#### 4. **HomeView**
- **Línea:** 251
- **Ruta:** `/` o `/ModuStackAdmin/`
- **Template:** Redirige automáticamente a login
- **Funcionalidad:** Página inicial que redirige

#### 5. **Navbar**
- **Línea:** 189
- **Template:** Barra de navegación
- **Funcionalidad:** Navegación, logout, estado de autenticación

#### 6. **AuthLayout**
- **Línea:** 662
- **Template:** Layout principal que incluye Navbar y router-view
- **Funcionalidad:** Contenedor principal de la aplicación

---

## Versión Vite/Vue (NO ACTIVA)

**Ubicación:** `resources/js/views/`

Existen archivos `.vue` separados pero **NO se están usando** actualmente:

- ✅ `LoginView.vue`
- ✅ `RegisterView.vue`
- ✅ `DashboardView.vue`
- ✅ `HomeView.vue`
- ✅ `resources/js/components/Navbar.vue`
- ✅ `resources/js/components/AuthLayout.vue`

**Nota:** Estos archivos fueron creados originalmente para usar con Vite, pero actualmente se usa la versión CDN que no requiere compilación.

---

## Cómo Editar las Vistas

### Opción 1: Editar versión CDN (ACTIVA)

**Archivo:** `public/js/auth-app.js`

Buscar los componentes por nombre:
- `LoginView` - línea ~273
- `RegisterView` - línea ~388
- `DashboardView` - línea ~543

**Ejemplo - Editar LoginView:**

```javascript
// Buscar: const LoginView = {
const LoginView = {
    setup() {
        // Lógica del componente
    },
    template: `
        <!-- Aquí está el HTML de la vista -->
        <div class="...">
            <!-- Contenido del formulario de login -->
        </div>
    `
};
```

### Opción 2: Usar versión Vite/Vue (requiere compilación)

Si prefieres usar archivos `.vue` separados:

1. **Editar archivos en:** `resources/js/views/`
2. **Modificar `resources/views/welcome.blade.php`:**
   - Cambiar de CDN a `@vite(['resources/js/app.js'])`
3. **Compilar:**
   ```bash
   npm run build
   # o para desarrollo
   npm run dev
   ```

---

## Estructura de Archivos

```
ModuStackAdmin/
├── public/
│   └── js/
│       └── auth-app.js          ✅ ACTIVO (CDN version)
│
└── resources/
    ├── views/
    │   └── welcome.blade.php    ✅ ACTIVO (carga auth-app.js)
    │
    └── js/
        ├── views/               ❌ NO ACTIVO
        │   ├── LoginView.vue
        │   ├── RegisterView.vue
        │   ├── DashboardView.vue
        │   └── HomeView.vue
        │
        ├── components/          ❌ NO ACTIVO
        │   ├── Navbar.vue
        │   └── AuthLayout.vue
        │
        └── app.js               ❌ NO ACTIVO (requiere Vite)
```

---

## Resumen

| Vista | Versión Activa | Ubicación | Línea |
|-------|---------------|-----------|-------|
| **Login** | ✅ CDN | `public/js/auth-app.js` | ~273 |
| **Registro** | ✅ CDN | `public/js/auth-app.js` | ~388 |
| **Dashboard** | ✅ CDN | `public/js/auth-app.js` | ~543 |
| **Home** | ✅ CDN | `public/js/auth-app.js` | ~251 |
| **Navbar** | ✅ CDN | `public/js/auth-app.js` | ~189 |

**Para editar:** Abre `public/js/auth-app.js` y busca el componente que necesites.

---

**Documentado por:** Auto (Cursor AI)  
**Fecha:** 2025-11-03  
**Estado:** ✅ Documentación completa

