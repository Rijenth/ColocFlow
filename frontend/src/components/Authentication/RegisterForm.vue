<template>
  <div
    class="flex flex-col items-center md:w-1/2 m-6 border p-4 bg-gray-900 text-white rounded-lg shadow-xl"
  >
    <h2 class="text-lg font-bold mb-2">Formulaire d'inscription</h2>
    <p class="text-s mb-4">
      Pour vous inscrire, nous allons avoir besoin des informations suivantes
    </p>
    <form method="post" @submit.prevent="registerUser" @keydown.space.prevent>
      <input
        class="input-field input-auth"
        @keydown="preventNumericOrSymbolsInput"
        type="text"
        placeholder="Nom de famille"
        v-model="user.lastname"
      />

      <input
        class="input-field input-auth"
        @keydown="preventNumericOrSymbolsInput"
        type="text"
        placeholder="Prénom"
        v-model="user.firstname"
      />

      <input
        class="input-field input-auth"
        type="email"
        placeholder="Adresse email"
        v-model="user.email"
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

      <div class="flex flex-col items-center mt-2">
        <LoadingButton
          :is-loading="loading"
          class="w-full md:w-1/2"
          text="Inscription"
        />
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
import axios from "@/services/axios";
import type { AxiosResponse } from "axios";

const { flash } = useSwal();

interface User {
  email: string;
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
      email: "",
      password: "",
      lastname: "",
      firstname: "",
    } as User,
    confirmPassword: "" as string,
    loading: false as boolean,
  }),

  methods: {
    preventNumericOrSymbolsInput(event: KeyboardEvent) {
      if (event.key.match(/^[0-9]$/)) {
        event.preventDefault();
      }

      // eslint-disable-next-line no-useless-escape
      if (event.key.match(/^[!@#$€£¤%^&*()_+\-=\[\]{};':"\\|,.<>\/?]*$/)) {
        event.preventDefault();
      }
    },
    updateSelectedComponent() {
      this.$emit("updateComponent", "LoginForm");
    },
    toggleLoading() {
      if (this.loading === true) {
        this.loading = false;
      } else {
        this.loading = true;
      }
    },
    async registerUser() {
      if (this.fieldsAreNotEmpty === false) {
        flash(
          "Formulaire vide",
          "Tous les champs doivent être remplis",
          "warning"
        );
        return;
      }

      if (this.userPasswordIsConfirmed === false) {
        flash(
          "Mot de passe non confirmé",
          "Les mots de passe ne correspondent pas",
          "warning"
        );
        return;
      }

      if (this.user.password.length < 5) {
        flash(
          "Mot de passe trop court",
          "Le mot de passe doit contenir au moins 5 caractères",
          "warning"
        );
        return;
      }

      if (this.isValidEmail === false) {
        flash(
          "Adresse email invalide",
          "Merci de saisir une adresse email valide",
          "warning"
        );
        return;
      }

      this.toggleLoading();

      const body = {
        data: {
          attributes: {
            email: this.user.email,
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

            this.toggleLoading();

            this.updateSelectedComponent();
          }
        })
        .catch((error) => {
          const e = error as Error & { response: AxiosResponse | undefined };

          if (e.response !== undefined) {
            flash(
              error.response.statusText,
              error.response.data.message,
              "error"
            );
          } else {
            flash("Une erreur est survenue", e.message, "error");
          }
          this.toggleLoading();
        });
    },
  },

  computed: {
    isValidEmail() {
      return /^[^@]+@\w+(\.\w+)+\w$/.test(this.user.email);
    },
    userPasswordIsConfirmed() {
      return this.user.password === this.confirmPassword;
    },
    fieldsAreNotEmpty() {
      return (
        this.user.email !== "" &&
        this.user.password !== "" &&
        this.user.lastname !== "" &&
        this.user.firstname !== ""
      );
    },
  },
};
</script>
