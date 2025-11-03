<template>
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
                            {{ auth.user?.name }}
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
</template>

<script setup>
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const auth = useAuthStore();
const router = useRouter();

const logout = async () => {
    await auth.logout();
    router.push('/');
};
</script>

