<template>
  <div
    class="bg-gray-900 w-xs md:w-full max-w-xs mx-auto p-4 mb-4 text-white border rounded-lg shadow-xl md:border-none"
  >
    <div>
      <h2 class="text-sm text-center font-bold mb-4">
        Détails des charges attribuées {{ roommate_name }}
      </h2>

      <ul class="text-sm">
        <li v-if="user_id === null" class="text-left text-xs">
          <p>Aucun colocataire sélectionné</p>
        </li>
        <li
          v-for="charge in getUserCharges(user_id)"
          :key="charge.id"
          class="flex justify-between"
        >
          <p>{{ $t("colocation.charges." + charge.attributes.name) }}</p>
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
        <option value="" disabled selected>Selectionner un colocataire</option>
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
  </div>
</template>

<script lang="ts">
export default {
  name: "ChargesResume",

  data() {
    return {
      user_id: null as number | null,
      roommate_name: "" as string,
    };
  },

  props: {
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
    updateComponentData(value: string) {
      const [userId, roommateName] = value.split("|");
      this.user_id = parseInt(userId);
      this.roommate_name = "à " + roommateName;
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
