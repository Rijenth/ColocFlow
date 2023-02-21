<template>
  <header class="bg-gray-900 p-3">
    <nav class="flex justify-end">
      <ul class="flex">
        <li class="mr-6">
          <RouterLink
            class="p-2 text-white hover:bg-gray-800 rounded mr-1"
            to="/"
            >Accueil</RouterLink
          >
          <RouterLink
            class="p-2 text-white hover:bg-gray-800 rounded mr-1"
            to="/dashboard"
            >Dashboard</RouterLink
          >
          <RouterLink
            class="p-2 text-white hover:bg-gray-800 rounded mr-1"
            to="/about"
            >À propos</RouterLink
          >
          <RouterLink
            class="p-2 text-white hover:bg-gray-800 rounded mr-1"
            @click.prevent="logout"
            to="/"
            v-if="isAuthenticated"
            >Deconnexion</RouterLink
          >
        </li>
      </ul>
    </nav>
  </header>

  <div class="flex flex-col md:flex-row h-screen">
    <div class="bg-gray-900 w-full md:w-1/2">
      <div class="flex flex-col items-center justify-center h-full">
        <h1 class="text-white text-4xl font-bold">ColocFlow</h1>
        <p class="text-white text-xl mt-2">
          Gérez votre coloc en toute simplicité
        </p>
        <img class="p-8" src="./assets/background.jpg" alt="ColocImage" />
      </div>
    </div>

    <div class="bg-gray-100 w-full md:w-1/2">
      <div class="flex flex-col items-center justify-center h-full">
        <div class="w-1/2">
          <RouterView />
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { RouterLink, RouterView } from "vue-router";
import { useLogout } from "../src/composables/useLogout";
import { useAuthStore } from "../src/stores/useAuthStore";

export default {
  name: "App",

  components: {
    RouterLink,
    RouterView,
  },

  setup() {
    const { logout } = useLogout();
    const authStore = useAuthStore();

    return {
      logout,
      authStore,
    };
  },

  computed: {
    isAuthenticated() {
      return this.authStore.isAuthenticated;
    },
  },
};
</script>
