<template>
  <div class="bg-gray-900 border mb-4 rounded md:mt-5">
    <button
      class="text-white p-2 px-3 hover:bg-gray-700 hover:rounded-l"
      @click="updateColocation"
      v-if="hasColocation === true"
    >
      Colocation
    </button>

    <button
      class="border-l text-white p-2 px-3 hover:bg-gray-700"
      @click="UpdatePersonnalInfo"
    >
      Utilisateur
    </button>

    <button
      class="border-l text-white p-2 px-3 hover:bg-gray-700"
      @click="updateRoomates"
      v-if="hasColocation === true"
    >
      Charges
    </button>
  </div>

  <div class="flex flex-col md:w-full md:h-full items-center">
    <UpdateColocation v-if="activeComponent === 'updateColocation'" />

    <UpdatePersonnalInfo
      v-else-if="activeComponent === 'UpdatePersonnalInfo'"
    />

    <UpdateColocationCharges
      v-else-if="activeComponent === 'UpdateColocationCharges'"
    />
  </div>
</template>

<script lang="ts">
import UpdateColocation from "@/components/Information/UpdateColocation.vue";
import UpdateColocationCharges from "@/components/Information/UpdateColocationCharges.vue";
import UpdatePersonnalInfo from "@/components/Information/UpdatePersonnalInfo.vue";
import { useColocationStore } from "@/stores/useColocationStore";

export default {
  name: "InformationView",

  components: {
    UpdateColocation,
    UpdatePersonnalInfo,
    UpdateColocationCharges,
  },

  setup() {
    const colocationStore = useColocationStore();

    return {
      colocationStore,
    };
  },

  computed: {
    hasColocation() {
      return this.colocationStore.colocationIsDefined;
    },
  },

  mounted() {
    this.hasColocation === true
      ? (this.activeComponent = "updateColocation")
      : (this.activeComponent = "UpdatePersonnalInfo");
  },

  data: () => ({
    activeComponent: "",
  }),

  methods: {
    updateColocation() {
      this.activeComponent = "updateColocation";
    },
    UpdatePersonnalInfo() {
      this.activeComponent = "UpdatePersonnalInfo";
    },
    updateRoomates() {
      this.activeComponent = "UpdateColocationCharges";
    },
  },
};
</script>
