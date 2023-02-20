<template>
  <div class="flex h-screen items-center justify-center">
    <div class="w-80 p-4 bg-gray-900 text-white rounded-lg shadow-xl">
      <h2 class="text-lg font-bold mb-2">Bienvenue sur ColocFlow</h2>
      <p class="text-s mb-4">Votre logiciel de gestion de colocation</p>
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
        <RedirectButton text="Connexion" />
        <RedirectButton @click="updateSelectedComponent" text="Inscription" />
      </form>
    </div>
  </div>
</template>

<script lang="ts">
import axios from "@/axios/axios";
import RedirectButton from "./RedirectButton.vue";
import { useAuthStore } from "@/stores/useAuthStore";
import { useSwal } from "@/composables/useSwal";

export default {
  name: "LoginForm",

  components: {
    RedirectButton,
  },

  data() {
    return {
      username: "" as string,
      password: "" as string,
    };
  },

  setup() {
    const authStore = useAuthStore();
    const { flash } = useSwal();

    return {
      authStore,
      flash,
    };
  },

  methods: {
    updateSelectedComponent() {
      this.$emit("updateComponent", "RegisterForm");
    },
    async submitForm() {
      if (!this.formIsValid) {
        this.flash(
          "Formulaire vide",
          "Veuillez remplir tous les champs",
          "warning"
        );
        return;
      }

      const token = await axios.get("/sanctum/csrf-cookie");

      if (token.status !== 204) {
        this.flash(
          "Authentification Error",
          "Une erreur est survenue. Merci de contacter l'administrateur",
          "error"
        );
        return;
      }

      await axios
        .post("/login", {
          username: this.username,
          password: this.password,
        })
        .then((response) => {
          if (response.status === 200) {
            this.authStore.login();
            this.authStore.setToken(response.data.token);
            this.authStore.setUser(response.data.user);

            if (this.authStore.getUser.relationships && this.authStore.getUser.relationships.colocation) {
              this.$router.push("/dashboard");
            } else {
              this.$router.push("/welcome");
            }
          }
        })
        .catch((error) => {
          if (error.response.status === 401) {
            this.flash("Unauthorized", error.response.data.message, "error");
            return;
          }

          if (error.response.status === 422) {
            this.flash(
              "Unprocessable Entity",
              "Veuillez reessayer en remplissant tous les champs requis",
              "error"
            );
            return;
          }

          this.flash(
            "Unknown",
            "Une erreur est survenue. Merci de contacter l'administrateur",
            "error"
          );
        });
    },
  },

  computed: {
    formIsValid() {
      return this.username.length > 0 && this.password.length > 0;
    },
  },
};
</script>
