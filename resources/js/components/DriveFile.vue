<script>
import axios from "axios";

export default {
    name: "DriveFile",
    data() {
        return {
            files: [],
            q: "",
            loading: false,
            folderStack: [],
            activeSection: "mydrive",
        };
    },
    computed: {
        filtered() {
            const q = this.q.trim().toLowerCase();
            if (!q) return this.files;
            return this.files.filter((f) =>
                (f.name || "").toLowerCase().includes(q)
            );
        },
        currentFolderName() {
            if (this.folderStack.length === 0) {
                return this.activeSection === "mydrive" ? "My Files" : "Shared with Me";
            }
            return "Files";
        },
    },
    methods: {
        async loadRootOptions() {
            this.files = [
                { id: "mydrive", name: "My Files", isFolder: true },
                { id: "shared", name: "Shared with Me", isFolder: true },
            ];
        },

        async fetchFiles(apiUrl, pushStack = true) {
            this.loading = true;
            try {
                const { data } = await axios.get(apiUrl);
                if (pushStack) this.folderStack.push(apiUrl);
                this.files = data;
            } finally {
                this.loading = false;
            }
        },

        openItem(f) {
            if (f.isFolder) {
                if (f.id === "mydrive") {
                    this.activeSection = "mydrive";
                    this.fetchFiles("/google/drive/root");
                } else if (f.id === "shared") {
                    this.activeSection = "shared";
                    this.fetchFiles("/google/drive/shared");
                } else {
                    this.fetchFiles(`/google/drive/folder/${f.id}`);
                }
            } else {
                window.open(f.webViewLink, "_blank");
            }
        },

        goBack() {
            if (this.folderStack.length > 1) {
                this.folderStack.pop();
                const prevUrl = this.folderStack[this.folderStack.length - 1];
                this.fetchFiles(prevUrl, false);
            } else {
                this.folderStack = [];
                this.loadRootOptions();
            }
        },

        sidebarNavigate(section) {
            this.activeSection = section;
            this.folderStack = [];
            if (section === "mydrive") {
                this.fetchFiles("/google/drive/root");
            } else if (section === "shared") {
                this.fetchFiles("/google/drive/shared");
            } else {
                this.loadRootOptions();
            }
        },

        getFileIcon(f) {
            if (f.isFolder) return "bi bi-folder-fill";

            const ext = (f.name || "").split(".").pop().toLowerCase();
            const extIcons = {
                pdf: "bi bi-file-pdf-fill",
                doc: "bi bi-file-word-fill",
                docx: "bi bi-file-word-fill",
                xls: "bi bi-file-excel-fill",
                xlsx: "bi bi-file-excel-fill",
                ppt: "bi bi-file-ppt-fill",
                pptx: "bi bi-file-ppt-fill",
                jpg: "bi bi-file-image-fill",
                jpeg: "bi bi-file-image-fill",
                png: "bi bi-file-image-fill",
                gif: "bi bi-file-image-fill",
                txt: "bi bi-file-text-fill",
                zip: "bi bi-file-zip-fill",
                rar: "bi bi-file-zip-fill",
            };
            return extIcons[ext] || "bi bi-file-earmark-fill";
        },

        getIconColor(f) {
            if (f.isFolder) return "#f59e0b";

            const ext = (f.name || "").split(".").pop().toLowerCase();
            const colors = {
                pdf: "#ef4444", doc: "#3b82f6", docx: "#3b82f6",
                xls: "#22c55e", xlsx: "#22c55e",
                ppt: "#f97316", pptx: "#f97316",
                jpg: "#06b6d4", jpeg: "#06b6d4", png: "#06b6d4", gif: "#06b6d4",
                txt: "#94a3b8", zip: "#8b5cf6", rar: "#8b5cf6",
            };
            return colors[ext] || "#64748b";
        },
    },
    mounted() {
        this.loadRootOptions();
    },
};
</script>

<template>
    <div class="drive-layout">

        <!-- ========== SIDEBAR ========== -->
        <aside class="drive-sidebar">
            <div class="sidebar-header">
                <i class="bi bi-google" style="color: #4285f4;"></i>
                <span>Google Drive</span>
            </div>

            <nav class="sidebar-nav">
                <button
                    class="sidebar-item"
                    :class="{ active: activeSection === 'mydrive' }"
                    @click="sidebarNavigate('mydrive')"
                >
                    <i class="bi bi-hdd"></i>
                    <span>My Drive</span>
                </button>
                <button
                    class="sidebar-item"
                    :class="{ active: activeSection === 'shared' }"
                    @click="sidebarNavigate('shared')"
                >
                    <i class="bi bi-people"></i>
                    <span>Shared with Me</span>
                </button>
                <button class="sidebar-item" disabled>
                    <i class="bi bi-trash3"></i>
                    <span>Trash</span>
                </button>
            </nav>

            <div class="sidebar-divider"></div>

            <div class="sidebar-footer">
                <a href="/auth/google" class="connect-btn">
                    <i class="bi bi-plug"></i>
                    <span>Connect / Reconnect</span>
                </a>
            </div>
        </aside>

        <!-- ========== MAIN CONTENT ========== -->
        <main class="drive-main">

            <!-- Top Bar -->
            <div class="drive-topbar">
                <div class="topbar-left">
                    <button
                        v-if="folderStack.length > 0"
                        @click="goBack"
                        class="back-btn"
                    >
                        <i class="bi bi-arrow-left"></i>
                    </button>
                    <h2 class="topbar-title">{{ currentFolderName }}</h2>
                </div>

                <div class="topbar-right">
                    <div class="search-wrapper">
                        <i class="bi bi-search search-icon"></i>
                        <input
                            v-model="q"
                            type="text"
                            class="search-input"
                            placeholder="Search files..."
                        />
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="drive-loading">
                <div class="loading-spinner"></div>
                <span>Loading files...</span>
            </div>

            <!-- Empty State -->
            <div v-else-if="filtered.length === 0 && !loading" class="drive-empty">
                <div class="empty-icon">
                    <i class="bi bi-folder2-open"></i>
                </div>
                <h3>No files found</h3>
                <p>This folder is empty or no files match your search.</p>
            </div>

            <!-- Files Grid -->
            <div v-else class="files-grid">
                <div
                    v-for="(f, i) in filtered"
                    :key="f.id"
                    class="file-card"
                    :style="{ animationDelay: (i * 0.03) + 's' }"
                    @dblclick="openItem(f)"
                >
                    <div class="file-icon" :style="{ color: getIconColor(f) }">
                        <i :class="getFileIcon(f)"></i>
                    </div>
                    <div class="file-name" :title="f.name">
                        {{ f.name }}
                    </div>
                    <div class="file-type">
                        {{ f.isFolder ? 'Folder' : (f.name || '').split('.').pop().toUpperCase() }}
                    </div>
                </div>
            </div>

        </main>
    </div>
</template>

<style scoped>
/* ========== LAYOUT ========== */
.drive-layout {
    display: flex;
    min-height: calc(100vh - var(--ff-nav-height, 64px));
}

/* ========== SIDEBAR ========== */
.drive-sidebar {
    width: 240px;
    flex-shrink: 0;
    background: rgba(255, 255, 255, 0.02);
    border-right: 1px solid rgba(255, 255, 255, 0.06);
    padding: 24px 14px;
    display: flex;
    flex-direction: column;
    animation: ff-slide-in-left 0.4s ease both;
}

.sidebar-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0 10px 20px;
    font-size: 16px;
    font-weight: 700;
    color: var(--ff-text-primary, #e2e8f0);
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    margin-bottom: 16px;
}

.sidebar-header i {
    font-size: 20px;
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.sidebar-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 10px;
    border: none;
    background: transparent;
    color: var(--ff-text-secondary, #94a3b8);
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    font-family: 'Inter', sans-serif;
    text-align: left;
    width: 100%;
}

.sidebar-item i {
    font-size: 17px;
    width: 20px;
    text-align: center;
}

.sidebar-item:hover:not(:disabled) {
    background: rgba(255, 255, 255, 0.05);
    color: var(--ff-text-primary, #e2e8f0);
}

.sidebar-item.active {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.sidebar-item:disabled {
    opacity: 0.35;
    cursor: not-allowed;
}

.sidebar-divider {
    height: 1px;
    background: rgba(255, 255, 255, 0.06);
    margin: 16px 10px;
}

.sidebar-footer {
    margin-top: auto;
}

.connect-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    border-radius: 10px;
    background: rgba(239, 68, 68, 0.08);
    color: #f87171;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}

.connect-btn:hover {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
}

/* ========== MAIN CONTENT ========== */
.drive-main {
    flex: 1;
    min-width: 0;
    padding: 24px 28px;
    animation: ff-fade-in 0.4s ease both;
}

/* ========== TOP BAR ========== */
.drive-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 28px;
    flex-wrap: wrap;
}

.topbar-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.back-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    background: rgba(255, 255, 255, 0.04);
    color: var(--ff-text-secondary, #94a3b8);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 16px;
}

.back-btn:hover {
    background: rgba(255, 255, 255, 0.08);
    color: var(--ff-text-primary, #e2e8f0);
    border-color: rgba(255, 255, 255, 0.12);
}

.topbar-title {
    font-size: 22px;
    font-weight: 700;
    color: var(--ff-text-primary, #e2e8f0);
    margin: 0;
    letter-spacing: -0.3px;
}

.topbar-right {
    display: flex;
    align-items: center;
    gap: 12px;
}

.search-wrapper {
    position: relative;
    width: 280px;
}

.search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 14px;
    color: var(--ff-text-muted, #64748b);
    pointer-events: none;
}

.search-input {
    width: 100%;
    padding: 10px 14px 10px 38px;
    border-radius: 10px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    background: rgba(255, 255, 255, 0.04);
    color: var(--ff-text-primary, #e2e8f0);
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    transition: all 0.2s ease;
    outline: none;
}

.search-input::placeholder {
    color: var(--ff-text-muted, #64748b);
}

.search-input:focus {
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(59, 130, 246, 0.4);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* ========== LOADING ========== */
.drive-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 80px 20px;
    color: var(--ff-text-secondary, #94a3b8);
    gap: 16px;
    font-size: 14px;
}

.loading-spinner {
    width: 36px;
    height: 36px;
    border: 3px solid rgba(255, 255, 255, 0.08);
    border-top-color: #3b82f6;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* ========== EMPTY STATE ========== */
.drive-empty {
    text-align: center;
    padding: 80px 20px;
    animation: ff-fade-in 0.5s ease both;
}

.empty-icon {
    width: 72px;
    height: 72px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.06);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 28px;
    color: var(--ff-text-muted, #64748b);
}

.drive-empty h3 {
    font-size: 18px;
    font-weight: 600;
    color: var(--ff-text-primary, #e2e8f0);
    margin: 0 0 8px;
}

.drive-empty p {
    font-size: 14px;
    color: var(--ff-text-secondary, #94a3b8);
    margin: 0;
}

/* ========== FILES GRID ========== */
.files-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 14px;
}

.file-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 14px;
    padding: 24px 16px 18px;
    text-align: center;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    animation: ff-scale-in 0.3s ease both;
}

.file-card:hover {
    transform: translateY(-4px);
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(59, 130, 246, 0.25);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}

.file-icon {
    font-size: 36px;
    margin-bottom: 12px;
    transition: transform 0.2s ease;
}

.file-card:hover .file-icon {
    transform: scale(1.1);
}

.file-name {
    font-size: 13px;
    font-weight: 600;
    color: var(--ff-text-primary, #e2e8f0);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 4px;
}

.file-type {
    font-size: 11px;
    color: var(--ff-text-muted, #64748b);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 900px) {
    .drive-sidebar {
        display: none;
    }

    .drive-main {
        padding: 20px 16px;
    }

    .search-wrapper {
        width: 100%;
    }

    .drive-topbar {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }
}

@media (max-width: 480px) {
    .files-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .file-card {
        padding: 18px 12px 14px;
    }
}
</style>