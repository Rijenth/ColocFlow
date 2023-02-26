import axios from "../axios/axios";
import { useAuthStore } from "@/stores/useAuthStore";
import { useColocationStore } from "@/stores/useColocationStore";
import { useSwal } from "./useSwal";

export function useLogout() {
  function logout() {
    const { flash } = useSwal();
    const authStore = useAuthStore();
    const colocationStore = useColocationStore();

    axios
      .post("/logout")
      .then(() => {
        authStore.logout();
        authStore.unsetUser();
        colocationStore.unSetColocation();
      })
      .catch(() => {
        flash(
          "Error",
          "This action can only be performed while authenticated",
          "error"
        );
        return;
      });
  }

  return { logout };
}
