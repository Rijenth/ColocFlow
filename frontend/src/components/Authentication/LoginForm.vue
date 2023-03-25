<template>
  <div class="flex justify-center md:h-screen md:items-center">
    <div class="w-80 p-4 bg-gray-900 border rounded-lg text-white md:shadow-xl">
      <h2 class="text-lg font-bold mb-2">Bienvenue sur ColocFlow</h2>
      <p class="text-s mb-4">Logiciel de gestion de colocation</p>
      <form @submit.prevent="submitForm">
        <input
          class="input-field input-auth"
          type="email"
          placeholder="Adresse email"
          @keydown.space.prevent
          v-model="email"
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
import type { AxiosResponse } from "axios";

export default {
  name: "LoginForm",

  components: {
    LoadingButton,
  },

  data() {
    return {
      email: "admin@admin.fr" as string,
      password: "admin" as string,
      loading: false as boolean,
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
      if (this.loading === true) {
        this.loading = false;
      } else {
        this.loading = true;
      }
    },
    async submitForm() {
      if (this.isValidEmail === false) {
        return this.flash(
          "Adresse email invalide",
          "Merci de saisir une adresse email valide",
          "warning"
        );
      }

      if (this.validateForm === false) {
        return this.flash(
          "Formulaire vide",
          "Veuillez remplir tous les champs",
          "warning"
        );
      }

      this.toggleLoading();

      try {
        await this.authStore.login(this.email, this.password);

        if (this.authStore.isRoommate === true) {
          const colocationId: number | undefined =
            this.authStore.getColocationId;

          if (colocationId !== undefined) {
            await this.colocationStore.fetchColocation(
              Number(this.authStore.getColocationId)
            );
          } else {
            throw new Error(
              "No colocation id found for this roommate, please contact the administrator"
            );
          }
        } else if (
          this.authStore.getUser.relationships !== undefined &&
          this.authStore.getUser.relationships.owner !== undefined
        ) {
          await this.colocationStore.fetchColocation(
            this.authStore.getUser.relationships.owner.data.id
          );
        }

        if (this.colocationStore.colocationIsDefined === true) {
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
        const e = error as Error & { response: AxiosResponse | undefined };

        this.toggleLoading();

        if (
          e.response !== undefined &&
          e.response.statusText &&
          e.response.data.message !== undefined
        ) {
          return this.flash(
            e.response.statusText,
            e.response.data.message,
            "error"
          );
        }

        return this.flash(
          "Une erreur est survenue",
          "Une erreur inconnue est survenue, merci de contacter l'administrateur",
          "error"
        );
      }
    },
  },

  computed: {
    isValidEmail() {
      return /^[^@]+@\w+(\.\w+)+\w$/.test(this.email);
    },
    validateForm() {
      return this.email.length > 0 && this.password.length > 0;
    },
  },
};
</script>
