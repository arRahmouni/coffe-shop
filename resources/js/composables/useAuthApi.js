import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useToast } from "vue-toastification";

export const useAuthApi = () => {
    const router = useRouter();
    const toast = useToast();
    const isLoading = ref(false);
    const error = ref(null);
    const message = ref("");
    const messageClass = ref("");

    // Function for handling authentication (login/register)
    const authenticate = (apiRoute, formData, clearErrors, successMessage) => {
        const handleAuth = async () => {
            try {
                clearErrors();
                isLoading.value = true;
                error.value = null;

                const response = await axios.post(apiRoute, formData);

                toast.success(successMessage);

                if(apiRoute === '/register'){
                    localStorage.setItem('registered_email', formData.email);

                    router.push({
                        path: '/email-verification',
                        query: { email: formData.email }
                    });

                    return;
                }

                localStorage.setItem(
                    "authToken",
                    response.data.data.token.access_token
                );

                axios.defaults.headers.common[
                    "Authorization"
                ] = `Bearer ${response.data.data.token.access_token}`;

                router.push('/dashboard');

            } catch (err) {
                error.value = err;
                if(err.status == 403) {
                    localStorage.setItem('registered_email', formData.email);

                    router.push({
                        path: '/email-verification',
                        query: { email: formData.email }
                    });
                }
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

    // Function for resending the verification email
    const resendVerificationEmail = async (email) => {
        try {
            isLoading.value = true;
            message.value = "";

            const response = await axios.post("/email/resend", {
                email: email,
            });

            // Show success message
            message.value = "Verification link has been resent successfully";
            messageClass.value = "bg-green-100 text-green-800";
        } catch (err) {
            // Handle errors
            message.value =
                err.response?.data?.message ||
                "An error occurred while resending the link";
            messageClass.value = "bg-red-100 text-red-800";
        } finally {
            isLoading.value = false;
        }
    };

    return {
        authenticate,
        resendVerificationEmail,
        isLoading,
        error,
        message,
        messageClass,
    };
};
