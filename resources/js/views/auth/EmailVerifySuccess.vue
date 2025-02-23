<template>
    <div
        class="min-h-screen flex items-center justify-center bg-cover bg-center relative"
        style="
            background-image: url('https://images.unsplash.com/photo-1498804103079-a6351b050096');
        ">
        <!-- Dark overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Content -->
        <div class="max-w-md mx-auto p-6 bg-white bg-opacity-90 rounded-lg shadow-md relative z-10">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">
                    Email Verified Successfully!
                </h1>
                <p class="text-gray-600">
                    Your email has been verified. You can now log in to your
                    account.
                </p>
            </div>

            <div class="bg-green-50 p-4 rounded-lg mb-6">
                <p class="text-green-800">
                    Thank you for verifying your email address.
                </p>
            </div>

            <div class="flex flex-col items-center gap-4">
                <router-link
                    to="/admin/login"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md text-center hover:bg-blue-700 transition-colors"
                >
                    Go to Login
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { useRouter } from "vue-router";
    const router = useRouter();
</script>
