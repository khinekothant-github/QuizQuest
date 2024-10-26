<template>
  <nav
    class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600"
  >
    <div
      class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4"
    >
      <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
        <span
          class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white"
          >Quiz Quest</span
        >
      </a>
      <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
        <div v-if="isAuthenticated">
          <span
            class="text-gray-900 dark:text-white"
            style="margin-right: 1.5rem"
            >{{ username }}</span
          >
          <button
            type="button"
            class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2"
            @click="logout"
          >
            Log Out
          </button>
        </div>
        <div v-else>
          <button
            type="button"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2"
          >
            Get started
          </button>
        </div>
        <button
          data-collapse-toggle="navbar-sticky"
          type="button"
          class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
          aria-controls="navbar-sticky"
          aria-expanded="false"
        >
          <span class="sr-only">Open main menu</span>
          <svg
            class="w-5 h-5"
            aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 17 14"
          >
            <path
              stroke="currentColor"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M1 1h15M1 7h15M1 13h15"
            />
          </svg>
        </button>
      </div>
      <div
        class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1"
        id="navbar-sticky"
      >
        <ul
          class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700"
        >
          <li>
            <router-link
              to="/admin"
              class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
              aria-current="page"
              >Home</router-link
            >
          </li>
          <li v-if="isAuthenticated && hasPermission('view user')">
            <router-link
              to="/admin/users"
              class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
            >
              User Management
            </router-link>
          </li>
          <li v-if="isAuthenticated && hasPermission('view roles')">
            <router-link
              to="/admin/roles"
              class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
              >Roles Management</router-link
            >
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import AuthService from "../services/auth";
import EventBus from "../eventBus";
import { mapGetters } from "vuex/dist/vuex.cjs.js";

export default {
  data() {
    return {
      isAuthenticated: false,
      username: "",
      hasLeaderboardPermission: false,
      hasRoleManagementPermission: false,
      hasQuizPermission: false,
    };
  },
  computed: {
        ...mapGetters({
            hasPermission:'hasPermission'
        }),
    },
  methods: {
    async checkPermissions() {
      const token = localStorage.getItem("token");
      if (token) {
        this.isAuthenticated = true;
        try {
          const userResponse = await AuthService.getUser(); // Fetch user info
          this.username = userResponse.data.name; // Assuming the user object has a name property
          const quizResponse = await AuthService.checkPermission(
            "view quizzes"
          );
          console.log("Quiz Permission:", quizResponse.data.allowed); // Debug log
        } catch (error) {
          console.error("Error checking permissions:", error);
        }
      }
    },
    async logout() {
      await AuthService.logout();
      localStorage.removeItem("token");
      this.isAuthenticated = false;
      this.username = "";
      this.$router.push({ name: "Admin Login" });
    },
  },
  mounted() {
    this.$store
      .dispatch("fetchUserDetails")
      .then(() => {
        console.log("User details fetched successfully");
      })
      .catch(() => {
        console.log("Failed to fetch user details");
      }); // Fetch available permissions on mount

    this.checkPermissions();
    EventBus.on("userLoggedIn", this.checkPermissions); // Listen for login event
  },
  beforeDestroy() {
    EventBus.off("userLoggedIn", this.checkPermissions); // Clean up the listener
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>
