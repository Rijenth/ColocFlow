import axios from "@/services/axios";
import router from "@/router";
import type { AxiosResponse } from "axios";
import { useDestroyStore } from "./useDestroyStore";

export function useLogout() {
  function logout() {
    const { destroyStore } = useDestroyStore();

    axios
      .post("/logout")
      .then(() => {
        destroyStore();

        if (router.currentRoute.value.name !== "home") {
          router.push({ name: "home" });
        }
      })
      .catch((error) => {
        const e = error as Error & { response: AxiosResponse };
        console.error(e.response.data);
      });
  }

  return { logout };
}
