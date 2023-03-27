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
import { useColocationChargeStore } from "@/stores/useColocationChargeStore";
import { useRoommateStore } from "@/stores/useRoommateStore";
import type { AxiosResponse } from "axios";

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
    ColocationHasBeenCreated(value: boolean) {
      this.colocationCreated = value;
    },
    async CreateColocation() {
      const body = sessionStorage.getItem("colocation") as string;

      if (!body) {
        this.flash(
          "Données manquantes !",
          "Aucune donnée permettant la création de votre colocation n'a été retrouvée.",
          "error"
        );

        this.loading = false;

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
          const relationships = sessionStorage.getItem(
            "relationships"
          ) as string;

          const owner = createColocation.data.included.owner.data;

          if (owner !== undefined) {
            this.colocationStore.setOwnerFirstName(owner.attributes.firstname);
          }

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
                colocationUpdated.data.included.owner.data
              );
            }
          } else {
            this.colocationStore.setColocation(colocation);

            this.authStore.setUser(owner);
          }

          await this.roommateStore.fetchRoomates(
            this.colocationStore.getColocationId
          );

          await this.colocationChargeStore.fetchColocationCharges(
            this.colocationStore.getColocationId
          );

          this.flash(
            "Succès !",
            "Votre colocation a bien été crée.",
            "success"
          );

          this.$router.push({ name: "dashboard" });
        }
      } catch (error) {
        const e = error as Error & { response: AxiosResponse | undefined };

        if (
          e.response !== undefined &&
          e.response.statusText &&
          e.response.data.message !== undefined
        ) {
          this.flash(
            e.response.statusText + " !",
            e.response.data.message,
            "error"
          );
        } else {
          this.flash("Erreur !", e.message, "error");
        }

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
