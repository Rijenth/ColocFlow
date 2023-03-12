import { defineStore } from "pinia";
import axios from "@/services/axios";

interface User {
  type: string;
  id: number;
  attributes: {
    lastname: string;
    firstname: string;
    username: string;
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

export const useRoommateStore = defineStore("roommate", {
  state: (): Roommates => ({
    data: [],
  }),

  actions: {
    async fetchRoomates(colocation_id: number) {
      try {
        const response = await axios.get(
          `api/colocations/${colocation_id}/roommates`
        );

        if (response.status === 200) {
          this.data = response.data.data;
        }
      } catch (error) {
        console.log(error);
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
