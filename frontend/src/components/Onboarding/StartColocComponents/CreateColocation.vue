<template>
  <h2 class="text-lg font-bold mb-2 text-center">Créer une colocation</h2>
  <form class="flex flex-col items-center" @submit.prevent="StoreColocation">
    <div class="flex flex-col w-3/4">
      <label class="text-left text-white my-2">Nom de la colocation</label>
      <input
        type="text"
        v-model="colocationAttributes.name"
        class="w-full h-10 p-4 border-2 border-gray-900 rounded-lg text-black"
        placeholder="La joyeuse colonie"
      />

      <label class="text-left text-white my-2">Code confidentiel d'accès</label>
      <input
        type="text"
        v-model="colocationAttributes.access_key"
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
        type="number"
        step="0.01"
        v-model="colocationAttributes.monthly_rent"
        class="w-full h-10 p-4 border-2 border-gray-900 rounded-lg text-black"
        v-on:keypress="allowOnlyPositiveNumbersWithMaxTwoDecimals"
      />

      <label class="text-left text-white my-2"
        >Nombre max de colocataires</label
      >
      <input
        v-model="colocationAttributes.max_roommates"
        class="w-full h-10 p-4 border-2 border-gray-900 rounded-lg text-black"
        v-on:keypress="PreventNonNumericValue"
      />
      <label class="text-white my-4">
        <input v-model="isRoommate" type="checkbox" class="mr-2" />
        Je suis colocataire, je participe aux dépenses et aux tâches
      </label>
    </div>

    <LoadingButton
      class="w-1/2 border h-10 bg-gray-900 text-white rounded-lg mt-4 hover:bg-gray-600"
      text="Suivant"
    />
  </form>
</template>

<script lang="ts">
import { useSwal } from "@/composables/useSwal";
import { useAuthStore } from "@/stores/useAuthStore";
import { useColocationStore } from "@/stores/useColocationStore";
import { useRoommateStore } from "@/stores/useRoommateStore";
import LoadingButton from "@/components/LoadingButton.vue";

interface Colocation {
  name: string;
  access_key: string;
  monthly_rent: number;
  max_roommates: number;
}

interface keypressEvent extends KeyboardEvent {
  which: number;
}

export default {
  name: "StartColoc",

  emits: ["colocationCreated"],

  components: {
    LoadingButton,
  },

  data() {
    return {
      colocationAttributes: {
        name: "",
        access_key: "",
        monthly_rent: 0,
        max_roommates: 1,
      } as Colocation,
      confirmAccessKey: "",
      isRoommate: false,
      loading: false,
    };
  },

  setup() {
    const { flash } = useSwal();
    const authStore = useAuthStore();
    const colocationStore = useColocationStore();
    const roommateStore = useRoommateStore();

    return {
      authStore,
      colocationStore,
      flash,
      roommateStore,
    };
  },

  mounted() {
    const previousColocationData = sessionStorage.getItem(
      "colocation"
    ) as string;
    const previousRelationshipsData = sessionStorage.getItem(
      "relationships"
    ) as string;

    if (previousColocationData) {
      const colocation = JSON.parse(previousColocationData);
      this.colocationAttributes = colocation.data.attributes;
      this.confirmAccessKey = colocation.data.attributes.access_key;
    }

    if (previousRelationshipsData) {
      const relationships = JSON.parse(previousRelationshipsData);
      this.isRoommate =
        relationships.data.relationships.roommates.data !== null;
    }
  },

  methods: {
    PreventNonNumericValue(event: keypressEvent) {
      const charCode = event.which ? event.which : event.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        event.preventDefault();
        return false;
      }
      return true;
    },
    allowOnlyPositiveNumbersWithMaxTwoDecimals($event: KeyboardEvent) {
      const input = ($event.target as HTMLInputElement).value + $event.key;

      if (
        isNaN(input) ||
        (input.includes(".") && input.split(".")[1].length > 2)
      ) {
        event.preventDefault();
      }
    },
    StoreColocation() {
      if (this.ValidateForm() === false) {
        this.flash(
          "Erreur de formulaire !",
          "Veuillez remplir tous les champs",
          "warning"
        );
        return;
      } else if (
        this.colocationAttributes.access_key !== this.confirmAccessKey
      ) {
        this.flash(
          "Erreur code d'accès !",
          "Les codes d'accès ne correspondent pas",
          "warning"
        );
        return;
      } else if (this.colocationAttributes.max_roommates < 1) {
        this.flash(
          "Erreur nombre de colocataires !",
          "Le nombre de colocataires doit être supérieur à 0",
          "warning"
        );
        return;
      }

      const body = {
        data: {
          type: "colocations",
          attributes: this.colocationAttributes,
        },
      };

      sessionStorage.setItem("colocation", JSON.stringify(body));

      if (this.isRoommate === true) {
        const user = this.authStore.getUser;

        const relationships = {
          data: {
            relationships: {
              roommates: {
                data: {
                  type: user.type,
                  id: user.id,
                },
              },
            },
          },
        };

        sessionStorage.setItem("relationships", JSON.stringify(relationships));
      }

      this.$emit("colocationCreated", true);
    },
    ValidateForm() {
      return (
        this.colocationAttributes.name !== "" &&
        this.colocationAttributes.access_key !== "" &&
        this.colocationAttributes.monthly_rent !== "" &&
        this.colocationAttributes.max_roommates !== ""
      );
    },
  },
};
</script>
