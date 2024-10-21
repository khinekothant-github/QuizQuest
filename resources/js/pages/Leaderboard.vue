<template>
    <Navbar></Navbar>
    <section class="bg-gray-50 dark:bg-gray-900 h-screen flex justify-center overflow-y-auto" style="padding-top: 5%;">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Leaderboard</h2>

            <div class="overflow-x-auto">
              <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                  <tr>
                    <th scope="col" class="py-3 px-6">Rank</th>
                    <th scope="col" class="py-3 px-6">Name</th>
                    <th scope="col" class="py-3 px-6">Email</th>
                    <th scope="col" class="py-3 px-6">Score</th>
                    <th scope="col" class="py-3 px-6">Last Attempt</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="user in paginatedLeaderboard" :key="user.id" :class="{ 'bg-green-100 dark:bg-green-900': user.id === currentUserId }" class="border-b dark:border-gray-700">
                    <td class="py-4 px-6">{{ user.rank }}</td>
                    <td class="py-4 px-6">{{ user.name }}</td>
                    <td class="py-4 px-6">{{ user.email }}</td>
                    <td class="py-4 px-6">{{ user.score }}</td>
                    <td class="py-4 px-6">{{ user.last_attempt }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination Controls -->
            <div class="flex justify-between items-center mt-4">
              <button @click="prevPage" :disabled="currentPage === 1" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-400 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                Previous
              </button>
              <span class="text-gray-700 dark:text-gray-400">Page {{ currentPage }} of {{ totalPages }}</span>
              <button @click="nextPage" :disabled="currentPage === totalPages" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-400 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                Next
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>

  <script>
  import axios from "axios";
  import Navbar from "../components/Navbar.vue";


  const API_URL = 'http://127.0.0.1:8000/api/';

  export default {
    name: "Leaderboard",
    components:{
        Navbar
    },
    data() {
      return {
        leaderboard: [],
        currentUserId: null,
        currentPage: 1,
        perPage: 5, // Show 5 users per page
      };
    },
    computed: {
      totalPages() {
        return Math.ceil(this.leaderboard.length / this.perPage);
      },
      paginatedLeaderboard() {
        const start = (this.currentPage - 1) * this.perPage;
        const end = start + this.perPage;
        return this.leaderboard.slice(start, end);
      }
    },
    methods: {
      async fetchLeaderboard() {
        try {
          const token = localStorage.getItem("token");
          if (!token) {
            throw new Error("No token found in local storage");
          }
          const response = await axios.get(`${API_URL}leaderboard`, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          this.leaderboard = response.data.leaderboard;
          this.currentUserId = response.data.currentUserId;
        } catch (error) {
          console.error("Error fetching leaderboard:", error);
        }
      },
      nextPage() {
        if (this.currentPage < this.totalPages) {
          this.currentPage += 1;
        }
      },
      prevPage() {
        if (this.currentPage > 1) {
          this.currentPage -= 1;
        }
      },
    },
    mounted() {
      this.fetchLeaderboard();
    },
  };
  </script>

  <style scoped>
  .bg-green-100 {
    background-color: #d1fae5;
  }

  .dark .bg-green-100 {
    background-color: #065f46;
  }
  </style>
