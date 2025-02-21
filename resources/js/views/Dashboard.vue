<template>
    <div class="dashboard">
        <!-- Navbar -->
        <nav class="navbar">
            <div class="navbar-brand">Dashboard</div>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <router-link to="/dashboard" class="nav-link">Home</router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/dashboard/profile" class="nav-link">Profile</router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/dashboard/settings" class="nav-link">Settings</router-link>
                </li>
                <li class="nav-item">
                    <button @click="handleLogout" class="nav-link logout-button">Logout</button>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="dashboard-content">
            <router-view></router-view> <!-- Nested routes will be rendered here -->
        </div>
    </div>
</template>

<script setup>
    import { useRouter } from "vue-router";
    import { useToast } from "vue-toastification";
    import axios from "axios";

    const router = useRouter();
    const toast = useToast();

    // Logout function
    const handleLogout = async () => {
        try {
            await axios.post("/api/v1/logout");
            localStorage.removeItem("authToken"); // Remove the token from localStorage
            delete axios.defaults.headers.common["Authorization"]; // Remove the token from Axios headers
            toast.success("Logged out successfully!");
            router.push("/login"); // Redirect to login page
        } catch (error) {
            toast.error("An error occurred during logout. Please try again.");
        }
    };
</script>

<style scoped>
    .dashboard {
        display: flex;
        min-height: 100vh;
    }

    .navbar {
        width: 250px;
        background-color: #2c3e50;
        color: white;
        padding: 1rem;
    }

    .navbar-brand {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .navbar-nav {
        list-style: none;
        padding: 0;
    }

    .nav-item {
        margin-bottom: 1rem;
    }

    .nav-link {
        color: white;
        text-decoration: none;
        display: block;
        padding: 0.5rem;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .nav-link:hover {
        background-color: #34495e;
    }

    .logout-button {
        background: none;
        border: none;
        cursor: pointer;
        width: 100%;
        text-align: left;
        padding: 0.5rem;
    }

    .dashboard-content {
        flex-grow: 1;
        padding: 2rem;
        background-color: #f5f5f5;
    }
</style>
