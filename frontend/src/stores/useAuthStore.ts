import { defineStore } from "pinia";

interface relationships {
  colocation?: {
    data: {
      type: string;
      id: number;
    };
  };
}
interface User {
  type: string;
  id: number;
  attributes: {
    lastname: string;
    firstname: string;
    username: string;
  };
  relationships: relationships;
}

interface AuthState {
  authenticated: boolean;
  user: User;
  token: string;
}

export const useAuthStore = defineStore("auth", {
  state: (): AuthState => ({
    authenticated: false,
    user: {
      type: "",
      id: 0,
      attributes: {
        lastname: "",
        firstname: "",
        username: "",
      },
      relationships: {},
    },
    token: "",
  }),

  getters: {
    isAuthenticated(): boolean {
      return this.authenticated;
    },
    getUser(): User {
      return this.user;
    },
    getToken(): string {
      return this.token;
    }
  },

  actions: {
    login() {
      this.authenticated = true;
    },
    logout() {
      this.authenticated = false;
    },
    setUser(user: User) {
      this.user = user;
    },
    unsetUser() {
      this.user = {
        type: "",
        id: 0,
        attributes: {
          lastname: "",
          firstname: "",
          username: "",
        },
        relationships: {},
      };
    },
    setToken(token: string) {
      this.token = token;
    },
  },
});
