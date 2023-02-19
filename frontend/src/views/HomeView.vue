<template>
  <AuthView v-if="isAuthenticated === false" />
  <LoggedIn v-else />
</template>

<script lang="ts">
import AuthView from "../views/AuthView.vue";
import LoggedIn from "@/components/Authentication/LoggedIn.vue";
import { useAuthStore } from "@/stores/useAuthStore";
import { computed } from "vue";

export default {
  name: "HomeView",

  components: {
    AuthView,
    LoggedIn,
  },

  setup() {
    const authStore = useAuthStore();
    const isAuthenticated = computed(() => authStore.isAuthenticated);

    return {
      isAuthenticated,
    };
  },

  // Méthode avec ref et watchEffect
  /*   setup() {
    const authStore = useAuthStore();
    const isAuthenticated = ref(authStore.isAuthenticated);

    watchEffect(() => {
      isAuthenticated.value = authStore.isAuthenticated;
    });

    return {
      isAuthenticated,
    };
  },  */
};
</script>
