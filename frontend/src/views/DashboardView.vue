<template>
  <div class="bg-gray-900 border mb-4 rounded">
    <button
      class="text-white p-2 px-3 hover:bg-gray-700 hover:rounded-l"
      @click="generalCard"
    >
      Général
    </button>
    <button
      class="border-l text-white p-2 px-3 hover:bg-gray-700"
      @click="repartitionCard"
    >
      Répartition
    </button>
    <button
      class="border-l text-white p-2 px-3 hover:bg-gray-700"
      @click="propositionCard"
    >
      Propositions
    </button>
    <button
      class="border-l text-white p-2 px-3 hover:bg-gray-700 hover:rounded-r"
      @click="incidentReportCard"
    >
      Rapports
    </button>
  </div>

  <DashboardOverview />
</template>

<script lang="ts">
import DashboardOverview from "@/components/Dashboard/DashboardOverview.vue";

export default {
  name: "DashboardView",

  components: {
    DashboardOverview,
  },

  methods: {
    generalCard() {
      console.log("generalCard");
    },
    repartitionCard() {
      console.log("repartitionCard");
    },
    propositionCard() {
      console.log("propositionCard");
    },
    incidentReportCard() {
      console.log("incidentReportCard");
    },
  },
};
</script>
