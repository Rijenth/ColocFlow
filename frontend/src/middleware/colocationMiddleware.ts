import { useColocationStore } from "@/stores/useColocationStore";
import type { NavigationGuardNext } from "vue-router";

function HasNoColocation({ next }: { next: NavigationGuardNext }) {
  const colocationStore = useColocationStore();

  if (colocationStore.colocationIsDefined) {
    return next({ name: "home" });
  }

  return next();
}

function HasColocation({ next }: { next: NavigationGuardNext }) {
  const colocationStore = useColocationStore();

  if (!colocationStore.colocationIsDefined) {
    return next({ name: "home" });
  }

  return next();
}

export { HasNoColocation, HasColocation };
