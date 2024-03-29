<template>
  <div class="bg-gray-900 dashboard-overview-card">
    <h2 class="text-sm underline text-center font-bold mb-4">
      Vos informations
    </h2>
    <div class="flex flex-col mb-5">
      <label for="lastname" class="mb-4">
        Nom de famille :
        <p v-if="updateInfo === false">
          {{ user.attributes.lastname }}
        </p>
        <input
          v-else
          class="text-sm w-full px-2 py-1 rounded text-black text-left"
          type="text"
          v-model="updatedUser.attributes.lastname"
        />
      </label>

      <label for="firstname" class="mb-4">
        Prénom :
        <p v-if="updateInfo === false">
          {{ user.attributes.firstname }}
        </p>
        <input
          v-else
          class="text-sm w-full px-2 py-1 rounded text-black text-left"
          type="text"
          v-model="updatedUser.attributes.firstname"
        />
      </label>

      <label for="email">
        Adresse email :
        <p v-if="updateInfo === false">
          {{ user.attributes.email }}
        </p>
        <input
          v-else
          class="text-sm w-full px-2 py-1 rounded text-black text-left"
          type="email"
          v-model="updatedUser.attributes.email"
        />
      </label>
    </div>

    <div class="flex flex-row justify-around">
      <LoadingButton
        v-if="updateInfo === false"
        class="main-button"
        @click="setInfoInputsData"
        :text="'Modifier mes informations'"
      />

      <LoadingButton
        v-if="updateInfo === true"
        @click="updateUser"
        class="sub-button"
        :text="'Envoyer'"
      />

      <LoadingButton
        v-if="updateInfo === true"
        @click="updateInfo = false"
        class="sub-button"
        :text="'Annuler'"
      />
    </div>

    <div class="flex flex-row justify-center">
      <LoadingButton
        v-if="updateInfo === true"
        class="bg-red-600 hover:bg-red-900 font-bold text-sm"
        @click="deleteUser"
        :is-loading="deleting"
        :text="'Supprimer l\'utilisateur'"
      />
    </div>
  </div>
</template>

<script lang="ts">
import type { User } from "@/stores/useAuthStore";
import { useColocationStore } from "@/stores/useColocationStore";
import LoadingButton from "@/components/LoadingButton.vue";
import type { AxiosResponse } from "axios";
import { useDestroyStore } from "@/composables/useDestroyStore";

type UserAttributes = {
  firstname: string;
  lastname: string;
  email: string;
  [key: string]: string;
};

export default {
  name: "UserInfo",

  components: {
    LoadingButton,
  },

  setup() {
    const colocationStore = useColocationStore();
    const { destroyStore } = useDestroyStore();

    return { colocationStore, destroyStore };
  },

  computed: {
    user(): User {
      return this.userStore.getUser;
    },
  },

  data() {
    return {
      updatedUser: {
        attributes: {
          firstname: "",
          lastname: "",
          email: "",
        },
      },
      loading: false,
      updateInfo: false,
      deleting: false,
    };
  },

  methods: {
    async deleteUser() {
      const response = window.confirm(
        "Êtes-vous sûr de vouloir supprimer votre compte ?"
      );

      if (!response) {
        return;
      }

      this.loading = true;

      try {
        const response = await this.userStore.deleteUser();

        if (response.status === 204) {
          this.flash("Succès", "L'utilisateur a bien été supprimé", "success");

          this.destroyStore();

          this.$router.push({ name: "home" });
        }
      } catch (error) {
        const e = error as Error & { response: AxiosResponse | undefined };

        if (e.response !== undefined) {
          this.flash(e.response.statusText, e.response.data.message, "error");
        } else {
          this.flash("Une erreur est survenue", e.message, "error");
        }
      }

      this.loading = false;
    },
    async updateUser() {
      if (
        this.updatedUser.attributes.firstname === "" ||
        this.updatedUser.attributes.lastname === "" ||
        this.updatedUser.attributes.email === ""
      ) {
        return this.flash(
          "Formulaire vide",
          "Les champs ne peuvent pas être vides",
          "warning"
        );
      }

      if (
        this.updatedUser.attributes.firstname ===
          this.user.attributes.firstname &&
        this.updatedUser.attributes.lastname ===
          this.user.attributes.lastname &&
        this.updatedUser.attributes.email === this.user.attributes.email
      ) {
        return this.flash(
          "Aucune modification",
          "Vous n'avez pas modifié vos informations",
          "warning"
        );
      }

      if (this.isValidEmail() === false) {
        return this.flash(
          "Email invalide",
          "L'adresse email n'est pas valide",
          "warning"
        );
      }

      const originalAttributes: UserAttributes = this.user.attributes;
      const updatedAttributes: UserAttributes = {
        firstname: this.updatedUser.attributes.firstname,
        lastname: this.updatedUser.attributes.lastname,
        email: this.updatedUser.attributes.email,
      };

      for (const key in updatedAttributes) {
        if (updatedAttributes[key] === originalAttributes[key]) {
          delete updatedAttributes[key];
        }
      }

      this.toggleLoading();

      try {
        const response = await this.userStore.updateUser(updatedAttributes);

        if (response.status === 200) {
          this.flash(
            "Modification réussie",
            "Vos informations ont bien été modifiées",
            "success"
          );

          this.updateInfo = false;

          this.colocationStore.setOwnerFirstName(
            this.updatedUser.attributes.firstname
          );
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
    setInfoInputsData() {
      this.updatedUser.attributes.firstname = this.user.attributes.firstname;
      this.updatedUser.attributes.lastname = this.user.attributes.lastname;
      this.updatedUser.attributes.email = this.user.attributes.email;
      this.updateInfo = true;
    },
    toggleLoading() {
      if (this.loading === false) {
        this.loading = true;
      } else {
        this.loading = false;
      }
    },
    isValidEmail() {
      return /^[^@]+@\w+(\.\w+)+\w$/.test(this.updatedUser.attributes.email);
    },
  },

  props: {
    userStore: {
      type: Object,
      required: true,
    },
    flash: {
      type: Function as () => Function,
      required: true,
    },
  },
};
</script>
