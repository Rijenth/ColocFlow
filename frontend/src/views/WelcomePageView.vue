<template>
  <div
    class="flex flex-col md:flex-row m-4 bg-gray-900 text-white rounded-xl shadow-xl"
  >
    <div class="p-4 mt-4 border-b-2 md:border-none">
      <JoinColoc class="" />
    </div>
    <div class="p-4 mt-4">
      <StartColoc class="md:border-l-2" />
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
