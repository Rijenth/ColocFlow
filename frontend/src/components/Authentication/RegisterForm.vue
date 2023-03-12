<template>
  <div
    class="md:w-1/2 m-6 border p-4 bg-gray-900 text-white rounded-lg shadow-xl"
  >
    <h2 class="text-lg font-bold mb-2">Formulaire d'inscription</h2>
    <p class="text-s mb-4">
      Pour vous inscrire, nous allons avoir besoin des informations suivantes
    </p>
    <form method="post" @submit.prevent="registerUser" @keydown.space.prevent>
      <input
        class="input-field input-auth"
        type="text"
        placeholder="Nom de famille"
        v-model="user.lastname"
      />

      <input
        class="input-field input-auth"
        type="text"
        placeholder="Prénom"
        v-model="user.firstname"
      />

      <input
        class="input-field input-auth"
        type="text"
        placeholder="Nom d'utilisateur"
        v-model="user.username"
      />

      <input
        class="input-field input-auth"
        type="password"
        placeholder="Mot de passe"
        v-model="user.password"
      />

      <input
        class="input-field input-auth"
        type="password"
        placeholder="Confirmer votre mot de passe"
        v-model="confirmPassword"
      />

      <LoadingButton :is-loading="loading" class="w-full" text="Inscription" />

      <div class="text-center mt-2">
        <a
          class="hover:text-blue-800"
          href="/"
          v-on:click.prevent="updateSelectedComponent"
          >Retour</a
        >
      </div>
    </form>
  </div>
</template>

<script lang="ts">
import LoadingButton from "@/components/LoadingButton.vue";
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
    LoadingButton,
  },

  data: () => ({
    user: {
      username: "",
      password: "",
      lastname: "",
      firstname: "",
    } as User,
    confirmPassword: "",
    loading: false,
  }),

  methods: {
    updateSelectedComponent() {
      this.$emit("updateComponent", "LoginForm");
    },
    ToggleLoading() {
      this.loading = !this.loading;
    },
    async registerUser() {
      if (!this.fieldsAreEmpty) {
        flash(
          "Erreur !",
          "Erreur, tous les champs doivent être remplis",
          "warning"
        );
        return;
      }

      if (!this.userPasswordIsConfirmed) {
        flash(
          "Erreur !",
          "Erreur, les mots de passe ne correspondent pas",
          "warning"
        );
        return;
      }

      this.ToggleLoading();

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

            this.ToggleLoading();

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
          this.ToggleLoading();
        });
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
