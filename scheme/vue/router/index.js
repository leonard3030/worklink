import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/site/Home.vue";
import NotFound from "../views/NotFound.vue";
import Dashboard from "../views/admin/dashboard.vue";

Vue.use(VueRouter);
const routes = [
	{
		path: "/",
		name: "Home",
		component: Home,
	},
	{
		path: "/admin",
		name: "Dashboard",
		component: Dashboard,
	},
	{
		path: "/:id",
		name: "Home",
		component: Home,
	},
	{
		path: "*",
		component: NotFound,
		name: "notfound",
	},
];

const router = new VueRouter({
	mode: "history",
	base: process.env.BASE_URL,
	routes,
	duplicateNavigationPolicy: "ignore",
	linkActiveClass: "active",
	scrollBehavior: () => ({
		x: 0,
		y: 0,
	}),
});

export default router;
