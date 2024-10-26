import { createStore } from "vuex";
import AuthService from '../services/auth';
export default createStore({
    state: {
        user: null,
        roleNames: [],
        permissions: [],
    },
    mutations: {
        setUser(state, user) {
            state.user = user;
        },
        setRoleNames(state, roleNames) {
            state.roleNames = roleNames;
        },
        setPermissions(state, permissions) {
            state.permissions = permissions;
        },
    },
    actions: {
        async fetchUserDetails({ commit }) {
            try {
                const response = await AuthService.getUserDetails();
                console.log("User details response:", response); // Add this line
                commit('setUser', response.data.user);
                commit('setRoleNames', response.data.roleNames);
                commit('setPermissions', response.data.permissions);
            } catch (error) {
                console.log("Failed to fetch user details:", error);
            }
        }
    },

    getters: {
        hasPermission: (state) => (permission)=>{


            console.log(state);

            return state.permissions.includes(permission);
        },
    }
});
