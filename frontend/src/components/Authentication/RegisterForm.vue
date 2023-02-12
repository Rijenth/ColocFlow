<template>
  <div class="flex h-screen items-center justify-center">
    <div class="w-80 p-4 bg-gray-900 text-white rounded-lg shadow-xl">
      <h2 class="text-lg font-bold mb-2">Formulaire d'inscription</h2>
      <p class="text-s mb-4">
        Pour vous inscrire, nous allons avoir besoin des informations suivantes
      </p>

      <input
        class="border-2 rounded border-gray-300 w-full p-2 mb-4"
        type="text"
        placeholder="Nom de famille"
      />

      <input
        class="border-2 rounded border-gray-300 w-full p-2 mb-4"
        type="text"
        placeholder="Prénom"
      />

      <input
        class="border-2 rounded border-gray-300 w-full p-2 mb-4"
        type="text"
        placeholder="Nom d'utilisateur"
      />

      <input
        class="border-2 rounded border-gray-300 w-full p-2 mb-4"
        type="password"
        placeholder="Mot de passe"
      />

      <input
        class="border-2 rounded border-gray-300 w-full p-2 mb-4"
        type="password"
        placeholder="Confirmer votre mot de passe"
      />

      <RedirectButton text="Inscription" />
      <RedirectButton @click="updateSelectedComponent" text="Retour" />
    </div>
  </div>
</template>

<script lang="ts">
import RedirectButton from "./RedirectButton.vue";

export default {
  name: "RegisterForm",

  components: {
    RedirectButton,
  },

  methods: {
    updateSelectedComponent() {
      this.$emit("updateComponent", "LoginForm");
    },
  },
};
</script>
