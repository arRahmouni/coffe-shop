<template>
    <div class="auth-page">
        <div class="auth-box">
            <h2 class="custom-auth-title">Welcome Back!</h2>
            <form @submit.prevent="handleLogin" class="custom-auth-form">
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
                <button type="submit" class="custom-auth-button">Login</button>
            </form>
            <p class="auth-link">
                Don't have an account?
                <router-link to="/register" class="custom-auth-link-text">Register</router-link>
            </p>
        </div>
    </div>
</template>

<script setup>
    import { reactive } from "vue";
    import axios from "axios";
    import { useRouter } from "vue-router";
    import { useToast } from "vue-toastification";
    import "../../../css/auth.css";
    import { useFormValidation } from "@/composables/useFormValidation"; // Import the composable
    import ErrorMessage from "../components/ErrorMessage.vue"; // Import the ErrorMessage component

    const router = useRouter();
    const toast = useToast();

    // Reactive form state
    const initialFormState  = reactive({
        email: "",
        password: "",
    });

    const { form, errors, clearErrors, handleApiError } = useFormValidation(initialFormState);

    const handleLogin = async () => {
        try {
            const response =  await axios.post("/api/v1/login", form);

            localStorage.setItem('authToken', response.data.data.token.access_token);

            axios.defaults.headers.common["Authorization"] = `Bearer ${response.data.access_token}`;

            toast.success("Login successful!");

            router.push("/dashboard");
        } catch (error) {
            handleApiError(error);
        }
    };
</script>
