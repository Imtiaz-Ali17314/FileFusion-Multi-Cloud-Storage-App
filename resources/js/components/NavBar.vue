<script>
export default {
    name: "Navbar",
    data() {
        return {
            isNavbarOpen: false,
        };
    },
    methods: {
        toggleNavbar() {
            this.isNavbarOpen = !this.isNavbarOpen;
        },
    },
};
</script>

<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <router-link class="navbar-brand" to="/">
                <img src="/images/logo-icon.png" alt="Logo" style="height: 50px;" />
                FileFusion
            </router-link>

            <button class="navbar-toggler" type="button" @click="toggleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" :class="{ show: isNavbarOpen }" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <router-link class="nav-link active" to="/home">Home</router-link>
                    </li>

                    <li class="nav-item">
                        <router-link class="nav-link" to="/posts">Post</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" to="/uploads">Users</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" to="/gdrive">GoogleDrive</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" to="/odrive">OneDrive</router-link>
                    </li>
                    <!-- <li class="nav-item">
                        <router-link class="nav-link" to="/about"
                            >About</router-link
                        >
                    </li> -->
                </ul>
            </div>
        </div>
    </nav>
</template>

<style scoped>
.navbar-brand {
    display: flex;
    align-items: center;
    color: #0087ff;
        font-weight: bold;
        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
}

.navbar-brand:hover {
    color: #0087ff;
}
</style>