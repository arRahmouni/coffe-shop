import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useToast } from "vue-toastification";

export const authenticate = (apiRoute, formData, clearErrors, message) => {
    const router = useRouter();
    const toast = useToast();
    const isLoading = ref(false);
    const error = ref(null);

    const handleAuth = async () => {
        try {
            clearErrors();
            isLoading.value = true;
            error.value = null;

            const response = await axios.post(apiRoute, formData);

            localStorage.setItem(
                "authToken",
                response.data.data.token.access_token
            );
            axios.defaults.headers.common[
                "Authorization"
            ] = `Bearer ${response.data.data.token.access_token}`;

            toast.success(message);
            router.push("/dashboard");

            return response.data;
        } catch (err) {
            error.value = err;
            throw err;
        } finally {
            isLoading.value = false;
        }
    };

    return {
        handleAuth,
        isLoading,
        error,
    };
};
