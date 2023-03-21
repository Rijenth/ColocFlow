import { defineStore } from "pinia";
import axios from "@/services/axios";

interface User {
  type: string;
  id: number;
  attributes: {
    lastname: string;
    firstname: string;
    email: string;
    colocation_id?: number;
  };
  relationships: {
    owner?: {
      data: {
        type: string;
        id: number;
      };
    };
    roommates?: {
      data: {
        type: string;
        id: number;
      };
    };
  };
}

interface Roommates {
  data: User[] | [];
}

export const useRoommateStore = defineStore("roommateStore", {
  state: (): Roommates =>
    (sessionStorage.getItem("roommateStore") as string)
      ? JSON.parse(sessionStorage.getItem("roommateStore") as string)
      : {
          data: [],
        },

  actions: {
    async fetchRoomates(colocation_id: number) {
      const response = await axios.get(
        `api/colocations/${colocation_id}/roommates`
      );

      if (response.status === 200) {
        this.data = response.data.data;
      }
    },
    unSetRoomates() {
      this.data = [];
    },
  },

  getters: {
    getRoommates: (state) => state.data,
  },
});
