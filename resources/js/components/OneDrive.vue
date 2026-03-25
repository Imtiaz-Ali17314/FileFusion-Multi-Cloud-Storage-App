<template>
    <div class="onedrive-layout">

        <!-- ========== SIDEBAR ========== -->
        <aside class="od-sidebar">
            <div class="sidebar-header">
                <img src="/images/onedrvie.png" alt="OneDrive" class="sidebar-logo" />
                <span>OneDrive</span>
            </div>

            <nav class="sidebar-nav">
                <button class="sidebar-item active" @click="goHome">
                    <i class="bi bi-hdd"></i>
                    <span>My Files</span>
                </button>
                <button class="sidebar-item" disabled>
                    <i class="bi bi-people"></i>
                    <span>Shared</span>
                </button>
                <button class="sidebar-item" disabled>
                    <i class="bi bi-clock-history"></i>
                    <span>Recent</span>
                </button>
                <button class="sidebar-item" disabled>
                    <i class="bi bi-trash3"></i>
                    <span>Trash</span>
                </button>
            </nav>

            <div class="sidebar-divider"></div>

            <div class="sidebar-footer">
                <a href="/auth/onedrive" class="connect-btn">
                    <i class="bi bi-plug"></i>
                    <span>Connect / Reconnect</span>
                </a>
            </div>
        </aside>

        <!-- ========== MAIN CONTENT ========== -->
        <main class="od-main">

            <!-- Top Bar -->
            <div class="od-topbar">
                <div class="topbar-left">
                    <button v-if="pathSegments.length > 0" class="back-btn" @click="goHome">
                        <i class="bi bi-arrow-left"></i>
                    </button>

                    <nav class="od-breadcrumbs" v-if="pathSegments.length > 0">
                        <span class="crumb" @click="goHome">Root</span>
                        <template v-for="(seg, idx) in pathSegments" :key="idx">
                            <i class="bi bi-chevron-right crumb-sep"></i>
                            <span
                                class="crumb"
                                :class="{ 'crumb-active': idx === pathSegments.length - 1 }"
                                @click="idx !== pathSegments.length - 1 && goToCrumb(idx)"
                            >
                                {{ seg }}
                            </span>
                        </template>
                    </nav>
                    <h2 v-else class="topbar-title">My Files</h2>
                </div>

                <div class="topbar-right">
                    <div class="search-wrapper">
                        <i class="bi bi-search search-icon"></i>
                        <input
                            v-model="query"
                            type="text"
                            class="search-input"
                            placeholder="Search files..."
                        />
                    </div>

                    <div class="topbar-actions">
                        <label class="action-btn upload-btn" for="odFileInput">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <span>Upload</span>
                        </label>
                        <input
                            type="file"
                            id="odFileInput"
                            multiple
                            @change="uploadFiles"
                            style="display: none;"
                        />

                        <button class="action-btn" @click="createFolderPrompt">
                            <i class="bi bi-folder-plus"></i>
                            <span>New Folder</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="od-loading">
                <div class="loading-spinner"></div>
                <span>Loading files...</span>
            </div>

            <!-- Empty State -->
            <div v-else-if="filteredFiles.length === 0 && !loading" class="od-empty">
                <div class="empty-icon">
                    <i class="bi bi-folder2-open"></i>
                </div>
                <h3>No items found</h3>
                <p>This folder is empty or no files match your search.</p>
            </div>

            <!-- Files Grid -->
            <div v-else class="files-grid">
                <div
                    v-for="(item, i) in filteredFiles"
                    :key="item.id"
                    class="file-card"
                    :style="{ animationDelay: (i * 0.03) + 's' }"
                    @dblclick="openItem(item)"
                >
                    <div class="file-icon" :style="{ color: getIconColor(item) }">
                        <i :class="fileIcon(item)"></i>
                    </div>

                    <div class="file-name" :title="item.name">
                        {{ item.name }}
                    </div>

                    <div class="file-meta" v-if="!item.folder && humanSize(item.size)">
                        {{ humanSize(item.size) }}
                    </div>
                    <div class="file-meta" v-else-if="item.folder">Folder</div>

                    <!-- Actions -->
                    <div class="file-actions">
                        <button
                            class="file-action-btn"
                            title="Preview"
                            @click.stop="preview(item)"
                        >
                            <i class="bi bi-eye"></i>
                        </button>
                        <button
                            v-if="!item.folder"
                            class="file-action-btn"
                            title="Download"
                            @click.stop="download(item)"
                        >
                            <i class="bi bi-download"></i>
                        </button>
                        <button
                            class="file-action-btn file-action-danger"
                            title="Delete"
                            @click.stop="deleteItem(item)"
                        >
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                </div>
            </div>
        </main>

        <!-- ========== TOAST ========== -->
        <transition name="toast">
            <div v-if="toast.show" class="toast-wrap">
                <div class="toast-inner" :class="toast.type">
                    <i :class="toast.type === 'ok' ? 'bi bi-check-circle' : 'bi bi-exclamation-circle'"></i>
                    {{ toast.message }}
                </div>
            </div>
        </transition>

        <!-- ========== PREVIEW MODAL ========== -->
        <transition name="modal">
            <div v-if="showPreview" class="preview-backdrop" @click.self="closePreview">
                <div class="preview-modal">
                    <div class="preview-header">
                        <div class="preview-title">
                            <i class="bi bi-file-earmark"></i>
                            {{ previewItem?.name }}
                        </div>
                        <button class="preview-close" @click="closePreview">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <iframe
                        v-if="previewUrl"
                        :src="previewUrl"
                        class="preview-frame"
                        referrerpolicy="no-referrer"
                    ></iframe>
                    <div v-else class="preview-unavailable">
                        <i class="bi bi-eye-slash"></i>
                        <p>Preview not available. Try downloading instead.</p>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "OneDrive",
    data() {
        return {
            files: [],
            loading: false,
            error: "",
            query: "",
            currentPath: "/",
            showPreview: false,
            previewItem: null,
            previewUrl: "",
            toast: { show: false, type: "ok", message: "" },
            API_BASE: "http://localhost:8000/api/onedrive",
            isConnected: false,
        };
    },
    computed: {
        pathSegments() {
            if (!this.currentPath || this.currentPath === "/") return [];
            return this.currentPath.split("/").filter(Boolean);
        },
        filteredFiles() {
            const q = this.query.trim().toLowerCase();
            if (!q) return this.files;
            return this.files.filter((x) =>
                (x.name || "").toLowerCase().includes(q)
            );
        },
    },
    mounted() {
        this.fetchFiles("/");
    },
    methods: {
        // ------------ UI helpers ------------
        connectOneDrive() {
            window.location.href = "/auth/onedrive";
        },

        toastOk(msg) {
            this.toastShow(msg, "ok");
        },
        toastErr(msg) {
            this.toastShow(msg, "err");
        },
        toastShow(message, type = "ok") {
            this.toast = { show: true, message, type };
            setTimeout(() => (this.toast.show = false), 2200);
        },
        humanSize(bytes) {
            if (!bytes && bytes !== 0) return "";
            const sizes = ["B", "KB", "MB", "GB", "TB"];
            const i =
                bytes === 0 ? 0 : Math.floor(Math.log(bytes) / Math.log(1024));
            return `${(bytes / Math.pow(1024, i)).toFixed(1)} ${sizes[i]}`;
        },
        fileIcon(item) {
            if (item.folder) return "bi bi-folder-fill";
            const name = (item.name || "").toLowerCase();
            if (/\.(jpg|jpeg|png|gif|webp|bmp|tiff)$/i.test(name))
                return "bi bi-file-image-fill";
            if (name.endsWith(".pdf")) return "bi bi-file-pdf-fill";
            if (/\.(doc|docx)$/i.test(name))
                return "bi bi-file-word-fill";
            if (/\.(xls|xlsx)$/i.test(name))
                return "bi bi-file-excel-fill";
            if (/\.(ppt|pptx)$/i.test(name))
                return "bi bi-file-ppt-fill";
            if (/\.(txt|md|json|xml|yml|yaml|csv|log)$/i.test(name))
                return "bi bi-file-text-fill";
            if (/\.(js|ts|vue|php|py|rb|java|c|cpp|cs|go|rs)$/i.test(name))
                return "bi bi-file-code-fill";
            if (/\.(mp4|mkv|mov|avi|webm)$/i.test(name))
                return "bi bi-file-play-fill";
            if (/\.(mp3|wav|m4a|flac)$/i.test(name))
                return "bi bi-file-music-fill";
            return "bi bi-file-earmark-fill";
        },

        getIconColor(item) {
            if (item.folder) return "#f59e0b";
            const name = (item.name || "").toLowerCase();
            if (/\.(jpg|jpeg|png|gif|webp|bmp|tiff)$/i.test(name)) return "#06b6d4";
            if (name.endsWith(".pdf")) return "#ef4444";
            if (/\.(doc|docx)$/i.test(name)) return "#3b82f6";
            if (/\.(xls|xlsx)$/i.test(name)) return "#22c55e";
            if (/\.(ppt|pptx)$/i.test(name)) return "#f97316";
            if (/\.(mp4|mkv|mov|avi|webm)$/i.test(name)) return "#a855f7";
            if (/\.(mp3|wav|m4a|flac)$/i.test(name)) return "#ec4899";
            return "#64748b";
        },

        // ------------ Path helpers ------------
        normalizeFromParent(parentPath) {
            if (!parentPath) return "/";
            const idx = parentPath.indexOf("/drive/root:");
            if (idx === -1) return "/";
            const sub =
                parentPath.substring(idx + "/drive/root:".length) || "/";
            return sub === "" ? "/" : sub;
        },
        goHome() {
            this.currentPath = "/";
            this.fetchFiles("/");
        },
        goToCrumb(idx) {
            const segs = this.pathSegments.slice(0, idx + 1);
            const to = "/" + segs.join("/");
            this.currentPath = to || "/";
            this.fetchFiles(this.currentPath);
        },

        isImage(item) {
            if (!item.name) return false;
            return /\.(jpg|jpeg|png|gif|webp|bmp|tiff)$/i.test(item.name);
        },

        // ------------ API calls ------------
        async fetchFiles(path = "/") {
            try {
                this.loading = true;
                const res = await axios.get(`${this.API_BASE}/files`, {
                    params: { path },
                });
                this.files = res.data.value || [];
                this.currentPath = path;
            } catch (error) {
                console.error(
                    "files error",
                    error?.response?.data || error.message
                );
                this.toastErr("Failed to load files");
            } finally {
                this.loading = false;
            }
        },

        async uploadFiles(event) {
            try {
                const files = Array.from(event.target.files || []);
                if (!files.length) return;
                this.loading = true;

                await Promise.all(
                    files.map(async (f) => {
                        const form = new FormData();
                        form.append("file", f);
                        form.append("path", this.currentPath || "/");
                        await axios.post(`${this.API_BASE}/upload`, form, {
                            headers: { "Content-Type": "multipart/form-data" },
                        });
                    })
                );

                this.toastOk("Upload complete");
                this.fetchFiles(this.currentPath);
                event.target.value = "";
            } catch (e) {
                console.error(e);
                this.toastErr("Upload failed");
            } finally {
                this.loading = false;
            }
        },

        async createFolderPrompt() {
            const name = prompt("Enter folder name:");
            if (!name) return;
            try {
                this.loading = true;
                await axios.post(`${this.API_BASE}/folder`, {
                    name,
                    path: this.currentPath || "/",
                });
                this.toastOk("Folder created");
                this.fetchFiles(this.currentPath);
            } catch (e) {
                console.error(e);
                this.toastErr("Failed to create folder");
            } finally {
                this.loading = false;
            }
        },

        async download(item) {
            window.location.href = `${this.API_BASE}/download/${item.id}`;
        },

        async deleteItem(item) {
            if (!confirm(`Delete "${item.name}"?`)) return;
            try {
                this.loading = true;
                await axios.delete(`${this.API_BASE}/delete/${item.id}`);
                this.toastOk("Deleted");
                this.fetchFiles(this.currentPath);
            } catch (e) {
                console.error(e);
                this.toastErr("Delete failed");
            } finally {
                this.loading = false;
            }
        },

        openItem(item) {
            if (item.folder) {
                const parent = this.normalizeFromParent(
                    item.parentReference?.path || "/"
                );
                const next = this.joinPath(parent, item.name);
                this.fetchFiles(next);
            } else {
                this.preview(item);
            }
        },

        // ------------ Preview ------------
        preview(item) {
            if (item.webUrl) {
                this.previewItem = item;
                this.previewUrl = item.webUrl;
                this.showPreview = true;
            } else {
                window.open(`${this.API_BASE}/download/${item.id}`, "_blank");
            }
        },
        closePreview() {
            this.showPreview = false;
            this.previewItem = null;
            this.previewUrl = "";
        },

        // ------------ utils ------------
        joinPath(base, name) {
            if (!base || base === "/") return `/${name}`;
            return `${base.replace(/\/+$/, "")}/${name}`;
        },
    },
};
</script>

<style scoped>
/* ========== LAYOUT ========== */
.onedrive-layout {
    display: flex;
    min-height: calc(100vh - var(--ff-nav-height, 64px));
}

/* ========== SIDEBAR ========== */
.od-sidebar {
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

.sidebar-logo {
    width: 24px;
    height: auto;
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
    background: rgba(0, 120, 212, 0.12);
    color: #38bdf8;
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
.od-main {
    flex: 1;
    min-width: 0;
    padding: 24px 28px;
    animation: ff-fade-in 0.4s ease both;
}

/* ========== TOP BAR ========== */
.od-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 28px;
    flex-wrap: wrap;
}

.topbar-left {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
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
    flex-shrink: 0;
}

.back-btn:hover {
    background: rgba(255, 255, 255, 0.08);
    color: var(--ff-text-primary, #e2e8f0);
}

.topbar-title {
    font-size: 22px;
    font-weight: 700;
    color: var(--ff-text-primary, #e2e8f0);
    margin: 0;
    letter-spacing: -0.3px;
}

/* Breadcrumbs */
.od-breadcrumbs {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.crumb {
    font-size: 14px;
    font-weight: 500;
    color: var(--ff-text-secondary, #94a3b8);
    cursor: pointer;
    transition: color 0.2s ease;
    padding: 4px 6px;
    border-radius: 6px;
}

.crumb:hover:not(.crumb-active) {
    color: var(--ff-text-primary, #e2e8f0);
    background: rgba(255, 255, 255, 0.05);
}

.crumb-active {
    color: var(--ff-text-primary, #e2e8f0);
    cursor: default;
}

.crumb-sep {
    font-size: 10px;
    color: var(--ff-text-muted, #64748b);
}

.topbar-right {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.search-wrapper {
    position: relative;
    width: 240px;
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

.topbar-actions {
    display: flex;
    gap: 8px;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 9px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(255, 255, 255, 0.04);
    color: var(--ff-text-primary, #e2e8f0);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    font-family: 'Inter', sans-serif;
}

.action-btn:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(59, 130, 246, 0.3);
}

.upload-btn {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(6, 182, 212, 0.1));
    border-color: rgba(59, 130, 246, 0.25);
    color: #60a5fa;
}

.upload-btn:hover {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.25), rgba(6, 182, 212, 0.15));
}

/* ========== LOADING ========== */
.od-loading {
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
    border-top-color: #0078d4;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* ========== EMPTY STATE ========== */
.od-empty {
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

.od-empty h3 {
    font-size: 18px;
    font-weight: 600;
    color: var(--ff-text-primary, #e2e8f0);
    margin: 0 0 8px;
}

.od-empty p {
    font-size: 14px;
    color: var(--ff-text-secondary, #94a3b8);
    margin: 0;
}

/* ========== FILES GRID ========== */
.files-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
    gap: 14px;
}

.file-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 14px;
    padding: 24px 16px 16px;
    text-align: center;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    animation: ff-scale-in 0.3s ease both;
    position: relative;
}

.file-card:hover {
    transform: translateY(-4px);
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(0, 120, 212, 0.3);
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

.file-meta {
    font-size: 11px;
    color: var(--ff-text-muted, #64748b);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
    margin-bottom: 8px;
}

/* File Actions */
.file-actions {
    display: flex;
    justify-content: center;
    gap: 4px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.file-card:hover .file-actions {
    opacity: 1;
}

.file-action-btn {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    background: rgba(255, 255, 255, 0.04);
    color: var(--ff-text-secondary, #94a3b8);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 13px;
}

.file-action-btn:hover {
    background: rgba(59, 130, 246, 0.12);
    color: #60a5fa;
    border-color: rgba(59, 130, 246, 0.3);
}

.file-action-danger:hover {
    background: rgba(239, 68, 68, 0.12);
    color: #f87171;
    border-color: rgba(239, 68, 68, 0.3);
}

/* ========== TOAST ========== */
.toast-wrap {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
}

.toast-inner {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 12px;
    color: white;
    font-weight: 600;
    font-size: 14px;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.25);
    backdrop-filter: blur(12px);
}

.toast-inner.ok {
    background: linear-gradient(135deg, #22c55e, #16a34a);
}

.toast-inner.err {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.toast-enter-active,
.toast-leave-active {
    transition: all 0.3s ease;
}

.toast-enter-from {
    opacity: 0;
    transform: translateY(16px) scale(0.95);
}

.toast-leave-to {
    opacity: 0;
    transform: translateY(-8px) scale(0.95);
}

/* ========== PREVIEW MODAL ========== */
.preview-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    display: grid;
    place-items: center;
    z-index: 9000;
    padding: 24px;
}

.preview-modal {
    width: min(1100px, 96vw);
    height: min(80vh, 900px);
    background: rgba(15, 23, 42, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 18px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    box-shadow: 0 24px 64px rgba(0, 0, 0, 0.4);
}

.preview-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

.preview-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    font-size: 15px;
    color: var(--ff-text-primary, #e2e8f0);
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.preview-close {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    background: rgba(255, 255, 255, 0.04);
    color: var(--ff-text-secondary, #94a3b8);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.preview-close:hover {
    background: rgba(239, 68, 68, 0.12);
    color: #f87171;
}

.preview-frame {
    flex: 1;
    width: 100%;
    border: 0;
}

.preview-unavailable {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--ff-text-muted, #64748b);
    gap: 12px;
}

.preview-unavailable i {
    font-size: 40px;
}

.preview-unavailable p {
    font-size: 14px;
    margin: 0;
}

/* Modal animation */
.modal-enter-active,
.modal-leave-active {
    transition: all 0.3s ease;
}

.modal-enter-from {
    opacity: 0;
}

.modal-enter-from .preview-modal {
    transform: scale(0.95);
}

.modal-leave-to {
    opacity: 0;
}

/* ========== ANIMATIONS ========== */
@keyframes ff-slide-in-left {
    from { opacity: 0; transform: translateX(-20px); }
    to   { opacity: 1; transform: translateX(0); }
}

@keyframes ff-fade-in {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

@keyframes ff-scale-in {
    from { opacity: 0; transform: scale(0.95); }
    to   { opacity: 1; transform: scale(1); }
}

/* ========== RESPONSIVE ========== */
@media (max-width: 900px) {
    .od-sidebar {
        display: none;
    }

    .od-main {
        padding: 20px 16px;
    }

    .search-wrapper {
        width: 100%;
    }

    .od-topbar {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }

    .topbar-right {
        flex-direction: column;
    }

    .topbar-actions {
        width: 100%;
    }

    .action-btn {
        flex: 1;
        justify-content: center;
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

    .file-actions {
        opacity: 1;
    }
}
</style>
