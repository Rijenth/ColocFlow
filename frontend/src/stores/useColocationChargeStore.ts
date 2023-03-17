import { defineStore } from "pinia";
import axios from "@/services/axios";

interface Users {
  id: number;
  type: string;
  attributes: {
    amount: number;
  };
}
interface Charge {
  type: string;
  id: number;
  attributes: {
    amount: number;
    amount_affected: number;
    key: string;
    name: string;
  };
  relationships: {
    colocation: {
      data: {
        type: string;
        id: number;
      };
    };
    users?: {
      data: Users[];
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
    getChargesByUser: (state) => (userId: number) => {
      const charges = state.data.filter((charge) => {
        if (charge.relationships.users) {
          return charge.relationships.users.data.find(
            (user) => user.id === userId
          );
        }
      });

      return charges ? charges : [];
    },
    getUserChargeAffectedAmount:
      (state) => (userId: number, chargeId: number) => {
        const charge = state.data.find((charge) => charge.id === chargeId);

        if (charge) {
          const user = charge.relationships.users?.data.find(
            (user) => user.id === userId
          );

          if (user) {
            return user.attributes.amount;
          }
        }

        return 0;
      },
    getColocationCharges: (state) => state.data,
    getColocationCharge: (state) => (id: number) => {
      return state.data.find((charge) => charge.id === id);
    },
    getRentAmount: (state) => {
      const rent = state.data.find(
        (charge) => charge.attributes.name === "Rent"
      );

      return rent ? rent.attributes.amount : 0;
    },
    getUserTotalAffectedAmount: (state) => (userId: number) => {
      let total = 0;

      state.data.forEach((charge) => {
        if (charge.relationships.users) {
          const userCharge = charge.relationships.users.data.find(
            (user) => user.id === userId
          );

          if (userCharge) {
            total += userCharge.attributes.amount;
          }
        }
      });

      return total;
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
    getTotalAmountAffected: (state) => {
      let total = 0;

      state.data.forEach((charge) => {
        total += charge.attributes.amount_affected;
      });

      return total;
    },
  },
});
