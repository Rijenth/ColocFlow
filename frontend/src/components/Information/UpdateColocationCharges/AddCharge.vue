<template>
  <div class="bg-gray-900 dashboard-overview-card">
    <h2 class="text-sm underline text-center font-bold mb-4">
      Ajouter une charge à la colocation
    </h2>
    <div class="flex flex-col pt-3">
      <label for="chargeName" class="mb-4 flex justify-between">
        Nom de la charge :
        <input
          id="chargeName"
          class="text-sm w-1/2 px-2 py-1 rounded text-black text-left"
          type="text"
          v-model="charge.name"
        />
      </label>

      <label for="chargeAmount" class="flex justify-between">
        Montant (€) :
        <input
          id="chargeAmount"
          class="text-sm w-1/2 px-2 py-1 rounded text-black text-left"
          type="number"
          step="0.01"
          v-on:keypress="isNumber($event)"
          v-model="charge.amount"
        />
      </label>
    </div>

    <div class="flex flex-row justify-center mt-4">
      <LoadingButton
        class="sub-button"
        :is-loading="loading"
        :text="'Ajouter'"
        @click="AddChargeToColocation"
      />
    </div>
  </div>
</template>

<script lang="ts">
import LoadingButton from "@/components/LoadingButton.vue";
import { useSwal } from "@/composables/useSwal";
import type { AxiosResponse } from "axios";

export default {
  name: "AddCharge",

  components: {
    LoadingButton,
  },

  setup() {
    const { flash } = useSwal();
    return { flash };
  },

  data() {
    return {
      charge: {
        name: "",
        amount: 0,
      },
      loading: false,
    };
  },

  methods: {
    isNumber(event: KeyboardEvent) {
      if (event.key !== ".") {
        if (isNaN(Number(event.key))) {
          event.preventDefault();
        }
      }
    },
    async AddChargeToColocation() {
      if (this.charge.name === "") {
        return this.flash(
          "Formulaire invalide",
          "Le formulaire contient des données non valides",
          "warning"
        );
      }

      if (this.charge.amount < 0) {
        return this.flash(
          "Montant invalide",
          "Le montant doit être supérieur ou égal à 0",
          "warning"
        );
      }

      this.loading = true;

      try {
        const colocation_id = this.storeColocation.getColocationId;

        const response = await this.storeColocationCharge.createCharge(
          colocation_id,
          this.charge
        );

        if (response.status === 200) {
          this.flash(
            "Charge ajoutée",
            "La charge a bien été ajoutée à la colocation",
            "success"
          );
          this.charge.name = "";
          this.charge.amount = 0;
          this.loading = false;
        }
      } catch (error) {
        const e = error as Error & { response: AxiosResponse | undefined };

        this.loading = false;

        if (e.response !== undefined) {
          return this.flash(
            e.response.statusText,
            e.response.data.message,
            "error"
          );
        }

        return this.flash("Une erreur est survenue", e.message, "error");
      }
    },
  },

  props: {
    storeColocation: {
      type: Object,
      required: true,
    },
    storeColocationCharge: {
      type: Object,
      required: true,
    },
  },
};
</script>
