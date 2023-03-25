import { defineStore } from "pinia";
import type { User } from "@/stores/useAuthStore";
import axios from "@/services/axios";

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
