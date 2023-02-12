<template>
  <div class="flex h-screen items-center justify-center">
    <div class="w-80 p-4 bg-black text-white rounded-lg shadow-xl">
      <h2 class="text-lg font-bold mb-2">Bienvenue sur ColocFlow</h2>
      <p class="text-s mb-4">Votre logiciel de gestion de colocation</p>

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

      <RedirectButton text="Connexion" />

      <RedirectButton @click="updateSelectedComponent" text="Inscription" />
    </div>
  </div>
</template>

<script lang="ts">
import RedirectButton from "./RedirectButton.vue";
export default {
  name: "LoginForm",

  components: {
    RedirectButton,
  },

  methods: {
    updateSelectedComponent() {
      this.$emit("updateComponent", "RegisterForm");
    },
  },
};
</script>
