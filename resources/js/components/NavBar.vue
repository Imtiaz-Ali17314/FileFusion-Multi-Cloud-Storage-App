<script>
export default {
    name: "Navbar",
    data() {
        return {
            isNavbarOpen: false,
            scrolled: false,
        };
    },
    methods: {
        toggleNavbar() {
            this.isNavbarOpen = !this.isNavbarOpen;
        },
        closeNavbar() {
            this.isNavbarOpen = false;
        },
        handleScroll() {
            this.scrolled = window.scrollY > 10;
        },
    },
    mounted() {
        window.addEventListener("scroll", this.handleScroll);
    },
    beforeUnmount() {
        window.removeEventListener("scroll", this.handleScroll);
    },
};
</script>

<template>
    <nav class="nav-bar" :class="{ 'nav-scrolled': scrolled }">
        <div class="nav-container">

            <!-- LEFT: Logo -->
            <router-link class="navbar-brand" to="/" @click="closeNavbar">
                <div class="logo-wrapper">
                    <img src="/images/logo-icon.png" alt="FileFusion Logo" class="logo" />
                </div>
                <span class="brand-text">FileFusion</span>
            </router-link>

            <!-- MOBILE TOGGLE -->
            <button class="menu-btn" @click="toggleNavbar" :aria-label="isNavbarOpen ? 'Close menu' : 'Open menu'">
                <div class="hamburger" :class="{ active: isNavbarOpen }">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>

            <!-- CENTER + RIGHT -->
            <div class="nav-content" :class="{ open: isNavbarOpen }">

                <!-- LINKS -->
                <ul class="nav-links">
                    <li>
                        <router-link to="/home" class="nav-link" @click="closeNavbar">
                            <i class="bi bi-house-door"></i>
                            <span>Home</span>
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/gdrive" class="nav-link" @click="closeNavbar">
                            <i class="bi bi-google"></i>
                            <span>Google Drive</span>
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/odrive" class="nav-link" @click="closeNavbar">
                            <i class="bi bi-cloud"></i>
                            <span>OneDrive</span>
                        </router-link>
                    </li>
                </ul>

                <!-- RIGHT SIDE ACTIONS -->
                <div class="nav-actions">
                    <button class="login-btn">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Login</span>
                    </button>

                    <!-- Future: user avatar -->
                    <!-- <div class="avatar-placeholder">
                        <span>A</span>
                    </div> -->
                </div>

            </div>
        </div>

        <!-- Mobile overlay -->
        <div v-if="isNavbarOpen" class="nav-overlay" @click="closeNavbar"></div>
    </nav>
</template>

<style scoped>
/* ========== NAVBAR BASE ========== */
.nav-bar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    height: var(--ff-nav-height, 64px);

    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);

    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.nav-bar.nav-scrolled {
    background: rgba(15, 23, 42, 0.85);
    border-bottom-color: rgba(255, 255, 255, 0.1);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
}

/* ========== CONTAINER ========== */
.nav-container {
    max-width: 1280px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 100%;
    padding: 0 28px;
}

/* ========== BRAND ========== */
.navbar-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    flex-shrink: 0;
    transition: opacity 0.2s ease;
}

.navbar-brand:hover {
    opacity: 0.85;
}

.logo-wrapper {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.logo {
    height: 28px;
    width: auto;
    object-fit: contain;
}

.brand-text {
    font-size: 20px;
    font-weight: 700;
    letter-spacing: -0.3px;
    background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 50%, #22d3ee 100%);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* ========== HAMBURGER (MOBILE) ========== */
.menu-btn {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: 6px;
    z-index: 10;
}

.hamburger {
    display: flex;
    flex-direction: column;
    gap: 5px;
    width: 22px;
}

.hamburger span {
    display: block;
    height: 2px;
    background: var(--ff-text-primary, #e2e8f0);
    border-radius: 2px;
    transition: all 0.3s ease;
    transform-origin: center;
}

.hamburger.active span:nth-child(1) {
    transform: translateY(7px) rotate(45deg);
}

.hamburger.active span:nth-child(2) {
    opacity: 0;
    transform: scaleX(0);
}

.hamburger.active span:nth-child(3) {
    transform: translateY(-7px) rotate(-45deg);
}

/* ========== NAV CONTENT ========== */
.nav-content {
    display: flex;
    align-items: center;
    gap: 32px;
}

/* ========== LINKS ========== */
.nav-links {
    display: flex;
    list-style: none;
    gap: 4px;
    margin: 0;
    padding: 0;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    color: var(--ff-text-secondary, #94a3b8);
    font-size: 14px;
    font-weight: 500;
    padding: 8px 14px;
    border-radius: var(--ff-radius-sm, 8px);
    transition: all 0.2s ease;
    position: relative;
    white-space: nowrap;
}

.nav-link i {
    font-size: 15px;
    opacity: 0.7;
    transition: opacity 0.2s ease;
}

.nav-link:hover {
    color: var(--ff-text-primary, #e2e8f0);
    background: rgba(255, 255, 255, 0.05);
}

.nav-link:hover i {
    opacity: 1;
}

/* ACTIVE LINK */
.nav-link.router-link-active {
    color: #3b82f6;
    background: rgba(59, 130, 246, 0.1);
}

.nav-link.router-link-active i {
    opacity: 1;
}

.nav-link.router-link-active::after {
    content: "";
    position: absolute;
    bottom: -1px;
    left: 50%;
    transform: translateX(-50%);
    width: 20px;
    height: 2px;
    background: linear-gradient(90deg, #3b82f6, #06b6d4);
    border-radius: 2px;
}

/* ========== RIGHT ACTIONS ========== */
.nav-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

/* LOGIN BUTTON */
.login-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #3b82f6, #06b6d4);
    border: none;
    padding: 8px 18px;
    border-radius: var(--ff-radius-sm, 8px);
    color: white;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
    font-family: 'Inter', sans-serif;
    letter-spacing: 0.2px;
}

.login-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(59, 130, 246, 0.35);
    background: linear-gradient(135deg, #2563eb, #0891b2);
}

.login-btn:active {
    transform: translateY(0);
}

/* AVATAR PLACEHOLDER (for future use) */
.avatar-placeholder {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #06b6d4);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 700;
    color: white;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 2px solid transparent;
}

.avatar-placeholder:hover {
    border-color: rgba(59, 130, 246, 0.5);
    box-shadow: 0 0 16px rgba(59, 130, 246, 0.3);
}

/* ========== MOBILE OVERLAY ========== */
.nav-overlay {
    display: none;
}

/* ========== MOBILE ========== */
@media (max-width: 768px) {
    .nav-bar {
        height: 56px;
    }

    .nav-container {
        padding: 0 16px;
    }

    .menu-btn {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .nav-content {
        position: fixed;
        top: 0;
        right: -100%;
        width: 280px;
        height: 100vh;
        flex-direction: column;
        align-items: stretch;
        gap: 0;
        padding: 80px 20px 24px;

        background: rgba(15, 23, 42, 0.97);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border-left: 1px solid rgba(255, 255, 255, 0.08);

        transition: right 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 5;
    }

    .nav-content.open {
        right: 0;
    }

    .nav-overlay {
        display: block;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 4;
    }

    .nav-links {
        flex-direction: column;
        gap: 2px;
    }

    .nav-link {
        padding: 12px 16px;
        font-size: 15px;
        border-radius: 10px;
    }

    .nav-actions {
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .login-btn {
        width: 100%;
        justify-content: center;
        padding: 12px 18px;
        font-size: 14px;
    }
}
</style>