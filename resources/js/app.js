import './bootstrap';

import { createApp } from 'vue';

import App from './views/App.vue';
import Toast from 'vue-toastification'; // Import the default Toast plugin
import 'vue-toastification/dist/index.css'; // Import the CSS
import router from './router/index.js';
import axios from "axios";

axios.defaults.withCredentials = true; // Send cookies with every request
axios.defaults.baseURL = "http://127.0.0.1:8000/api/v1"; // Set the base URL for API requests
// Create the Vue app
const app = createApp(App);

// Toastification options
const toastOptions = {
    timeout: 3000, // Default toast duration
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: 'button',
    icon: true,
    rtl: false,
};

axios.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response) {
            if (error.response.status === 401) {
                localStorage.removeItem('authToken');
                delete axios.defaults.headers.common['Authorization'];
                window.location.href = '/login';
            }
        }
        return Promise.reject(error);
    }
);

// Use the plugin
app.use(Toast, toastOptions);

// Mount the app
app.use(router).mount('#app');
