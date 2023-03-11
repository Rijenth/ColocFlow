<template>
  <div
    class="flex flex-col md:flex-row md:items-center p-4 md:m-4 bg-gray-900 text-white md:rounded-xl md:shadow-xl"
  >
    <div class="border rounded md:border-none p-4">
      <JoinColoc />
    </div>
    <div class="border rounded md:border-none p-4 mt-4">
      <StartColoc class="md:md:border-l-2" />
    </div>
  </div>
</template>

<script lang="ts">
import StartColoc from "@/components/Onboarding/StartColoc.vue";
import JoinColoc from "@/components/Onboarding/JoinColoc.vue";

export default {
  name: "WelcomePage",
  components: {
    StartColoc,
    JoinColoc,
  },
};
</script>
