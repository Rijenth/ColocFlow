import { defineStore } from "pinia";

interface Relationships {
  owner?: {
    data: {
      type: string;
      id: number;
    };
  };
  roomates?: {
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
      max_roomates: number;
    };
    relationships: Relationships;
  };
}

export const useColocationStore = defineStore("colocation", {
  state: (): Colocation => ({
    data: {
      type: "",
      id: 0,
      attributes: {
        name: "",
        monthly_rent: 0,
        max_roomates: 0,
      },
      relationships: {},
    },
  }),

  getters: {
    getColocation(): Colocation {
      return this;
    },
  },

  actions: {
    setColocation(colocation: Colocation) {
      this.data = colocation.data;
    },
    unSetColocation() {
      this.data = {
        type: "",
        id: 0,
        attributes: {
          name: "",
          monthly_rent: 0,
          max_roomates: 0,
        },
        relationships: {},
      };
    },
  },
});