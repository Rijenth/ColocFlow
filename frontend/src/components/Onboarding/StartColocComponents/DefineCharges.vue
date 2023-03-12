<template>
  <h2 class="text-lg font-bold mb-4 text-center">Définissez les dépenses</h2>

  <form class="ml-4" @submit.prevent="$emit('expensesIsDefined', true)">
    <div class="flex flex-col">
      <label class="text-white mb-4 text-center"
        >Loyer mensuel :
        {{ monthlyRent }}
        €
      </label>

      <div class="border rounded-lg p-3">
        <h3 class="text-l font-semibold text-center">
          Charges mensuelles (€) :
        </h3>
        <ul>
          <li class="flex justify-between my-2">
            <label>Chauffage :</label>
            <input
              class="w-20 text-right input-field rounded px-1"
              type="text"
              placeholder="0"
              v-model="heating_charge"
            />
          </li>
          <li class="flex justify-between my-2">
            <label>Eau :</label>
            <input
              class="w-20 text-right input-field rounded px-1"
              type="text"
              placeholder="0"
              v-model="water_charge"
            />
          </li>
          <li class="flex justify-between my-2">
            <label>Electricité :</label>
            <input
              class="w-20 text-right input-field rounded px-1"
              type="text"
              placeholder="0"
              v-model="electricity_charge"
            />
          </li>
          <li class="flex justify-between my-2">
            <label>Internet :</label>
            <input
              class="w-20 text-right input-field rounded px-1"
              type="text"
              placeholder="0"
              v-model="internet_charge"
            />
          </li>
          <li class="flex justify-between my-2">
            <label>Gaz :</label>
            <input
              class="w-20 text-right input-field rounded px-1"
              type="text"
              placeholder="0"
              v-model="gas_charge"
            />
          </li>
          <li class="flex justify-between my-2">
            <label>Autres :</label>
            <input
              class="w-20 text-right input-field rounded px-1"
              type="text"
              placeholder="0"
              v-model="others_charge"
            />
          </li>
        </ul>
      </div>
    </div>
    <div class="flex flex-row">
      <LoadingButton
        :is-loading="loading"
        class="w-1/2 border h-10 bg-gray-900 text-white rounded-lg mt-4 hover:bg-gray-600"
        text="Créer"
      />
      <button
        class="w-1/2 border h-10 bg-gray-900 text-white rounded-lg mt-4 hover:bg-gray-600"
        @click.prevent="$emit('colocationCreated', false)"
      >
        Retour
      </button>
    </div>
  </form>
</template>

<script lang="ts">
import LoadingButton from "@/components/LoadingButton.vue";

export default {
  name: "DefineCharges",

  components: {
    LoadingButton,
  },

  emits: ["colocationCreated", "expensesIsDefined"],

  data() {
    return {
      electricity_charge: 0,
      heating_charge: 0,
      gas_charge: 0,
      internet_charge: 0,
      monthlyRent: 0,
      others_charge: 0,
      water_charge: 0,
    };
  },

  props: {
    loading: {
      type: Boolean,
      required: true,
    },
  },

  mounted() {
    const colocation = sessionStorage.getItem("colocation");

    if (colocation) {
      this.colocation = JSON.parse(colocation);

      this.monthlyRent = this.colocation.data.attributes.monthly_rent;
    }
  },
};
</script>
