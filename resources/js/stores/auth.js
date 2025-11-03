import { defineStore } from 'pinia';
import axios from 'axios';

axios.defaults.withCredentials = true;
axios.defaults.baseURL = '/api';

export const useAuthStore = defineStore('auth', {
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
                    message: error.response?.data?.message || 'Error al iniciar sesión' 
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
                    message: error.response?.data?.message || 'Error al registrar usuario',
                    errors: error.response?.data?.errors || {}
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

