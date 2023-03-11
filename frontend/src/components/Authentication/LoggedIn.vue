<template>
  <div
    class="w-120 mb-6 p-6 bg-gray-900 text-white border rounded-lg shadow-xl"
  >
    <h2 class="text-lg font-bold mb-2">Bienvenue sur ColocFlow</h2>
    <p class="text-s mb-4">Votre logiciel de gestion de colocation</p>
    <p class="text-s mb-4">Bonjour {{ username }} !</p>
    <p class="text-s mb-4">
      À ce jour, le site propose les fonctionnalités suivantes :
    </p>
    <ul class="text-s mb-4">
      <li>-> Création de compte</li>
      <li>-> Création de colocation</li>
      <li>-> Ajout de colocataire</li>
    </ul>
    <p>De nouvelles fonctionnalités sont en cours de développement :</p>
    <ul class="text-s mb-4">
      <li>-> Création de tâche</li>
      <li>-> Création de dépense</li>
      <li>-> Création de facture</li>
      <li>-> Création de budget</li>
      <li>-> Création de calendrier</li>
    </ul>
    <LoadingButton
      class="w-full"
      @click.prevent="updateAuthenticationState"
      :is-loading="loading"
      text="Deconnexion"
    />
  </div>
</template>

<script lang="ts">
import { useAuthStore } from "@/stores/useAuthStore";
import LoadingButton from "@/components/LoadingButton.vue";
import { useLogout } from "@/composables/useLogout";

export default {
  name: "AuthView",

  components: {
    LoadingButton,
  },

  data() {
    return {
      username: "" as string,
      loading: false,
    };
  },

  setup() {
    const authStore = useAuthStore();
    const { logout } = useLogout();

    return {
      authStore,
      logout,
    };
  },

  methods: {
    async updateAuthenticationState() {
      this.ToggleLoading();

      await this.logout();

      this.$emit("isLoggedIn", false);
    },
    ToggleLoading() {
      this.loading = !this.loading;
    },
  },

  mounted() {
    this.username = this.authStore.getUser.attributes.username;
  },
};
</script>
