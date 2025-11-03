<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Sistema Modular - Autenticación</title>
    
    <!-- TailwindCSS desde CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Vue 3 desde CDN -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    
    <!-- Vue Router desde CDN -->
    <script src="https://unpkg.com/vue-router@4/dist/vue-router.global.js"></script>
    
    <!-- Pinia desde CDN -->
    <script src="https://unpkg.com/pinia@2/dist/pinia.iife.js"></script>
    
    <!-- Axios desde CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <style>
        .input {
            @apply appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm;
        }
        .btn {
            @apply bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 font-medium disabled:opacity-50 disabled:cursor-not-allowed transition-colors;
        }
        .btn-danger {
            @apply bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 font-medium disabled:opacity-50 disabled:cursor-not-allowed transition-colors;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div id="app"></div>
    
    <script>
        const { createApp, ref, reactive, computed, onMounted } = Vue;
        const { createRouter, createWebHistory } = VueRouter;
        const { createPinia, defineStore } = Pinia;

        // Configurar Axios
        axios.defaults.withCredentials = true;
        axios.defaults.baseURL = '/api';

        // Store de Autenticación
        const useAuthStore = defineStore('auth', {
            state: () => ({
                user: null,
                token: localStorage.getItem('token') || null,
            }),

            getters: {
                isAuthenticated: (state) => !!state.token,
            },

            actions: {
                async login(email, password) {
                    try {
                        const { data } = await axios.post('/auth/login', { email, password });
                        this.token = data.token;
                        this.user = data.user;
                        localStorage.setItem('token', data.token);
                        axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;
                        return { success: true };
                    } catch (error) {
                return { 
                    success: false, 
                    message: (error.response && error.response.data && error.response.data.message) || 'Error al iniciar sesión' 
                };
                    }
                },

                async register(payload) {
                    try {
                        const { data } = await axios.post('/auth/register', payload);
                        this.token = data.token;
                        this.user = data.user;
                        localStorage.setItem('token', data.token);
                        axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;
                        return { success: true };
                    } catch (error) {
                return { 
                    success: false, 
                    message: (error.response && error.response.data && error.response.data.message) || 'Error al registrar usuario',
                    errors: (error.response && error.response.data && error.response.data.errors) || {}
                };
                    }
                },

                async logout() {
                    try {
                        if (this.token) {
                            await axios.post('/auth/logout');
                        }
                    } catch (error) {
                        console.error('Error al cerrar sesión:', error);
                    } finally {
                        this.token = null;
                        this.user = null;
                        localStorage.removeItem('token');
                        delete axios.defaults.headers.common['Authorization'];
                    }
                },

                async fetchProfile() {
                    try {
                        const { data } = await axios.get('/auth/profile');
                        this.user = data.user;
                        return { success: true };
                    } catch (error) {
                        this.logout();
                        return { success: false };
                    }
                },

                initAuth() {
                    if (this.token) {
                        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
                        this.fetchProfile();
                    }
                },
            },
        });

        // Componente Navbar
        const Navbar = {
            setup() {
                const auth = useAuthStore();
                const router = VueRouter.useRouter();
                
                const logout = async () => {
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
                                        to="/login" 
                                        class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-800 transition-colors"
                                    >
                                        Iniciar Sesión
                                    </router-link>
                                    <router-link 
                                        to="/register" 
                                        class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium transition-colors"
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

        // Componente HomeView
        const HomeView = {
            setup() {
                const auth = useAuthStore();
                
                if (auth.token && !auth.user) {
                    auth.fetchProfile();
                }
                
                return { auth };
            },
            template: `
                <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                        <div class="text-center mb-12">
                            <h1 class="text-5xl font-bold text-gray-900 mb-4">
                                Sistema Modular Laravel
                            </h1>
                            <p class="text-xl text-gray-600 mb-8">
                                Autenticación segura con Laravel Sanctum y Vue 3
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                            <div class="bg-white rounded-lg shadow-lg p-6">
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg mb-4">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Autenticación Segura</h3>
                                <p class="text-gray-600">Sistema de autenticación con tokens Sanctum</p>
                            </div>
                            
                            <div class="bg-white rounded-lg shadow-lg p-6">
                                <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg mb-4">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Módulos Integrados</h3>
                                <p class="text-gray-600">Arquitectura modular con nwidart/laravel-modules</p>
                            </div>
                            
                            <div class="bg-white rounded-lg shadow-lg p-6">
                                <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg mb-4">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a4 4 0 004-4v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Frontend Moderno</h3>
                                <p class="text-gray-600">Interfaz con Vue 3, Pinia y TailwindCSS</p>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <div v-if="auth.isAuthenticated" class="space-y-4">
                                <p class="text-lg text-gray-700">
                                    ¡Bienvenido de nuevo, <span class="font-semibold">{{ auth.user && auth.user.name }}</span>!
                                </p>
                                <router-link 
                                    to="/dashboard" 
                                    class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium"
                                >
                                    Ir al Dashboard
                                </router-link>
                            </div>
                            <div v-else class="space-y-4">
                                <p class="text-lg text-gray-700 mb-6">
                                    Comienza a usar el sistema ahora
                                </p>
                                <div class="flex justify-center space-x-4">
                                    <router-link 
                                        to="/login" 
                                        class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium"
                                    >
                                        Iniciar Sesión
                                    </router-link>
                                    <router-link 
                                        to="/register" 
                                        class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-medium"
                                    >
                                        Crear Cuenta
                                    </router-link>
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
                
                const email = ref('');
                const password = ref('');
                const loading = ref(false);
                const errorMessage = ref('');
                const errors = ref({});
                
                onMounted(() => {
                    if (auth.isAuthenticated) {
                        router.push('/dashboard');
                    }
                });
                
                const loginUser = async () => {
                    loading.value = true;
                    errorMessage.value = '';
                    errors.value = {};
                    
                    try {
                        const result = await auth.login(email.value, password.value);
                        
                        if (result.success) {
                            router.push('/dashboard');
                        } else {
                            errorMessage.value = result.message || 'Credenciales incorrectas';
                        }
                    } catch (error) {
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
                        router.push('/dashboard');
                    }
                });
                
                const registerUser = async () => {
                    loading.value = true;
                    errorMessage.value = '';
                    errors.value = {};
                    
                    try {
                        const result = await auth.register(form);
                        
                        if (result.success) {
                            router.push('/dashboard');
                        } else {
                            errorMessage.value = result.message || 'Error al registrar usuario';
                            if (result.errors) {
                                errors.value = result.errors;
                            }
                        }
                    } catch (error) {
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
                
                const loading = ref(true);
                const loggingOut = ref(false);
                
                onMounted(async () => {
                    if (!auth.user && auth.isAuthenticated) {
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
                
                onMounted(() => {
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

        // Configurar Router
        const routes = [
            {
                path: '/',
                name: 'home',
                component: HomeView,
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

        const router = createRouter({
            history: createWebHistory(),
            routes,
        });

        router.beforeEach((to, from, next) => {
            const auth = useAuthStore();
            
            if (to.meta.requiresAuth && !auth.isAuthenticated) {
                next('/login');
            } else {
                next();
            }
        });

        // Inicializar App
        const pinia = createPinia();
        const app = createApp(AuthLayout);
        
        app.use(pinia);
        app.use(router);
        app.mount('#app');
    </script>
</body>
</html>
