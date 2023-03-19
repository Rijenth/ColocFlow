<template>
  <div class="bg-gray-900 border mb-4 rounded md:mt-5">
    <button
      class="text-white p-2 px-3 hover:bg-gray-700 hover:rounded-l"
      @click="generalCard"
    >
      Général
    </button>
    <button
      class="border-l text-white p-2 px-3 hover:bg-gray-700"
      @click="managementCard"
    >
      Gestion
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

  <div class="flex flex-col md:w-full md:h-full items-center">
    <DashboardOverview
      v-if="activeComponent === 'DashboardOverview'"
      :userCharges="userCharges"
      :colocation="colocation"
      :roommates="roommates"
    />
    <DashboardManagement
      v-else-if="activeComponent === 'DashboardManagement'"
    />
  </div>
</template>

<script lang="ts">
import DashboardOverview from "@/components/Dashboard/DashboardOverview.vue";
import DashboardManagement from "@/components/Dashboard/DashboardManagement.vue";
import { useAuthStore } from "@/stores/useAuthStore";
import { useColocationStore } from "@/stores/useColocationStore";
import { useColocationChargeStore } from "@/stores/useColocationChargeStore";
import { useRoommateStore } from "@/stores/useRoommateStore";

export default {
  name: "DashboardView",

  components: {
    DashboardOverview,
    DashboardManagement,
  },

  setup() {
    const authStore = useAuthStore();
    const colocationStore = useColocationStore();
    const colocationChargeStore = useColocationChargeStore();
    const roommateStore = useRoommateStore();

    return {
      authStore,
      colocationStore,
      colocationChargeStore,
      roommateStore,
    };
  },

  data: () => ({
    activeComponent: "DashboardOverview",
  }),

  methods: {
    generalCard() {
      this.activeComponent = "DashboardOverview";
    },
    managementCard() {
      this.activeComponent = "DashboardManagement";
    },
    propositionCard() {
      console.log("propositionCard");
    },
    incidentReportCard() {
      console.log("incidentReportCard");
    },
  },

  computed: {
    colocation() {
      return this.colocationStore.getAttributes;
    },
    roommates() {
      return this.roommateStore.getRoommates;
    },
    userCharges() {
      return this.colocationChargeStore.getChargesByUser(
        this.authStore.getUser.id
      );
    },
  },
};
</script>
