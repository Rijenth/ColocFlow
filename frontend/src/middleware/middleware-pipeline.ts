import type {
  NavigationGuardNext,
  RouteLocationNormalized,
  RouteLocationRaw,
} from "vue-router";

interface middlewareContext {
  to: RouteLocationNormalized;
  from: RouteLocationNormalized;
  next: NavigationGuardNext;
}

export default function middlewarePipeline(
  context: middlewareContext,
  middleware: any[],
  index: number
) {
  const nextMiddleware = middleware[index];

  if (!nextMiddleware) {
    return context.next;
  }

  return (param: RouteLocationRaw) => {
    if (param) return context.next(param);

    const nextPipeline = middlewarePipeline(context, middleware, index + 1);

    nextMiddleware({ ...context, next: nextPipeline });
  };
}