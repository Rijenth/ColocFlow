import { defineStore } from "pinia";

/* interface User {
    id: number;
    name: string;
    email: string;
    password: string;
} */

interface AuthState {
  authenticated: boolean;
}

export const useAuthStore = defineStore("auth", {
  state: (): AuthState => ({
    authenticated: false,
  }),

  getters: {
    isAuthenticated(): boolean {
      return this.authenticated;
    },
  },

  actions: {
    login() {
      this.authenticated = true;
    },
    logout() {
      this.authenticated = false;
    },
  },
});
