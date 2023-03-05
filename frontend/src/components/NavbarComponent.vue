<template>
  <header class="bg-gray-900 p-3">
    <nav class="flex flex-col">
      <div class="flex md:hidden ml-auto">
        <button
          @click="isMenuOpen = !isMenuOpen"
          class="text-white hover:text-gray-300 focus:outline-none"
        >
          <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24">
            <path
              v-if="isMenuOpen"
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M18 6H6v2h12V6Zm0 5H6v2h12V11Zm0 5H6v2h12v-2Z"
            />
            <path
              v-else
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M4 6h16v2H4V6Zm0 5h16v2H4v-2Zm0 5h16v2H4v-2Z"
            />
          </svg>
        </button>
      </div>
      <div
        :class="{ hidden: !isMenuOpen }"
        class="border w-full text-center md:border-none md:flex md:items-center"
      >
        <ul class="md:flex md:items-center">
          <li class="border-b md:border-none">
            <RouterLink class="router-link" to="/">Accueil</RouterLink>
          </li>
          <li class="border-b md:border-none">
            <RouterLink class="router-link" to="/dashboard"
              >Dashboard</RouterLink
            >
          </li>
          <li class="border-b md:border-none">
            <RouterLink class="router-link" to="/about">À propos</RouterLink>
          </li>
          <li
            v-if="isAuthenticated && routeIsNotHome"
            class="border-b md:border-none"
          >
            <button class="router-link w-full" @click.prevent="logout">
              Deconnexion
            </button>
          </li>
        </ul>
      </div>
    </nav>
  </header>
</template>

<script lang="ts">
import { RouterLink } from "vue-router";
import { useLogout } from "../composables/useLogout";
import { useAuthStore } from "../stores/useAuthStore";

export default {
  name: "NavbarComponent",

  components: {
    RouterLink,
  },

  data() {
    return {
      isMenuOpen: false,
    };
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
    routeIsNotHome() {
      return this.$route.name !== "home";
    },
  },
};
</script>
