<script>
export default {
    name: "HomePage",
    data() {
        return {
            features: [
                {
                    icon: "bi bi-lightning-charge-fill",
                    title: "Lightning Fast",
                    description: "Access and manage files across all your clouds instantly with zero lag.",
                    color: "#f59e0b",
                },
                {
                    icon: "bi bi-shield-lock-fill",
                    title: "Bank-Grade Security",
                    description: "End-to-end encryption keeps your data safe across every cloud provider.",
                    color: "#22c55e",
                },
                {
                    icon: "bi bi-cloud-fill",
                    title: "Multi-Cloud Unified",
                    description: "One dashboard for Google Drive, OneDrive, and more — beautifully unified.",
                    color: "#3b82f6",
                },
                {
                    icon: "bi bi-arrow-repeat",
                    title: "Real-Time Sync",
                    description: "Changes sync automatically across all connected storage providers.",
                    color: "#06b6d4",
                },
            ],
            providers: [
                {
                    name: "Google Drive",
                    description: "Browse, upload, and manage your Google Drive files seamlessly.",
                    icon: "fab fa-google-drive",
                    route: "/gdrive",
                    gradient: "linear-gradient(135deg, #4285f4, #34a853)",
                    iconColor: "#4285f4",
                },
                {
                    name: "OneDrive",
                    description: "Full file management for your Microsoft OneDrive storage.",
                    imgSrc: "/images/onedrvie.png",
                    route: "/odrive",
                    gradient: "linear-gradient(135deg, #0078d4, #00bcf2)",
                    iconColor: "#0078d4",
                },
            ],
        };
    },
    methods: {
        goTo(route) {
            this.$router.push(route);
        },
    },
};
</script>

<template>
    <div class="home-page">

        <!-- ========== HERO SECTION ========== -->
        <section class="hero">
            <div class="hero-badge">
                <i class="bi bi-stars"></i>
                <span>Multi-Cloud Storage Platform</span>
            </div>

            <h1 class="hero-title">
                Manage All Your Cloud
                <br />
                <span class="hero-title-accent">Storage in One Place</span>
            </h1>

            <p class="hero-subtitle">
                Connect Google Drive, OneDrive and manage all your files
                seamlessly. One powerful dashboard for all your cloud storage needs.
            </p>

            <div class="hero-actions">
                <button @click="goTo('/gdrive')" class="hero-btn hero-btn-primary">
                    <i class="bi bi-google"></i>
                    Connect Google Drive
                </button>
                <button @click="goTo('/odrive')" class="hero-btn hero-btn-secondary">
                    <i class="bi bi-cloud"></i>
                    Connect OneDrive
                </button>
            </div>

            <!-- Decorative glow orbs -->
            <div class="hero-glow hero-glow-1"></div>
            <div class="hero-glow hero-glow-2"></div>
        </section>

        <!-- ========== FEATURES SECTION ========== -->
        <section class="features-section">
            <div class="section-header">
                <span class="section-label">Why FileFusion</span>
                <h2 class="section-title">Everything you need to manage your files</h2>
            </div>

            <div class="features-grid">
                <div
                    v-for="(feature, index) in features"
                    :key="index"
                    class="feature-card"
                    :style="{ animationDelay: (index * 0.08) + 's' }"
                >
                    <div class="feature-icon-wrapper" :style="{ '--accent': feature.color }">
                        <i :class="feature.icon"></i>
                    </div>
                    <h3 class="feature-title">{{ feature.title }}</h3>
                    <p class="feature-desc">{{ feature.description }}</p>
                </div>
            </div>
        </section>

        <!-- ========== CLOUD PROVIDERS SECTION ========== -->
        <section class="providers-section">
            <div class="section-header">
                <span class="section-label">Cloud Providers</span>
                <h2 class="section-title">Connect your favorite storage</h2>
            </div>

            <div class="providers-grid">
                <div
                    v-for="(provider, index) in providers"
                    :key="index"
                    class="provider-card"
                    @click="goTo(provider.route)"
                >
                    <div class="provider-icon-bg" :style="{ background: provider.gradient }">
                        <i v-if="provider.icon" :class="provider.icon"></i>
                        <img v-else-if="provider.imgSrc" :src="provider.imgSrc" :alt="provider.name" />
                    </div>
                    <div class="provider-info">
                        <h3>{{ provider.name }}</h3>
                        <p>{{ provider.description }}</p>
                    </div>
                    <div class="provider-arrow">
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========== FOOTER CTA ========== -->
        <section class="footer-cta">
            <div class="cta-card">
                <h2>Ready to unify your cloud?</h2>
                <p>Join thousands of users managing their files smarter with FileFusion.</p>
                <button @click="goTo('/gdrive')" class="hero-btn hero-btn-primary">
                    Get Started Free
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </section>

    </div>
</template>

<style scoped>
/* ========== PAGE WRAPPER ========== */
.home-page {
    max-width: 1120px;
    margin: 0 auto;
    padding: 0 24px 80px;
}

/* ========== HERO ========== */
.hero {
    text-align: center;
    padding: 80px 0 100px;
    position: relative;
    overflow: hidden;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 100px;
    padding: 6px 16px;
    font-size: 13px;
    font-weight: 500;
    color: #60a5fa;
    margin-bottom: 28px;
    animation: ff-fade-in 0.5s ease both;
}

.hero-badge i {
    font-size: 14px;
}

.hero-title {
    font-size: 52px;
    font-weight: 800;
    line-height: 1.15;
    color: var(--ff-text-primary, #e2e8f0);
    margin: 0 0 20px;
    letter-spacing: -1px;
    animation: ff-fade-in 0.6s ease 0.1s both;
}

.hero-title-accent {
    background: linear-gradient(135deg, #3b82f6, #06b6d4, #22d3ee);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-subtitle {
    color: var(--ff-text-secondary, #94a3b8);
    font-size: 18px;
    line-height: 1.7;
    max-width: 560px;
    margin: 0 auto 36px;
    animation: ff-fade-in 0.6s ease 0.2s both;
}

.hero-actions {
    display: flex;
    justify-content: center;
    gap: 14px;
    flex-wrap: wrap;
    animation: ff-fade-in 0.6s ease 0.3s both;
}

.hero-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 28px;
    border-radius: 10px;
    border: none;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
    font-family: 'Inter', sans-serif;
    letter-spacing: 0.1px;
}

.hero-btn-primary {
    background: linear-gradient(135deg, #3b82f6, #06b6d4);
    color: white;
    box-shadow: 0 4px 16px rgba(59, 130, 246, 0.25);
}

.hero-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(59, 130, 246, 0.4);
    background: linear-gradient(135deg, #2563eb, #0891b2);
}

.hero-btn-secondary {
    background: rgba(255, 255, 255, 0.04);
    color: var(--ff-text-primary, #e2e8f0);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.hero-btn-secondary:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(59, 130, 246, 0.4);
    transform: translateY(-2px);
}

/* Decorative glow orbs */
.hero-glow {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    filter: blur(80px);
}

.hero-glow-1 {
    width: 400px;
    height: 400px;
    background: rgba(59, 130, 246, 0.12);
    top: -100px;
    left: -150px;
}

.hero-glow-2 {
    width: 350px;
    height: 350px;
    background: rgba(6, 182, 212, 0.1);
    bottom: -80px;
    right: -120px;
}

/* ========== SECTION HEADERS ========== */
.section-header {
    text-align: center;
    margin-bottom: 48px;
}

.section-label {
    display: inline-block;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: #3b82f6;
    margin-bottom: 10px;
}

.section-title {
    font-size: 32px;
    font-weight: 700;
    color: var(--ff-text-primary, #e2e8f0);
    letter-spacing: -0.5px;
    margin: 0;
}

/* ========== FEATURES ========== */
.features-section {
    margin-bottom: 100px;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.feature-card {
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 16px;
    padding: 32px 24px;
    text-align: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    animation: ff-fade-in-up 0.5s ease both;
}

.feature-card:hover {
    transform: translateY(-6px);
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(59, 130, 246, 0.3);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.feature-icon-wrapper {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 18px;
    background: color-mix(in srgb, var(--accent) 12%, transparent);
    color: var(--accent);
    font-size: 22px;
    transition: transform 0.3s ease;
}

.feature-card:hover .feature-icon-wrapper {
    transform: scale(1.1);
}

.feature-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--ff-text-primary, #e2e8f0);
    margin: 0 0 8px;
}

.feature-desc {
    font-size: 13px;
    color: var(--ff-text-secondary, #94a3b8);
    line-height: 1.6;
    margin: 0;
}

/* ========== PROVIDERS ========== */
.providers-section {
    margin-bottom: 100px;
}

.providers-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.provider-card {
    display: flex;
    align-items: center;
    gap: 20px;
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 18px;
    padding: 28px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    animation: ff-fade-in-up 0.5s ease both;
}

.provider-card:hover {
    transform: translateY(-4px);
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(59, 130, 246, 0.3);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.provider-icon-bg {
    flex-shrink: 0;
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
}

.provider-card:hover .provider-icon-bg {
    transform: scale(1.08);
}

.provider-icon-bg i {
    font-size: 26px;
    color: white;
}

.provider-icon-bg img {
    width: 32px;
    height: auto;
    filter: brightness(0) invert(1);
}

.provider-info {
    flex: 1;
    min-width: 0;
}

.provider-info h3 {
    font-size: 17px;
    font-weight: 700;
    color: var(--ff-text-primary, #e2e8f0);
    margin: 0 0 4px;
}

.provider-info p {
    font-size: 13px;
    color: var(--ff-text-secondary, #94a3b8);
    margin: 0;
    line-height: 1.5;
}

.provider-arrow {
    flex-shrink: 0;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--ff-text-muted, #64748b);
    transition: all 0.3s ease;
}

.provider-card:hover .provider-arrow {
    background: rgba(59, 130, 246, 0.15);
    color: #3b82f6;
    transform: translateX(4px);
}

/* ========== FOOTER CTA ========== */
.footer-cta {
    padding-bottom: 20px;
}

.cta-card {
    text-align: center;
    background: rgba(59, 130, 246, 0.06);
    border: 1px solid rgba(59, 130, 246, 0.15);
    border-radius: 24px;
    padding: 60px 40px;
    position: relative;
    overflow: hidden;
}

.cta-card::before {
    content: "";
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse at 30% 50%, rgba(59, 130, 246, 0.08) 0%, transparent 70%),
        radial-gradient(ellipse at 70% 50%, rgba(6, 182, 212, 0.06) 0%, transparent 70%);
    pointer-events: none;
}

.cta-card h2 {
    font-size: 30px;
    font-weight: 700;
    margin: 0 0 12px;
    color: var(--ff-text-primary, #e2e8f0);
    position: relative;
}

.cta-card p {
    font-size: 16px;
    color: var(--ff-text-secondary, #94a3b8);
    margin: 0 0 28px;
    position: relative;
}

.cta-card .hero-btn {
    position: relative;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 1024px) {
    .features-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .hero {
        padding: 48px 0 64px;
    }

    .hero-title {
        font-size: 32px;
    }

    .hero-subtitle {
        font-size: 16px;
    }

    .hero-actions {
        flex-direction: column;
        align-items: stretch;
    }

    .hero-btn {
        justify-content: center;
    }

    .features-grid {
        grid-template-columns: 1fr;
    }

    .providers-grid {
        grid-template-columns: 1fr;
    }

    .section-title {
        font-size: 24px;
    }

    .cta-card {
        padding: 40px 24px;
    }

    .cta-card h2 {
        font-size: 24px;
    }
}
</style>