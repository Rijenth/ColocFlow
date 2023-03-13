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
import LoadingButton from "@/components/LoadingButton.vue";
import { useAuthStore } from "@/stores/useAuthStore";
import { useColocationStore } from "@/stores/useColocationStore";
import { useColocationChargeStore } from "@/stores/useColocationChargeStore";
import { useRoommateStore } from "@/stores/useRoommateStore";
import { useSwal } from "@/composables/useSwal";

export default {
  name: "LoginForm",

  components: {
    LoadingButton,
  },

  data() {
    return {
      username: "admin" as string,
      password: "admin" as string,
      loading: false,
    };
  },

  setup() {
    const authStore = useAuthStore();
    const colocationStore = useColocationStore();
    const colocationChargeStore = useColocationChargeStore();
    const roommateStore = useRoommateStore();
    const { flash } = useSwal();

    return {
      authStore,
      colocationStore,
      colocationChargeStore,
      flash,
      roommateStore,
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
      if (!this.validateForm) {
        return this.flash(
          "Formulaire vide",
          "Veuillez remplir tous les champs",
          "warning"
        );
      }

      this.toggleLoading();

      try {
        await this.authStore.login(this.username, this.password);

        if (this.authStore.isRoommate) {
          await this.colocationStore.fetchColocation(
            this.authStore.getColocationId
          );
        } else if (
          this.authStore.getUser.relationships &&
          this.authStore.getUser.relationships.owner
        ) {
          await this.colocationStore.fetchColocation(
            this.authStore.getUser.relationships.owner.data.id
          );
        }

        if (this.colocationStore.colocationIsDefined) {
          this.roommateStore.fetchRoomates(
            this.colocationStore.getColocationId
          );

          this.colocationChargeStore.fetchColocationCharges(
            this.colocationStore.getColocationId
          );

          this.$router.push("/dashboard");

          return;
        }

        this.$router.push("/welcome");
      } catch (error) {
        this.toggleLoading();

        return this.flash(
          error.response.statusText,
          error.response.data.message,
          "error"
        );
      }
    },
  },

  computed: {
    validateForm() {
      return this.username.length > 0 && this.password.length > 0;
    },
  },
};
</script>
