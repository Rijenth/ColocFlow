<template>
  <div
    class="bg-gray-700 w-80 p-4 mb-4 text-white border rounded-lg shadow-xl md:border-none"
  >
    <h2 class="text-lg text-center font-semibold">Gestion des comptes</h2>
  </div>
  <div class="md:mx-4 md:grid md:grid-cols-2 md:gap-4">
    <div class="md:col-span-1">
      <ManagementOverview
        :storeRoommates="storeRoommates"
        :storeCharges="storeCharges"
      />
    </div>
    <div
      class="md:col-span-1"
      v-if="
        storeCharges.getColocationCharges.length !== 0 &&
        storeRoommates.getRoommates.length !== 0
      "
    >
      <ChargesResume
        :storeAuth="storeAuth"
        :storeRoommates="storeRoommates"
        :storeCharges="storeCharges"
      />
      <ChargesAttribution
        :storeRoommates="storeRoommates"
        :storeCharges="storeCharges"
      />
    </div>
  </div>
</template>

<script lang="ts">
import ManagementOverview from "@/components/Dashboard/DashboardComponents/ManagementOverview.vue";
import ChargesAttribution from "@/components/Dashboard/DashboardComponents/ChargesAttribution.vue";
import ChargesResume from "@/components/Dashboard/DashboardComponents/ChargesResume.vue";
import { useAuthStore } from "@/stores/useAuthStore";
import { useColocationChargeStore } from "@/stores/useColocationChargeStore";
import { useRoommateStore } from "@/stores/useRoommateStore";

export default {
  name: "DashboardManagement",

  setup() {
    const authStore = useAuthStore();
    const colocationChargeStore = useColocationChargeStore();
    const roommateStore = useRoommateStore();

    return {
      authStore,
      colocationChargeStore,
      roommateStore,
    };
  },

  components: {
    ManagementOverview,
    ChargesAttribution,
    ChargesResume,
  },

  computed: {
    storeAuth() {
      return this.authStore;
    },
    storeRoommates() {
      return this.roommateStore;
    },
    storeCharges() {
      return this.colocationChargeStore;
    },
  },
};
</script>
