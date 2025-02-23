<template>
    <div class="min-h-screen flex items-center justify-center bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1498804103079-a6351b050096');">
        <!-- Dark overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Content -->
        <div class="max-w-md mx-auto p-6 bg-white bg-opacity-90 rounded-lg shadow-md relative z-10">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">
                    Account Activation
                </h1>
                <p class="text-gray-600">
                    A verification link has been sent to your email
                </p>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg mb-6">
                <p class="text-blue-800">
                    Please check your inbox or spam folder for the email sent to
                    <span class="font-semibold">{{ email }}</span>
                </p>
            </div>

            <div class="flex flex-col items-center gap-4">
                <button
                    @click="resendVerificationEmail(email)"
                    :disabled="isLoading"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 disabled:bg-gray-400 transition-colors"
                >
                    <span v-if="!isLoading">Resend Verification Link</span>
                    <span v-else>Sending...</span>
                </button>

                <router-link
                    to="/admin/login"
                    class="text-blue-600 hover:text-blue-800 text-sm"
                >
                    Back to Login
                </router-link>
            </div>

            <!-- Feedback Messages -->
            <div v-if="message" class="mt-4 p-3 rounded-lg" :class="messageClass">
                {{ message }}
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted } from "vue";
    import { useRoute } from "vue-router";
    import { useAuthApi } from "@/composables/useAuthApi";

    const route = useRoute();

    const email = ref("");

    const { resendVerificationEmail, isLoading, message, messageClass } = useAuthApi();

    onMounted(() => {
        email.value = route.query.email || localStorage.getItem("registered_email");
    });
</script>
