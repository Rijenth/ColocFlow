<template>
  <div class="w-80">
    <h2 class="text-lg font-bold mb-2 text-center underline-2">
      Créer une colocation
    </h2>
    <form class="flex flex-col items-center" @submit.prevent="StoreColocation">
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
          v-on:keypress="AcceptNumber"
        />

        <label class="text-left text-white my-2"
          >Nombre max de colocataires</label
        >
        <input
          v-model="Colocation.max_roomates"
          class="w-full h-10 p-4 border-2 border-gray-900 rounded-lg text-black"
          v-on:keypress="AcceptNumber"
        />
        <label class="text-white my-4">
          <input v-model="isRoomate" type="checkbox" class="mr-2" />
          Je suis colocataire, je participe aux dépenses et aux tâches
        </label>
      </div>

      <LoadingButton
        :is-loading="loading"
        class="w-1/2 border h-10 bg-gray-900 text-white rounded-lg mt-4 hover:bg-gray-600"
        text="Créer la colocation"
      />
    </form>
  </div>
</template>

<script lang="ts">
import { useSwal } from "@/composables/useSwal";
import axios from "@/axios/axios";
import { useColocationStore } from "@/stores/useColocationStore";
import { useAuthStore } from "@/stores/useAuthStore";
import LoadingButton from "@/components/LoadingButton.vue";

interface Colocation {
  name: string;
  access_key: string;
  monthly_rent: number;
  max_roomates: number;
}

export default {
  name: "CreateColoc",

  components: {
    LoadingButton,
  },

  data() {
    return {
      Colocation: {
        name: "",
        access_key: "",
        monthly_rent: 0,
        max_roomates: 1,
      } as Colocation,
      confirmAccessKey: "",
      isRoomate: false,
      loading: false,
    };
  },

  setup() {
    const { flash } = useSwal();
    const authStore = useAuthStore();
    const colocationStore = useColocationStore();

    return {
      authStore,
      colocationStore,
      flash,
    };
  },

  methods: {
    toggleLoading() {
      this.loading = !this.loading;
    },
    AcceptNumber(event: any) {
      const charCode = event.which ? event.which : event.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        event.preventDefault();
        return false;
      }
      return true;
    },
    async StoreColocation() {
      if (this.FormIsValid() === false) {
        this.flash(
          "Erreur de formulaire !",
          "Veuillez remplir tous les champs",
          "warning"
        );
        return;
      } else if (this.Colocation.access_key !== this.confirmAccessKey) {
        this.flash(
          "Erreur code d'accès !",
          "Les codes d'accès ne correspondent pas",
          "warning"
        );
        return;
      } else if (this.Colocation.max_roomates < 1) {
        this.flash(
          "Erreur nombre de colocataires !",
          "Le nombre de colocataires doit être supérieur à 0",
          "warning"
        );
        return;
      }

      this.toggleLoading();

      const body = {
        data: {
          attributes: {
            name: this.Colocation.name,
            access_key: this.Colocation.access_key,
            monthly_rent: this.Colocation.monthly_rent,
            max_roomates: this.Colocation.max_roomates,
          },
        },
      };

      try {
        const createColocation = await axios.post(
          "/api/colocations?include=owner",
          body
        );

        if (createColocation.status === 200) {
          const colocation = createColocation.data;
          const owner = createColocation.data.included.owner.data[0];

          if (this.isRoomate === true) {
            const user = this.authStore.getUser;

            const relationships = {
              data: {
                relationships: {
                  roomates: {
                    data: {
                      type: user.type,
                      id: user.id,
                    },
                  },
                },
              },
            };

            const colocationUpdated = await axios.patch(
              `/api/colocations/${colocation.data.id}?include=owner`,
              relationships
            );

            if (colocationUpdated.status === 200) {
              this.colocationStore.setColocation(colocationUpdated.data);

              this.authStore.setUser(
                colocationUpdated.data.included.owner.data[0]
              );
            }
          } else {
            this.colocationStore.setColocation(colocation);

            this.authStore.setUser(owner);
          }

          this.toggleLoading();

          this.flash(
            "Succès !",
            "Votre colocation a bien été crée.",
            "success"
          );

          this.$router.push({ name: "dashboard" });
        }
      } catch (error) {
        this.toggleLoading();

        this.flash(
          error.response.statusText + " !",
          error.response.data.message,
          "error"
        );
      }
    },
    FormIsValid() {
      return (
        this.Colocation.name !== "" &&
        this.Colocation.access_key !== "" &&
        this.Colocation.monthly_rent !== "" &&
        this.Colocation.max_roomates !== ""
      );
    },
  },
};
</script>
