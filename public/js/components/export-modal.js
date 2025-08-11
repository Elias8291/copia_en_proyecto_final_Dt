/**
 * Export Modal - Modal para selección de columnas de exportación
 * Extiende ModalManager para funcionalidad específica de exportación
 */
class ExportModal extends ModalManager {
    constructor(modalId, exportUrl) {
        super(modalId);
        this.exportUrl = exportUrl;
        this.setupExportEvents();
    }

    setupExportEvents() {
        if (!this.modal) return;

        // Contador de columnas
        this.updateColumnCounter();
        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('columna-checkbox')) {
                this.updateColumnCounter();
            }
        });

        // Seleccionar todas las columnas
        const selectAllBtn = this.modal.querySelector('[data-select-all-columns]');
        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', () => this.selectAllColumns());
        }

        // Limpiar todas las columnas
        const clearAllBtn = this.modal.querySelector('[data-clear-all-columns]');
        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', () => this.clearAllColumns());
        }

        // Confirmar exportación
        const confirmBtn = this.modal.querySelector('[data-confirm-export]');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', () => this.confirmExport());
        }
    }

    onOpen() {
        this.updateColumnCounter();
    }

    updateColumnCounter() {
        const counter = this.modal.querySelector('[data-column-counter]');
        if (counter) {
            const checked = this.modal.querySelectorAll('.columna-checkbox:checked');
            counter.textContent = checked.length;
        }
    }

    selectAllColumns() {
        const checkboxes = this.modal.querySelectorAll('.columna-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = true);
        this.updateColumnCounter();
    }

    clearAllColumns() {
        const checkboxes = this.modal.querySelectorAll('.columna-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = false);
        this.updateColumnCounter();
    }

    confirmExport() {
        const selectedColumns = [];
        const checkboxes = this.modal.querySelectorAll('.columna-checkbox:checked');
        
        if (checkboxes.length === 0) {
            alert('Debes seleccionar al menos una columna para exportar.');
            return;
        }

        checkboxes.forEach(checkbox => {
            selectedColumns.push(checkbox.value);
        });

        // Construir URL de exportación
        const exportUrl = this.buildExportUrl(selectedColumns);
        
        // Mostrar indicador de carga
        this.showLoadingState();
        
        // Cerrar modal y realizar exportación
        this.close();
        window.open(exportUrl.toString(), '_blank');

        // Restaurar estado después de un delay
        setTimeout(() => this.hideLoadingState(), 2000);
    }

    buildExportUrl(selectedColumns) {
        const urlParams = new URLSearchParams(window.location.search);
        const exportUrl = new URL(this.exportUrl, window.location.origin);
        
        // Copiar parámetros de búsqueda existentes
        urlParams.forEach((value, key) => {
            exportUrl.searchParams.append(key, value);
        });

        // Agregar filtros de formulario ocultos
        this.addHiddenInputsToUrl(exportUrl, 'actividad_economica[]');
        this.addHiddenInputsToUrl(exportUrl, 'sector[]');
        this.addHiddenInputsToUrl(exportUrl, 'estado_geografico[]');
        
        // Agregar columnas seleccionadas
        exportUrl.searchParams.set('columnas', selectedColumns.join(','));
        
        return exportUrl;
    }

    addHiddenInputsToUrl(exportUrl, inputName) {
        const inputs = document.querySelectorAll(`input[name="${inputName}"]`);
        inputs.forEach(input => {
            if (input.value && input.value.trim() !== '') {
                exportUrl.searchParams.append(inputName, input.value);
            }
        });
    }

    showLoadingState() {
        const confirmBtn = this.modal.querySelector('[data-confirm-export]');
        if (confirmBtn) {
            const originalText = confirmBtn.innerHTML;
            confirmBtn.setAttribute('data-original-text', originalText);
            confirmBtn.innerHTML = `
                <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Generando...
            `;
            confirmBtn.disabled = true;
        }
    }

    hideLoadingState() {
        const confirmBtn = this.modal.querySelector('[data-confirm-export]');
        if (confirmBtn) {
            const originalText = confirmBtn.getAttribute('data-original-text');
            if (originalText) {
                confirmBtn.innerHTML = originalText;
                confirmBtn.disabled = false;
            }
        }
    }
}

// Exportar para uso global
window.ExportModal = ExportModal;



