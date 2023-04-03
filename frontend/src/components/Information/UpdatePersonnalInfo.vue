<template>
  <div class="bg-gray-700 dashboard-overview-card">
    <h2 class="text-lg text-center font-semibold">
      Mise à jour de l'utilisateur
    </h2>
  </div>

  <div class="flex flex-col md:flex-row">
    <UserInfo :flash="flash" :userStore="storeAuth" />
    <UserPassword :flash="flash" :userStore="storeAuth" />
  </div>
</template>

<script lang="ts">
import { useAuthStore } from "@/stores/useAuthStore";
import UserInfo from "@/components/Information/UpdatePersonnalInfo/UserInfo.vue";
import UserPassword from "@/components/Information/UpdatePersonnalInfo/UserPassword.vue";
import { useSwal } from "@/composables/useSwal";

export default {
  name: "UpdatePersonnalInfo",

  components: {
    UserInfo,
    UserPassword,
  },

  setup() {
    const authStore = useAuthStore();
    const { flash } = useSwal();

    return {
      authStore,
      flash,
    };
  },

  data() {
    return {
      loading: false,
    };
  },

  computed: {
    storeAuth() {
      return this.authStore;
    },
  },
};
</script>
