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
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  middleware: any[],
  index: number
) {
  const nextMiddleware = middleware[index];

  if (nextMiddleware === undefined) {
    return context.next;
  }

  return (param: RouteLocationRaw) => {
    // eslint-disable-next-line @typescript-eslint/strict-boolean-expressions
    if (param) return context.next(param);

    const nextPipeline = middlewarePipeline(context, middleware, index + 1);

    nextMiddleware({ ...context, next: nextPipeline });
  };
}
