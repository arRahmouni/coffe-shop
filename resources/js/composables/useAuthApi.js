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
                        path: '/admin/email-verification',
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

                router.push('/admin/dashboard');

            } catch (err) {
                error.value = err;
                if(err.status == 403) {
                    localStorage.setItem('registered_email', formData.email);

                    router.push({
                        path: '/admin/email-verification',
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

    const handleForgotPassword = async (email) => {
        try {
            console.log("Email being sent:", email); // Debugging
            isLoading.value = true;
            error.value = null;
            message.value = "";

            const response = await axios.post("/forgot-password", {
                email: email,
            });

            message.value        = "Password reset link sent to your email!";
            messageClass.value   = "bg-green-100 text-green-800";
            toast.success("Password reset link sent to your email!");

            router.push("/admin/login");
        } catch (err) {
            error.value = err;
            message.value =
                err.response?.data?.message ||
                "An error occurred while sending the reset link";
            messageClass.value = "bg-red-100 text-red-800";
            throw err;
        } finally {
            isLoading.value = false;
        }
    };

    const handleResetPassword = async (email, token, password, password_confirmation) => {
        try {
            isLoading.value = true;
            error.value = null;
            message.value = "";

            const response = await axios.post("/reset-password", {
                email,
                token,
                password,
                password_confirmation
            });

            // Handle success
            message.value = "Password reset successfully!";
            messageClass.value = "bg-green-100 text-green-800";
            toast.success("Password reset successfully!");

            router.push("/admin/login");
        } catch (err) {
            // Handle errors
            error.value = err;
            message.value =
                err.response?.data?.message ||
                "An error occurred while resetting your password";
            messageClass.value = "bg-red-100 text-red-800";
            throw err;
        } finally {
            isLoading.value = false;
        }
    };

    return {
        authenticate,
        resendVerificationEmail,
        handleForgotPassword,
        handleResetPassword,
        isLoading,
        error,
        message,
        messageClass,
    };
};
