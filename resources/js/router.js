import { createRouter, createWebHistory } from "vue-router"

import LoginPage from "./pages/LoginPage.vue"
import SelectPage from "./pages/SelectPage.vue"
/*import MainApp from "./pages/MainApp.vue"
import Main20App from "./pages/Main20App.vue"
import Main25App from "./pages/Main25App.vue"
import Main28App from "./pages/Main28App.vue"*/
import Grid from "./pages/Grid.vue"
import Print from "./pages/Print.vue"
import RiceGrid from "./pages/RiceGrid.vue"

const routes = [
  { path: "/login", name: "login", component: LoginPage },
  { path: "/select", name: "select", component: SelectPage },
  { path: "/grid", name: "grid", component: Grid },
  { path: "/print", name: "print", component: Print },
  { path: "/ricegrid", name: "ricegrid", component: RiceGrid },
  /*{ path: "/app", name: "app", component: MainApp },
  { path: "/app20", name: "app20", component: Main20App },
  { path: "/app25", name: "app25", component: Main25App },
  { path: "/app28", name: "app28", component: Main28App },*/
  { path: "/", redirect: "/login" },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

/* -------------- 認証ガード ---------------- */
router.beforeEach((to, from, next) => {
  const loggedIn = localStorage.getItem("loggedIn")

  if (to.name !== "login" && !loggedIn) {
    next("/login")
  } else {
    next()
  }
})

export default router

