<template>
    <div>
        <Navbar />
        <router-view />
    </div>
</template>

<script setup>
import Navbar from './Navbar.vue';
import { useAuthStore } from '../stores/auth';
import { onMounted } from 'vue';

const auth = useAuthStore();

onMounted(() => {
    auth.initAuth();
});
</script>

