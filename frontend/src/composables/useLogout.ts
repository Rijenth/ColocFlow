import axios from "@/services/axios";
import { useAuthStore } from "@/stores/useAuthStore";
import { useColocationStore } from "@/stores/useColocationStore";
import { useColocationChargeStore } from "@/stores/useColocationChargeStore";
import { useRoommateStore } from "@/stores/useRoommateStore";
import { useSwal } from "./useSwal";
import router from "@/router";

export function useLogout() {
  function logout() {
    const { flash } = useSwal();
    const authStore = useAuthStore();
    const colocationStore = useColocationStore();
    const colocationChargeStore = useColocationChargeStore();
    const roommateStore = useRoommateStore();

    axios
      .post("/logout")
      .then(() => {
        authStore.logout();
        authStore.unsetUser();
        colocationStore.unSetColocation();
        colocationChargeStore.unSetColocationCharges();
        roommateStore.unSetRoomates();
        sessionStorage.clear();

        if (router.currentRoute.value.name !== "home") {
          router.push({ name: "home" });
        }
      })
      .catch(() => {
        window.location.reload();
      });
  }

  return { logout };
}
