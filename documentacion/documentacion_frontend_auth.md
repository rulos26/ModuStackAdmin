# Documentación del Frontend - Módulo Auth

## Fecha: 2025-11-03
## Estado: ✅ FRONTEND COMPLETAMENTE IMPLEMENTADO

---

## Resumen Ejecutivo

Frontend SPA (Single Page Application) completamente funcional usando **Vue 3 + Composition API + Pinia + Vue Router**, totalmente integrado con la API REST de autenticación (Laravel Sanctum).

---

## Tecnologías Utilizadas

### Stack Frontend
- **Vue 3.4.0** - Framework JavaScript reactivo
- **Vue Router 4.3.0** - Enrutamiento del SPA
- **Pinia 2.1.0** - Gestión de estado global
- **Axios 1.7.0** - Cliente HTTP para API
- **TailwindCSS 3.4** - Framework CSS utilitario
- **Vite 6.0** - Build tool y dev server

### Integración
- **Laravel Sanctum** - Autenticación API con tokens
- **Vite Laravel Plugin** - Integración con Laravel

---

## Estructura del Proyecto

```
resources/
├── js/
│   ├── app.js                      # Punto de entrada Vue
│   ├── bootstrap.js                # Configuración básica
│   ├── router/
│   │   └── index.js                # Configuración Vue Router
│   ├── stores/
│   │   └── auth.js                 # Store Pinia de autenticación
│   ├── views/
│   │   ├── HomeView.vue            # Página principal
│   │   ├── LoginView.vue           # Página de login
│   │   ├── RegisterView.vue        # Página de registro
│   │   └── DashboardView.vue       # Dashboard protegido
│   └── components/
│       ├── AuthLayout.vue          # Layout principal
│       └── Navbar.vue              # Barra de navegación
├── css/
│   └── app.css                     # Estilos TailwindCSS
└── views/
    └── welcome.blade.php           # Vista principal (SPA entry point)
```

---

## Componentes Implementados

### 1. App Principal (`resources/js/app.js`)

```javascript
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import App from './components/AuthLayout.vue';

const app = createApp(App);
app.use(createPinia());
app.use(router);
app.mount('#app');
```

**Funcionalidad:**
- Inicializa Vue 3
- Registra Pinia para estado global
- Configura Vue Router
- Monta la aplicación en `#app`

---

### 2. Vue Router (`resources/js/router/index.js`)

**Rutas configuradas:**

| Ruta | Componente | Protección | Descripción |
|------|------------|------------|-------------|
| `/` | HomeView | Pública | Página principal |
| `/login` | LoginView | Pública | Inicio de sesión |
| `/register` | RegisterView | Pública | Registro de usuarios |
| `/dashboard` | DashboardView | **Requiere Auth** | Dashboard del usuario |

**Protección de rutas:**
- `beforeEach` guard verifica autenticación
- Redirige a `/login` si no está autenticado
- Funciona con el store Pinia de autenticación

---

### 3. Store Pinia (`resources/js/stores/auth.js`)

**Estado:**
- `user`: Datos del usuario autenticado
- `token`: Token de Sanctum almacenado en localStorage

**Getters:**
- `isAuthenticated`: Verifica si hay token activo

**Actions:**
- `login(email, password)`: Autentica y guarda token
- `register(payload)`: Registra nuevo usuario
- `logout()`: Cierra sesión y revoca token
- `fetchProfile()`: Obtiene perfil del usuario
- `initAuth()`: Inicializa autenticación al cargar app

**Configuración Axios:**
- Base URL: `/api`
- Headers Authorization automático
- Manejo de errores centralizado

---

### 4. Vistas Vue

#### HomeView.vue
- Página principal del SPA
- Muestra opciones según estado de autenticación
- Enlaces a login/register o dashboard
- Diseño moderno con TailwindCSS

#### LoginView.vue
- Formulario de login con validación
- Manejo de errores del backend
- Redirección a dashboard tras login exitoso
- Enlace a registro

#### RegisterView.vue
- Formulario de registro completo
- Validación de contraseñas (confirmación)
- Manejo de errores de validación
- Auto-login tras registro exitoso

#### DashboardView.vue
- Vista protegida (requiere autenticación)
- Muestra información del perfil del usuario
- Carga datos desde API `/api/auth/profile`
- Botón de logout funcional

---

### 5. Componentes

#### AuthLayout.vue
- Layout principal del SPA
- Incluye Navbar y router-view
- Inicializa autenticación al montar

#### Navbar.vue
- Barra de navegación responsiva
- Muestra estado de autenticación
- Botones de login/register o logout según estado
- Navegación con Vue Router

---

## Configuración

### 1. Vite Config (`vite.config.js`)

```javascript
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
```

### 2. Vista Principal (`resources/views/welcome.blade.php`)

```blade
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema Modular - Autenticación</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div id="app"></div>
</body>
</html>
```

**Características:**
- Carga Vite assets
- Div `#app` para montar Vue
- Meta tag CSRF token
- Estilos base TailwindCSS

---

## Flujo de Autenticación

### 1. Registro de Usuario
1. Usuario llena formulario en `/register`
2. Store llama a `POST /api/auth/register`
3. Backend crea usuario y genera token
4. Token se guarda en localStorage
5. Usuario es redirigido a `/dashboard`

### 2. Login
1. Usuario ingresa credenciales en `/login`
2. Store llama a `POST /api/auth/login`
3. Backend valida credenciales y genera token
4. Token se guarda en localStorage
5. Usuario es redirigido a `/dashboard`

### 3. Acceso a Dashboard
1. Router verifica autenticación en `beforeEach`
2. Si hay token, permite acceso
3. Dashboard carga perfil con `GET /api/auth/profile`
4. Muestra información del usuario

### 4. Logout
1. Usuario hace clic en "Salir"
2. Store llama a `POST /api/auth/logout`
3. Backend revoca token
4. Token se elimina de localStorage
5. Usuario es redirigido a `/`

---

## Manejo de Tokens

### Almacenamiento
- **LocalStorage**: Token persistente entre sesiones
- **Axios Headers**: Token enviado automáticamente en requests

### Inicialización
```javascript
initAuth() {
    if (this.token) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        this.fetchProfile();
    }
}
```

### Revocación
- Logout elimina token de localStorage
- Elimina header Authorization de Axios
- Limpia estado del store

---

## Validaciones y Manejo de Errores

### Frontend
- Validación de campos requeridos
- Validación de formato email
- Validación de coincidencia de contraseñas
- Mensajes de error del backend
- Estados de carga (loading)

### Backend
- Validación con Request classes
- Errores retornados en formato JSON
- Mensajes personalizados en español

---

## Usuarios de Prueba

Creados por `AuthSeeder`:

1. **root@system.local** / `root1234`
2. **admin@example.com** / `password`
3. **test@example.com** / `password`

**Ejecutar seeder:**
```bash
php artisan db:seed --class="Modules\Auth\Database\Seeders\AuthSeeder"
```

---

## Comandos de Desarrollo

### Desarrollo (con Hot Reload)
```bash
npm run dev
```

### Producción (Build)
```bash
npm run build
```

### Verificar Rutas
```bash
php artisan route:list | grep -E "^GET|POST.*/"
```

---

## Características Implementadas

### ✅ Funcionalidades Core
- [x] Registro de usuarios
- [x] Login con credenciales
- [x] Logout con revocación de token
- [x] Dashboard protegido
- [x] Perfil del usuario
- [x] Navegación con Vue Router
- [x] Protección de rutas
- [x] Persistencia de sesión

### ✅ UX/UI
- [x] Diseño moderno con TailwindCSS
- [x] Formularios responsivos
- [x] Mensajes de error claros
- [x] Estados de carga
- [x] Navegación intuitiva
- [x] Barra de navegación contextual

### ✅ Integración
- [x] Axios configurado con base URL
- [x] Headers Authorization automáticos
- [x] Manejo de errores HTTP
- [x] Integración completa con API

---

## Estado Final

### ✅ FRONTEND COMPLETAMENTE FUNCIONAL

**Rutas disponibles:**
- ✅ `/` - Home (SPA)
- ✅ `/login` - Login
- ✅ `/register` - Registro
- ✅ `/dashboard` - Dashboard protegido

**Integración:**
- ✅ API REST funcionando
- ✅ Tokens Sanctum operativos
- ✅ Autenticación completa
- ✅ Navegación SPA sin recargas

---

## Próximos Pasos Sugeridos

### Mejoras Futuras
1. Recuperación de contraseña
2. Verificación de email
3. Cambio de contraseña
4. Refresh tokens
5. Notificaciones toast
6. Modo oscuro

---

**Documentado por:** Auto (Cursor AI)  
**Fecha:** 2025-11-03  
**Estado:** ✅ FRONTEND COMPLETAMENTE IMPLEMENTADO

