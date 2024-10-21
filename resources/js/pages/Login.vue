<template>
    <Navbar></Navbar>
    <section class="bg-gray-50 dark:bg-gray-900 h-screen flex items-center justify-center">
        <div class="w-full max-w-md bg-white rounded-lg shadow dark:border dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white text-center">
                    Login to your account
                </h1>
                <form @submit.prevent="login" class="space-y-4 md:space-y-6">
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                        <input v-model="email" type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required />
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input v-model="password" type="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="••••••••" required />
                    </div>

                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Login
                    </button>

                    <div v-if="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-3" role="alert">
                        {{ errorMessage }}
                    </div>

                    <p class="text-sm font-light text-gray-500 dark:text-gray-400 text-center">
                        Don't have an account? <router-link to="/register" class="font-medium text-primary-600 hover:underline dark:text-primary-500">Register here</router-link>
                    </p>
                </form>
            </div>
        </div>
    </section>
</template>

<script>
import AuthService from '../services/auth';
import EventBus from '../eventBus';
import Navbar from '../components/Navbar.vue';


export default {
    components:{
        Navbar
    },
    data() {
        return {
            email: '',
            password: '',
            errorMessage: '',
        };
    },
    methods: {
        async login() {
            this.errorMessage = ''; // Reset error message
            try {
                const response = await AuthService.login({ email: this.email, password: this.password });
                localStorage.setItem('token', response.data.token);
                EventBus.emit('userLoggedIn'); // Emit the login event
                this.$router.push({ name: 'Home' }); // Redirect to Home page
            } catch (error) {
                if (error.response) {
                    if (error.response.status === 422) {
                        this.errorMessage = 'Please fill in all required fields correctly.';
                    } else if (error.response.status === 404) {
                        this.errorMessage = 'User not found. Please check your email.';
                    } else if (error.response.status === 401) {
                        this.errorMessage = 'Incorrect password. Please try again.';
                    } else {
                        this.errorMessage = 'An unexpected error occurred. Please try again later.';
                    }
                } else {
                    this.errorMessage = 'Network error. Please check your connection.';
                }
            }
        },
    },
};
</script>

<style scoped>
/* No custom styles needed as Tailwind and Flowbite handle the design */
</style>
