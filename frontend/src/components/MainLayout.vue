<template>
  <div class="flex flex-col md:flex-row h-screen bg-gray-900">
    <div class="md:w-1/2">
      <div class="flex flex-col items-center justify-center h-full">
        <h1 class="text-white text-4xl font-bold">ColocFlow</h1>
        <p class="text-white text-xl mt-2">
          Gérez votre coloc en toute simplicité
        </p>
        <img class="p-8" src="../assets/background.jpg" alt="ColocImage" />
      </div>
    </div>

    <div class="w-screen bg-gray-900 md:bg-gray-100 md:w-2/3">
      <div class="flex flex-col items-center justify-center h-full">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
export default {
  name: "LeftBar",
};
</script>
