import axios from 'axios';

const API_URL = 'http://127.0.0.1:8000/api/';

class AuthService {
    constructor() {
        this.setupInterceptors();
    }

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

    async register(user) {
        return await axios.post(API_URL + 'register', user);
    }

    async login(user) {
        return await axios.post(API_URL + 'login', user);
    }

    async logout() {
        return await axios.post(API_URL + 'logout', {}, { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } });
    }

    async checkPermission(permission) {
        try {
            return await axios.get(`${API_URL}permissions/check`, {
                headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
                params: { permission }
            });
        } catch (error) {
            throw error; // Rethrow other errors
        }
    }

    async getUser() {
        const token = localStorage.getItem('token');
        return await axios.get(`${API_URL}user`, {
            headers: { Authorization: `Bearer ${token}` }
        });
    }
}

export default new AuthService();
