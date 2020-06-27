import Router from "vue-router";
import HomePage from "@/components/HomePage";
import Login from "@/components/auth/Login";
import Dashboard from "@/components/Dashboard";
import Register from "@/components/auth/Register";
import EventsPage from "@/components/EventsPage";
import ChartsPage from "@/components/chart/ChartsPage";
import store from "@/store";

const ifNotAuth = (to, from, next) => {
  if (!store.getters.isAuthenticated) {
    next();
    return;
  }
  next("/");
};

const ifAuth = (to, from, next) => {
  if (store.getters.isAuthenticated) {
    next();
    return;
  }
  next("/login");
};

const router = new Router({
  mode: "history",
  routes: [
    {
      path: "/",
      name: "HomePage",
      component: HomePage,
    },
    {
      path: "/login",
      name: "Login",
      component: Login,
      meta: {
        auth: false,
      },
      beforeEnter: ifNotAuth,
    },
    {
      path: "/register",
      name: "Register",
      component: Register,
      meta: {
        auth: false,
      },
      beforeEnter: ifNotAuth,
    },
    {
      path: "/dash",
      name: "DashBoard",
      component: Dashboard,
      meta: {
        auth: true,
      },
      beforeEnter: ifAuth,
    },
    {
      path: "/events",
      name: "EventPage",
      component: EventsPage,
      meta: {
        auth: true,
      },
      beforeEnter: ifAuth,
    },
    {
      path: "/charts",
      name: "ChartsPage",
      component: ChartsPage,
      meta: {
        auth: true,
      },
      beforeEnter: ifAuth,
    },
    {
      path: "/charts/:id",
      name: "ChartsPage",
      component: ChartsPage,
      meta: {
        auth: true,
      },
      beforeEnter: ifAuth,
    },
  ],
});

export default router;
