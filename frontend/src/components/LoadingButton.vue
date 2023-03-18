<template>
  <button
    class="bg-gray-800 my-2 text-white p-2 px-3 rounded hover:bg-blue-800 text-center"
    :disabled="isLoading"
  >
    <PulseLoader color="white" v-if="isLoading" size="10px" />

    <p v-else>
      {{ text }}
    </p>
  </button>
</template>

<script lang="ts">
import PulseLoader from "vue-spinner/src/PulseLoader.vue";

export default {
  name: "LoadingButton",

  components: {
    PulseLoader,
  },

  props: {
    text: {
      type: String,
      required: true,
    },
    isLoading: {
      type: Boolean,
      default: false,
    },
  },
};
</script>
