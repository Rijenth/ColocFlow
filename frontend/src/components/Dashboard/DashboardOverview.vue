<template>
  <div class="bg-gray-700 dashboard-overview-card">
    <h2 class="text-lg text-center font-semibold">
      Bienvenue chez
      {{ colocation.name }}
      !
    </h2>
  </div>

  <div class="bg-gray-900 dashboard-overview-card">
    <h2 class="text-sm text-center font-bold mb-4">Etat des lieux :</h2>
    <StateIndicator color="green">
      <p class="text-sm">Règlement du loyer du mois de {{ TodayMonth }}</p>
    </StateIndicator>
    <StateIndicator color="green">
      <p class="text-sm">Aucun message à lire</p>
    </StateIndicator>
    <StateIndicator color="green">
      <p class="text-sm">Incident majeur non résolu</p>
    </StateIndicator>
  </div>

  <div class="flex flex-col w-full items-center">
    <div class="bg-gray-900 dashboard-overview-card">
      <h2 class="text-sm text-center font-bold mb-4">Suivi des règlements :</h2>
      <ul class="text-sm">
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
    </div>

    <div class="bg-gray-900 dashboard-overview-card">
      <h2 class="text-sm text-center font-bold mb-4">Vos charges :</h2>
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

    <div class="bg-gray-900 dashboard-overview-card">
      <h2 class="text-sm text-center font-bold mb-4">Messagerie :</h2>
      <p>Envoyer des messages à quelqu'un en le selectionnant</p>
    </div>
  </div>
</template>

<script lang="ts">
import StateIndicator from "@/components/StateIndicator.vue";

export default {
  name: "DashboardOverview",

  components: {
    StateIndicator,
  },

  props: {
    colocation: {
      type: Object,
      required: true,
    },
    colocationCharges: {
      type: Array,
      required: true,
    },
    roommates: {
      type: Array,
      required: true,
    },
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
