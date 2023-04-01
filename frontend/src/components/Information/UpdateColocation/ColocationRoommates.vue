<template>
  <div
    class="bg-gray-900 w-xs md:w-80 max-w-xs mx-auto p-4 mb-4 text-white border rounded-lg shadow-xl md:border-none"
  >
    <h2 class="text-sm underline text-center font-bold mb-4">
      Gestion des colocataires
    </h2>

    <div class="text-center">
      <select
        id="colocataire"
        class="text-sm w-full px-2 py-1 mt-2 border rounded text-black"
        v-model="roommate_id"
        @change="
          roommate_id = parseInt(($event.target as HTMLInputElement).value)
        "
      >
        <option :value="0" disabled selected>
          Sélectionnez un colocataire
        </option>

        <option
          v-for="roommate in roommates"
          :key="roommate.id"
          :value="roommate.id"
        >
          {{ roommate.attributes.lastname.toUpperCase() }}.
          {{ roommate.attributes.firstname }}
        </option>
      </select>

      <p
        v-if="roommate_id == auth_user_id"
        class="text-red-400 text-sm italic mt-4 mb-0"
      >
        Vous ne pouvez pas vous retirer de la colocation.
      </p>

      <LoadingButton
        class="text-sm border text-white bg-gray-800 hover:bg-blue-900 rounded mt-4 mb-0"
        @click="removeRoommate"
        :loading="loading"
        :text="'Supprimer le colocataire'"
        v-if="roommate_id != 0 && roommate_id != auth_user_id"
      />
    </div>
  </div>
</template>

<script lang="ts">
import { useAuthStore } from "@/stores/useAuthStore";
import { useColocationStore } from "@/stores/useColocationStore";
import { useColocationChargeStore } from "@/stores/useColocationChargeStore";
import { useRoommateStore } from "@/stores/useRoommateStore";
import LoadingButton from "@/components/LoadingButton.vue";
import type { AxiosResponse } from "axios";
import { useSwal } from "@/composables/useSwal";

export default {
  name: "ColocationRoommates",

  components: {
    LoadingButton,
  },

  data() {
    return {
      auth_user_id: 0,
      roommate_id: 0,
      loading: false,
    };
  },

  methods: {
    async removeRoommate() {
      this.loading = true;

      try {
        const response = await this.colocationStore.removeRoommates([
          this.roommate_id,
        ]);

        if (response.status === 204) {
          this.roommateStore.removeRoommate(this.roommate_id);

          this.roommate_id = 0;

          await this.colocationChargeStore.fetchColocationCharges(
            this.colocationStore.getColocationId
          );

          this.loading = false;

          return this.flash(
            "Colocataire supprimé",
            "Le colocataire a bien été supprimé",
            "success"
          );
        }
      } catch (error) {
        const e = error as Error & { response: AxiosResponse | undefined };

        console.log(e);

        this.loading = false;

        if (e.response !== undefined) {
          return this.flash(
            e.response.statusText,
            e.response.data.message,
            "error"
          );
        }

        return this.flash("Une erreur est survenue", e.message, "error");
      }
    },
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

  mounted() {
    this.auth_user_id = this.authStore.getUser.id;
  },

  computed: {
    roommates() {
      return this.roommateStore.getRoommates;
    },
  },
};
</script>
