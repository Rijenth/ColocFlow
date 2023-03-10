import { defineStore } from "pinia";

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

export const useColocationStore = defineStore("colocation", {
  state: (): Colocation => ({
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
  }),

  getters: {
    // ne devrais pas fonctionner ainsi
    getColocation(): Colocation {
      return this;
    },
    getAttributes(): Colocation["data"]["attributes"] {
      return this.data.attributes;
    },
  },

  actions: {
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
