<template>
  <div
    class="bg-gray-900 w-xs md:w-full max-w-xs mx-auto p-4 mb-4 text-white border rounded-lg shadow-xl md:border-none"
  >
    <div>
      <h2 class="text-sm text-center font-bold mb-4">
        Détails des charges attribuées {{ roommate_name }}
      </h2>

      <ul class="text-sm">
        <li v-if="user_id === 0" class="text-left text-xs">
          <p>Aucun colocataire sélectionné</p>
        </li>
        <li
          v-else-if="getUserCharges(user_id).length === 0"
          class="text-left text-xs"
        >
          <p>Aucune charge attribuée</p>
        </li>
        <li
          v-for="charge in getUserCharges(user_id)"
          :key="charge.id"
          class="flex justify-between"
        >
          <label for="chargeName" class="flex flex-row">
            <input
              class="mr-2"
              type="checkbox"
              v-if="removeCharge"
              :value="charge.id"
            />
            <p id="chargeName">
              {{ $t("colocation.charges." + charge.attributes.name) }}
            </p>
          </label>

          <p>
            {{ getAffectedAmount(this.user_id, charge.id) }}
            €
          </p>
        </li>
      </ul>
    </div>

    <hr class="my-2" />

    <div class="font-bold flex justify-between">
      <p>Total</p>
      <p>{{ totalAffectedAmount(this.user_id) }} €</p>
    </div>

    <hr class="my-2" />

    <div class="block mb-4">
      <select
        id="colocataire"
        class="text-sm w-full px-2 py-1 mt-2 border rounded text-black"
        @change="updateComponentData($event.target.value)"
      >
        <option :value="0" disabled selected>
          Selectionner un colocataire
        </option>
        <option
          v-for="roommate in roommates"
          :key="roommate.id"
          :value="
            roommate.id + '|' + userFirstname(roommate.attributes.firstname)
          "
        >
          {{ userLastname(roommate.attributes.lastname) }}
          {{ userFirstname(roommate.attributes.firstname) }}
        </option>
      </select>
    </div>

    <div class="flex flex-row justify-around">
      <LoadingButton
        @click="deleteCharges"
        class="text-sm border text-white bg-gray-800 hover:bg-blue-900 py-2 px-4 rounded"
        :is-loading="loading"
        text="Valider"
        v-if="removeCharge && user_id !== 0"
      />

      <LoadingButton
        class="text-sm border text-white bg-gray-800 hover:bg-blue-900 py-2 px-4 rounded"
        @click="removeCharge = false"
        text="Annuler"
        v-if="removeCharge && user_id !== 0"
      />

      <button
        class="text-sm border text-white bg-gray-800 hover:bg-blue-900 py-2 px-4 rounded"
        @click="removeCharge = true"
        v-if="!removeCharge && user_id !== 0"
      >
        Retirer des charges
      </button>
    </div>
  </div>
</template>

<script lang="ts">
import LoadingButton from "@/components/LoadingButton.vue";
import { useSwal } from "@/composables/useSwal";

export default {
  name: "ChargesResume",

  components: {
    LoadingButton,
  },

  setup() {
    const { flash } = useSwal();

    return {
      flash,
    };
  },

  data() {
    return {
      user_id: 0 as number,
      roommate_name: "" as string,
      removeCharge: false as boolean,
      loading: false as boolean,
    };
  },

  props: {
    storeAuth: {
      type: Object,
      required: true,
    },
    storeRoommates: {
      type: Object,
      required: true,
    },
    storeCharges: {
      type: Object,
      required: true,
    },
  },

  methods: {
    async deleteCharges() {
      this.loading = !this.loading;

      const checkboxes = document.querySelectorAll(
        "input[type=checkbox]:checked"
      );

      const chargeIds = Array.from(checkboxes).map((el) => el.value);

      if (chargeIds.length === 0) {
        this.flash(
          "Aucun élément sélectionné",
          "Vous devez sélectionner au moins une charge à supprimer",
          "warning"
        );
        this.loading = !this.loading;
        return;
      }

      const body = {
        data: chargeIds.map((chargeId) => {
          return {
            id: chargeId,
            type: "charges",
          };
        }),
      };

      try {
        const response = await this.storeCharges.deleteChargeUserRelationship(
          JSON.stringify(body),
          this.user_id
        );

        if (response && response.status === 204) {
          await this.storeCharges.fetchColocationCharges(
            this.storeAuth.getColocationId
          );

          this.flash(
            "Charge(s) supprimée(s)",
            "Opération effectuée avec succès",
            "success"
          );
        }
      } catch (error) {
        if (error.response) {
          this.flash(
            error.response.statusText,
            error.response.data.message,
            "error"
          );
        } else {
          this.flash(
            "Une erreur est survenue",
            "Impossible de supprimer les charges, si le problème persiste, veuillez contacter l'administrateur du site",
            "error"
          );
        }
      }

      this.removeCharge = false;
      this.loading = !this.loading;
    },
    updateComponentData(value: string) {
      const [userId, roommateName] = value.split("|");
      this.user_id = parseInt(userId);
      this.roommate_name = "à " + roommateName;
      this.removeCharge = false;
    },
  },

  computed: {
    getUserCharges() {
      return (userId: number) => {
        return this.storeCharges.getChargesByUser(userId);
      };
    },
    getAffectedAmount() {
      return (userId: number, chargeId: number) => {
        return this.storeCharges.getUserChargeAffectedAmount(userId, chargeId);
      };
    },
    roommates() {
      return this.storeRoommates.getRoommates;
    },
    totalAffectedAmount() {
      return (userId: number) => {
        return this.storeCharges.getUserTotalAffectedAmount(userId);
      };
    },
    userFirstname() {
      return (firstname: string) => {
        return (
          firstname.charAt(0).toUpperCase() + firstname.slice(1).toLowerCase()
        );
      };
    },
    userLastname() {
      return (lastname: string) => {
        return lastname.toUpperCase();
      };
    },
  },
};
</script>
