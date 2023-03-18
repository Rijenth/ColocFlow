<template>
  <h2 class="text-lg font-bold mb-4 text-center">Définissez les dépenses</h2>
  <form
    class="ml-4"
    @submit.prevent="StoreExpenses"
    @keydown.space.prevent
    v-on:keypress="PreventNonNumericValue"
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
    PreventNonNumericValue(event: any) {
      const charCode = event.which ? event.which : event.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        event.preventDefault();
        return false;
      }
      return true;
    },
    StoreExpenses() {
      const expenses = [
        { key: "electricity_charge", amount: this.electricity_charge },
        { key: "heating_charge", amount: this.heating_charge },
        { key: "gas_charge", amount: this.gas_charge },
        { key: "internet_charge", amount: this.internet_charge },
        { key: "others_charge", amount: this.others_charge },
        { key: "water_charge", amount: this.water_charge },
      ];

      const filteredExpenses = Object.fromEntries(
        // eslint-disable-next-line
        Object.entries(expenses).filter(([key, value]) => value.amount != null && value.amount != "")
      );

      const colocation = sessionStorage.getItem("colocation");

      if (colocation && Object.keys(filteredExpenses).length > 0) {
        const colocationData = JSON.parse(colocation);

        colocationData.data.attributes.charges = filteredExpenses;

        sessionStorage.setItem("colocation", JSON.stringify(colocationData));
      }

      this.$emit("expensesIsDefined", true);
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
