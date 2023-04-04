<template>
  <div class="bg-gray-700 dashboard-overview-card">
    <h2 class="text-lg text-center font-semibold">Bienvenue chez</h2>
    <p class="text-sm text-center">{{ colocation.getAttributes.name }} !</p>
  </div>

  <div class="bg-gray-900 dashboard-overview-card">
    <h2 class="text-sm underline text-center font-bold mb-4">Etat des lieux</h2>
    <StateIndicator color="green">
      <p class="text-sm">Taux d'avancement des tâches : 100%</p>
    </StateIndicator>
    <StateIndicator :color="chargeCompletionIndicator">
      <p class="text-sm">
        Taux d'attribution des charges :
        {{ getColocationChargesPercentageOfCompletion }}%
      </p>
    </StateIndicator>
    <StateIndicator color="green">
      <p class="text-sm">Propriétaire : {{ colocation.getOwnerFirstName }}</p>
    </StateIndicator>
  </div>

  <div class="flex flex-col w-full items-center">
    <div class="bg-gray-900 dashboard-overview-card">
      <h2 class="text-sm underline text-center font-bold mb-4">
        Indicateur de participation
      </h2>
      <ul class="text-sm" v-if="roommates.length !== 0">
        <li v-for="roommate in roommates" :key="roommate.id">
          <StateIndicator color="green">
            <p>
              {{ roommate.attributes.lastname.toUpperCase() }}
              {{
                roommate.attributes.firstname.charAt(0).toUpperCase() +
                roommate.attributes.firstname.slice(1).toLowerCase()
              }}
            </p>
          </StateIndicator>
        </li>
      </ul>
      <p class="text-sm" v-else>Aucun colocataire enregistré</p>
    </div>

    <div class="bg-gray-900 dashboard-overview-card">
      <h2 class="text-sm underline text-center font-bold mb-4">Vos charges</h2>
      <ul class="text-sm">
        <li v-if="userCharges.length === 0">
          <p>Aucune charge enregistrée</p>
        </li>
        <li
          v-for="charge in userCharges"
          :key="charge.id"
          class="flex justify-between"
        >
          <p>{{ charge.attributes.name }}</p>
          <p>{{ charge.attributes.amount }} €</p>
        </li>
      </ul>
    </div>

    <!--     <div class="bg-gray-900 dashboard-overview-card">
      <h2 class="text-sm underline text-center font-bold mb-4">Messagerie</h2>
      <p>Envoyer des messages à quelqu'un en le selectionnant</p>
    </div> -->
  </div>
</template>

<script lang="ts">
import StateIndicator from "@/components/StateIndicator.vue";
import type { Charge } from "@/stores/useColocationChargeStore";
import type { User } from "@/stores/useAuthStore";

export default {
  name: "DashboardOverview",

  components: {
    StateIndicator,
  },

  props: {
    colocationStore: {
      type: Object,
      required: true,
    },
    colocationChargesStore: {
      type: Object,
      required: true,
    },
    userCharges: {
      type: Array as () => Array<Charge>,
      required: true,
    },
    roommates: {
      type: Array as () => Array<User>,
      required: true,
    },
  },

  computed: {
    colocation() {
      return this.colocationStore;
    },
    chargeCompletionIndicator() {
      const totalAmount = this.colocationChargesStore.getTotalChargesAmount;
      const totalAmountPaid =
        this.colocationChargesStore.getTotalAmountAffected;

      const percentage = Math.round((totalAmountPaid / totalAmount) * 100);

      if (percentage < 50) {
        return "red";
      } else if (percentage < 100) {
        return "yellow";
      }
      return "green";
    },
    getColocationChargesPercentageOfCompletion() {
      const totalAmount = this.colocationChargesStore.getTotalChargesAmount;
      const totalAmountPaid =
        this.colocationChargesStore.getTotalAmountAffected;

      return Math.round((totalAmountPaid / totalAmount) * 100);
    },
  },
};
</script>
