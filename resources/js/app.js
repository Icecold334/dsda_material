import "./bootstrap";
import "flowbite";
import { Grid } from "gridjs";
import "gridjs/dist/theme/mermaid.css";
import { idID } from "gridjs/l10n";
function mapByColumns(rows, columns) {
    return rows.map((row) => columns.map((col) => row[col.id]));
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
            page: 2,
            summary: false,
        },
        search: true,
        sort: true,
        page: [5, 10, 15],
        language: idID,
        server: api
            ? {
                  // url: `${api}?${params}`,
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
