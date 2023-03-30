<template>
  <div class="bg-gray-900 dashboard-overview-card md:ml-3">
    <h2 class="text-sm underline text-center font-bold mb-4">
      Modifier le code d'accès
    </h2>
    <div class="flex flex-col mb-4">
      <label for="old_access_key" class="mb-2"> Ancien code d'accès : </label>
      <input
        class="text-sm w-full px-2 py-1 rounded text-black text-left"
        :disabled="updateAccessKey === false"
        type="password"
        v-model="old_access_key"
      />

      <label for="new_access_key" class="mb-2"> Nouveau code d'accès : </label>
      <input
        class="text-sm w-full px-2 py-1 rounded text-black text-left"
        :disabled="updateAccessKey === false"
        type="password"
        v-model="new_access_key"
      />

      <label for="new_access_key_confirm" class="mb-2">
        Confirmer le code d'accès :
      </label>
      <input
        class="text-sm w-full px-2 py-1 rounded text-black text-left"
        :disabled="updateAccessKey === false"
        type="password"
        v-model="new_access_key_confirm"
      />
    </div>

    <div class="flex flex-row justify-around">
      <LoadingButton
        class="main-button"
        :disabled="loading"
        @click="updateAccessKey = true"
        :text="'Modifier'"
        v-if="updateAccessKey === false"
      />
      <LoadingButton
        class="sub-button"
        @click="updateColocationPassword"
        :is-loading="loading"
        :text="'Envoyer'"
        v-if="updateAccessKey === true"
      />
      <LoadingButton
        class="sub-button"
        @click="resetForm"
        :disabled="loading"
        :text="'Annuler'"
        v-if="updateAccessKey === true"
      />
    </div>
  </div>
</template>

<script lang="ts">
import LoadingButton from "@/components/LoadingButton.vue";
import { useSwal } from "@/composables/useSwal";
import type { AxiosResponse } from "axios";
import { useColocationStore } from "@/stores/useColocationStore";

export default {
  name: "UpdateColocation",

  components: {
    LoadingButton,
  },

  setup() {
    const colocationStore = useColocationStore();
    const { flash } = useSwal();

    return {
      colocationStore,
      flash,
    };
  },

  data() {
    return {
      loading: false,
      new_access_key: "",
      new_access_key_confirm: "",
      old_access_key: "",
      updateAccessKey: false,
    };
  },

  methods: {
    resetForm() {
      this.new_access_key = "";
      this.new_access_key_confirm = "";
      this.old_access_key = "";
      this.updateAccessKey = false;
    },
    toggleLoading() {
      if (this.loading === false) {
        this.loading = true;
      } else {
        this.loading = false;
      }
    },
    async updateColocationPassword() {
      if (
        this.new_access_key === "" ||
        this.new_access_key_confirm === "" ||
        this.old_access_key === ""
      ) {
        this.flash(
          "Formulaire vide",
          "Veuillez remplir tous les champs",
          "warning"
        );
        return;
      }

      if (this.new_access_key === this.old_access_key) {
        this.flash(
          "Mot de passe identique",
          "Le nouveau mot de passe doit être différent de l'ancien",
          "warning"
        );
        return;
      }

      if (this.new_access_key !== this.new_access_key_confirm) {
        this.flash(
          "Mot de passe non identique",
          "La confirmation du mot de passe ne correspond pas au mot de passe",
          "warning"
        );
        return;
      }

      if (this.new_access_key.length < 4) {
        this.flash(
          "Mot de passe trop court",
          "Le mot de passe doit contenir au moins 4 caractères",
          "warning"
        );
        return;
      }

      this.toggleLoading();

      try {
        const response = await this.colocationStore.updateColocation({
          access_key: this.new_access_key,
          old_access_key: this.old_access_key,
        });

        if (response.data !== undefined) {
          this.toggleLoading();
          this.flash(
            "Succès",
            "Le mot de passe de la colocation a bien été modifié",
            "success"
          );
          this.resetForm();
        }
      } catch (error) {
        const e = error as Error & { response: AxiosResponse | undefined };
        this.toggleLoading();

        if (e.response !== undefined && e.response.status === 422) {
          return this.flash(
            e.response.statusText,
            e.response.data.message,
            "warning"
          );
        } else if (e.response !== undefined) {
          return this.flash(
            e.response.statusText,
            e.response.data.message,
            "error"
          );
        } else {
          return this.flash("Une erreur est survenue", e.message, "error");
        }
      }
    },
  },
};
</script>
