    <template>
        <Admin_navbar></Admin_navbar>
        <section class="bg-gray-50 dark:bg-gray-900 h-screen flex items-center justify-center overflow-y-auto">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mx-auto w-4/5">
                <!-- Title -->
                <h1 class="text-2xl tracking-tight font-bold text-gray-900 dark:text-white mb-4">Role Management</h1>

                <!-- Create New Role Button -->
                <button
                    @click="showCreateRoleModal = true"
                    class="bg-blue-600 text-white rounded-lg py-2.5 px-5 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 mb-4"
                >
                    Create New Role
                </button>

                <!-- Existing Roles List -->
                <div class="mb-6">
                    <div class="overflow-y-auto max-h-64">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="py-3 px-6">Role Name</th>
                                    <th scope="col" class="py-3 px-6">Permissions</th>
                                    <th scope="col" class="py-3 px-6">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="role in paginatedRoles" :key="role.id" class="border-b dark:border-gray-700">
                                    <td class="py-4 px-6">{{ role.name }}</td>
                                    <td class="py-4 px-6">
                                        <ul>
                                            <li v-for="permission in role.permissions" :key="permission.id">{{ permission.name }}</li>
                                        </ul>
                                    </td>
                                    <td class="py-4 px-6 flex space-x-4">
                                        <button @click="editRole(role)" class="text-gray-800 hover:text-gray-900 dark:text-white dark:hover:text-gray-300">
                                            <svg class="w-6 h-6 text-blue-500 dark:text-blue-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                            </svg>
                                        </button>
                                        <button @click="deleteRole(role.id)" class="text-gray-800 hover:text-gray-900 dark:text-white dark:hover:text-gray-300">
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

                <!-- Create Role Modal -->
                <div v-if="showCreateRoleModal" class="fixed top-0 left-0 right-0 z-50 w-full h-full flex items-center justify-center bg-gray-900 bg-opacity-50">
                    <div class="relative w-full max-w-md p-4">
                        <div class="bg-white rounded-lg shadow dark:bg-gray-800">
                            <div class="flex justify-between items-start p-4 border-b rounded-t dark:border-gray-700">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Create Role</h3>
                                <button @click="closeCreateRoleModal" class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg p-1.5 ml-auto dark:hover:bg-gray-600 dark:hover:text-white">
                                    ×
                                </button>
                            </div>
                            <div class="p-6 space-y-4">
                                <form @submit.prevent="createRole">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Role Name</label>
                                            <input
                                                v-model="newRoleName"
                                                type="text"
                                                placeholder="Role Name"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Assign Permissions</label>
                                            <div v-for="permission in availablePermissions" :key="permission.id" class="flex items-center mb-2">
                                                <input type="checkbox" :value="permission.id" v-model="selectedPermissions" class="mr-2" />
                                                <span class="text-white">{{ permission.name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-end pt-4">
                                        <button type="submit" class="bg-blue-600 text-white rounded-lg py-2.5 px-5 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
                                            Create Role
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Role Modal -->
                <div v-if="showEditRoleModal" class="fixed top-0 left-0 right-0 z-50 w-full h-full flex items-center justify-center bg-gray-900 bg-opacity-50">
                    <div class="relative w-full max-w-md p-4">
                        <div class="bg-white rounded-lg shadow dark:bg-gray-800">
                            <div class="flex justify-between items-start p-4 border-b rounded-t dark:border-gray-700">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Role</h3>
                                <button @click="closeEditRoleModal" class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg p-1.5 ml-auto dark:hover:bg-gray-600 dark:hover:text-white">
                                    ×
                                </button>
                            </div>
                            <div class="p-6 space-y-4">
                                <form @submit.prevent="updateRole">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Role Name</label>
                                            <input
                                                v-model="editingRole.name"
                                                type="text"
                                                placeholder="Role Name"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Assign Permissions</label>
                                            <div v-for="permission in availablePermissions" :key="permission.id" class="flex items-center mb-2">
                                                <input type="checkbox" :value="permission.id" v-model="editingRole.permissions" class="mr-2" />
                                                <span class="text-white">{{ permission.name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-end pt-4">
                                        <button type="submit" class="bg-green-600 text-white rounded-lg py-2.5 px-5 hover:bg-green-700 focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800">
                                            Update Role
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
    import axios from 'axios';

    export default {
        components: {
            Admin_navbar,
        },
        data() {
            return {
                roles: [],
                availablePermissions: [],
                newRoleName: '',
                selectedPermissions: [],
                editingRole: null,
                showCreateRoleModal: false,
                showEditRoleModal: false,
                currentPage: 1,
                perPage: 5,
            };
        },
        computed: {
            totalPages() {
                return Math.ceil(this.roles.length / this.perPage);
            },
            paginatedRoles() {
                const start = (this.currentPage - 1) * this.perPage;
                const end = start + this.perPage;
                return this.roles.slice(start, end);
            },
        },
        methods: {
            async fetchRoles() {
                const response = await axios.get('http://127.0.0.1:8000/api/roles');
                this.roles = response.data;
            },
            async fetchPermissions() {
                const response = await axios.get('http://127.0.0.1:8000/api/permissions');
                this.availablePermissions = response.data; // Fetch available permissions
            },
            async createRole() {
                await axios.post('http://127.0.0.1:8000/api/roles', {
                    name: this.newRoleName,
                    permissions: this.selectedPermissions,
                });
                this.newRoleName = '';
                this.selectedPermissions = [];
                this.showCreateRoleModal = false;
                this.fetchRoles();
            },
            async editRole(role) {
                this.editingRole = { ...role, permissions: role.permissions.map(p => p.id) };
                this.showEditRoleModal = true;
            },
            async updateRole() {
                await axios.put(`http://127.0.0.1:8000/api/roles/${this.editingRole.id}`, {
                    name: this.editingRole.name,
                    permissions: this.editingRole.permissions,
                });
                this.showEditRoleModal = false;
                this.fetchRoles();
            },
            async deleteRole(roleId) {
                await axios.delete(`http://127.0.0.1:8000/api/roles/${roleId}`);
                this.fetchRoles();
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
            closeCreateRoleModal() {
                this.showCreateRoleModal = false;
                this.newRoleName = '';
                this.selectedPermissions = [];
            },
            closeEditRoleModal() {
                this.showEditRoleModal = false;
                this.editingRole = null;
            },
        },
        mounted() {
            this.fetchRoles();
            this.fetchPermissions(); // Fetch available permissions on mount
        },
    };
    </script>

    <style scoped>
    /* Additional styling if needed */
    </style>

