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
      Colocataires
    </button>
  </div>

  <div class="flex flex-col md:w-full md:h-full items-center">
    <UpdateColocation v-if="activeComponent === 'updateColocation'" />

    <UpdatePersonnalInfo
      v-else-if="activeComponent === 'UpdatePersonnalInfo'"
    />

    <UpdateRoommates v-else-if="activeComponent === 'updateRoommates'" />
  </div>
</template>

<script lang="ts">
import UpdateColocation from "@/components/Information/UpdateColocation.vue";
import UpdateRoommates from "@/components/Information/UpdateRoommates.vue";
import UpdatePersonnalInfo from "@/components/Information/UpdatePersonnalInfo.vue";
import { useColocationStore } from "@/stores/useColocationStore";

export default {
  name: "InformationView",

  components: {
    UpdateColocation,
    UpdatePersonnalInfo,
    UpdateRoommates,
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

  /* TODO:
        Objectif : Pouvoir mettre à jour des informations sur la colocation
        -> supprimer un colocataire et mettre à jour les charges en conséquence
          -> /colocations/:colocation/relationships/:relationship (OK) Il faut refetch les charges

        -> mettre à jour les charges (ajouter/supprimer/modifier) 
          -> supprimer un charge :  users/:user/relationships/:relationship (OK)
          -> modifier une charge : charges/:charges (OK)
          -> ajouter une charge : colocation/:colocation/charges (OK)

        -> créer un composant qui permet de mettre à jour les informations de la colocation
          -> qui permet de supprimer un colocataire et de mettre à jour les charges en conséquence
        -> créer un composant qui permet d'ajouter / supprimer une charge
        -> créer un composant qui permet de mettre à jour ses informations personnelles

    */

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
      this.activeComponent = "updateRoommates";
    },
  },
};
</script>
