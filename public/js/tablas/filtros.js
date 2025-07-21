/**
 * Sistema de filtros simple y reutilizable para tablas
 */
class TablaFiltros {
    constructor(config) {
        // Configuración con valores por defecto
        this.entityName = config.entityName || "elementos";
        this.filtros = config.filtros || [];
        this.messages = {
            noResults: "No se encontraron resultados",
            results: "resultado",
            results_plural: "resultados",
            ...config.messages,
        };

        // Elementos DOM
        this.tableRows = document.querySelectorAll("tbody tr:not(.empty-row)");
        this.emptyRow = document.querySelector("tbody tr.empty-row");
        this.resultsCount = document.querySelector("#results-count");
        this.clearButton = document.querySelector("#clear-filters");

        this.init();
    }

    init() {
        this.addEventListeners();
        this.filter();
    }

    addEventListeners() {
        // Eventos para cada filtro
        this.filtros.forEach((filtro) => {
            const element = document.getElementById(filtro.id);
            if (element) {
                const event = element.tagName === "SELECT" ? "change" : "input";
                element.addEventListener(event, () => this.filter());
            }
        });

        // Botón limpiar
        if (this.clearButton) {
            this.clearButton.addEventListener("click", () => this.clear());
        }
    }

    filter() {
        const activeFilters = this.getActiveFilters();
        let visibleCount = 0;

        this.tableRows.forEach((row) => {
            const shouldShow = activeFilters.every((filtro) =>
                this.matchRow(row, filtro)
            );

            row.style.display = shouldShow ? "" : "none";
            if (shouldShow) visibleCount++;
        });

        this.updateMessages(visibleCount, activeFilters);
    }

    getActiveFilters() {
        return this.filtros
            .map((filtro) => {
                const element = document.getElementById(filtro.id);
                return element && element.value
                    ? {
                          ...filtro,
                          value: element.value.toLowerCase(),
                          label: filtro.label || element.value,
                      }
                    : null;
            })
            .filter(Boolean);
    }

    matchRow(row, filtro) {
        const cell = row.querySelector(`td:nth-child(${filtro.columna})`);
        if (!cell) return true;

        const cellText = filtro.selector
            ? cell.querySelector(filtro.selector)?.textContent || ""
            : cell.textContent;

        const cellValue = cellText.toLowerCase().trim();

        switch (filtro.tipo) {
            case "contains":
                return cellValue.includes(filtro.value);
            case "exact":
                return cellValue === filtro.value;
            case "estado":
                return cellValue.includes(filtro.value);
            case "tipo_persona":
                return (
                    cellValue.includes(filtro.value) ||
                    cellValue.includes("ambos")
                );
            default:
                return cellValue.includes(filtro.value);
        }
    }

    updateMessages(count, activeFilters) {
        // Actualizar contador
        if (this.resultsCount) {
            const resultText = count === 1 ? this.messages.results : this.messages.results_plural;
            let message = `${count} ${resultText}`;

            if (activeFilters.length > 0) {
                if (count === 0) {
                    const terms = activeFilters.map(f => f.label).join(', ');
                    message = `${this.messages.noResults} para "${terms}"`;
                } else {
                    message += ` encontrado${count !== 1 ? 's' : ''}`;
                }
            } else {
                message += ` total`;
            }

            this.resultsCount.textContent = message;
        }

        // Actualizar estado vacío
        if (this.emptyRow) {
            if (count === 0) {
                const emptyMessage = this.emptyRow.querySelector('p.font-medium');
                const subtitle = this.emptyRow.querySelector('p.mt-1');
                
                if (activeFilters.length > 0) {
                    const terms = activeFilters.map(f => f.label).join(', ');
                    if (emptyMessage) {
                        emptyMessage.textContent = `${this.messages.noResults} para "${terms}"`;
                    }
                    if (subtitle) {
                        subtitle.textContent = `Intenta ajustar los filtros o buscar otros ${this.entityName}.`;
                    }
                } else {
                    if (emptyMessage) {
                        emptyMessage.textContent = `No hay ${this.entityName} disponibles`;
                    }
                    if (subtitle) {
                        subtitle.textContent = `Crea el primer ${this.messages.results} para comenzar.`;
                    }
                }
            }
            this.emptyRow.style.display = count === 0 ? '' : 'none';
        }
    }

    clear() {
        this.filtros.forEach((filtro) => {
            const element = document.getElementById(filtro.id);
            if (element) element.value = "";
        });
        this.filter();
    }
}

window.TablaFiltros = TablaFiltros;
