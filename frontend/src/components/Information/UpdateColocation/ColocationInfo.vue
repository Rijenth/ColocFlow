<template>
  <div class="bg-gray-900 dashboard-overview-card">
    <h2 class="text-sm underline text-center font-bold mb-4">
      Information de la colocation
    </h2>
    <div class="flex flex-col mb-5">
      <label for="name" class="mb-4">
        Nom de la colocation :
        <p v-if="updateColocation === false">
          {{ colocation.data.attributes.name }}
        </p>
        <input
          v-else
          class="text-sm w-full px-2 py-1 rounded text-black text-left"
          type="text"
          v-model="updatedColocation.attributes.name"
        />
      </label>

      <label for="monthly_rent" class="mb-4">
        Loyer mensuel :
        <p v-if="updateColocation === false">
          {{ colocation.data.attributes.monthly_rent }} €
        </p>
        <input
          v-else
          class="text-sm w-full px-2 py-1 rounded text-black text-left"
          @keypress="allowOnlyPositiveNumbersWithMaxTwoDecimals($event)"
          type="number"
          step="0.01"
          v-model="updatedColocation.attributes.monthly_rent"
        />
      </label>

      <label for="max_roommates">
        Nombre de maximum de colocataires :
        <p v-if="updateColocation === false">
          {{ colocation.data.attributes.max_roommates }} personnes
        </p>
        <input
          v-else
          class="text-sm w-full px-2 py-1 rounded text-black text-left"
          @keypress="positiveNumberOnly($event)"
          type="number"
          v-model="updatedColocation.attributes.max_roommates"
        />
      </label>

      <p
        class="text-xs mt-3 bg-yellow-600 rounded p-2"
        v-if="userIsOwner && !userIsRoommate"
      >
        Vous êtes le propriétaire de cette colocation mais vous n'êtes pas
        inscrit en tant que colocataire.
      </p>

      <label
        class="text-xs text-white mt-1"
        v-if="userIsOwner && !userIsRoommate && updateColocation"
      >
        <input v-model="registerAsRoommate" type="checkbox" class="mr-2" />
        M'enregistrer en tant que colocataire
      </label>
    </div>

    <div class="flex flex-row justify-around">
      <LoadingButton
        @click="setInfoInputsData"
        class="main-button"
        :disabled="loading"
        :text="'Modifier les informations'"
        v-if="updateColocation === false"
      />
      <LoadingButton
        class="sub-button"
        @click="updateColocationData"
        :is-loading="loading"
        :text="'Envoyer'"
        v-if="updateColocation === true"
      />
      <LoadingButton
        class="sub-button"
        @click="updateColocation = false"
        :disabled="loading"
        :text="'Annuler'"
        v-if="updateColocation === true"
      />
    </div>

    <div class="flex flex-row justify-center">
      <LoadingButton
        v-if="updateColocation === true && userIsOwner"
        class="bg-red-600 hover:bg-red-900 font-bold text-sm"
        @click="deleteColocation"
        :is-loading="deleting"
        :text="'Supprimer la colocation'"
      />
    </div>
  </div>
</template>

<script lang="ts">
import type { AxiosResponse } from "axios";
import { useSwal } from "@/composables/useSwal";
import {
  useColocationStore,
  type Colocation,
} from "@/stores/useColocationStore";
import { useAuthStore } from "@/stores/useAuthStore";
import { useColocationChargeStore } from "@/stores/useColocationChargeStore";
import LoadingButton from "@/components/LoadingButton.vue";

export default {
  name: "ColocationInfo",

  setup() {
    const authStore = useAuthStore();
    const colocationStore = useColocationStore();
    const chargeStore = useColocationChargeStore();
    const { flash } = useSwal();

    return {
      authStore,
      chargeStore,
      colocationStore,
      flash,
    };
  },

  components: {
    LoadingButton,
  },

  computed: {
    colocation() {
      return this.colocationStore.getColocation;
    },
    roommatesCount(): number {
      return this.colocationStore.getNumberOfRoommates;
    },
    userIsColocationOwner(): boolean {
      const colocationOwner = this.colocationStore.getOwnerRelationship;
      const userIsColocationOwner =
        colocationOwner?.id === this.authStore.getUser.id;
      return userIsColocationOwner;
    },
    userIsOwner(): boolean {
      const userIsOwner = this.authStore.isOwner;
      return userIsOwner;
    },
    userIsRoommate(): boolean {
      const userIsRoommate = this.authStore.isRoommate;
      return userIsRoommate;
    },
  },

  data() {
    return {
      deleting: false,
      loading: false,
      registerAsRoommate: false,
      updateColocation: false,
      updatedColocation: {
        attributes: {
          name: "",
          monthly_rent: 0,
          max_roommates: 0,
        },
      },
    };
  },

  methods: {
    allowOnlyPositiveNumbersWithMaxTwoDecimals($event: KeyboardEvent) {
      const input = ($event.target as HTMLInputElement).value + $event.key;

      if (
        isNaN(Number(input)) ||
        (input.includes(".") && input.split(".")[1].length > 2)
      ) {
        $event.preventDefault();
      }
    },
    async deleteColocation() {
      const confirmDeletion = window.confirm(
        "Voulez-vous vraiment supprimer la colocation ?"
      );

      if (confirmDeletion) {
        this.deleting = true;

        try {
          const response = await this.colocationStore.deleteColocation();

          if (response.status === 204) {
            this.flash(
              "Colocation supprimée",
              "La colocation a bien été supprimée",
              "success"
            );

            this.logout();
          }
        } catch (error) {
          const err = error as Error & { response: AxiosResponse | undefined };

          this.deleting = false;

          if (
            err.response !== undefined &&
            err.response.statusText &&
            err.response.data.message !== undefined
          ) {
            return this.flash(
              err.response.statusText,
              err.response.data.message,
              "error"
            );
          }

          return this.flash("Erreur", err.message, "error");
        }
      }
    },
    positiveNumberOnly($event: KeyboardEvent) {
      const input = ($event.target as HTMLInputElement).value + $event.key;

      if (isNaN(Number(input)) || input.includes(".") || input.includes(",")) {
        $event.preventDefault();
      }
    },
    setInfoInputsData() {
      this.updateColocation = true;
      this.updatedColocation.attributes.name =
        this.colocation.data.attributes.name;
      this.updatedColocation.attributes.monthly_rent =
        this.colocation.data.attributes.monthly_rent;
      this.updatedColocation.attributes.max_roommates =
        this.colocation.data.attributes.max_roommates;
    },
    toggleLoading() {
      if (this.loading === false) {
        this.loading = true;
      } else {
        this.loading = false;
      }
    },
    async updateColocationData() {
      const originalColocation: Colocation["data"]["attributes"] =
        this.colocation.data.attributes;
      const roommateCount: number = this.roommatesCount;
      const updatedColoc: Colocation["data"]["attributes"] =
        this.updatedColocation.attributes;

      if (
        originalColocation.name === updatedColoc.name &&
        originalColocation.monthly_rent === updatedColoc.monthly_rent &&
        originalColocation.max_roommates === updatedColoc.max_roommates &&
        this.registerAsRoommate === false
      ) {
        return this.flash(
          "Données identiques",
          "Aucune modification détectée",
          "info"
        );
      }

      if (
        updatedColoc.name === "" ||
        Number(updatedColoc.monthly_rent) === 0 ||
        Number(updatedColoc.max_roommates) === 0
      ) {
        return this.flash(
          "Formulaire invalide",
          "Le nom, le loyer et le nombre maximum de colocataires sont obligatoires",
          "warning"
        );
      }

      if (updatedColoc.max_roommates < roommateCount) {
        return this.flash(
          "Nombre de colocataires invalide",
          "Le nombre maximum de colocataires ne peut pas être inférieur au nombre de colocataires actuel",
          "warning"
        );
      }

      this.toggleLoading();

      try {
        const data = this.updatedColocation.attributes;

        let relationships = {};

        if (this.registerAsRoommate === true) {
          relationships = {
            roommates: {
              data: {
                id: this.authStore.getUser.id,
                type: "users",
              },
            },
          };
        }

        const response = await this.colocationStore.updateColocation(
          data,
          {
            include: ["charges"],
          },
          relationships
        );

        if (
          response.included.charges !== undefined &&
          response.included.charges.data.length > 0
        ) {
          const updatedCharges = response.included.charges.data;

          this.chargeStore.setColocationCharges(updatedCharges);
        }
      } catch (error) {
        const e = error as Error & { response: AxiosResponse | undefined };

        this.toggleLoading();

        if (
          e.response !== undefined &&
          e.response.statusText &&
          e.response.data.message !== undefined
        ) {
          return this.flash(
            e.response.statusText,
            e.response.data.message,
            "error"
          );
        }

        return this.flash(
          "Une erreur est survenue",
          "Une erreur inconnue est survenue, merci de contacter l'administrateur",
          "error"
        );
      }

      if (this.registerAsRoommate === true) {
        const relationships = {
          data: {
            type: "colocations",
            id: this.colocation.data.id,
          },
        };
        this.authStore.setRoommateRelationship(relationships);
      }

      this.flash(
        "Mise à jour réussie",
        "Les informations de la colocation ont été mises à jour",
        "success"
      );

      this.toggleLoading();
      this.updateColocation = false;
      this.registerAsRoommate = false;
    },
  },

  props: {
    logout: {
      type: Function,
      required: true,
    },
  },
};
</script>
