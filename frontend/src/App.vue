<script setup lang="ts">
import { RouterLink, RouterView } from "vue-router";
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
            class="px-3 py-2 text-white hover:bg-gray-800 rounded mr-3"
            to="/"
            >Home</RouterLink
          >
          <RouterLink
            class="px-3 py-2 text-white hover:bg-gray-800 rounded mr-3"
            to="/dashboard"
            >Dashboard</RouterLink
          >
          <RouterLink
            class="px-3 py-2 text-white hover:bg-gray-800 rounded"
            to="/about"
            >About</RouterLink
          >
        </li>
      </ul>
    </nav>
  </header>

  <RouterView />
</template>

<style></style>
