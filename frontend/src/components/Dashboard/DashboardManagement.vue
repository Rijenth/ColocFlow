<template>
  <div
    class="bg-gray-700 w-80 md:w-1/2 p-4 mb-4 text-white border rounded-lg shadow-xl md:border-none"
  >
    <h2 class="text-lg text-center font-semibold">Gestion des comptes</h2>
  </div>
  <div class="md:grid md:grid-cols-2 md:gap-4">
    <div class="md:col-span-1">
      <ManagementOverview />
    </div>
    <div class="md:col-span-1">
      <ChargesAttribution />
    </div>
  </div>
</template>

<script lang="ts">
import ManagementOverview from "@/components/Dashboard/DashboardComponents/ManagementOverview.vue";
import ChargesAttribution from "@/components/Dashboard/DashboardComponents/ChargesAttribution.vue";

export default {
  name: "DashboardManagement",

  components: {
    ManagementOverview,
    ChargesAttribution,
  },
};
</script>
