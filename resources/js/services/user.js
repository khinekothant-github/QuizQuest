import axios from 'axios';

const API_URL = 'http://127.0.0.1:8000/api/';

class UserService {

    setupInterceptors() {
        axios.interceptors.response.use(
            response => response,
            error => {
                if (error.response && error.response.status === 403) {
                    alert('You do not have permission to do this action.');
                }
                return Promise.reject(error);
            }
        );
    }
    constructor() {
        this.token = localStorage.getItem('token');
        this.setAuthToken();
    }

    setAuthToken() {
        if (this.token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        } else {
            delete axios.defaults.headers.common['Authorization'];
        }
    }

    async fetchUsers() {
        this.token = localStorage.getItem('token');
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        const response = await axios.get(`${API_URL}users`);
        return response.data;
    }

    async createUser(userData) {
        const response = await axios.post(`${API_URL}users`, userData);
        return response.data;
    }

    async updateUser(id, userData) {
        const response = await axios.put(`${API_URL}users/${id}`, userData);
        return response.data;
    }

    async assignRole(userId, roleId) {
        const response = await axios.put(`${API_URL}users/${userId}/assign-role`, { role_id: roleId });
        return response.data;
    }

    async deleteUser(id) {
        await axios.delete(`${API_URL}users/${id}`);
    }
}

export default new UserService();
