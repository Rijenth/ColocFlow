<template>
  <div
    class="bg-gray-900 w-xs md:w-full max-w-xs mx-auto p-4 mb-4 text-white border rounded-lg shadow-xl md:border-none"
  >
    <h2 class="text-sm text-center font-bold mb-4">Attribution de charges</h2>
    <div class="block mb-4">
      <label for="personne" class="block">Selection du colocataire :</label>
      <select
        id="personne"
        class="text-sm w-full px-2 py-1 mt-2 border rounded text-black"
      >
        <option>Eric BATISTA</option>
        <option>Tom HOLLANDE</option>
        <option>Hui YUI</option>
      </select>
    </div>

    <div class="mb-4 flex flex-col">
      <label for="montant" class="mb-2">Montant (€ ou %) :</label>
      <div class="flex">
        <input
          type="text"
          id="montant"
          class="text-sm w-1/3 px-2 py-1 rounded-l text-black text-right mr-1"
        />
        <select
          id="percentage"
          class="text-sm w-1/3 px-2 py-1 border-l border-t border-b rounded-r text-black"
        >
          <option>10%</option>
          <option>20%</option>
          <option>30%</option>
          <option>40%</option>
          <option>50%</option>
          <option>60%</option>
          <option>70%</option>
          <option>80%</option>
          <option>90%</option>
          <option>100%</option>
        </select>
      </div>
    </div>

    <div class="flex flex-col">
      <label for="charges" class="mr-4">Charge à payer :</label>
      <select
        id="charges"
        class="text-sm w-1/3 px-2 py-1 mt-2 rounded text-black"
      >
        <option>Loyer</option>
        <option>Electricité</option>
        <option>Eau</option>
        <option>Internet</option>
        <option>Gaz</option>
        <option>Autres</option>
      </select>
    </div>

    <div class="flex justify-center">
      <LoadingButton
        class="w-full mt-4"
        :loading="false"
        :disabled="false"
        :text="'Attribuer'"
      />
    </div>
  </div>
</template>

<script lang="ts">
import LoadingButton from "@/components/LoadingButton.vue";

export default {
  name: "ChargesAttribution",

  components: {
    LoadingButton,
  },
};
</script>
