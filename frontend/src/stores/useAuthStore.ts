import { defineStore } from "pinia";

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
    isAuthenticated: (state: AuthState): boolean => state.authenticated,
    getColocationId: (state: AuthState): number | undefined =>
      state.user.relationships.roommate?.data.id,
    getUser: (state: AuthState): User => state.user,
    isRoommate: (state: AuthState): boolean =>
      state.user.relationships.roommate !== undefined,
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
