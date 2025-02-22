import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/auth/Login.vue'
import Register from '../views/auth/Register.vue'
import Dashboard from "../views/Dashboard.vue";
import DashboardHome from "../views/dashboard/Home.vue";
import NotFound from '../views/errors/404.vue'
import EmailVerify from '../views/auth/EmailVerify.vue';
import EmailVerifySuccess from '../views/auth/EmailVerifySuccess.vue';
import EmailVerifyFailure from '../views/auth/EmailVerifyFailure.vue';

const routes = [
    {
        path: '/login',
        component: Login,
        meta: { guest: true }
    },
    {
        path: '/register',
        component: Register,
        meta: { guest: true }
    },
    {
        path: '/:pathMatch(.*)*',
        component: NotFound
    },
    {
        path: '/dashboard',
        component: Dashboard,
        meta: { requiresAuth: true },
        children: [
            { path: '', component: DashboardHome },
        ]
    },
    {
        path: '/email-verification',
        component: EmailVerify,
        meta: { guest: true }
    },
    {
        path: '/email-verification-success',
        component: EmailVerifySuccess,
        meta: { guest: true }
    },
    {
        path: '/email-verification-failed',
        component: EmailVerifyFailure,
        meta: { guest: true }
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach(async (to, from, next) => {
    if (to.meta.requiresAuth) {
        const token = localStorage.getItem('authToken');

        if (token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

            try {
                await axios.get('/user');
                next();
            } catch (error) {
                localStorage.removeItem('authToken');
                delete axios.defaults.headers.common['Authorization'];
                next('/login');
            }
        } else {
            next('/login');
        }
    } else if (to.meta.guest) {
        const token = localStorage.getItem('authToken');

        if (token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

            try {
                await axios.get('/user');
                next('/dashboard');
            } catch (error) {
                localStorage.removeItem('authToken');
                delete axios.defaults.headers.common['Authorization'];
                next();
            }
        } else {
            next();
        }
    }

    else {
        next();
    }
});

export default router
