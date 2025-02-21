<template>
    <div class="auth-page">
        <div class="auth-box">
            <h2 class="custom-auth-title">Welcome Back!</h2>
            <form @submit.prevent="handleAuth" class="custom-auth-form">
                <div class="form-group">
                    <input
                        type="email"
                        v-model="form.email"
                        placeholder="Email"
                        class="custom-input"
                        :class="{ 'is-invalid': errors.email }"
                    />
                    <ErrorMessage :errors="errors" field="email" />
                </div>
                <div class="form-group">
                    <input
                        type="password"
                        v-model="form.password"
                        placeholder="Password"
                        class="custom-input"
                        :class="{ 'is-invalid': errors.password }"
                    />
                    <ErrorMessage :errors="errors" field="password" />
                </div>
                <button type="submit" class="custom-auth-button" :disabled="isLoading">{{ isLoading ? 'Please wait...' : 'Login' }}</button>
            </form>
            <p class="auth-link">
                Don't have an account?
                <router-link to="/register" class="custom-auth-link-text">Register</router-link>
            </p>
        </div>
    </div>
</template>

<script setup>
    import { reactive, watch  } from "vue";
    import { useFormValidation } from "@/composables/useFormValidation";
    import { authenticate } from '@/composables/useAuthApi';
    import ErrorMessage from "../components/ErrorMessage.vue";
    import "@/../css/auth.css";

    // Reactive form state
    const initialFormState  = reactive({
        email: "",
        password: "",
    });

    const { form, errors, clearErrors, handleApiError } = useFormValidation(initialFormState);

    const { handleAuth, isLoading, error } = authenticate('/login', form, clearErrors, 'You have successfully logged in.');

    watch(error, (newError) => {
        if (newError) {
            handleApiError(newError);
        }
    });
</script>
