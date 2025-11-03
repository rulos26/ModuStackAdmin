<template>
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
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const auth = useAuthStore();
const router = useRouter();

const form = ref({
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
        const result = await auth.register(form.value);
        
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
</script>

<style scoped>
.input {
    @apply appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm;
}

.btn {
    @apply bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 font-medium disabled:opacity-50 disabled:cursor-not-allowed transition-colors;
}
</style>

