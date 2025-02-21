import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/auth/Login.vue'
import Register from '../views/auth/Register.vue'
import Dashboard from "../views/Dashboard.vue";
import DashboardHome from "../views/dashboard/Home.vue";
import NotFound from '../views/errors/404.vue'

const routes = [
    { path: '/login', component: Login },
    { path: '/register', component: Register },
    { path: '/:pathMatch(.*)*', component: NotFound },
    {
        path: '/dashboard',
        component: Dashboard,
        meta: { requiresAuth: true },
        children: [
            { path: '', component: DashboardHome },
        ]
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach(async (to, from, next) => {
    if (to.meta.requiresAuth) {
        try {
            // Check if the user is authenticated by making an API call
            await axios.get("/api/v1/user");
            next(); // User is authenticated, allow access
        } catch (error) {
            // User is not authenticated, redirect to login
            next("/login");
        }
    } else {
        next(); // Route does not require authentication, allow access
    }
});

export default router
