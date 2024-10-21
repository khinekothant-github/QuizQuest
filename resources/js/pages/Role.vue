<template>
    <div>
        <h1>Role Management</h1>
        <form @submit.prevent="createRole">
            <input v-model="roleName" placeholder="Role Name" required />
            <button type="submit">Create Role</button>
        </form>
        <h2>Existing Roles</h2>
        <ul>
            <li v-for="role in roles" :key="role.id">{{ role.name }}</li>
        </ul>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            roleName: '',
            roles: [],
        };
    },
    methods: {
        async fetchRoles() {
            const response = await axios.get('http://127.0.0.1:8000/api/roles');
            this.roles = response.data;
        },
        async createRole() {
            await axios.post('http://127.0.0.1:8000/api/roles', { name: this.roleName });
            this.roleName = '';
            this.fetchRoles();
        },
    },
    mounted() {
        this.fetchRoles();
    },
};
</script>
