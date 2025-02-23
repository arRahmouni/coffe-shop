<template>
    <div class="min-h-screen flex items-center justify-center bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1498804103079-a6351b050096');">
        <!-- Dark overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Content -->
        <div class="max-w-lg w-full mx-auto p-8 bg-white bg-opacity-90 rounded-lg shadow-md relative z-10">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">
                    Welcome Back!
                </h1>
                <p class="text-gray-600">
                    Please log in to continue
                </p>
            </div>

            <form @submit.prevent="handleAuth" class="space-y-4">
                <!-- Email Input -->
                <div class="form-group relative">
                    <input
                        type="email"
                        v-model="form.email"
                        placeholder="Email"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        :class="{ 'border-red-500': errors.email }"
                    />
                    <ErrorMessage :errors="errors" field="email" />
                </div>

                <!-- Password Input with Show/Hide Icon -->
                <div class="form-group relative">
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        v-model="form.password"
                        placeholder="Password"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10"
                        :class="{ 'border-red-500': errors.password }"
                    />
                    <!-- Show/Hide Password Icon -->
                    <span
                        class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer"
                        @click="toggleShowPassword"
                    >
                        <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-gray-500"></i>
                    </span>
                    <ErrorMessage :errors="errors" field="password" />
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 disabled:bg-gray-400 transition-colors"
                    :disabled="isLoading"
                >
                    {{ isLoading ? 'Please wait...' : 'Login' }}
                </button>
            </form>

            <!-- Register Link -->
            <p class="mt-4 text-center text-gray-600">
                Don't have an account?
                <router-link to="/admin/register" class="text-blue-600 hover:text-blue-800">
                    Register
                </router-link>
            </p>

            <!-- Forgot Password Link -->
            <p class="mt-4 text-center text-gray-600">
                <router-link to="/admin/forgot-password" class="text-blue-600 hover:text-blue-800">
                    Forgot Password?
                </router-link>
            </p>
        </div>
    </div>
</template>

<script setup>
    import { reactive, watch, ref  } from "vue";
    import { useFormValidation } from "@/composables/useFormValidation";
    import { useAuthApi } from '@/composables/useAuthApi'; // Import the updated composable
    import ErrorMessage from "@/views/components/ErrorMessage.vue";

    // Reactive form state
    const initialFormState  = reactive({
        email: "",
        password: "",
    });

    const { form, errors, clearErrors, handleApiError } = useFormValidation(initialFormState);
    const { authenticate } = useAuthApi();
    const { handleAuth, isLoading, error } = authenticate('/login', form, clearErrors, 'You have successfully logged in.');
    const showPassword = ref(false);

    const toggleShowPassword = () => {
        showPassword.value = !showPassword.value;
    };
    watch(error, (newError) => {
        if (newError) {
            handleApiError(newError);
        }
    });
</script>
