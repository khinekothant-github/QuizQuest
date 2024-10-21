<template>
    <Navbar></Navbar>
    <section class="bg-gray-50 dark:bg-gray-900 h-screen flex justify-center overflow-y-auto">
      <div class="gap-8 items-center md:grid md:grid-cols-2 xl:gap-16 sm:py-16 lg:px-6">
        <img class="w-full dark:hidden" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/cta/cta-dashboard-mockup.svg" alt="Quiz App image">
        <img class="w-full hidden dark:block" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/cta/cta-dashboard-mockup-dark.svg" alt="Quiz App image">
        <div class="mt-4 md:mt-0">
          <h1 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">
            Welcome to the Quiz Quest!
          </h1>
          <p class="mb-6 text-lg font-light text-gray-500 dark:text-gray-400">
            Test your knowledge and have fun! This app offers a variety of quizzes across different subjects. Whether you're a trivia master or just looking to learn something new, there's something for everyone!
          </p>
          <p class="mb-6 text-lg font-light text-gray-500 dark:text-gray-400">
            Join our community of quiz enthusiasts, track your progress, compete with friends, and earn badges as you complete quizzes!
          </p>
          <p class="mb-6 text-lg font-light text-gray-500 dark:text-gray-400">
            Ready to dive in? Click the button below to start your quiz journey!
          </p>
          <div class="flex space-x-4">
            <router-link
                :to="isAuthenticated ? '/quiz' : '/login'"
                class="inline-flex items-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:focus:ring-primary-900">
              Start Quiz
              <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
              </svg>
            </router-link>
          </div>
        </div>
      </div>
    </section>
  </template>

  <script>
import Navbar from '../components/Navbar.vue';

  export default {
    name: 'Home',
    components:{
        Navbar
    },
    data() {
      return {
        isAuthenticated: false, // This should be set based on your authentication logic
      };
    },
    mounted() {
      const token = localStorage.getItem('token');
      this.isAuthenticated = !!token; // Check if the token exists to determine authentication
    },
  };
</script>
  <style scoped>
  /* Add any additional styles here */
  </style>
