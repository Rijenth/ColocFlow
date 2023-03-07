<template>
  <div class="bg-gray-700 dashboard-overview-card">
    <h2 class="text-lg text-center font-semibold">
      Bienvenue chez {{ colocation.name }} !
    </h2>
  </div>

  <div class="bg-gray-900 dashboard-overview-card">
    <h2 class="text-lg text-center font-bold mb-4">Etat des lieux :</h2>
    <StateIndicator color="green">
      <p class="text-sm">Règlement du loyer du mois de {{ TodayMonth }}</p>
    </StateIndicator>
  </div>

  <div class="flex flex-col w-full items-center">
    <div class="bg-gray-900 dashboard-overview-card">
      <h2 class="text-lg text-center font-bold mb-4">Suivi des règlements :</h2>
      <ul class="text-sm">
        <li>
          <StateIndicator color="green">
            <p>BATISTA Eric</p>
          </StateIndicator>
        </li>
        <li>
          <StateIndicator color="green">
            <p>HOLLANDE Tom</p>
          </StateIndicator>
        </li>
        <li>
          <StateIndicator color="green">
            <p>YUI Hui</p>
          </StateIndicator>
        </li>
      </ul>
    </div>

    <div class="bg-gray-900 dashboard-overview-card">
      <h2 class="text-lg text-center font-bold mb-4">Suivi des tâches :</h2>
      <ul class="text-sm">
        <li>
          <StateIndicator color="green">
            <p>BATISTA Eric</p>
          </StateIndicator>
        </li>
        <li>
          <StateIndicator color="green">
            <p>HOLLANDE Tom</p>
          </StateIndicator>
        </li>
        <li>
          <StateIndicator color="green">
            <p>YUI Hui</p>
          </StateIndicator>
        </li>
      </ul>
    </div>
  </div>
</template>

<script lang="ts">
import { useColocationStore } from "@/stores/useColocationStore";
import StateIndicator from "@/components/StateIndicator.vue";

export default {
  name: "DashboardOverview",

  components: {
    StateIndicator,
  },

  setup() {
    const colocation = useColocationStore().getAttributes;

    return {
      colocation,
    };
  },

  methods: {},

  computed: {
    TodayMonth() {
      const today = new Date();
      return today.toLocaleString("fr-FR", { month: "long" }).toUpperCase();
    },
  },
};
</script>
