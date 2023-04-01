import axios from "@/services/axios";
import router from "@/router";
import type { AxiosResponse } from "axios";
import { useDestroyStore } from "./useDestroyStore";
import { useSwal } from "./useSwal";

export function useLogout() {
  function logout() {
    const { destroyStore } = useDestroyStore();
    const { flash } = useSwal();

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

        if (e.response.status === 401) {
          destroyStore();
          router.push({ name: "home" });
          return;
        }

        flash(e.response.statusText, e.response.data.message, "error");
      });
  }

  return { logout };
}
