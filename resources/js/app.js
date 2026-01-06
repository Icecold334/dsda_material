import "./bootstrap";
import "flowbite";
import { initFlowbite } from "flowbite";
import { Grid, html } from "gridjs";
import "gridjs/dist/theme/mermaid.css";
import { idID } from "gridjs/l10n";
import Swal from "sweetalert2";

// Reusable SweetAlert Functions
window.SwalConfirm = {
    delete: function (options = {}) {
        const defaults = {
            title: "Yakin ingin menghapus?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            confirmText: "Ya, hapus!",
            cancelText: "Batal",
            eventName: "delete",
            eventData: {},
        };
        const config = { ...defaults, ...options };

        return Swal.fire({
            title: config.title,
            text: config.text,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: config.confirmText,
            cancelButtonText: config.cancelText,
        }).then((result) => {
            if (result.isConfirmed) {
                window.Livewire.dispatch(config.eventName, config.eventData);
            }
        });
    },
};

window.SwalAlert = {
    success: function (options = {}) {
        const defaults = {
            title: "Berhasil!",
            text: "Operasi berhasil dilakukan.",
            timer: 2000,
            showConfirmButton: false,
        };
        const config = { ...defaults, ...options };

        return Swal.fire({
            title: config.title,
            text: config.text,
            icon: "success",
            timer: config.timer,
            showConfirmButton: config.showConfirmButton,
        });
    },
    error: function (options = {}) {
        const defaults = {
            title: "Gagal!",
            text: "Terjadi kesalahan.",
        };
        const config = { ...defaults, ...options };

        return Swal.fire({
            title: config.title,
            text: config.text,
            icon: "error",
            confirmButtonText: "OK",
        });
    },
    info: function (options = {}) {
        const defaults = {
            title: "Informasi",
            text: "",
        };
        const config = { ...defaults, ...options };

        return Swal.fire({
            title: config.title,
            text: config.text,
            icon: "info",
            confirmButtonText: "OK",
        });
    },
};

// Listen untuk success events
document.addEventListener("livewire:init", () => {
    Livewire.on("driver-deleted", () => {
        SwalAlert.success({
            title: "Terhapus!",
            text: "Driver berhasil dihapus.",
        });
    });

    Livewire.on("driver-updated", () => {
        SwalAlert.success({
            title: "Berhasil!",
            text: "Driver berhasil diupdate.",
        });
    });

    Livewire.on("security-deleted", () => {
        SwalAlert.success({
            title: "Terhapus!",
            text: "Security berhasil dihapus.",
        });
    });

    Livewire.on("security-updated", () => {
        SwalAlert.success({
            title: "Berhasil!",
            text: "Security berhasil diupdate.",
        });
    });

    Livewire.on("sudin-deleted", () => {
        SwalAlert.success({
            title: "Terhapus!",
            text: "Sudin berhasil dihapus.",
        });
    });

    Livewire.on("sudin-updated", () => {
        SwalAlert.success({
            title: "Berhasil!",
            text: "Sudin berhasil diupdate.",
        });
    });

    Livewire.on("district-deleted", () => {
        SwalAlert.success({
            title: "Terhapus!",
            text: "Kecamatan berhasil dihapus.",
        });
    });

    Livewire.on("district-updated", () => {
        SwalAlert.success({
            title: "Berhasil!",
            text: "Kecamatan berhasil diupdate.",
        });
    });

    Livewire.on("subdistrict-deleted", () => {
        SwalAlert.success({
            title: "Terhapus!",
            text: "Kecamatan berhasil dihapus.",
        });
    });

    Livewire.on("subdistrict-updated", () => {
        SwalAlert.success({
            title: "Berhasil!",
            text: "Kecamatan berhasil diupdate.",
        });
    });

    Livewire.on("warehouse-deleted", () => {
        SwalAlert.success({
            title: "Terhapus!",
            text: "Gudang berhasil dihapus.",
        });
    });

    Livewire.on("warehouse-updated", () => {
        SwalAlert.success({
            title: "Berhasil!",
            text: "Gudang berhasil diupdate.",
        });
    });

    Livewire.on("item-category-deleted", () => {
        SwalAlert.success({
            title: "Terhapus!",
            text: "Kategori barang berhasil dihapus.",
        });
    });

    Livewire.on("item-category-updated", () => {
        SwalAlert.success({
            title: "Berhasil!",
            text: "Kategori barang berhasil diupdate.",
        });
    });

    Livewire.on("item-deleted", () => {
        SwalAlert.success({
            title: "Terhapus!",
            text: "Barang berhasil dihapus.",
        });
    });

    Livewire.on("item-updated", () => {
        SwalAlert.success({
            title: "Berhasil!",
            text: "Barang berhasil diupdate.",
        });
    });
});

function mapByColumns(rows, columns) {
    return rows.map((row) => columns.map((col) => html(row[col.id] ?? "")));
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
        sort: true,
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
});

document.addEventListener("livewire:navigated", () => {
    scanAndInitGrid();
    initFlowbite();
});
