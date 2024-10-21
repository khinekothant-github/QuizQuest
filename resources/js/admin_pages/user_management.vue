<template>
    <Admin_navbar></Admin_navbar>
    <section class="bg-gray-50 dark:bg-gray-900 h-screen flex items-center justify-center overflow-y-auto ">
      <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mx-auto w-4/5">
        <!-- Title -->
        <h1 class="text-2xl tracking-tight font-bold text-gray-900 dark:text-white mb-4">User Management</h1>

        <!-- Add User Button -->
        <button
          @click="showAddUserModal = true"
          class="bg-blue-600 text-white rounded-lg py-2.5 px-5 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 mb-4"
        >
          Add User
        </button>

        <!-- Add New User Modal -->
        <div v-if="showAddUserModal" id="addUserModal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 w-full h-full flex items-center justify-center bg-gray-900 bg-opacity-50">
          <div class="relative w-full max-w-md p-4">
            <div class="bg-white rounded-lg shadow dark:bg-gray-800">
              <div class="flex justify-between items-start p-4 border-b rounded-t dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Add User</h3>
                <button type="button" @click="closeAddUserModal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                  <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                  </svg>
                </button>
              </div>

              <div class="p-6 space-y-4">
                <form @submit.prevent="addUser">
                  <div class="space-y-4">
                    <div>
                        <p v-if="errorMessage" class="text-red-600">{{ errorMessage }}</p>
                      <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                      <input
                        v-model="newUser.name"
                        type="text"
                        placeholder="Name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required
                      />
                    </div>
                    <div>
                      <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                      <input
                        v-model="newUser.email"
                        type="email"
                        placeholder="Email"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required
                      />
                    </div>
                    <div>
                      <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                      <input
                        v-model="newUser.password"
                        type="password"
                        placeholder="Password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required
                      />
                    </div>
                    <div>
                      <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Confirm Password</label>
                      <input
                        v-model="newUser.password_confirmation"
                        type="password"
                        placeholder="Confirm Password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required
                      />
                    </div>
                  </div>
                  <div class="flex justify-end pt-4">
                    <button
                      type="submit"
                      class="bg-blue-600 text-white rounded-lg py-2.5 px-5 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800"
                    >
                      Add User
                    </button>

                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Existing Users List -->
        <div class="mb-6">
          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
              <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                  <th scope="col" class="py-3 px-6">Name</th>
                  <th scope="col" class="py-3 px-6">Email</th>
                  <th scope="col" class="py-3 px-6">Role Assigned</th> <!-- New column -->
                  <th scope="col" class="py-3 px-6">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="user in paginatedUsers" :key="user.id" class="border-b dark:border-gray-700">
                  <td class="py-4 px-6">{{ user.username }}</td>
                  <td class="py-4 px-6">{{ user.email }}</td>
                  <td class="py-4 px-6">{{ user.role }}</td> <!-- Display role -->
                  <td class="py-4 px-6 flex space-x-4">
                    <button @click="editUser(user)" class="text-gray-800 hover:text-gray-900 dark:text-white dark:hover:text-gray-300">
                      <svg class="w-6 h-6 text-blue-500 dark:text-blue-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                      </svg>
                    </button>
                    <button @click="deleteUser(user.id)" class="text-gray-800 hover:text-gray-900 dark:text-white dark:hover:text-gray-300">
                      <svg class="w-6 h-6 text-red-500 dark:text-red-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                      </svg>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between mt-4">
          <button @click="prevPage" :disabled="currentPage === 1" class="bg-gray-300 text-gray-700 rounded-lg px-4 py-2">Previous</button>
          <span class="text-white">Page {{ currentPage }} of {{ totalPages }}</span>
          <button @click="nextPage" :disabled="currentPage === totalPages" class="bg-gray-300 text-gray-700 rounded-lg px-4 py-2">Next</button>
        </div>

        <!-- Edit User Modal -->
        <div v-if="editingUser" id="editUserModal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 w-full h-full flex items-center justify-center bg-gray-900 bg-opacity-50">
          <div class="relative w-full max-w-md p-4">
            <div class="bg-white rounded-lg shadow dark:bg-gray-800">
              <div class="flex justify-between items-start p-4 border-b rounded-t dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit User</h3>
                <button type="button" @click="closeEditModal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                  <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                  </svg>
                </button>
              </div>
              <div class="p-6 space-y-4">
                <form @submit.prevent="updateUser">
                  <div class="space-y-4">
                    <div>
                        <p v-if="errorMessage" class="text-red-600">{{ errorMessage }}</p>
                      <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                      <input
                        v-model="editingUser.username"
                        type="text"
                        placeholder="Name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required
                      />
                    </div>
                    <div>
                      <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                      <input
                        v-model="editingUser.email"
                        type="email"
                        placeholder="Email"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required
                      />
                    </div>
                    <div>
                      <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                      <select v-model="selectedRole" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option v-for="role in availableRoles" :key="role.id" :value="role.id">{{ role.name }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="flex justify-end pt-4">
                    <button
                      type="submit"
                      class="bg-green-600 text-white rounded-lg py-2.5 px-5 hover:bg-green-700 focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800"
                    >
                      Update User
                    </button>

                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
</template>

<script>
import Admin_navbar from '../components/admin_navbar.vue';
import UserService from '../services/user';
import EventBus from '../eventBus';

export default {
    name: "UserManagement",
    components: {
        Admin_navbar,
    },
    data() {
        return {
            users: [],
            newUser: {
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            },
            editingUser: null,
            selectedRole: null,
            availableRoles: [],
            errorMessage: '',
            showAddUserModal: false,
            currentPage: 1,
            perPage: 5 // Number of users per page
        };
    },
    computed: {
        totalPages() {
            return Math.ceil(this.users.length / this.perPage);
        },
        paginatedUsers() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            return this.users.slice(start, end);
        }
    },
    methods: {
        async fetchUsers() {
            try {
                const response = await UserService.fetchUsers();
                this.users = [...response]; // Ensure reactivity by creating a new array
            } catch (error) {
                this.handleApiError(error);
            }
        },
        async fetchRoles() {
            try {
                this.availableRoles = await UserService.fetchRoles();
            } catch (error) {
                this.handleApiError(error);
            }
        },
        async fetchPermissions() {
            try {
                const permissions = await UserService.getPermissions();
                this.hasQuizPermission = permissions.quiz;
                this.hasLeaderboardPermission = permissions.leaderboard;
                this.hasRoleManagementPermission = permissions.roleManagement;
            } catch (error) {
                this.handleApiError(error);
            }
        },
        async initializeData() {
            await Promise.all([this.fetchUsers(), this.fetchRoles(), this.fetchPermissions()]);
        },
        async addUser() {
            try {
                await UserService.createUser(this.newUser);
                this.newUser = { name: '', email: '', password: '', password_confirmation: '' };
                this.closeAddUserModal();
                await this.fetchUsers(); // Refresh the user list
                EventBus.emit('userAdded');
            } catch (error) {
                this.handleApiError(error);
            }
        },
        async editUser(user) {
            this.editingUser = { ...user };
            this.selectedRole = user.current_user_role.id;
            this.availableRoles = user.available_roles;
            this.errorMessage = '';
        },
        closeEditModal() {
            this.editingUser = null;
            this.selectedRole = null;
        },
        closeAddUserModal() {
            this.showAddUserModal = false;
        },
        async updateUser() {
            if (!this.editingUser) return;
            try {
                await UserService.updateUser(this.editingUser.id, {
                    name: this.editingUser.username,
                    email: this.editingUser.email,
                });
                await UserService.assignRole(this.editingUser.id, { role_id: this.selectedRole });
                this.closeEditModal();
                await this.fetchUsers(); // Refresh the user list
                EventBus.emit('userUpdated');
            } catch (error) {
                this.handleApiError(error);
            }
        },
        async deleteUser(id) {
            try {
                await UserService.deleteUser(id);
                await this.fetchUsers(); // Refresh the user list
                EventBus.emit('userDeleted');
            } catch (error) {
                this.errorMessage = 'Failed to delete user. Please try again.';
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
        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
            }
        },
        handleApiError(error) {
            if (error.response) {
                const message = error.response.data.message;

                // Use the message directly if it exists
                if (message) {
                    this.errorMessage = message;
                } else if (error.response.status === 422) {
                    this.errorMessage = 'Please fill in all required fields correctly.';
                } else if (error.response.status === 409) {
                    this.errorMessage = 'Email already exists. Please use a different email.';
                } else {
                    this.errorMessage = 'An unexpected error occurred. Please try again later.';
                }
            }
        }
    },
    mounted() {

        this.initializeData(); // Fetch all data on mount
    },
    beforeDestroy() {
        EventBus.off('adminUserLoggedIn', this.initializeData);
        EventBus.off('userAdded', this.fetchUsers);
        EventBus.off('userUpdated', this.fetchUsers);
        EventBus.off('userDeleted', this.fetchUsers);
    }
};
</script>


<style scoped>
/* Additional styling if needed */
</style>



