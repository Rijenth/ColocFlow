<template>
  <div class="flex flex-col md:flex-row h-screen bg-gray-900">
    <div class="md:w-1/3 flex flex-col items-center">
      <h1 class="text-white text-4xl font-bold">ColocFlow</h1>
      <p class="text-white text-xl mt-2">
        Gérez votre coloc en toute simplicité
      </p>
      <img
        class="p-8 w-2/3 md:w-full"
        src="../assets/background.jpg"
        alt="Drawing with 5 persons talking to each others"
      />
    </div>

    <div class="md:w-2/3 bg-blue-50">
      <div
        class="flex flex-col items-center justify-center h-full mt-4 md:mt-0"
      >
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
