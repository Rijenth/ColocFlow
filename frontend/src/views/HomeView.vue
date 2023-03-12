<template>
  <AuthView v-if="authenticated === false" />
  <LoggedIn @isLoggedIn="updateComponent" v-else />
</template>

<script lang="ts">
import AuthView from "../views/AuthView.vue";
import LoggedIn from "@/components/Authentication/LoggedIn.vue";
import { useAuthStore } from "@/stores/useAuthStore";

export default {
  name: "HomeView",

  components: {
    AuthView,
    LoggedIn,
  },

  data() {
    return {
      authenticated: false,
    };
  },

  setup() {
    const authStore = useAuthStore();
    const isAuthenticated = authStore.isAuthenticated;

    return {
      isAuthenticated,
    };
  },

  methods: {
    updateComponent(value: boolean) {
      this.authenticated = value;
    },
  },

  mounted() {
    this.authenticated = this.isAuthenticated;
  },
};
</script>
