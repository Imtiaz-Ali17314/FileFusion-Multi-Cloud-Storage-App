// router.js
import { createRouter, createWebHistory } from "vue-router";

import Home from "./pages/Home.vue";
import DriveFile from "./components/DriveFile.vue";
import OneDrive from "./components/OneDrive.vue";

const routes = [
    { path: "/", redirect: { name: "HomePage" } },
    { path: "/home", name: "HomePage", component: Home },
    { path: "/gdrive", name: "DriveFiles", component: DriveFile },
    { path: "/odrive", name: "OneDrive", component: OneDrive },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
