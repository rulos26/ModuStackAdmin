<template>
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
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const auth = useAuthStore();
const router = useRouter();

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
</script>

<style scoped>
.btn-danger {
    @apply bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 font-medium disabled:opacity-50 disabled:cursor-not-allowed transition-colors;
}
</style>

