<template>
  <div class="bg-gray-900 dashboard-management-card">
    <h2 class="text-sm underline text-center font-bold mb-4">
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

      <hr class="my-2" />

      <li class="font-bold flex justify-between">
        <p>Total</p>
        <p>{{ rentAmount + totalCharges }} €</p>
      </li>
    </ul>
  </div>

  <div class="bg-gray-900 dashboard-management-card">
    <h2 class="text-sm underline text-center font-bold mb-4">
      Détails des charges
    </h2>
    <ul class="text-sm">
      <li v-if="colocationCharges.length === 0" class="text-left text-xs">
        <p>Aucune charge n'a été ajoutée</p>
      </li>
      <li
        v-for="charge in colocationCharges"
        :key="charge.id"
        class="flex justify-between"
      >
        <p>{{ $t("colocation.charges." + charge.attributes.name) }}</p>
        <p>{{ charge.attributes.amount }} €</p>
      </li>
    </ul>

    <hr class="my-2" />

    <div class="font-bold flex justify-between">
      <p>Total</p>
      <p>{{ totalCharges }} €</p>
    </div>
  </div>

  <div class="bg-gray-900 dashboard-management-card">
    <h2 class="text-sm underline text-center font-bold mb-4">
      Répartition des paiements
    </h2>
    <ul class="text-sm">
      <li v-if="getRoommates.length === 0" class="text-left text-xs">
        <p>Aucun colocataire n'a été ajouté</p>
      </li>
      <li
        class="flex justify-between"
        v-for="roommate in getRoommates"
        :key="roommate.id"
      >
        <p>
          {{ roommate.attributes.lastname.toUpperCase() }}
          {{
            roommate.attributes.firstname.charAt(0).toUpperCase() +
            roommate.attributes.firstname.slice(1).toLowerCase()
          }}
        </p>
        <p>{{ getSumUserChargesAmount(roommate.id) }} €</p>
      </li>
    </ul>

    <hr class="my-2" />

    <div class="font-bold flex justify-between">
      <p>Total</p>
      <p>{{ getTotalAmountAffected }} €</p>
    </div>

    <hr class="my-2" />

    <div class="flex justify-between">
      <p>Restant dû</p>
      <p>{{ amountDue }} €</p>
    </div>
  </div>
</template>

<script lang="ts">
import type { Charge } from "@/stores/useColocationChargeStore";

export default {
  name: "ManagementOverview",

  props: {
    storeCharges: {
      type: Object,
      required: true,
    },
    storeRoommates: {
      type: Object,
      required: true,
    },
  },

  computed: {
    amountDue() {
      return (
        parseFloat(
          (
            this.storeCharges.getTotalChargesAmount +
            this.storeCharges.getRentAmount -
            this.storeCharges.getTotalAmountAffected
          ).toFixed(2)
        ) || 0
      );
    },
    colocationCharges() {
      return this.storeCharges.getColocationCharges.filter(
        (charge: Charge) => charge.attributes.name !== "Rent"
      );
    },
    getSumUserChargesAmount() {
      return (userId: number) => {
        return this.storeCharges.getUserTotalAffectedAmount(userId);
      };
    },
    rentAmount() {
      return this.storeCharges.getRentAmount;
    },
    getRoommates() {
      return this.storeRoommates.getRoommates;
    },
    totalCharges() {
      return this.storeCharges.getTotalChargesAmount;
    },
    getTotalAmountAffected() {
      return this.storeCharges.getTotalAmountAffected;
    },
  },
};
</script>
