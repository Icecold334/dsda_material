import "./bootstrap";
import "flowbite";
import { Grid,html } from "gridjs";
import "gridjs/dist/theme/mermaid.css";
import { idID } from "gridjs/l10n";
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

    new Grid({
        columns,
        pagination: {
            limit: limit,
        },
        // search: true,
        sort: false,
        page: [5, 10, 15],
        language: idID,
        className: {
            th: 'text-center',

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
}

function scanAndInitGrid() {
    requestAnimationFrame(() => {
        document.querySelectorAll("[data-grid]").forEach(initGrid);
    });
}

document.addEventListener("DOMContentLoaded", scanAndInitGrid);
document.addEventListener("livewire:navigated", scanAndInitGrid);


