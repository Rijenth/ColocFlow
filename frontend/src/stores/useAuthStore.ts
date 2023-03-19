import { defineStore } from "pinia";
import axios from "@/services/axios";

interface Relationships {
  owner?: {
    data: {
      type: string;
      id: number;
    };
  };
  roommate?: {
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

export const useAuthStore = defineStore("authStore", {
  state: (): AuthState =>
    sessionStorage.getItem("authStore")
      ? JSON.parse(sessionStorage.getItem("authStore") as string)
      : {
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
        },

  getters: {
    isAuthenticated: (state: AuthState): boolean => state.authenticated,
    getColocationId: (state: AuthState): number | undefined =>
      state.user.relationships.roommate?.data.id ||
      state.user.relationships.owner?.data.id,
    getUser: (state: AuthState): User => state.user,
    isRoommate: (state: AuthState): boolean =>
      state.user.relationships.roommate !== undefined,
  },

  actions: {
    async login(username: string, password: string) {
      const login = await axios.post("/login", {
        username: username,
        password: password,
      });

      if (login.status === 200) {
        this.authenticated = true;
        this.setUser(login.data.user);
      }
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
