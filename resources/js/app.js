import "./bootstrap";
import "flowbite";
import { initFlowbite } from "flowbite";
import { Grid, html } from "gridjs";
import "gridjs/dist/theme/mermaid.css";
import { idID } from "gridjs/l10n";
import Swal from "sweetalert2";

window.showConfirm = function ({
    title = "Yakin?",
    text = "",
    type = "warning",
    confirmButtonText = "Ya",
    cancelButtonText = "Batal",
    callbackEvent = null,
    callbackData = {},
} = {}) {

    return Swal.fire({
        title,
        text,
        icon: type,
        showCancelButton: true,
        confirmButtonText,
        cancelButtonText,
    }).then((result) => {
        if (callbackEvent) {
            window.Livewire.dispatch(callbackEvent, {
                confirmed: result.isConfirmed,
                ...callbackData,
            });
        }
    });
};


window.showAlert = function ({
    mode = "alert",        // alert | confirm
    type = "success",      // success | error | info | warning
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
        if (
            mode === "confirm" &&
            result.isConfirmed &&
            confirmEvent
        ) {
            window.Livewire.dispatch(confirmEvent, confirmData);
        }
    });
};


document.addEventListener("livewire:init", () => {
    document.getElementById("btnCari").addEventListener("click", async () => {
    const nomorKontrak = document.getElementById("nomorKontrak").value.trim();
    const tahun = document.getElementById("tahunKontrak").value.trim();
    
    if (!nomorKontrak || !tahun) {
        showAlert({
            icon: "error",
            title: "Lengkapi Data!",
            text: "Nomor kontrak dan tahun wajib diisi",
            showConfirmButton: true,
        });
        return;
    }

    try {
        const params = new URLSearchParams({
            nomor_kontrak: nomorKontrak,
            tahun: tahun,
        });

        const res = await fetch(`/kontrak/api/emonev?${params.toString()}`, {
            headers: {
                "Accept": "application/json",
            },
        });

        const data = await res.json();

        if (!res.ok) {
            throw new Error(data.message || "Terjadi kesalahan");
        }

        // 🔥 SUCCESS
        console.log(data.data); // kontrak object

        showAlert({
            icon: "success",
            title: "Berhasil!",
            text: "Kontrak ditemukan",
        });

        // TODO:
        // render ke table / modal / component
        // open modal detail kontrak

    } catch (err) {
        showAlert({
            icon: "error",
            title: "Gagal!",
            text: err.message,
            showConfirmButton: true,
        });
    }
});

    Livewire.on("confirm", (payload = {}) => {
        
        showConfirm(payload);
    });

    Livewire.on("alert", (payload = {}) => {
        showAlert(payload); // alert biasa
    });
});





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
});

document.addEventListener("livewire:navigated", () => {
    scanAndInitGrid();
    initFlowbite();
});
