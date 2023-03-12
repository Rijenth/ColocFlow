<template>
  <div class="w-80">
    <CreateColocation
      v-if="colocationCreated === false"
      @colocationCreated="ColocationHasBeenCreated"
    />
    <DefineCharges
      v-else
      @colocationCreated="ColocationHasBeenCreated"
      @expensesIsDefined="ExpensesHasBeenDefined"
      :loading="loading"
    />
  </div>
</template>

<script lang="ts">
import axios from "@/services/axios";
import { useSwal } from "@/composables/useSwal";
import CreateColocation from "@/components/Onboarding/StartColocComponents/CreateColocation.vue";
import DefineCharges from "@/components/Onboarding/StartColocComponents/DefineCharges.vue";
import { useAuthStore } from "@/stores/useAuthStore";
import { useColocationStore } from "@/stores/useColocationStore";
import { useRoommateStore } from "@/stores/useRoommateStore";

export default {
  name: "StartColoc",

  components: {
    CreateColocation,
    DefineCharges,
  },

  data() {
    return {
      colocationCreated: false,
      expensesDefined: false,
      loading: false,
    };
  },

  setup() {
    const authStore = useAuthStore();
    const colocationStore = useColocationStore();
    const roommateStore = useRoommateStore();
    const { flash } = useSwal();

    return {
      authStore,
      colocationStore,
      flash,
      roommateStore,
    };
  },

  methods: {
    ColocationHasBeenCreated(value: boolean) {
      this.colocationCreated = value;
    },
    async CreateColocation() {
      const body = sessionStorage.getItem("colocation");

      if (body === undefined || body === null) {
        this.flash(
          "Erreur !",
          "Aucune donnée permettant la création de votre colocation n'a été retrouvée.",
          "success"
        );
        return;
      }

      try {
        const createColocation = await axios.post(
          "/api/colocations?include=owner",
          JSON.parse(body)
        );

        if (createColocation.status === 200) {
          sessionStorage.removeItem("colocation");
          const colocation = createColocation.data;
          const owner = createColocation.data.included.owner.data[0];
          const relationships = sessionStorage.getItem("relationships");

          if (relationships) {
            const body = JSON.parse(relationships);

            body.data.type = "colocations";

            body.data.id = colocation.data.id;

            const colocationUpdated = await axios.patch(
              `/api/colocations/${colocation.data.id}?include=owner`,
              body
            );

            if (colocationUpdated.status === 200) {
              sessionStorage.removeItem("relationships");

              this.colocationStore.setColocation(colocationUpdated.data);

              this.authStore.setUser(
                colocationUpdated.data.included.owner.data[0]
              );

              this.roommateStore.fetchRoomates(
                this.colocationStore.getColocationId
              );

              this.colocationChargeStore.fetchColocationCharges(
                this.colocationStore.getColocationId
              );
            }
          } else {
            this.colocationStore.setColocation(colocation);

            this.authStore.setUser(owner);
          }

          this.flash(
            "Succès !",
            "Votre colocation a bien été crée.",
            "success"
          );

          this.$router.push({ name: "dashboard" });
        }
      } catch (error) {
        this.flash(
          error.response.statusText + " !",
          error.response.data.message,
          "error"
        );

        this.loading = false;
      }
    },
    ExpensesHasBeenDefined(value: boolean) {
      this.expensesDefined = value;

      this.loading = true;

      if (this.colocationCreated === true && this.expensesDefined === true) {
        this.CreateColocation();
      }
    },
  },
};
</script>
