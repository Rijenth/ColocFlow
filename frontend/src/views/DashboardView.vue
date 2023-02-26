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

  <!-- This is a card -->
  <div
    class="w-120 mb-6 p-6 bg-gray-700 text-white border rounded-lg shadow-xl"
  >
    <h2 class="text-lg font-bold mb-2">Bienvenue sur ColocFlow</h2>
    <p class="text-s mb-4">Votre logiciel de gestion de colocation</p>
  </div>
</template>

<script lang="ts">
export default {
  name: "DashboardView",

  components: {},

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
