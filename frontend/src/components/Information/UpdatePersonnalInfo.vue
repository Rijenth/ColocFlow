<template>
  <div class="bg-gray-700 dashboard-overview-card">
    <h2 class="text-lg text-center font-semibold">
      Mise à jour de l'utilisateur
    </h2>
  </div>

  <div class="flex flex-col md:flex-row">
    <UserInfo :flash="flash" :userStore="storeAuth" />
    <UserPassword :flash="flash" :userStore="storeAuth" />
  </div>

  <LoadingButton
    class="bg-red-600 hover:bg-red-900 font-bold"
    @click="deleteUser"
    :is-loading="loading"
    :text="'Supprimer l\'utilisateur'"
  />
</template>

<script lang="ts">
import { useAuthStore } from "@/stores/useAuthStore";
import UserInfo from "@/components/Information/UpdatePersonnalInfo/UserInfo.vue";
import UserPassword from "@/components/Information/UpdatePersonnalInfo/UserPassword.vue";
import { useSwal } from "@/composables/useSwal";
import LoadingButton from "@/components/LoadingButton.vue";
import type { AxiosResponse } from "axios";
import { useDestroyStore } from "@/composables/useDestroyStore";
import { useLogout } from "@/composables/useLogout";

export default {
  name: "UpdatePersonnalInfo",

  components: {
    LoadingButton,
    UserInfo,
    UserPassword,
  },

  setup() {
    const authStore = useAuthStore();
    const { destroyStore } = useDestroyStore();
    const { flash } = useSwal();
    const { logout } = useLogout();

    return {
      authStore,
      destroyStore,
      flash,
      logout,
    };
  },

  data() {
    return {
      loading: false,
    };
  },

  methods: {
    async deleteUser() {
      const response = window.confirm(
        "Êtes-vous sûr de vouloir supprimer votre compte ?"
      );

      if (!response) {
        return;
      }

      this.loading = true;

      try {
        const response = await this.storeAuth.deleteUser();

        if (response.status === 204) {
          this.flash("Succès", "L'utilisateur a bien été supprimé", "success");

          this.destroyStore();

          this.$router.push({ name: "home" });
        }
      } catch (error) {
        const e = error as Error & { response: AxiosResponse | undefined };

        if (e.response !== undefined) {
          this.flash(e.response.statusText, e.response.data.message, "error");
        } else {
          this.flash("Une erreur est survenue", e.message, "error");
        }
      }

      this.loading = false;
    },
  },

  computed: {
    storeAuth() {
      return this.authStore;
    },
  },
};
</script>
