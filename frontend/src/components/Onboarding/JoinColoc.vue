<template>
  <div class="w-80 border-l-2">
    <h2 class="text-lg font-bold mb-2 text-center">Rejoindre une colocation</h2>
    <div class="text-s m-4 ml-6 align-middle text-left">
      <p>
        Pour rejoindre une colocation existante, vous devez nous fournir le code
        confidentiel unique de cette colocation. Si vous ne connaissez pas ce
        code, veuillez contacter le propriétaire de la colocation.
      </p>

      <hr class="my-6" />
    </div>
    <div class="flex w-full flex-col items-center align-middle my-10">
      <input
        type="text"
        class="w-1/2 h-10 p-4 border-2 border-gray-900 rounded-lg text-black"
        placeholder="Code secret"
      />
      <button
        class="w-1/2 border h-10 bg-gray-900 text-white rounded-lg mt-4 hover:bg-gray-600"
      >
        Rejoindre
      </button>
    </div>
  </div>
</template>

<script lang="ts">
export default {
  name: "JoinColoc",
};
</script>
