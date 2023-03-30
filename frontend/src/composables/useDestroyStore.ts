import { useAuthStore } from "@/stores/useAuthStore";
import { useColocationStore } from "@/stores/useColocationStore";
import { useColocationChargeStore } from "@/stores/useColocationChargeStore";
import { useRoommateStore } from "@/stores/useRoommateStore";

export const useDestroyStore = () => {
  const authStore = useAuthStore();
  const colocationStore = useColocationStore();
  const colocationChargeStore = useColocationChargeStore();
  const roommateStore = useRoommateStore();

  function destroyStore() {
    authStore.logout();
    authStore.unsetUser();
    colocationStore.unSetColocation();
    colocationChargeStore.unSetColocationCharges();
    roommateStore.unSetRoomates();
    sessionStorage.clear();
  }

  return { destroyStore };
};
