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
export interface User {
  type: string;
  id: number;
  attributes: {
    lastname: string;
    firstname: string;
    email: string;
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
    (sessionStorage.getItem("authStore") as string)
      ? JSON.parse(sessionStorage.getItem("authStore") as string)
      : {
          authenticated: false,
          user: {
            type: "",
            id: 0,
            attributes: {
              lastname: "",
              firstname: "",
              email: "",
              colocation_id: null,
            },
            relationships: {},
          },
        },

  getters: {
    isAuthenticated: (state: AuthState): boolean => state.authenticated,
    getColocationId: (state: AuthState): number | undefined =>
      (state.user.relationships.roommate?.data.id as number) ||
      (state.user.relationships.owner?.data.id as number),
    getUser: (state: AuthState): User => state.user,
    isRoommate: (state: AuthState): boolean =>
      state.user.relationships.roommate !== undefined,
  },

  actions: {
    async login(email: string, password: string) {
      const login = await axios.post("/login", {
        email: email,
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
          email: "",
          colocation_id: null,
        },
        relationships: {},
      };
    },
  },
});
