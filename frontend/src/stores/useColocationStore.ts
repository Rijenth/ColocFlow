import { defineStore } from "pinia";
import axios from "@/services/axios";

interface Relationships {
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
}

interface Colocation {
  data: {
    type: string;
    id: number;
    attributes: {
      name: string;
      monthly_rent: number;
      max_roommates: number;
    };
    relationships: Relationships;
  };
  isDefined: boolean;
}

export const useColocationStore = defineStore("colocationStore", {
  state: (): Colocation =>
    sessionStorage.getItem("colocationStore")
      ? JSON.parse(sessionStorage.getItem("colocationStore") as string)
      : {
          data: {
            type: "",
            id: 0,
            attributes: {
              name: "",
              monthly_rent: 0,
              max_roommates: 0,
            },
            relationships: {},
          },
          isDefined: false,
        },

  getters: {
    getAttributes: (state: Colocation) => state.data.attributes,
    getColocationId: (state: Colocation) => state.data.id,
    colocationIsDefined: (state: Colocation) => state.isDefined,
  },

  actions: {
    async fetchColocation(colocationId: number) {
      const colocation = await axios.get(`api/colocations/${colocationId}`);

      if (colocation.status === 200) {
        this.setColocation(colocation.data);
      }
    },
    setColocation(colocation: Colocation) {
      this.data = colocation.data;
      this.isDefined = true;
    },
    unSetColocation() {
      this.data = {
        type: "",
        id: 0,
        attributes: {
          name: "",
          monthly_rent: 0,
          max_roommates: 0,
        },
        relationships: {},
      };
      this.isDefined = false;
    },
  },
});
