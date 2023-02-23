<template>
  <div class="w-80">
    <h2 class="text-lg font-bold mb-2 text-center underline-2">
      Créer une colocation
    </h2>
    <form class="flex flex-col items-center">
      <div class="flex flex-col w-3/4">
        <label class="text-left text-white my-2">Nom de la colocation</label>
        <input
          type="text"
          v-model="Colocation.name"
          class="w-full h-10 p-4 border-2 border-gray-900 rounded-lg text-black"
          placeholder="La joyeuse colonie"
        />

        <label class="text-left text-white my-2"
          >Code confidentiel d'accès</label
        >
        <input
          type="text"
          v-model="Colocation.access_key"
          class="w-full h-10 p-4 border-2 border-gray-900 rounded-lg text-black"
          placeholder="*************"
        />

        <label class="text-left text-white my-2"
          >Confirmer votre code d'accès</label
        >
        <input
          type="text"
          v-model="confirmAccessKey"
          class="w-full h-10 p-4 border-2 border-gray-900 rounded-lg text-black"
          placeholder="*************"
        />

        <label class="text-left text-white my-2">Loyer mensuel</label>
        <input
          v-model="Colocation.monthly_rent"
          class="w-full h-10 p-4 border-2 border-gray-900 rounded-lg text-black"
          v-on:keypress="NumbersOnly"
        />

        <label class="text-left text-white my-2"
          >Nombre max de colocataires</label
        >
        <input
          v-model="Colocation.max_roomates"
          class="w-full h-10 p-4 border-2 border-gray-900 rounded-lg text-black"
          v-on:keypress="NumbersOnly"
        />
      </div>

      <button
        class="w-1/2 border h-10 bg-gray-900 text-white rounded-lg mt-6 hover:bg-gray-600"
      >
        Créer la colocation
      </button>
    </form>
  </div>
</template>

<script lang="ts">
interface Colocation {
  name: string;
  access_key: string;
  monthly_rent: number;
  max_roomates: number;
}

export default {
  name: "CreateColoc",
  data() {
    return {
      Colocation: {
        name: "",
        access_key: "",
        monthly_rent: 0,
        max_roomates: 0,
      } as Colocation,
      confirmAccessKey: "",
    };
  },

  methods: {
    NumbersOnly(event: any) {
      const charCode = event.which ? event.which : event.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        event.preventDefault();
        return false;
      }
      return true;
    },
  },
};
</script>
