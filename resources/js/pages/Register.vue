<template>
    <Navbar></Navbar>
    <section class="bg-gray-50 dark:bg-gray-900 h-screen flex items-center justify-center pt-16 overflow-y-auto">
        <div class="w-full max-w-md bg-white rounded-lg shadow dark:border dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-2 md:space-y-3 sm:p-8"> <!-- reduce spacing between inner divs -->
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white text-center">
                    Sign Up
                </h1>
                <form @submit.prevent="register" class="space-y-2 md:space-y-3 flex-1 mt-4"> <!-- reduce spacing between form fields -->
                    <div>
                        <label for="name" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                        <input v-model="name" type="text" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="John Doe" required />
                    </div>
                    <div>
                        <label for="email" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input v-model="email" type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required />
                    </div>
                    <div>
                        <label for="password" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input v-model="password" type="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="••••••••" required />
                    </div>
                    <div>
                        <label for="password_confirmation" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Confirm Password</label>
                        <input v-model="password_confirmation" type="password" id="password_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="••••••••" required />
                    </div>
                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Register
                    </button>
                    <div v-if="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-2" role="alert"> <!-- reduce margin between error message and form fields -->
                        {{ errorMessage }}
                    </div>
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400 text-center">
                        Already have an account? <router-link to="/login" class="font-medium text-primary-600 hover:underline dark:text-primary-500">Login here</router-link>
                    </p>
                </form>
            </div>
        </div>
    </section>
</template>

<script>
import Navbar from '../components/Navbar.vue';
import AuthService from '../services/auth';

export default {
    components:{
        Navbar
    },
    data() {
        return {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            errorMessage: '',
        };
    },
    methods: {
        async register() {
            this.errorMessage = ''; // Reset error message
            try {
                await AuthService.register({
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                });
                this.$router.push({ name: 'Login' });
            } catch (error) {
                if (error.response) {
                    const errors = error.response.data.errors;

                    // Handle specific error responses
                    if (errors.password && errors.password_confirmation) {
                        this.errorMessage = 'Password and password confirmation must be the same.';
                    } else if (error.response.status === 422) {
                        this.errorMessage = 'Please fill in all required fields correctly.';
                    } else if (error.response.status === 409) {
                        this.errorMessage = 'Email already exists. Please use a different email.';
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
