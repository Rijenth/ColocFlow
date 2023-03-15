import { defineStore } from "pinia";
import axios from "@/services/axios";

interface Charge {
  type: string;
  id: number;
  attributes: {
    name: string;
    amount: number;
    key: string;
  };
  relationships: {
    colocation: {
      data: {
        type: string;
        id: number;
      };
    };
  };
}

interface ColocationCharges {
  data: Charge[];
}

export const useColocationChargeStore = defineStore("colocationChargeStore", {
  state: (): ColocationCharges =>
    sessionStorage.getItem("colocationChargeStore")
      ? JSON.parse(sessionStorage.getItem("colocationChargeStore")!)
      : {
          data: [],
        },

  actions: {
    async fetchColocationCharges(colocation_id: number) {
      const colocationCharges = await axios.get(
        `api/colocations/${colocation_id}/charges`
      );

      if (colocationCharges.status === 200) {
        this.data = colocationCharges.data.data;
      }
    },
    unSetColocationCharges() {
      this.data = [];
    },
  },

  getters: {
    getColocationCharges: (state) => state.data,
    getRentAmount: (state) => {
      const rent = state.data.find(
        (charge) => charge.attributes.name === "Rent"
      );
      return rent ? rent.attributes.amount : 0;
    },
    getTotalChargesAmount: (state) => {
      let total = 0;
      state.data.forEach((charge) => {
        if (charge.attributes.name !== "Rent") {
          total += charge.attributes.amount;
        }
      });
      return total;
    },
  },
});