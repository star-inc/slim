import Vue from "vue";
import VueRouter from "vue-router";
import manage from "./manage";
import Manage from "../views/Manage";
import Logout from "@/views/Logout";

Vue.use(VueRouter);

const routes = [
  {
    path: "/manage",
    name: "Manage",
    component: Manage,
    children: manage
  },
  {
    path: "/logout",
    name: "Logout",
    component: Logout
  }
];

const router = new VueRouter({
  routes
});

export default router;
