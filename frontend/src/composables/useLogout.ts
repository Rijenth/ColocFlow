import axios from "../axios/axios";
import { useAuthStore } from "@/stores/useAuthStore";
import { useSwal } from "./useSwal";

export function useLogout() {
  function logout() {
    const { flash } = useSwal();
    const authStore = useAuthStore();

    axios
      .post("/logout")
      .then(() => {
        authStore.logout();
        authStore.unsetUser();
        authStore.setToken("");
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
