<template>
  <div class="flex justify-center md:h-screen md:items-center">
    <div class="w-80 p-4 bg-gray-900 border rounded-lg text-white md:shadow-xl">
      <h2 class="text-lg font-bold mb-2">Bienvenue sur ColocFlow</h2>
      <p class="text-s mb-4">Logiciel de gestion de colocation</p>
      <form @submit.prevent="submitForm">
        <input
          class="input-field input-auth"
          type="text"
          placeholder="Nom d'utilisateur"
          @keydown.space.prevent
          v-model="username"
        />
        <input
          class="input-field input-auth"
          type="password"
          placeholder="Mot de passe"
          @keydown.space.prevent
          v-model="password"
        />
        <LoadingButton :is-loading="loading" class="w-full" text="Connexion" />
        <div class="text-center mt-2">
          <a
            class="hover:text-blue-800"
            href="/register"
            v-on:click.prevent="updateSelectedComponent"
            >Inscription</a
          >
        </div>
      </form>
    </div>
  </div>
</template>

<script lang="ts">
import axios from "@/axios/axios";
import LoadingButton from "@/components/LoadingButton.vue";
import { useAuthStore } from "@/stores/useAuthStore";
import { useColocationStore } from "@/stores/useColocationStore";
import { useRoommateStore } from "@/stores/useRoommateStore";
import { useSwal } from "@/composables/useSwal";

export default {
  name: "LoginForm",

  components: {
    LoadingButton,
  },

  data() {
    return {
      username: "" as string,
      password: "" as string,
      loading: false,
    };
  },

  setup() {
    const authStore = useAuthStore();
    const colocationStore = useColocationStore();
    const roommateStore = useRoommateStore();
    const { flash } = useSwal();

    return {
      authStore,
      colocationStore,
      flash,
      roommateStore,
    };
  },

  methods: {
    updateSelectedComponent() {
      this.$emit("updateComponent", "RegisterForm");
    },
    ToggleLoading() {
      this.loading = !this.loading;
    },
    async submitForm() {
      if (!this.ValidateForm) {
        return this.flash(
          "Formulaire vide",
          "Veuillez remplir tous les champs",
          "warning"
        );
      }

      this.ToggleLoading();

      try {
        const login = await axios.post("/login", {
          username: this.username,
          password: this.password,
        });

        if (login.status === 200) {
          this.authStore.login();
          this.authStore.setUser(login.data.user);
          let destination = "/welcome";

          if (
            (this.authStore.getUser.relationships &&
              this.authStore.getUser.relationships.owner) ||
            this.authStore.isRoommate
          ) {
            let colocation = null;

            if (this.authStore.isRoommate) {
              colocation = await axios.get(
                `api/colocations/${this.authStore.getColocationId}`
              );
            } else if (
              this.authStore.getUser.relationships &&
              this.authStore.getUser.relationships.owner
            ) {
              colocation = await axios.get(
                `api/colocations/${this.authStore.getUser.relationships.owner.data.id}`
              );
            }

            if (colocation.status !== 200) {
              return this.flash(
                "Erreur !",
                "Un problème est survenue lors de la récupération de la colocation. Merci de contacter l'administrateur",
                "error"
              );
            }

            this.colocationStore.setColocation(colocation.data);

            this.roommateStore.fetchRoomates(
              this.colocationStore.getColocationId
            );

            destination = "/dashboard";
          }

          this.ToggleLoading();

          this.$router.push(destination);
        }
      } catch (error) {
        this.ToggleLoading();

        if (error.response.status === 401) {
          return this.flash(
            "Unauthorized",
            error.response.data.message,
            "error"
          );
        }

        if (error.response.status === 422) {
          return this.flash(
            "Unprocessable Entity",
            "Veuillez reessayer en remplissant tous les champs requis",
            "error"
          );
        }

        return this.flash(
          "Erreur !",
          "Un problème est survenue. Merci de contacter l'administrateur",
          "error"
        );
      }
    },
  },

  computed: {
    ValidateForm() {
      return this.username.length > 0 && this.password.length > 0;
    },
  },
};
</script>
