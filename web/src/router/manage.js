import Overview from "@/views/Manage/Overview.vue";
import Users from "@/views/Manage/Users";
import Products from "@/views/Manage/Products";
import Provider from "@/views/Manage/Providers";
import Levels from "@/views/Manage/Levels";

export default [
  {
    path: "",
    name: "Overview",
    component: Overview
  },
  {
    path: "users",
    name: "Users",
    component: Users
  },
  {
    path: "products",
    name: "Products",
    component: Products
  },
  {
    path: "providers",
    name: "Providers",
    component: Provider
  },
  {
    path: "levels",
    name: "Levels",
    component: Levels
  }
];
