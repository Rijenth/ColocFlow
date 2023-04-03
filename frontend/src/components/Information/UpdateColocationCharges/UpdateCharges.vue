<template>
  <div class="bg-gray-900 dashboard-overview-card">
    <h2 class="text-sm underline text-center font-bold mb-4">
      Mettre à jour une charge de la colocation
    </h2>

    <div class="flex flex-col">
      <select
        class="text-sm w-full px-2 py-1 mt-2 border rounded text-black mb-4"
        @change="updateComponentData($event)"
      >
        <option :value="0" disabled selected>Choisir une charge</option>
        <option v-for="charge in charges" :key="charge.id" :value="charge.id">
          {{ charge.attributes.name }}
        </option>
      </select>

      <label for="chargeName" class="mb-4 flex justify-between">
        Nom de la charge :
        <input
          id="chargeName"
          class="text-sm w-1/2 px-2 py-1 rounded text-black text-left"
          type="text"
          v-model="updatedCharge.attributes.name"
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
          v-model="updatedCharge.attributes.amount"
        />
      </label>
    </div>

    <div class="flex flex-row justify-center mt-2">
      <LoadingButton
        class="sub-button"
        :is-loading="updating"
        :text="'Modifier'"
        @click="updateCharge"
      />
      <LoadingButton
        class="sub-button"
        :is-loading="deleting"
        :text="'Supprimer'"
        @click="deleteCharge"
      />
    </div>
  </div>
</template>

<script lang="ts">
import LoadingButton from "@/components/LoadingButton.vue";
import type { Charge } from "@/stores/useColocationChargeStore";
import { useSwal } from "@/composables/useSwal";
import type { AxiosResponse } from "axios";

export default {
  name: "UpdateCharges",

  components: {
    LoadingButton,
  },

  setup() {
    const { flash } = useSwal();
    return { flash };
  },

  data() {
    return {
      updatedCharge: {
        id: 0,
        type: "charges",
        attributes: {
          name: "",
          amount: 0,
        },
      },
      updating: false,
      deleting: false,
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
    async deleteCharge() {
      if (this.updatedCharge.id === 0) {
        return this.flash(
          "Formulaire invalide",
          "Veuillez choisir une charge",
          "warning"
        );
      }

      const response = window.confirm(
        "Êtes-vous sûr de vouloir supprimer cette charge ? (Cette action est irréversible)"
      );

      if (response) {
        this.deleting = true;

        try {
          const response = await this.storeCharges.deleteCharge(
            this.updatedCharge.id
          );

          if (response.status === 204) {
            this.deleting = false;

            this.flash(
              "Suppression réussie",
              "La charge a bien été supprimée",
              "success"
            );

            this.updatedCharge = {
              id: 0,
              type: "charges",
              attributes: {
                name: "",
                amount: 0,
              },
            };
          }
        } catch (error) {
          const e = error as Error & { response: AxiosResponse | undefined };

          this.deleting = false;

          if (e.response !== undefined) {
            return this.flash(
              e.response.statusText,
              e.response.data.message,
              "error"
            );
          }

          return this.flash("Une erreur est survenue", e.message, "error");
        }
      }
    },
    async updateCharge() {
      if (this.updatedCharge.id === 0) {
        return this.flash(
          "Formulaire invalide",
          "Veuillez choisir une charge",
          "warning"
        );
      }

      if (this.updatedCharge.attributes.name === "") {
        return this.flash(
          "Formulaire invalide",
          "Le nom de la charge est obligatoire",
          "warning"
        );
      }

      if (this.updatedCharge.attributes.amount === 0) {
        return this.flash(
          "Formulaire invalide",
          "Le montant de la charge est obligatoire",
          "warning"
        );
      }

      const previousCharge = this.charges.find(
        (charge: Charge) => charge.id === this.updatedCharge.id
      );

      if (
        previousCharge.attributes.name === this.updatedCharge.attributes.name &&
        previousCharge.attributes.amount ===
          this.updatedCharge.attributes.amount
      ) {
        return this.flash(
          "Formulaire invalide",
          "Aucune modification n'a été apportée",
          "warning"
        );
      }

      this.updating = true;

      try {
        const response = await this.storeCharges.updateColocationCharge(
          this.updatedCharge
        );

        if (response.status === 200) {
          this.flash(
            "Modification réussie",
            "La charge a bien été modifiée",
            "success"
          );
        }

        this.updating = false;
      } catch (error) {
        const e = error as Error & { response: AxiosResponse | undefined };

        this.updating = false;

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
    updateComponentData(event: Event) {
      const chargeId = Number((event.target as HTMLInputElement).value);

      const charge = this.charges.find(
        (charge: Charge) => charge.id === chargeId
      );

      this.updatedCharge = {
        id: charge.id,
        type: "charges",
        attributes: {
          name: charge.attributes.name,
          amount: charge.attributes.amount,
        },
      };
    },
  },

  props: {
    storeCharges: {
      type: Object,
      required: true,
    },
  },

  computed: {
    charges() {
      return this.storeCharges.getColocationCharges;
    },
  },
};
</script>
