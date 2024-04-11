import { createRouter, createWebHistory } from "vue-router";
import ThankYou from './components/ThankYou.vue';

const routes = [
    {
        path: "/",
        component: () => import("./pages/HomeRoute.vue"),
    },
    {
        path: "/test",
        component: () => import("./pages/TestRoute.vue"),
    },
    {
        path: "/thank-you", component: ThankYou
    },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});