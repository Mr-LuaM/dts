import { defineStore } from 'pinia';
import axios from 'axios';
import router from '@/router';
import {jwtDecode} from 'jwt-decode';

export const useUserStore = defineStore('user', {
  persist: {
    enabled: true,
    strategies: [
      {
        key: 'my_user_store',
        storage: localStorage, // or sessionStorage
      },
    ],
  }, // Assuming you're using some form of persistence plugin for Pinia
  state: () => ({
    token: null,
    tokenExpiration: null, // Store the expiration timestamp
    userDetails: {},
  }),
  getters: {
    isAuthenticated(state) {
      // Check if the token exists and has not expired
      return state.token && new Date() < new Date(state.tokenExpiration);
    },
    role: (state) => state.userDetails?.role,
  },
  actions: {
    login(token) {
      this.token = token;
      const decoded = jwtDecode(token);
      if (decoded.exp) {
        this.tokenExpiration = new Date(decoded.exp * 1000).toISOString();
      }
      this.userDetails.role = decoded.role;
       this.fetchUserDetails(); // Assuming role is included in your token
       this.redirectBasedOnRole();
    },
    async fetchUserDetails() {
      if (!this.token || !this.isAuthenticated) {
        console.error('Authentication token not available or expired.');
        this.logout(); // Automatically log out if the token is invalid or expired
        return;
      }
      try {
        const response = await axios.get('/auth/user', {
          headers: { Authorization: `Bearer ${this.token}` },
        });
        this.userDetails = response.data;
      } catch (error) {
        console.error('Failed to fetch user details:', error);
        this.logout();
      }
    },
    async redirectBasedOnRole() {
      // Your redirect logic based on roles
      if (this.role === 'dwcc.dts.admin') {
        router.push({ name: 'adminDashboard' });
      } else if (this.role === 'dwcc.dts.user') {
        router.push({ name: 'userDashboard' });
      } else {
        console.error("Unexpected user role:", this.role);
        router.push({ name: 'login' }); // or some error page
      }
    },
    logout() {
      this.token = null;
      this.tokenExpiration = null; // Clear token expiration on logout
      this.userDetails = {};
      router.push({ name: 'login' });
    },
    session_logout() {
      this.token = null;
      this.tokenExpiration = null; // Clear token expiration on logout
      this.userDetails = {};
      router.push({ name: 'login', query: { reason: 'tokenExpired' } }); // Add the reason parameter
    },
  },
});
