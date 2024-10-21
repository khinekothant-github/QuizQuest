<template>
    <div class="bg-gray-50 dark:bg-gray-900 h-screen flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 max-w-md text-center">
            <h1 class="text-4xl font-bold text-red-600 dark:text-red-400">403 - Forbidden</h1>
            <p class="mt-4 text-lg text-gray-700 dark:text-gray-300">
                You do not have permission to do this action. Please check your credentials or contact support if you believe this is an error.
            </p>
            <button @click="goBack" class="mt-6 w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                Go Back
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Forbidden',
    methods: {
        goBack() {
            if (this.$router) {
                this.$router.go(-1); // Navigate to the previous route
            } else {
                window.history.back(); // Fallback in case router is not available
            }
        }
    }
};
</script>

<style scoped>
/* Additional styles can be added here if needed */
</style>
