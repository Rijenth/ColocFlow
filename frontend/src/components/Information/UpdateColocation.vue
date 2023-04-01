<template>
  <div class="bg-gray-700 dashboard-overview-card">
    <h2 class="text-lg text-center font-semibold">
      Mise à jour de la colocation
    </h2>
  </div>

  <div class="flex flex-col md:flex-row">
    <ColocationInfo />
    <ColocationAccessKey />
  </div>

  <div v-if="isOwner" class="flex flex-col md:flex-row">
    <ColocationRoommates />
  </div>

  <LoadingButton
    class="bg-red-600 hover:bg-red-900 font-bold"
    @click="deleteColocation"
    :is-loading="loading"
    :text="'Supprimer la colocation'"
    v-if="isOwner"
  />

  <LoadingButton
    class="bg-blue-800 hover:bg-blue-900 font-bold"
    @click="quitColocation"
    :is-loading="loading"
    :text="'Quitter la colocation'"
    v-else
  />
</template>

<script lang="ts">
import LoadingButton from "@/components/LoadingButton.vue";
import { useLogout } from "@/composables/useLogout";
import { useSwal } from "@/composables/useSwal";
import ColocationInfo from "@/components/Information/UpdateColocation/ColocationInfo.vue";
import ColocationAccessKey from "./UpdateColocation/ColocationAccessKey.vue";
import ColocationRoommates from "./UpdateColocation/ColocationRoommates.vue";
import type { AxiosResponse } from "axios";
import { useAuthStore } from "@/stores/useAuthStore";
import { useColocationStore } from "@/stores/useColocationStore";
import { useColocationChargeStore } from "@/stores/useColocationChargeStore";
import { useRoommateStore } from "@/stores/useRoommateStore";

export default {
  name: "UpdateColocation",

  components: {
    ColocationAccessKey,
    ColocationInfo,
    ColocationRoommates,
    LoadingButton,
  },

  setup() {
    const authStore = useAuthStore();
    const colocationStore = useColocationStore();
    const colocationChargeStore = useColocationChargeStore();
    const { logout } = useLogout();
    const roommateStore = useRoommateStore();
    const { flash } = useSwal();

    return {
      authStore,
      colocationStore,
      colocationChargeStore,
      logout,
      roommateStore,
      flash,
    };
  },

  data() {
    return {
      loading: false as boolean,
    };
  },

  methods: {
    toggleLoading() {
      if (this.loading === true) {
        this.loading = false;
      } else {
        this.loading = true;
      }
    },
    async deleteColocation() {
      const confirmDeletion = window.confirm(
        "Voulez-vous vraiment supprimer la colocation ?"
      );

      if (confirmDeletion) {
        this.toggleLoading();

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

          this.toggleLoading();

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
    async quitColocation() {
      const confirmDeletion = window.confirm(
        "Voulez-vous vraiment quitter la colocation ?"
      );

      if (confirmDeletion) {
        this.toggleLoading();

        try {
          const userId = [this.authStore.getUser.id];

          const response = await this.colocationStore.removeRoommates(userId);

          if (response.status === 204) {
            this.flash(
              "Opération réussie",
              "Vous avez bien quitté la colocation",
              "success"
            );

            this.logout();
          }
        } catch (error) {
          const err = error as Error & { response: AxiosResponse | undefined };

          this.toggleLoading();

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
  },

  computed: {
    isOwner() {
      const user = this.authStore.getUser;

      return this.colocationStore.checkOwnership(user.id);
    },
    isRoommate() {
      return this.authStore.isRoommate;
    },
  },
};
</script>
