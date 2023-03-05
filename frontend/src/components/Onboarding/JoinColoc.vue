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
    >
      <input
        type="text"
        class="w-1/2 h-10 p-4 border-2 border-gray-900 rounded-lg text-black"
        placeholder="Nom d'utilisateur"
        v-model="username"
      />
      <input
        type="text"
        class="w-1/2 h-10 p-4 border-2 border-gray-900 rounded-lg text-black"
        placeholder="Code d'accès"
        v-model="access_key"
      />
      <button
        class="w-1/2 border h-10 bg-gray-900 text-white rounded-lg mt-4 hover:bg-gray-600"
      >
        Rejoindre
      </button>
    </form>
  </div>
</template>

<script lang="ts">
import { useSwal } from "@/composables/useSwal";
import axios from "@/axios/axios";
import { useAuthStore } from "@/stores/useAuthStore";
import { useColocationStore } from "@/stores/useColocationStore";

export default {
  name: "JoinColoc",

  data() {
    return {
      access_key: "" as string,
      username: "" as string,
    };
  },

  setup() {
    const authStore = useAuthStore();
    const colocationStore = useColocationStore();
    const { flash } = useSwal();

    return {
      authStore,
      colocationStore,
      flash,
    };
  },

  methods: {
    async joinColocation() {
      const user = this.authStore.getUser;

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

      if (this.username === "") {
        this.flash(
          "Champs requis",
          "Veuillez entrer un nom d'utilisateur",
          "warning"
        );
        return;
      }

      try {
        const getColocation = await axios.get("/get-colocation", {
          params: {
            username: this.username,
            access_key: this.access_key,
          },
        });

        if (getColocation.status === 200) {
          const body = {
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

          const patchColocation = await axios.patch(
            "/api/colocations/" + getColocation.data.data.id,
            body
          );

          if (patchColocation.status === 200) {
            this.flash(
              "Succès !",
              "Vous avez rejoint la colocation",
              "success"
            );
            this.colocationStore.setColocation(patchColocation.data);

            this.$router.push("/dashboard");
          }
        }
      } catch (err) {
        if (err.response && err.response.status === 422) {
          this.flash(
            err.response.statusText,
            "Une erreur est survenue lors de l'ajout de l'utilisateur à la colocation",
            "error"
          );
        } else if (err.response && err.response.status === 404) {
          this.flash(
            "Erreur !",
            "Le nom d'utilisateur ou le code d'accès est incorrect",
            "error"
          );
        } else if (err.response && err.response.status === 401) {
          this.flash("Erreur !", "Le code d'accès est incorrect", "error");
        } else if (err.response && err.response.status === 409) {
          this.flash(
            "Colocation complète !",
            "Le nombre maximum de colocataires a été atteint",
            "error"
          );
        } else {
          this.flash("Erreur !", err.message, "error");
        }
      }
    },
  },
};
</script>
