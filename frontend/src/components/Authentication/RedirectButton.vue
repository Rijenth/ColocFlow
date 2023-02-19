<template>
  <button
    class="bg-blue-800 mr-2 text-white p-2 px-3 rounded hover:bg-blue-400"
    @click="redirect"
  >
    {{ text }}
  </button>
</template>

<script lang="ts">
export default {
  name: "RedirectButton",

  methods: {
    redirect() {
      if (this.url !== null) {
        this.$router.push(this.url);
      }
    },
  },

  props: {
    text: {
      type: String,
      required: true,
    },
    url: {
      type: String,
      default: null,
    },
  },
};
</script>
