import { useAuthStore } from "@/stores/useAuthStore";
import type { NavigationGuardNext } from "vue-router";

export default function authMiddleware({
  next,
}: {
  next: NavigationGuardNext;
}) {
  const authStore = useAuthStore();

  if (!authStore.isAuthenticated) {
    return next({ name: "home" });
  }

  return next();
}
