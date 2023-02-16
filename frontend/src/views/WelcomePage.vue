<template>
  <div class="flex h-screen items-center justify-center">
    <div class="flex h-3/5 p-4 bg-gray-900 text-white rounded-xl shadow-xl">
      <CreateColoc />
      <JoinColoc />
    </div>
  </div>
</template>

<script lang="ts">
import CreateColoc from "@/components/Onboarding/CreateColoc.vue";
import JoinColoc from "@/components/Onboarding/JoinColoc.vue";

export default {
  name: "WelcomePage",
  components: {
    CreateColoc,
    JoinColoc,
  },
};
</script>
