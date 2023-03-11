import axios from "../axios/axios";
import { useAuthStore } from "@/stores/useAuthStore";
import { useColocationStore } from "@/stores/useColocationStore";
import { useRoommateStore } from "@/stores/useRoommateStore";
import { useSwal } from "./useSwal";
import router from "@/router";

export function useLogout() {
  function logout() {
    const { flash } = useSwal();
    const authStore = useAuthStore();
    const colocationStore = useColocationStore();
    const roommateStore = useRoommateStore();

    axios
      .post("/logout")
      .then(() => {
        authStore.logout();
        authStore.unsetUser();
        colocationStore.unSetColocation();
        roommateStore.unSetRoomates();
        sessionStorage.clear();

        if (router.currentRoute.value.name !== "home") {
          router.push({ name: "home" });
        }
      })
      .catch(() => {
        flash(
          "Error Unauthenticated",
          "This action can only be performed while authenticated",
          "error"
        );
        return;
      });
  }

  return { logout };
}
