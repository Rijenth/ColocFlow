<template>
  <NavbarComponent />

  <MainLayout>
    <RouterView />
  </MainLayout>
</template>

<script lang="ts">
import { RouterView } from "vue-router";
import MainLayout from "./components/MainLayout.vue";
import NavbarComponent from "./components/NavbarComponent.vue";

export default {
  name: "App",

  components: {
    RouterView,
    MainLayout,
    NavbarComponent,
  },
};
</script>
