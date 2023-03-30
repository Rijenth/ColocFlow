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
            },
            relationships: {},
          },
        },

  getters: {
    getColocationId: (state: AuthState): number | undefined =>
      (state.user.relationships.roommate?.data.id as number) ||
      (state.user.relationships.owner?.data.id as number),
    getUser: (state: AuthState): User => state.user,
    isAuthenticated: (state: AuthState): boolean => state.authenticated,
    isRoommate: (state: AuthState): boolean =>
      state.user.relationships.roommate !== undefined,
    isOwner: (state: AuthState): boolean =>
      state.user.relationships.owner !== undefined,
  },

  actions: {
    async deleteUser() {
      const response = await axios.delete("/api/users/" + this.user.id);

      return response;
    },
    async login(email: string, password: string) {
      const login = await axios.post("/login", {
        email: email,
        password: password,
      });

      if (login.status === 200) {
        this.authenticated = true;
        this.unsetUser();
        this.setUser(login.data.user);
      }
    },
    logout() {
      this.authenticated = false;
    },
    async updateUser(attributes: {
      lastname?: string;
      firstname?: string;
      email?: string;
      password?: string;
      old_password?: string;
    }) {
      const response = await axios.patch("/api/users/" + this.user.id, {
        data: {
          type: "users",
          id: this.user.id,
          attributes: attributes,
        },
      });

      if (response.status === 200) {
        this.setUserAttributes(response.data.data.attributes);
      }

      return response;
    },
    setRoommateRelationship(roommate: Relationships["roommate"]) {
      this.user.relationships.roommate = roommate;
    },
    setUser(user: User) {
      this.user = user;
    },
    setUserAttributes(attributes: User["attributes"]) {
      this.user.attributes = attributes;
    },
    unsetUser() {
      this.user = {
        type: "",
        id: 0,
        attributes: {
          lastname: "",
          firstname: "",
          email: "",
        },
        relationships: {},
      };
    },
  },
});
