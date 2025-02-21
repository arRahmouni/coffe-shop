<template>
    <div class="auth-page">
        <div class="auth-box">
            <h2 class="custom-auth-title">Create an Account</h2>
            <form @submit.prevent="handleAuth" class="custom-auth-form">
                <!-- First Name -->
                <div class="form-group">
                    <input
                        type="text"
                        v-model="form.first_name"
                        placeholder="First Name"
                        class="custom-input"
                        :class="{ 'is-invalid': errors.first_name }"
                    />
                    <ErrorMessage :errors="errors" field="first_name" />
                </div>

                <!-- Last Name -->
                <div class="form-group">
                    <input
                        type="text"
                        v-model="form.last_name"
                        placeholder="Last Name"
                        class="custom-input"
                        :class="{ 'is-invalid': errors.last_name }"
                    />
                    <ErrorMessage :errors="errors" field="last_name" />
                </div>

                <!-- Email -->
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

                <!-- Username -->
                <div class="form-group">
                    <input
                        type="text"
                        v-model="form.username"
                        placeholder="Username"
                        class="custom-input"
                        :class="{ 'is-invalid': errors.username }"
                    />
                    <ErrorMessage :errors="errors" field="username" />
                </div>

                <!-- Password -->
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

                <!-- Confirm Password -->
                <div class="form-group">
                    <input
                        type="password"
                        v-model="form.password_confirmation"
                        placeholder="Confirm Password"
                        class="custom-input"
                        :class="{ 'is-invalid': errors.password_confirmation }"
                    />
                    <ErrorMessage :errors="errors" field="password_confirmation" />
                </div>

                <!-- Register Button -->
                <button type="submit" class="custom-auth-button" :disabled="isLoading">{{ isLoading ? 'Please wait...' : 'Register' }}</button>
            </form>
            <p class="auth-link">
                Already have an account?
                <router-link to="/login" class="custom-auth-link-text">Login</router-link>
            </p>
        </div>
    </div>
</template>

<script setup>
    import { reactive, watch } from "vue";
    import { useFormValidation } from "@/composables/useFormValidation"; // Import the composable
    import ErrorMessage from "../components/ErrorMessage.vue"; // Import the ErrorMessage component
    import { authenticate } from '@/composables/useAuthApi';
    import "@/../css/auth.css";

    // Reactive form state
    const initialFormState  = reactive({
        first_name: "",
        last_name: "",
        email: "",
        username: "",
        password: "",
        password_confirmation: "",
    });

    const { form, errors, clearErrors, handleApiError } = useFormValidation(initialFormState);

    const { handleAuth, isLoading, error } = authenticate('/register', form, clearErrors, 'You have successfully registered.');

    watch(error, (newError) => {
        if (newError) {
            handleApiError(newError);
        }
    });
</script>
