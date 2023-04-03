<template>
  <h2 class="text-lg font-bold mb-4 text-center">Définissez les dépenses</h2>
  <form
    class="ml-4"
    @submit.prevent="StoreExpenses"
    @keydown.space.prevent
    v-on:keypress="allowOnlyPositiveNumbersWithMaxTwoDecimals"
  >
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
              type="number"
              step="0.01"
              placeholder="0"
              v-model="heating_charge"
            />
          </li>
          <li class="flex justify-between my-2">
            <label>Eau :</label>
            <input
              class="w-20 text-right input-field rounded px-1"
              type="number"
              step="0.01"
              placeholder="0"
              v-model="water_charge"
            />
          </li>
          <li class="flex justify-between my-2">
            <label>Electricité :</label>
            <input
              class="w-20 text-right input-field rounded px-1"
              type="number"
              step="0.01"
              placeholder="0"
              v-model="electricity_charge"
            />
          </li>
          <li class="flex justify-between my-2">
            <label>Internet :</label>
            <input
              class="w-20 text-right input-field rounded px-1"
              type="number"
              step="0.01"
              placeholder="0"
              v-model="internet_charge"
            />
          </li>
          <li class="flex justify-between my-2">
            <label>Gaz :</label>
            <input
              class="w-20 text-right input-field rounded px-1"
              type="number"
              step="0.01"
              placeholder="0"
              v-model="gas_charge"
            />
          </li>
          <li class="flex justify-between my-2">
            <label>Autres :</label>
            <input
              class="w-20 text-right input-field rounded px-1"
              type="number"
              step="0.01"
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
      electricity_charge: null,
      heating_charge: null,
      gas_charge: null,
      internet_charge: null,
      monthlyRent: null,
      others_charge: null,
      water_charge: null,
    };
  },

  props: {
    loading: {
      type: Boolean,
      required: true,
    },
  },

  methods: {
    allowOnlyPositiveNumbersWithMaxTwoDecimals($event: KeyboardEvent) {
      const input = ($event.target as HTMLInputElement).value + $event.key;

      if (
        isNaN(Number(input)) ||
        (input.includes(".") && input.split(".")[1].length > 2)
      ) {
        $event.preventDefault();
      }
    },
    StoreExpenses() {
      const expenses = [
        { name: "Electricité", amount: this.electricity_charge },
        { name: "Chauffage", amount: this.heating_charge },
        { name: "Gaz", amount: this.gas_charge },
        { name: "Internet", amount: this.internet_charge },
        { name: "Autres", amount: this.others_charge },
        { name: "Eau", amount: this.water_charge },
      ];

      const filteredExpenses = expenses.filter(
        (expense) => expense.amount != null && expense.amount != ""
      );

      const colocation = sessionStorage.getItem("colocation") as string;

      if (colocation && filteredExpenses.length > 0) {
        const colocationData = JSON.parse(colocation);

        colocationData.data.attributes.charges = filteredExpenses;

        sessionStorage.setItem("colocation", JSON.stringify(colocationData));
      }

      this.$emit("expensesIsDefined", true);
    },
  },

  mounted() {
    const getColocation = sessionStorage.getItem("colocation") as string;

    if (getColocation) {
      const colocation = JSON.parse(getColocation);

      this.monthlyRent = colocation.data.attributes.monthly_rent;
    }
  },
};
</script>
