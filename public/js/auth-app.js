// ============================================
// Sistema Modular - Auth App (CDN Version)
// ============================================

console.log('🚀 Inicializando aplicación de autenticación...');

const { createApp, ref, reactive, computed, onMounted, provide, inject } = Vue;
const { createRouter, createWebHistory } = VueRouter;

console.log('✅ Vue y Vue Router cargados');

// Detectar subdirectorio automáticamente desde la URL actual
function getBasePath() {
    const path = window.location.pathname;
    const href = window.location.href;
    
    console.log('🔍 Detectando base path...');
    console.log('   Pathname:', path);
    console.log('   Href:', href);
    
    // Si está en un subdirectorio, extraer el path base
    // Casos posibles:
    // - /ModuStackAdmin/ -> /ModuStackAdmin
    // - /ModuStackAdmin/public/index.php -> /ModuStackAdmin
    // - /ModuStackAdmin/public/ -> /ModuStackAdmin
    
    if (path.includes('/ModuStackAdmin')) {
        // Extraer /ModuStackAdmin del inicio del path
        const match = path.match(/^(\/ModuStackAdmin)/);
        if (match) {
            const basePath = match[1];
            console.log('📁 Subdirectorio detectado:', basePath);
            return basePath;
        }
    }
    
    // Fallback: detectar desde la estructura de URL
    // Si la URL contiene /ModuStackAdmin/, usar ese como base
    if (href.includes('/ModuStackAdmin/')) {
        const urlParts = new URL(href);
        const pathParts = urlParts.pathname.split('/').filter(p => p);
        if (pathParts[0] === 'ModuStackAdmin') {
            const basePath = '/ModuStackAdmin';
            console.log('📁 Subdirectorio detectado desde URL:', basePath);
            return basePath;
        }
    }
    
    console.log('📁 Sin subdirectorio detectado, usando raíz');
    return '';
}

const BASE_PATH = getBasePath();
console.log('📍 Base path configurado:', BASE_PATH || '/ (raíz)');

// Configurar Axios
axios.defaults.withCredentials = true;
axios.defaults.baseURL = BASE_PATH + '/api';
console.log('✅ Axios configurado con baseURL:', axios.defaults.baseURL);

// Store de Autenticación Manual (sin Pinia)
function createAuthStore() {
    console.log('📦 Creando store de autenticación...');
    
    const state = reactive({
        user: null,
        token: localStorage.getItem('token') || null,
    });

    console.log('📦 Store creado. Token inicial:', state.token ? 'Presente' : 'Ausente');

    return {
        get state() {
            return state;
        },
        
        get user() {
            return state.user;
        },
        
        get token() {
            return state.token;
        },
        
        get isAuthenticated() {
            return !!state.token;
        },

        async login(email, password) {
            console.log('🔐 Intentando login con email:', email);
            try {
                const { data } = await axios.post('/auth/login', { email, password });
                console.log('✅ Login exitoso. Token recibido:', data.token ? 'Sí' : 'No');
                
                state.token = data.token;
                state.user = data.user;
                localStorage.setItem('token', data.token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;
                
                console.log('✅ Usuario autenticado:', data.user.name);
                return { success: true };
            } catch (error) {
                console.error('❌ Error en login:', error.response?.data || error.message);
                return { 
                    success: false, 
                    message: (error.response && error.response.data && error.response.data.message) || 'Error al iniciar sesión' 
                };
            }
        },

        async register(payload) {
            console.log('📝 Intentando registro con email:', payload.email);
            try {
                const { data } = await axios.post('/auth/register', payload);
                console.log('✅ Registro exitoso. Token recibido:', data.token ? 'Sí' : 'No');
                
                state.token = data.token;
                state.user = data.user;
                localStorage.setItem('token', data.token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;
                
                console.log('✅ Usuario registrado:', data.user.name);
                return { success: true };
            } catch (error) {
                console.error('❌ Error en registro:', error.response?.data || error.message);
                return { 
                    success: false, 
                    message: (error.response && error.response.data && error.response.data.message) || 'Error al registrar usuario',
                    errors: (error.response && error.response.data && error.response.data.errors) || {}
                };
            }
        },

        async logout() {
            console.log('🚪 Cerrando sesión...');
            try {
                if (state.token) {
                    await axios.post('/auth/logout');
                    console.log('✅ Logout exitoso en servidor');
                }
            } catch (error) {
                console.error('❌ Error al cerrar sesión:', error);
            } finally {
                state.token = null;
                state.user = null;
                localStorage.removeItem('token');
                delete axios.defaults.headers.common['Authorization'];
                console.log('✅ Sesión cerrada localmente');
            }
        },

        async fetchProfile() {
            console.log('👤 Obteniendo perfil del usuario...');
            try {
                const { data } = await axios.get('/auth/profile');
                state.user = data.user;
                console.log('✅ Perfil obtenido:', data.user.name);
                return { success: true };
            } catch (error) {
                console.error('❌ Error al obtener perfil:', error);
                this.logout();
                return { success: false };
            }
        },

        initAuth() {
            console.log('🔧 Inicializando autenticación...');
            if (state.token) {
                axios.defaults.headers.common['Authorization'] = `Bearer ${state.token}`;
                console.log('✅ Token restaurado desde localStorage');
                this.fetchProfile();
            } else {
                console.log('ℹ️ No hay token guardado');
            }
        },
    };
}

// Crear instancia única del store
const authStore = createAuthStore();
console.log('✅ Store de autenticación creado');

// Función helper para usar el store en componentes
function useAuthStore() {
    return authStore;
}

// Componente Navbar
const Navbar = {
    setup() {
        const auth = useAuthStore();
        const router = VueRouter.useRouter();
        
        console.log('📊 Navbar - Estado auth:', auth.isAuthenticated ? 'Autenticado' : 'No autenticado');
        
        const logout = async () => {
            console.log('🚪 Navbar - Iniciando logout...');
            await auth.logout();
            router.push('/');
        };
        
        return { auth, logout };
    },
    template: `
        <nav class="bg-gray-900 text-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <router-link to="/" class="font-bold text-xl hover:text-gray-300 transition-colors">
                            Sistema Modular
                        </router-link>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <router-link 
                            v-if="auth.isAuthenticated"
                            to="/dashboard" 
                            class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-800 transition-colors"
                        >
                            Dashboard
                        </router-link>
                        
                        <div v-if="auth.isAuthenticated" class="flex items-center space-x-4">
                            <span class="text-sm text-gray-300">
                                {{ auth.user && auth.user.name }}
                            </span>
                            <button 
                                @click="logout" 
                                class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-md text-sm font-medium transition-colors"
                            >
                                Salir
                            </button>
                        </div>
                        
                        <div v-else class="flex items-center space-x-4">
                            <router-link 
                                to="/register" 
                                class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-800 transition-colors"
                            >
                                Registrarse
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    `
};

// Componente HomeView - Página de Bienvenida
const HomeView = {
    setup() {
        const auth = useAuthStore();
        const router = VueRouter.useRouter();
        
        console.log('🏠 HomeView - Página de bienvenida cargada');
        console.log('🔐 Estado auth:', auth.isAuthenticated ? 'Autenticado' : 'No autenticado');
        
        // Si hay token pero no hay usuario, intentar cargar perfil
        onMounted(() => {
            if (auth.token && !auth.user) {
                console.log('📊 HomeView - Cargando perfil del usuario...');
                auth.fetchProfile();
            }
        });
        
        return { auth };
    },
    template: `
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <!-- Encabezado Principal -->
                <div class="text-center mb-12">
                    <h1 class="text-5xl font-bold text-gray-900 mb-4">
                        Sistema Modular Laravel
                    </h1>
                    <p class="text-xl text-gray-600 mb-8">
                        Autenticación segura con Laravel Sanctum y Vue 3
                    </p>
                </div>
                
                <!-- Características -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    <!-- Característica 1 -->
                    <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Autenticación Segura</h3>
                        <p class="text-gray-600">Sistema de autenticación con tokens Sanctum</p>
                    </div>
                    
                    <!-- Característica 2 -->
                    <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg mb-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Módulos Integrados</h3>
                        <p class="text-gray-600">Arquitectura modular con nwidart/laravel-modules</p>
                    </div>
                    
                    <!-- Característica 3 -->
                    <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg mb-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a4 4 0 004-4v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Frontend Moderno</h3>
                        <p class="text-gray-600">Interfaz con Vue 3 y TailwindCSS desde CDN</p>
                    </div>
                </div>
                
                <!-- Área de Acción según Estado -->
                <div class="text-center">
                    <!-- Si está autenticado -->
                    <div v-if="auth.isAuthenticated" class="space-y-4">
                        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md mx-auto">
                            <div class="mb-6">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <p class="text-lg text-gray-700">
                                    ¡Bienvenido de nuevo, <span class="font-semibold text-blue-600">{{ auth.user && auth.user.name }}</span>!
                                </p>
                                <p class="text-sm text-gray-500 mt-2">
                                    Estás autenticado y listo para comenzar
                                </p>
                            </div>
                            <router-link 
                                to="/dashboard" 
                                class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-md"
                            >
                                Ir al Dashboard
                            </router-link>
                        </div>
                    </div>
                    
                    <!-- Si NO está autenticado -->
                    <div v-else class="space-y-4">
                        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md mx-auto">
                            <p class="text-lg text-gray-700 mb-6">
                                Comienza a usar el sistema ahora
                            </p>
                            <div class="flex flex-col sm:flex-row justify-center gap-4">
                                <router-link 
                                    to="/login" 
                                    class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-md text-center"
                                >
                                    Iniciar Sesión
                                </router-link>
                                <router-link 
                                    to="/register" 
                                    class="inline-block bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition-colors font-medium shadow-md text-center"
                                >
                                    Crear Cuenta
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `
};

// Componente LoginView
const LoginView = {
    setup() {
        const auth = useAuthStore();
        const router = VueRouter.useRouter();
        
        console.log('🔐 LoginView - Componente cargado');
        
        const email = ref('');
        const password = ref('');
        const loading = ref(false);
        const errorMessage = ref('');
        const errors = ref({});
        
        onMounted(() => {
            if (auth.isAuthenticated) {
                console.log('✅ Usuario ya autenticado, redirigiendo a dashboard');
                router.push('/dashboard');
            }
        });
        
        const loginUser = async () => {
            console.log('🔐 Intentando login...');
            loading.value = true;
            errorMessage.value = '';
            errors.value = {};
            
            try {
                const result = await auth.login(email.value, password.value);
                
                if (result.success) {
                    console.log('✅ Login exitoso, redirigiendo a dashboard');
                    router.push('/dashboard');
                } else {
                    console.error('❌ Login falló:', result.message);
                    errorMessage.value = result.message || 'Credenciales incorrectas';
                }
            } catch (error) {
                console.error('❌ Error en login:', error);
                errorMessage.value = 'Error al conectar con el servidor';
            } finally {
                loading.value = false;
            }
        };
        
        return { email, password, loading, errorMessage, errors, loginUser, auth };
    },
    template: `
        <div class="flex flex-col items-center justify-center min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <div>
                    <h1 class="text-center text-3xl font-bold text-gray-900 mb-2">Iniciar Sesión</h1>
                    <p class="text-center text-gray-600">Accede a tu cuenta</p>
                </div>
                
                <form @submit.prevent="loginUser" class="bg-white shadow-lg p-8 rounded-xl space-y-6">
                    <div v-if="errorMessage" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                        {{ errorMessage }}
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Correo electrónico
                        </label>
                        <input
                            id="email"
                            v-model="email"
                            type="email"
                            placeholder="correo@example.com"
                            required
                            class="input w-full"
                            :class="{ 'border-red-500': errors.email }"
                        />
                        <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Contraseña
                        </label>
                        <input
                            id="password"
                            v-model="password"
                            type="password"
                            placeholder="••••••••"
                            required
                            class="input w-full"
                            :class="{ 'border-red-500': errors.password }"
                        />
                        <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password }}</p>
                    </div>
                    
                    <button 
                        type="submit" 
                        class="btn w-full"
                        :disabled="loading"
                    >
                        <span v-if="loading">Iniciando sesión...</span>
                        <span v-else>Entrar</span>
                    </button>
                </form>
                
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        ¿No tienes cuenta?
                        <router-link to="/register" class="text-blue-600 hover:text-blue-800 font-medium">
                            Crear cuenta
                        </router-link>
                    </p>
                </div>
            </div>
        </div>
    `
};

// Componente RegisterView
const RegisterView = {
    setup() {
        const auth = useAuthStore();
        const router = VueRouter.useRouter();
        
        console.log('📝 RegisterView - Componente cargado');
        
        const form = reactive({
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
        });
        
        const loading = ref(false);
        const errorMessage = ref('');
        const errors = ref({});
        
        onMounted(() => {
            if (auth.isAuthenticated) {
                console.log('✅ Usuario ya autenticado, redirigiendo a dashboard');
                router.push('/dashboard');
            }
        });
        
        const registerUser = async () => {
            console.log('📝 Intentando registro...');
            loading.value = true;
            errorMessage.value = '';
            errors.value = {};
            
            try {
                const result = await auth.register(form);
                
                if (result.success) {
                    console.log('✅ Registro exitoso, redirigiendo a dashboard');
                    router.push('/dashboard');
                } else {
                    console.error('❌ Registro falló:', result.message);
                    errorMessage.value = result.message || 'Error al registrar usuario';
                    if (result.errors) {
                        errors.value = result.errors;
                    }
                }
            } catch (error) {
                console.error('❌ Error en registro:', error);
                errorMessage.value = 'Error al conectar con el servidor';
            } finally {
                loading.value = false;
            }
        };
        
        return { form, loading, errorMessage, errors, registerUser, auth };
    },
    template: `
        <div class="flex flex-col items-center justify-center min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <div>
                    <h1 class="text-center text-3xl font-bold text-gray-900 mb-2">Crear Cuenta</h1>
                    <p class="text-center text-gray-600">Regístrate para comenzar</p>
                </div>
                
                <form @submit.prevent="registerUser" class="bg-white shadow-lg p-8 rounded-xl space-y-6">
                    <div v-if="errorMessage" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                        {{ errorMessage }}
                    </div>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre completo
                        </label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            placeholder="Juan Pérez"
                            required
                            class="input w-full"
                            :class="{ 'border-red-500': errors.name }"
                        />
                        <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name[0] }}</p>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Correo electrónico
                        </label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            placeholder="correo@example.com"
                            required
                            class="input w-full"
                            :class="{ 'border-red-500': errors.email }"
                        />
                        <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email[0] }}</p>
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Contraseña
                        </label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            placeholder="••••••••"
                            required
                            minlength="6"
                            class="input w-full"
                            :class="{ 'border-red-500': errors.password }"
                        />
                        <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password[0] }}</p>
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar contraseña
                        </label>
                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            placeholder="••••••••"
                            required
                            minlength="6"
                            class="input w-full"
                        />
                    </div>
                    
                    <button 
                        type="submit" 
                        class="btn w-full"
                        :disabled="loading"
                    >
                        <span v-if="loading">Registrando...</span>
                        <span v-else>Crear cuenta</span>
                    </button>
                </form>
                
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        ¿Ya tienes cuenta?
                        <router-link to="/login" class="text-blue-600 hover:text-blue-800 font-medium">
                            Iniciar sesión
                        </router-link>
                    </p>
                </div>
            </div>
        </div>
    `
};

// Componente DashboardView
const DashboardView = {
    setup() {
        const auth = useAuthStore();
        const router = VueRouter.useRouter();
        
        console.log('📊 DashboardView - Componente cargado');
        
        const loading = ref(true);
        const loggingOut = ref(false);
        
        onMounted(async () => {
            console.log('📊 DashboardView - Verificando autenticación...');
            if (!auth.user && auth.isAuthenticated) {
                console.log('📊 DashboardView - Cargando perfil...');
                await auth.fetchProfile();
            }
            loading.value = false;
        });
        
        const formatDate = (dateString) => {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            });
        };
        
        const handleLogout = async () => {
            console.log('🚪 DashboardView - Iniciando logout...');
            loggingOut.value = true;
            await auth.logout();
            router.push('/');
        };
        
        return { auth, loading, loggingOut, formatDate, handleLogout };
    },
    template: `
        <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white shadow-lg rounded-xl p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                            Autenticado
                        </span>
                    </div>
                    
                    <div v-if="loading" class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <p class="mt-2 text-gray-600">Cargando información...</p>
                    </div>
                    
                    <div v-else-if="auth.user" class="space-y-6">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Información del Perfil</h2>
                            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">ID</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ auth.user.id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ auth.user.name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Correo electrónico</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ auth.user.email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email verificado</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <span v-if="auth.user.email_verified_at" class="text-green-600">
                                            ✓ Verificado
                                        </span>
                                        <span v-else class="text-yellow-600">
                                            ⚠ No verificado
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Miembro desde</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ formatDate(auth.user.created_at) }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-blue-900 mb-2">Estado de Autenticación</h3>
                            <p class="text-sm text-blue-700">
                                Estás autenticado usando Laravel Sanctum. Tu token está activo y puedes acceder a todas las rutas protegidas.
                            </p>
                        </div>
                        
                        <div class="flex justify-end space-x-4 pt-4 border-t">
                            <button 
                                @click="handleLogout" 
                                class="btn-danger"
                                :disabled="loggingOut"
                            >
                                <span v-if="loggingOut">Cerrando sesión...</span>
                                <span v-else>Cerrar Sesión</span>
                            </button>
                        </div>
                    </div>
                    
                    <div v-else class="text-center py-8">
                        <p class="text-gray-600">No se pudo cargar la información del usuario.</p>
                    </div>
                </div>
            </div>
        </div>
    `
};

// Layout Principal
const AuthLayout = {
    components: {
        Navbar
    },
    setup() {
        const auth = useAuthStore();
        
        console.log('🏗️ AuthLayout - Inicializando...');
        
        onMounted(() => {
            console.log('🏗️ AuthLayout - Montado, inicializando auth...');
            auth.initAuth();
        });
        
        return {};
    },
    template: `
        <div>
            <Navbar />
            <router-view />
        </div>
    `
};

// Configurar Router - HOME como página principal
const routes = [
    {
        path: '/',
        name: 'home',
        component: HomeView, // Página de bienvenida
    },
    {
        path: '/login',
        name: 'login',
        component: LoginView,
    },
    {
        path: '/register',
        name: 'register',
        component: RegisterView,
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: DashboardView,
        meta: { requiresAuth: true },
    },
];

console.log('🛣️ Configurando rutas:', routes.map(r => r.path).join(', '));

const router = createRouter({
    history: createWebHistory(BASE_PATH), // Usar base path del subdirectorio
    routes,
});
console.log('📍 Router configurado con base path:', BASE_PATH || '/ (raíz)');

router.beforeEach((to, from, next) => {
    const auth = useAuthStore();
    
    console.log('🛣️ Navegación:', from.path, '→', to.path);
    console.log('🔐 Requiere auth:', to.meta.requiresAuth, '| Autenticado:', auth.isAuthenticated);
    
    if (to.meta.requiresAuth && !auth.isAuthenticated) {
        console.log('🚫 Acceso denegado, redirigiendo a login');
        next('/login');
    } else {
        next();
    }
});

console.log('✅ Router configurado');

// Inicializar App
console.log('🚀 Inicializando aplicación Vue...');
const app = createApp(AuthLayout);

// Proporcionar el store a todos los componentes
app.provide('authStore', authStore);
console.log('✅ Store proporcionado a componentes');

app.use(router);
console.log('✅ Router registrado');

app.mount('#app');
console.log('✅ Aplicación montada en #app');
console.log('🎉 Sistema de autenticación listo!');

