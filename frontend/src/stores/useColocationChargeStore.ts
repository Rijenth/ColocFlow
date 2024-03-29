import { defineStore } from "pinia";
import axios from "@/services/axios";

interface Users {
  id: number;
  type: string;
  attributes: {
    amount: number;
  };
}
export interface Charge {
  type: string;
  id: number;
  attributes: {
    amount: number;
    amount_affected: number;
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
    (sessionStorage.getItem("colocationChargeStore") as string)
      ? JSON.parse(sessionStorage.getItem("colocationChargeStore") as string)
      : {
          data: [],
        },

  actions: {
    async createCharge(
      colocation_id: number,
      charge_attributes: { name: string; amount: number }
    ) {
      const response = await axios.post(
        `api/colocations/${colocation_id}/charges`,
        {
          data: {
            type: "charges",
            attributes: charge_attributes,
          },
        }
      );

      if (response.status === 200) {
        this.data.push(response.data.data);
      }

      return response;
    },
    async deleteCharge(charge_id: number) {
      const response = await axios.delete(`api/charges/${charge_id}`);

      if (response.status === 204) {
        this.data = this.data.filter((charge) => charge.id !== charge_id);
      }

      return response;
    },
    async deleteChargeUserRelationship(body: string, user_id: number) {
      const formattedBody = {
        data: JSON.parse(body),
      };

      const response = await axios.delete(
        `api/users/${user_id}/relationships/charges`,
        formattedBody
      );

      return response;
    },
    async fetchColocationCharges(colocation_id: number) {
      const colocationCharges = await axios.get(
        `api/colocations/${colocation_id}/charges`
      );

      if (colocationCharges.status === 200) {
        this.data = colocationCharges.data.data;
      }
    },
    setColocationCharges(charges: Charge[]) {
      this.data = charges;
    },
    unSetColocationCharges() {
      this.data = [];
    },
    async updateColocationCharge(charge: Charge) {
      const response = await axios.patch(`api/charges/${charge.id}`, {
        data: charge,
      });

      if (response.status === 200) {
        this.data = this.data.map((charge) => {
          if (charge.id === response.data.data.id) {
            return response.data.data;
          }
          return charge;
        });
      }

      return response;
    },
    async updateChargeUserRelationship(body: string, charge_id: number) {
      const response = await axios.patch(
        `api/charges/${charge_id}`,
        JSON.parse(body)
      );

      if (response.status === 200) {
        const updatedCharge = response.data.data;

        this.data = this.data.map((charge) => {
          if (charge.id === updatedCharge.id) {
            return updatedCharge;
          }
          return charge;
        });
      }

      return response;
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

      return charges;
    },
    getUserChargeAffectedAmount:
      (state) => (userId: number, chargeId: number) => {
        const charge = state.data.find((charge) => charge.id === chargeId);

        if (charge) {
          const user = charge.relationships.users?.data.find(
            (user) => user.id === userId
          );

          if (user) {
            return parseFloat(user.attributes.amount.toFixed(2));
          }
        }

        return 0;
      },
    getColocationCharges: (state) => state.data,
    getColocationCharge: (state) => (id: number) => {
      return state.data.find((charge) => charge.id === id);
    },
    getUserColocationCharge: (state) => (userId: number, chargeId: number) => {
      const charge = state.data.find((charge) => charge.id === chargeId);

      if (charge) {
        const user = charge.relationships.users?.data.find(
          (user) => user.id === userId
        );

        if (user) {
          return user;
        }
      }
    },
    getRentAmount: (state) => {
      const rent = state.data.find(
        (charge) => charge.attributes.name === "Rent"
      );

      return rent ? parseFloat(rent.attributes.amount.toFixed(2)) : 0;
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

      return parseFloat(total.toFixed(2));
    },
    getTotalChargesAmount: (state) => {
      let total = 0;

      state.data.forEach((charge) => {
        if (charge.attributes.name !== "Rent") {
          total += charge.attributes.amount;
        }
      });

      return parseFloat(total.toFixed(2));
    },
    getTotalAmountAffected: (state) => {
      let total = 0;

      state.data.forEach((charge) => {
        total += charge.attributes.amount_affected;
      });

      return parseFloat(total.toFixed(2));
    },
  },
});
