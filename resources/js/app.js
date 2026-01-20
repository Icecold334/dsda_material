import "./bootstrap";
import "flowbite";
import { initFlowbite } from "flowbite";
import { Grid, html } from "gridjs";
import "gridjs/dist/theme/mermaid.css";
import { idID } from "gridjs/l10n";
import Swal from "sweetalert2";
window.Swal = Swal;

// ==================== SIDEBAR TOGGLE ====================
function initSidebar() {
    const toggleBtn = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');

    if (!toggleBtn || !sidebar) return;

    function isMobile() {
        return window.innerWidth < 768;
    }

    function openSidebar() {
        sidebar.classList.remove('sidebar-closed');
        localStorage.setItem('sidebarOpen', 'true');
        // Show backdrop on mobile
        if (isMobile() && backdrop) {
            backdrop.classList.remove('hidden');
        }
    }

    function closeSidebar() {
        sidebar.classList.add('sidebar-closed');
        localStorage.setItem('sidebarOpen', 'false');
        // Hide backdrop
        if (backdrop) {
            backdrop.classList.add('hidden');
        }
    }

    // Global function for backdrop click (called from onclick in HTML)
    window.closeSidebarMobile = closeSidebar;

    // Apply saved state (only on desktop, start closed on mobile)
    if (isMobile()) {
        closeSidebar();
    } else {
        const sidebarOpen = localStorage.getItem('sidebarOpen') === 'true';
        if (sidebarOpen) {
            openSidebar();
        } else {
            closeSidebar();
        }
    }

    // Handle window resize
    window.addEventListener('resize', () => {
        if (isMobile()) {
            // On mobile, always hide backdrop if sidebar is closed
            if (sidebar.classList.contains('sidebar-closed') && backdrop) {
                backdrop.classList.add('hidden');
            }
        } else {
            // On desktop, hide backdrop always
            if (backdrop) {
                backdrop.classList.add('hidden');
            }
        }
    });

    // Toggle button click handler (only attach once)
    if (!toggleBtn.dataset.initialized) {
        toggleBtn.addEventListener('click', function () {
            const isClosed = sidebar.classList.contains('sidebar-closed');
            if (isClosed) {
                openSidebar();
            } else {
                closeSidebar();
            }
        });
        toggleBtn.dataset.initialized = 'true';
    }
}

window.showConfirm = function ({
    title = "Yakin?",
    text = "",
    type = "warning",
    confirmButtonText = "Ya",
    cancelButtonText = "Batal",
    onConfirm = null, // 👈 FUNCTION
    onCancel = null, // 👈 OPTIONAL
} = {}) {
    return Swal.fire({
        title,
        text,
        icon: type,
        showCancelButton: true,
        confirmButtonText,
        cancelButtonText,
    }).then((result) => {
        if (result.isConfirmed && typeof onConfirm === "function") {
            onConfirm(result);
        }

        if (result.isDismissed && typeof onCancel === "function") {
            onCancel(result);
        }
    });
};
window.showAlert = function ({
    mode = "alert", // alert | confirm
    type = "success", // success | error | info | warning
    title = "",
    text = "",
    timer = 2000,
    showConfirmButton = false,
    confirmButtonText = "OK",
    cancelButtonText = "Batal",
    confirmEvent = null,
    confirmData = {},
} = {}) {
    const options = {
        title,
        text,
        icon: type,
    };

    // CONFIRM MODE
    if (mode === "confirm") {
        Object.assign(options, {
            showCancelButton: true,
            confirmButtonText,
            cancelButtonText,
        });
    } else {
        Object.assign(options, {
            showConfirmButton,
            timer: showConfirmButton ? undefined : timer,
        });
    }

    return Swal.fire(options).then((result) => {
        if (mode === "confirm" && result.isConfirmed && confirmEvent) {
            window.Livewire.dispatch(confirmEvent, confirmData);
        }
    });
};

// SwalConfirm helper object
window.SwalConfirm = {
    delete: function ({
        eventName,
        eventData = {},
        title = "Hapus Data?",
        text = "Data yang dihapus tidak dapat dikembalikan!",
        confirmButtonText = "Ya, Hapus!",
        cancelButtonText = "Batal",
    } = {}) {
        return Swal.fire({
            title,
            text,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText,
            cancelButtonText,
        }).then((result) => {
            if (result.isConfirmed && eventName) {
                window.Livewire.dispatch(eventName, eventData);
            }
        });
    },
};

function app() {
    Livewire.on("confirm", (payload = {}) => {
        showConfirm(payload);
    });

    Livewire.on("alert", (payload = {}) => {
        showAlert(payload); // alert biasa
    });
    Livewire.on("loading", (payload = {}) => {
        Swal.fire({
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });
    });
    Livewire.on("endLoading", (payload = {}) => {
        Swal.close();
    });

    // Generic success notifications
    Livewire.on("success-created", (data = {}) => {
        Swal.fire({
            icon: "success",
            title: "Berhasil!",
            text: data.message || "Data berhasil ditambahkan",
            timer: 2000,
            showConfirmButton: false,
        });
    });

    Livewire.on("success-updated", (data = {}) => {
        Swal.fire({
            icon: "success",
            title: "Berhasil!",
            text: data.message || "Data berhasil diperbarui",
            timer: 2000,
            showConfirmButton: false,
        });
    });

    Livewire.on("success-deleted", (data = {}) => {
        Swal.fire({
            icon: "success",
            title: "Berhasil!",
            text: data.message || "Data berhasil dihapus",
            timer: 2000,
            showConfirmButton: false,
        });
    });
}

function mapByColumns(rows, columns) {
    return rows.map((row) =>
        columns.map((col) => {
            const value = row[col.id] ?? "";

            // Kalau column punya className → bungkus
            if (col.className) {
                return html(`<div class="${col.className}">${value}</div>`);
            }

            return html(value);
        })
    );
}

function initGrid(wrapper) {
    if (!wrapper || !document.body.contains(wrapper)) return;

    wrapper.innerHTML = "";

    const api = wrapper.dataset.api;
    const columns = JSON.parse(wrapper.dataset.columns || "[]");

    const limit = Number(wrapper.dataset.limit || 5);
    // const defaultFilter = JSON.parse(wrapper.dataset.default || "{}");

    // const params = new URLSearchParams(defaultFilter).toString();

    const grid = new Grid({
        columns,
        pagination: {
            limit: limit,
        },
        // search: true,
        sort: false,
        page: [5, 10, 15],
        language: idID,
        className: {
            th: "text-center",
        },
        server: api
            ? {
                url: api,
                handle: (res) => {
                    if (res.status === 404) return { data: [] };
                    if (res.ok) return res.json();
                    throw new Error("Grid server error");
                },
                then: (json) => {
                    const rows = json.data ?? json;
                    return mapByColumns(rows, columns);
                },
            }
            : undefined,
    }).render(wrapper);

    // Add reload event listener
    wrapper.addEventListener("reload-grid", () => {
        grid.forceRender();
    });
}

function scanAndInitGrid() {
    requestAnimationFrame(() => {
        document.querySelectorAll("[data-grid]").forEach(initGrid);
    });
}

document.addEventListener("DOMContentLoaded", () => {
    scanAndInitGrid();
    initFlowbite();
    initSidebar();
    app();
});

document.addEventListener("livewire:navigated", () => {
    scanAndInitGrid();
    initFlowbite();
    initSidebar();
    app();
});
