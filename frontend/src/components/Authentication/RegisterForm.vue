<template>
  <div class="flex h-screen items-center justify-center">
    <div class="w-80 p-4 bg-gray-900 text-white rounded-lg shadow-xl">
      <h2 class="text-lg font-bold mb-2">Formulaire d'inscription</h2>
      <p class="text-s mb-4">
        Pour vous inscrire, nous allons avoir besoin des informations suivantes
      </p>
      <form method="post" @submit.prevent="registerUser">
        <input
          class="input-field input-register"
          type="text"
          placeholder="Nom de famille"
          v-model="user.lastname"
          @keydown.space.prevent
        />

        <input
          class="input-field input-register"
          type="text"
          placeholder="Prénom"
          v-model="user.firstname"
          @keydown.space.prevent
        />

        <input
          class="input-field input-register"
          type="text"
          placeholder="Nom d'utilisateur"
          v-model="user.username"
          @keydown.space.prevent
        />

        <input
          class="input-field input-register"
          type="password"
          placeholder="Mot de passe"
          v-model="user.password"
          @keydown.space.prevent
        />

        <input
          class="input-field input-register"
          type="password"
          placeholder="Confirmer votre mot de passe"
          v-model="confirmPassword"
          @keydown.space.prevent
        />

        <RedirectButton text="Inscription" />
        <RedirectButton @click="updateSelectedComponent" text="Retour" />
      </form>
    </div>
  </div>
</template>

<script lang="ts">
import RedirectButton from "./RedirectButton.vue";
import { useSwal } from "@/composables/useSwal";
import axios from "@/axios/axios";

const { flash } = useSwal();

interface User {
  username: string;
  password: string;
  lastname: string;
  firstname: string;
}

export default {
  name: "RegisterForm",

  components: {
    RedirectButton,
  },

  data: () => ({
    user: {
      username: "",
      password: "",
      lastname: "",
      firstname: "",
    } as User,
    confirmPassword: "",
  }),

  methods: {
    updateSelectedComponent() {
      this.$emit("updateComponent", "LoginForm");
    },
    async registerUser() {
      if (!this.fieldsAreEmpty) {
        flash(
          "Erreur !",
          "Erreur, tous les champs doivent être remplis",
          "info"
        );
        return;
      }

      if (!this.userPasswordIsConfirmed) {
        flash(
          "Erreur !",
          "Erreur, les mots de passe ne correspondent pas",
          "error"
        );
        return;
      }

      const token = await axios.get("/sanctum/csrf-cookie");

      if (token.status === 204) {
        const body = {
          data: {
            attributes: {
              username: this.user.username,
              password: this.user.password,
              lastname: this.user.lastname,
              firstname: this.user.firstname,
            },
          },
        };

        await axios
          .post("/register", JSON.stringify(body), {
            headers: {
              "Content-Type": "application/json",
            },
          })
          .then((response) => {
            if (response.status === 201) {
              flash("Succès !", "Vous êtes maintenant inscrit !", "success");
              this.updateSelectedComponent();
            }
          })
          .catch((error) => {
            if (error.response.status === 422) {
              flash(
                "Unprocessable entity",
                "Ce nom d'utilisateur est déjà utilisé",
                "error"
              );
            } else if (error.response.status === 401) {
              flash(
                "Unauthorized",
                "Vous n'êtes pas autorisé à accéder à cette page",
                "error"
              );
            } else {
              flash(
                "Internal server error",
                "Une erreur est survenue, merci de contacter l'administrateur",
                "error"
              );
            }
          });
      }
    },
  },

  computed: {
    userPasswordIsConfirmed() {
      return this.user.password === this.confirmPassword;
    },
    fieldsAreEmpty() {
      return (
        this.user.username !== "" &&
        this.user.password !== "" &&
        this.user.lastname !== "" &&
        this.user.firstname !== ""
      );
    },
  },
};
</script>
