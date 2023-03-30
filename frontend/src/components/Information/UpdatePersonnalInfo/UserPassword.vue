<template>
  <div class="bg-gray-900 dashboard-overview-card md:ml-3">
    <h2 class="text-sm underline text-center font-bold mb-4">
      Modifier votre mot de passe
    </h2>
    <div class="flex flex-col mb-4">
      <label for="old_password" class="mb-2"> Ancien mot de passe : </label>
      <input
        class="text-sm w-full px-2 py-1 rounded text-black text-left"
        :disabled="updatePassword === false"
        type="password"
        v-model="old_password"
      />

      <label for="new_password" class="mb-2"> Nouveau mot de passe : </label>
      <input
        class="text-sm w-full px-2 py-1 rounded text-black text-left"
        :disabled="updatePassword === false"
        type="password"
        v-model="new_password"
      />

      <label for="new_password_confirm" class="mb-2">
        Confirmer le mot de passe :
      </label>
      <input
        class="text-sm w-full px-2 py-1 rounded text-black text-left"
        :disabled="updatePassword === false"
        type="password"
        v-model="new_password_confirm"
      />
    </div>

    <div class="flex flex-row justify-around">
      <LoadingButton
        class="main-button"
        :disabled="loading"
        @click="updatePassword = true"
        :text="'Modifier'"
        v-if="updatePassword === false"
      />
      <LoadingButton
        class="sub-button"
        @click="updateUserPassword"
        :is-loading="loading"
        :text="'Envoyer'"
        v-if="updatePassword === true"
      />
      <LoadingButton
        class="sub-button"
        @click="resetForm"
        :disabled="loading"
        :text="'Annuler'"
        v-if="updatePassword === true"
      />
    </div>
  </div>
</template>

<script lang="ts">
import LoadingButton from "@/components/LoadingButton.vue";
import type { AxiosResponse } from "axios";

export default {
  name: "UserPassword",

  components: {
    LoadingButton,
  },

  data() {
    return {
      old_password: "",
      new_password: "",
      new_password_confirm: "",
      updatePassword: false,
      loading: false,
    };
  },

  methods: {
    resetForm() {
      this.old_password = "";
      this.new_password = "";
      this.new_password_confirm = "";
      this.updatePassword = false;
    },
    toggleLoading() {
      if (this.loading === false) {
        this.loading = true;
      } else {
        this.loading = false;
      }
    },
    async updateUserPassword() {
      if (
        this.new_password === "" ||
        this.new_password_confirm === "" ||
        this.old_password === ""
      ) {
        return this.flash(
          "Formulaire vide",
          "Les champs ne peuvent pas être vides",
          "warning"
        );
      }

      if (
        this.new_password.length < 5 ||
        this.new_password_confirm.length < 5 ||
        this.old_password.length < 5
      ) {
        return this.flash(
          "Mot de passe trop court",
          "Le mot de passe doit contenir au moins 5 caractères",
          "warning"
        );
      }

      if (this.new_password !== this.new_password_confirm) {
        return this.flash(
          "Confirmation de mot de passe",
          "Les mots de passe ne correspondent pas",
          "warning"
        );
      }

      if (this.old_password === this.new_password) {
        return this.flash(
          "Mot de passe identique",
          "Votre ancien mot de passe ne peut être identique à votre nouveau mot de passe",
          "warning"
        );
      }

      this.toggleLoading();

      try {
        const response = await this.storeAuth.updateUser({
          old_password: this.old_password,
          password: this.new_password,
        });

        if (response.status === 200) {
          this.flash(
            "Succès",
            "Votre mot de passe a bien été modifié",
            "success"
          );
          this.resetForm();
        }
      } catch (error) {
        const e = error as Error & { response: AxiosResponse | undefined };

        if (e.response !== undefined) {
          this.flash(e.response.statusText, e.response.data.message, "error");
        } else {
          this.flash("Une erreur est survenue", e.message, "error");
        }
      }

      this.toggleLoading();
    },
  },

  props: {
    flash: {
      type: Function,
      required: true,
    },
    userStore: {
      type: Object,
      required: true,
    },
  },

  computed: {
    storeAuth() {
      return this.userStore;
    },
  },
};
</script>
