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
import LoadingButton from "./LoadingButton.vue";
import { useAuthStore } from "@/stores/useAuthStore";
import { useColocationStore } from "@/stores/useColocationStore";
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
    const { flash } = useSwal();

    return {
      authStore,
      colocationStore,
      flash,
    };
  },

  methods: {
    updateSelectedComponent() {
      this.$emit("updateComponent", "RegisterForm");
    },
    toggleLoading() {
      this.loading = !this.loading;
    },
    async submitForm() {
      if (!this.formIsValid) {
        return this.flash(
          "Formulaire vide",
          "Veuillez remplir tous les champs",
          "warning"
        );
      }

      this.toggleLoading();

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
            this.authStore.getColocationId !== null
          ) {
            let colocation = null;

            if (this.authStore.getColocationId !== null) {
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

            this.toggleLoading();

            destination = "/dashboard";
          }

          this.toggleLoading();

          this.$router.push(destination);
        }
      } catch (error) {
        this.toggleLoading();

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
    formIsValid() {
      return this.username.length > 0 && this.password.length > 0;
    },
  },
};
</script>
