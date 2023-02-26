import { defineStore } from "pinia";

interface Relationships {
  owner?: {
    data: {
      type: string;
      id: number;
    };
  };
  roomate?: {
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
    colocation_id: number | null;
  };
  relationships: Relationships;
}

interface AuthState {
  authenticated: boolean;
  user: User;
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
        colocation_id: null,
      },
      relationships: {},
    },
  }),

  getters: {
    isAuthenticated(): boolean {
      return this.authenticated;
    },
    getUser(): User {
      return this.user;
    },
    getColocationId(): number | null {
      return this.user.attributes.colocation_id;
    },
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
          colocation_id: null,
        },
        relationships: {},
      };
    },
  },
});
