<template>
  <div class="flex items-center">
    <span :class="indicatorClass"></span>
    <slot />
  </div>
</template>

<script lang="ts">
export default {
  name: "StateIndicator",

  props: {
    color: {
      type: String,
      required: true,
    },
  },

  computed: {
    indicatorClass() {
      return {
        "inline-block h-3 w-3 rounded-full mr-2": true,
        "bg-green-500": this.color === "green",
        "bg-yellow-500": this.color === "yellow",
        "bg-red-500": this.color === "red",
      };
    },
  },
};
</script>
