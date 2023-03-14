import type { Pinia } from "pinia";
import { watch } from "vue";

export function useWatchers(pinia: Pinia) {
  watch(
    () => pinia.state.value.authStore,
    (state) => {
      sessionStorage.setItem("authStore", JSON.stringify(state));
    },
    { deep: true }
  );

  watch(
    () => pinia.state.value.colocationStore,
    (state) => {
      sessionStorage.setItem("colocationStore", JSON.stringify(state));
    },
    { deep: true }
  );

  watch(
    () => pinia.state.value.colocationChargeStore,
    (state) => {
      sessionStorage.setItem("colocationChargeStore", JSON.stringify(state));
    },
    { deep: true }
  );

  watch(
    () => pinia.state.value.roommateStore,
    (state) => {
      sessionStorage.setItem("roommateStore", JSON.stringify(state));
    },
    { deep: true }
  );
}
