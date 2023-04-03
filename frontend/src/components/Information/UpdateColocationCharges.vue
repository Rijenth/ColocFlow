<template>
  <div class="bg-gray-700 dashboard-overview-card">
    <h2 class="text-lg text-center font-semibold">Mise à jour des charges</h2>
  </div>

  <div class="flex flex-col md:flex-row md:space-x-3">
    <AddCharge
      :store-colocation="storeColocation"
      :storeColocationCharge="storeColocationCharge"
    />
    <UpdateCharges :store-charges="storeColocationCharge" />
  </div>
</template>

<script lang="ts">
import AddCharge from "@/components/Information/UpdateColocationCharges/AddCharge.vue";
import UpdateCharges from "@/components/Information/UpdateColocationCharges/UpdateCharges.vue";
import { useColocationStore } from "@/stores/useColocationStore";
import { useColocationChargeStore } from "@/stores/useColocationChargeStore";

export default {
  name: "UpdateColocationCharges",

  components: {
    AddCharge,
    UpdateCharges,
  },

  setup() {
    const colocationStore = useColocationStore();
    const colocationChargeStore = useColocationChargeStore();

    return {
      colocationStore,
      colocationChargeStore,
    };
  },

  computed: {
    storeColocation() {
      return this.colocationStore;
    },
    storeColocationCharge() {
      return this.colocationChargeStore;
    },
  },
};
</script>
