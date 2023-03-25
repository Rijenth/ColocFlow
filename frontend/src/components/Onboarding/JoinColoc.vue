<template>
  <div class="w-80">
    <h2 class="text-lg font-bold mb-2 text-center">Rejoindre une colocation</h2>
    <div class="text-s m-4 ml-6 align-middle text-left">
      <p>
        Pour rejoindre une colocation existante, vous devez nous fournir le nom
        de l'utilisateur ayant créé la colocation, ainsi que le code d'accès.
      </p>
      <hr class="my-6" />
    </div>
    <form
      class="flex w-full flex-col items-center align-middle my-10"
      @submit.prevent="joinColocation"
      v-on:keydown.space.prevent
    >
      <input
        type="text"
        class="w-1/2 h-10 p-4 border-2 border-gray-900 rounded-lg text-black"
        placeholder="Adresse email"
        v-model="email"
      />
      <input
        type="text"
        class="w-1/2 h-10 p-4 border-2 border-gray-900 rounded-lg text-black"
        placeholder="Code d'accès"
        v-model="access_key"
      />
      <LoadingButton
        :is-loading="loading"
        class="w-1/2 border h-10 bg-gray-900 text-white rounded-lg mt-4 hover:bg-gray-600"
        text="Rejoindre"
      />
    </form>
  </div>
</template>

<script lang="ts">
import { useSwal } from "@/composables/useSwal";
import axios from "@/services/axios";
import { useAuthStore } from "@/stores/useAuthStore";
import { useColocationStore } from "@/stores/useColocationStore";
import { useColocationChargeStore } from "@/stores/useColocationChargeStore";
import { useRoommateStore } from "@/stores/useRoommateStore";
import LoadingButton from "@/components/LoadingButton.vue";
import type { User } from "@/stores/useAuthStore";
import type { AxiosResponse } from "axios";

export default {
  name: "JoinColoc",

  components: {
    LoadingButton,
  },

  data() {
    return {
      access_key: "" as string,
      email: "" as string,
      loading: false,
    };
  },

  setup() {
    const authStore = useAuthStore();
    const colocationStore = useColocationStore();
    const colocationChargeStore = useColocationChargeStore();
    const roommateStore = useRoommateStore();
    const { flash } = useSwal();

    return {
      authStore,
      colocationStore,
      colocationChargeStore,
      flash,
      roommateStore,
    };
  },

  methods: {
    toggleLoading() {
      if (this.loading === false) {
        this.loading = true;
      } else {
        this.loading = false;
      }
    },
    async joinColocation() {
      const user: User = this.authStore.getUser;

      if (user.id === 0 || user.type === "") {
        this.flash(
          "Authentification requise !",
          "Une erreur d'authentification est survenue. Veuillez vous reconnecter",
          "error"
        );
        this.$router.push("/");
        return;
      }

      if (this.access_key === "") {
        this.flash(
          "Champs requis",
          "Veuillez entrer un code d'accès",
          "warning"
        );
        return;
      }

      if (this.isValidEmail === false) {
        this.flash(
          "Adresse email invalide",
          "Veuillez entrer une adresse email valide",
          "warning"
        );
        return;
      }

      this.toggleLoading();

      try {
        const getColocation = await axios.get("/get-colocation", {
          params: {
            email: this.email,
            access_key: this.access_key,
          },
        });

        if (getColocation.status === 200) {
          const reponse = getColocation.data;

          const body = {
            data: {
              type: reponse.data.type,
              id: reponse.data.id,
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

          const patchColocation = await axios.patch(
            "/api/colocations/" + reponse.data.id + "?include=roommates",
            body
          );

          if (patchColocation.status === 200) {
            const updatedUser =
              patchColocation.data.included.roommates.data.find(
                (user: User) => user.id === this.authStore.getUser.id
              );

            this.authStore.setUser(updatedUser);

            this.flash(
              "Succès !",
              "Vous avez rejoint la colocation",
              "success"
            );
            this.colocationStore.setColocation(patchColocation.data);

            this.roommateStore.fetchRoomates(
              this.colocationStore.getColocationId
            );

            this.colocationChargeStore.fetchColocationCharges(
              this.colocationStore.getColocationId
            );

            this.$router.push("/dashboard");
          }
        }
      } catch (error) {
        const e = error as Error & { response: AxiosResponse | undefined };

        this.toggleLoading();

        if (
          e.response !== undefined &&
          e.response.statusText &&
          e.response.status
        ) {
          if (e.response.status === 422) {
            this.flash(
              e.response.statusText,
              "Une erreur est survenue lors de l'ajout de l'utilisateur à la colocation",
              "error"
            );
          } else if (e.response.status === 404) {
            this.flash(
              "Erreur !",
              "Le nom d'utilisateur ou le code d'accès est incorrect",
              "error"
            );
          } else if (e.response.status === 401) {
            this.flash("Erreur !", "Le code d'accès est incorrect", "error");
          } else if (e.response.status === 409) {
            this.flash(
              "Colocation complète !",
              "Le nombre maximum de colocataires a été atteint",
              "error"
            );
          }
        } else {
          this.flash("Une erreur est survenue", e.message, "error");
        }
      }
    },
  },

  computed: {
    isValidEmail() {
      return /^[^@]+@\w+(\.\w+)+\w$/.test(this.email);
    },
  },
};
</script>
