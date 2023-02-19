<script setup lang="ts">
import { RouterLink, RouterView } from "vue-router";
import { useLogout } from "../src/composables/useLogout";

const { logout } = useLogout();
</script>

<template>
  <header class="bg-gray-900 p-3">
    <nav>
      <ul class="flex justify-between items-center">
        <li>
          <h1 class="text-white text-xl font-bold">ColocFlow</h1>
        </li>
        <li class="flex">
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
            >Deconnexion</RouterLink
          >
        </li>
      </ul>
    </nav>
  </header>

  <RouterView />
</template>
