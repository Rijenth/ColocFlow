<template>
  <div class="bg-gray-900 dashboard-management-card">
    <h2 class="text-sm text-center font-bold mb-4">
      Loyer et charges mensuelles
    </h2>
    <ul class="text-sm">
      <li class="flex justify-between">
        <p>Loyer</p>
        <p>{{ rentAmount }} €</p>
      </li>
      <li class="flex justify-between">
        <p>Charges</p>
        <p>{{ totalCharges }} €</p>
      </li>
      <li class="font-bold flex justify-between">
        <p>Total</p>
        <p>{{ rentAmount + totalCharges }} €</p>
      </li>
    </ul>
  </div>

  <div class="bg-gray-900 dashboard-management-card">
    <h2 class="text-sm text-center font-bold mb-4">Détails des charges</h2>
    <ul class="text-sm">
      <li
        v-for="charge in colocationCharges"
        :key="charge.id"
        class="flex justify-between"
      >
        <p>{{ $t("colocation.charges." + charge.attributes.name) }}</p>
        <p>{{ charge.attributes.amount }} €</p>
      </li>
    </ul>
  </div>

  <div class="bg-gray-900 dashboard-management-card">
    <h2 class="text-sm text-center font-bold mb-4">
      Répartition des paiements
    </h2>
    <ul class="text-sm">
      <li class="flex justify-between">
        <p>Eric BATISTA</p>
        <p>200€</p>
      </li>
      <li class="flex justify-between">
        <p>Tom HOLLANDE</p>
        <p>500€</p>
      </li>
      <li class="flex justify-between">
        <p>Hui YUI</p>
        <p>400€</p>
      </li>
    </ul>

    <div class="font-bold flex justify-between">
      <p>Total</p>
      <p>1100€</p>
    </div>
  </div>
</template>
<script lang="ts">
import { useColocationChargeStore } from "@/stores/useColocationChargeStore";

export default {
  name: "ManagementOverview",

  setup() {
    const colocationChargeStore = useColocationChargeStore();

    return {
      colocationChargeStore,
    };
  },

  computed: {
    colocationCharges() {
      return this.colocationChargeStore.getColocationCharges;
    },
    rentAmount() {
      return this.colocationChargeStore.getRentAmount;
    },
    totalCharges() {
      return this.colocationChargeStore.getTotalChargesAmount;
    },
  },
};
</script>
