<template>
    <router-view></router-view>
</template>

<!-- <script setup>
    import { onMounted } from 'vue';
    import { useRouter } from 'vue-router';
    import axios from 'axios';

    const router = useRouter();

    onMounted(async () => {
        const token = localStorage.getItem('authToken');

        if (token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

            try {
                await axios.get('/api/user');
            } catch (error) {
                localStorage.removeItem('authToken');
                delete axios.defaults.headers.common['Authorization'];
                router.push('/login');
            }
        } else {
            router.push('/login');
        }
    });
</script> -->
