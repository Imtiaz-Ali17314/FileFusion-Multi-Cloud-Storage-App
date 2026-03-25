<script>
import NavBar from "./components/NavBar.vue";
export default {
    name: "App",
    components: {
        NavBar,
    },
};
</script>

<template>
    <NavBar />

    <div id="router-view" class="app-bg">
        <router-view v-slot="{ Component, route }">
            <transition name="page" mode="out-in">
                <component :is="Component" :key="route.fullPath"></component>
            </transition>
        </router-view>
    </div>
</template>

<style>
/* ========== Page Transitions ========== */
#router-view {
    overflow-x: hidden;
    padding: 0;
    position: relative;
}

.page-enter-active {
    animation: pageEnter 0.35s cubic-bezier(0.4, 0, 0.2, 1) both;
}

.page-leave-active {
    animation: pageLeave 0.2s cubic-bezier(0.4, 0, 0.2, 1) both;
}

@keyframes pageEnter {
    from {
        opacity: 0;
        transform: translateY(16px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pageLeave {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-8px);
    }
}
</style>
