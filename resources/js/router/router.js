import { createRouter, createWebHistory } from 'vue-router';
import Home from '../pages/Home.vue';

import Login from '../pages/Login.vue';
import Register from '../pages/Register.vue';
import AuthService from '../services/auth';

import Leaderboard from '../pages/Leaderboard.vue';
import Quiz from '../pages/Quiz.vue';
import Admin_login from '../admin_pages/admin_login.vue';
import User_management from '../admin_pages/user_management.vue';
import Forbidden from '../components/Forbidden.vue';
import Admin_home from '../admin_pages/admin_home.vue';
import RoleManagement from '../admin_pages/RoleManagement.vue';


const routes = [
    {
        path: '/login',
        name: 'Login',
        component: Login
    },
    {
        path: '/admin/login',
        name: 'Admin Login',
        component: Admin_login
    },
    {
        path: '/admin/users',
        name: 'UserManagement',
        component: User_management,
        meta: { permission: 'view user' }
    },
    {
        path: '/register',
        name: 'Register',
        component: Register
    },
    {
        path: '/logout',
        name: 'Logout',
        beforeEnter: async (to, from, next) => {
            await AuthService.logout();
            localStorage.removeItem('token');
            next({ name: 'Login' });
        }
    },
    {
        path: '/',
        name: 'Home',
        component: Home,
    },
    {
        path: '/admin',
        name: 'Admin_home',
        component: Admin_home,
    },
    {
        path: '/quiz',
        name: 'Quiz',
        component: Quiz,
        meta: { permission: 'view quizzes' } // Add permission requirement
    },
    {
        path: '/leaderboard',
        name: 'Leaderboard',
        component: Leaderboard,
        meta: { permission: 'view leaderboard' } // Add permission requirement
    },
    {
        path: '/403',
        component: Forbidden,
        name: 'Forbidden'
    },
    {
        path: '/admin/roles',
        name: 'RoleManagement',
        component: RoleManagement,
        meta: { permission: 'view roles' }
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Global before guard
router.beforeEach(async (to, from, next) => {
    const token = localStorage.getItem('token');
    if (to.meta.permission) {
        if (!token) {
            return next({ name: 'Login' });
        }
        try {
            const response = await AuthService.checkPermission(to.meta.permission);
            if (response.data.allowed) {
                return next();
            } else {
                return next({ name: 'Forbidden' }); // Redirect to the Forbidden component
            }
        } catch (error) {
            console.error('Error checking permission:', error);
            return next({ name: 'Forbidden' }); // Redirect to the Forbidden component
        }
    }
    next(); // Proceed if no permission check is needed
});

export default router;
