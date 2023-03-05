import {
  HasNoColocation,
  HasColocation,
} from "./../middleware/colocationMiddleware";
import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import authMiddleware from "@/middleware/authMiddleware";
import middlewarePipeline from "@/middleware/middleware-pipeline";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: HomeView,
    },
    {
      path: "/about",
      name: "about",
      component: () => import("../views/AboutView.vue"),
    },
    {
      path: "/welcome",
      name: "welcome",
      component: () => import("../views/WelcomePageView.vue"),
      meta: {
        middleware: [authMiddleware, HasNoColocation],
      },
    },
    {
      path: "/dashboard",
      name: "dashboard",
      component: () => import("../views/DashboardView.vue"),
      meta: {
        middleware: [authMiddleware, HasColocation],
      },
    },
    {
      path: "/:pathMatch(.*)*",
      name: "not-found",
      component: () => import("../views/NotFoundView.vue"),
    },
  ],
});

router.beforeEach((to, from, next) => {
  if (!to.meta.middleware) {
    return next();
  }
  const middleware = to.meta.middleware;

  if (!Array.isArray(middleware)) {
    throw new Error("Middleware must be an array of functions");
  }

  const context = {
    to,
    from,
    next,
  };

  return middleware[0]({
    ...context,
    next: middlewarePipeline(context, middleware, 1),
  });
});

export default router;
