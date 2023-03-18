<template>
  <div
    class="bg-gray-900 w-xs md:w-full max-w-xs mx-auto p-4 mb-4 text-white border rounded-lg shadow-xl md:border-none"
  >
    <h2 class="text-sm text-center font-bold mb-4">Attribution de charges</h2>
    <form @submit.prevent="updateChargeUserRelationship" @keydown.space.prevent>
      <div class="block mb-4">
        <label for="userId" class="block">Selection du colocataire :</label>
        <select
          id="userId"
          class="text-sm w-full px-2 py-1 mt-2 border rounded text-black"
          v-model="userId"
        >
          <option :value="0" disabled selected>
            Selectionner un colocataire
          </option>
          <option
            v-for="roommate in roommates"
            :key="roommate.id"
            :value="roommate.id"
          >
            {{ userLastname(roommate.attributes.lastname) }}
            {{ userFirstname(roommate.attributes.firstname) }}
          </option>
        </select>
      </div>

      <div class="block mb-4">
        <label for="chargeId" class="block">Selection de la charge :</label>
        <select
          id="chargeId"
          class="text-sm w-full px-2 py-1 mt-2 border rounded text-black"
          v-model="chargeId"
          @change="updateComponentDataAmount($event.target.value)"
        >
          <option :value="0" disabled selected>Selectionner une charge</option>
          <option v-for="charge in charges" :key="charge.id" :value="charge.id">
            {{ $t("colocation.charges." + charge.attributes.name) }}
          </option>
        </select>
      </div>

      <div class="mb-4 flex flex-col">
        <label for="amount" class="mb-2">Montant (€ ou %) :</label>
        <div class="flex">
          <input
            class="text-sm w-3/4 px-2 py-1 rounded text-black text-right mr-2"
            :disabled="!chargeId"
            id="amount"
            @keypress="allowOnlyPositiveNumbersWithMaxTwoDecimals($event)"
            type="number"
            step="0.01"
            v-model="amount"
          />
          <select
            id="percentage"
            class="text-sm w-1/4 px-2 py-1 rounded text-black"
            :disabled="!chargeId"
            @change="updateComponentDataAmount($event.target.value, true)"
          >
            <option disabled selected>0%</option>
            <option value="10">10%</option>
            <option value="20">20%</option>
            <option value="30">30%</option>
            <option value="40">40%</option>
            <option value="50">50%</option>
            <option value="60">60%</option>
            <option value="70">70%</option>
            <option value="80">80%</option>
            <option value="90">90%</option>
            <option value="100">100%</option>
          </select>
        </div>
      </div>

      <div class="flex justify-center">
        <LoadingButton
          class="w-full mt-4 text-sm border"
          :isLoading="loading"
          :text="'Attribuer'"
        />
      </div>
    </form>
  </div>
</template>

<script lang="ts">
import LoadingButton from "@/components/LoadingButton.vue";
import { useSwal } from "@/composables/useSwal";

interface Data {
  amount: number;
  chargeId: number;
  loading: boolean;
  userId: number;
}

export default {
  name: "ChargesAttribution",

  components: {
    LoadingButton,
  },

  data() {
    return {
      amount: 0,
      chargeId: 0,
      userId: 0,
      loading: false,
    } as Data;
  },

  setup() {
    const { flash } = useSwal();

    return {
      flash,
    };
  },

  props: {
    storeRoommates: {
      type: Object,
      required: true,
    },
    storeCharges: {
      type: Object,
      required: true,
    },
  },

  computed: {
    charges() {
      return this.storeCharges.getColocationCharges;
    },
    roommates() {
      return this.storeRoommates.getRoommates;
    },
    userFirstname() {
      return (firstname: string) => {
        return (
          firstname.charAt(0).toUpperCase() + firstname.slice(1).toLowerCase()
        );
      };
    },
    userLastname() {
      return (lastname: string) => {
        return lastname.toUpperCase();
      };
    },
  },

  methods: {
    allowOnlyPositiveNumbersWithMaxTwoDecimals($event: KeyboardEvent) {
      const input = ($event.target as HTMLInputElement).value + $event.key;

      if (
        isNaN(input) ||
        (input.includes(".") && input.split(".")[1].length > 2)
      ) {
        event.preventDefault();
      }
    },
    async updateChargeUserRelationship() {
      if (this.userId === 0 || this.chargeId === 0 || this.amount === "") {
        this.flash(
          "Formulaire incomplet",
          "Veuillez remplir tous les champs du formulaire",
          "warning"
        );

        return;
      }

      if (this.amount === 0) {
        this.flash(
          "Erreur de saisie",
          "Vous ne pouvez pas attribuer 0 à une charge payée par un colocataire",
          "warning"
        );

        return;
      }

      const userAffectedAmount = this.storeCharges.getUserColocationCharge(
        this.userId,
        this.chargeId
      );

      if (userAffectedAmount) {
        if (this.amount - userAffectedAmount.attributes.amount === 0) {
          this.flash(
            "Montant déjà affecté",
            "Vous avez déjà affecté ce montant à ce colocataire pour cette charge",
            "warning"
          );

          return;
        }
      }

      this.loading = !this.loading;

      const body = {
        data: {
          type: "charges",
          id: this.chargeId,
          relationships: {
            users: {
              data: {
                type: "users",
                id: this.userId,
                attributes: {
                  amount: this.amount,
                },
              },
            },
          },
        },
      };

      try {
        const response = await this.storeCharges.updateChargeUserRelationship(
          JSON.stringify(body),
          this.chargeId
        );

        if (response && response.status === 200) {
          this.loading = !this.loading;

          this.flash(
            "Attribution de charge",
            "La charge a bien été attribuée au colocataire",
            "success"
          );
        }
      } catch (error) {
        this.loading = !this.loading;

        if (error.response && error.response.status === 422) {
          this.flash(
            error.response.statusText + " !",
            error.response.data.message,
            "error"
          );

          return;
        }

        this.flash(
          "Attribution de charge impossible",
          "Une erreur est survenue lors de l'attribution de la charge",
          "error"
        );
      }
    },
    updateComponentDataAmount(value: string, isPercentage: boolean = false) {
      const number = parseInt(value);

      if (this.chargeId !== 0) {
        const charge = this.charges.find(
          (charge) => charge.id === this.chargeId
        );

        if (charge) {
          let amount = charge.attributes.amount;

          if (isPercentage === true) {
            amount = parseFloat(((amount * number) / 100).toFixed(2));
          } else {
            const select = document.getElementById(
              "percentage"
            ) as HTMLSelectElement;

            select.selectedIndex = 0;
          }

          this.amount = amount;
        }
      }
    },
  },
};
</script>
