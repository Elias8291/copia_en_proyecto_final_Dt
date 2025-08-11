/**
 * Filter Modal - Modal genérico para filtros con checkboxes
 * Extiende ModalManager para funcionalidad específica de filtros
 */
class FilterModal extends ModalManager {
    constructor(modalId, options = {}) {
        super(modalId, options);
        this.config = {
            checkboxSelector: '.filter-checkbox',
            counterSelector: '[data-counter]',
            searchSelector: '[data-search]',
            selectAllSelector: '[data-select-all]',
            clearAllSelector: '[data-clear-all]',
            applySelector: '[data-apply]',
            hiddenInputId: '',
            buttonTextId: '',
            formId: 'searchForm',
            inputName: '',
            ...options
        };
        this.setupFilterEvents();
    }

    setupFilterEvents() {
        if (!this.modal) return;

        // Contador automático
        this.updateCounter();
        document.addEventListener('change', (e) => {
            if (e.target.matches(this.config.checkboxSelector)) {
                this.updateCounter();
            }
        });

        // Buscador
        const searchInput = this.modal.querySelector(this.config.searchSelector);
        if (searchInput) {
            searchInput.addEventListener('input', (e) => this.search(e.target.value));
        }

        // Seleccionar todos
        const selectAllBtn = this.modal.querySelector(this.config.selectAllSelector);
        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', () => this.selectAll());
        }

        // Limpiar todos
        const clearAllBtn = this.modal.querySelector(this.config.clearAllSelector);
        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', () => this.clearAll());
        }

        // Aplicar filtro
        const applyBtn = this.modal.querySelector(this.config.applySelector);
        if (applyBtn) {
            applyBtn.addEventListener('click', () => this.apply());
        }
    }

    onOpen() {
        this.loadSelectedItems();
        this.updateCounter();
    }

    updateCounter() {
        const counter = this.modal.querySelector(this.config.counterSelector);
        if (counter) {
            const checked = this.modal.querySelectorAll(`${this.config.checkboxSelector}:checked`);
            counter.textContent = checked.length;
        }
    }

    search(term) {
        const items = this.modal.querySelectorAll('[data-name]');
        const searchTerm = term.toLowerCase();
        
        items.forEach(item => {
            const name = item.getAttribute('data-name');
            item.style.display = name.includes(searchTerm) ? 'flex' : 'none';
        });
    }

    selectAll() {
        const checkboxes = this.modal.querySelectorAll(this.config.checkboxSelector);
        checkboxes.forEach(cb => cb.checked = true);
        this.updateCounter();
    }

    clearAll() {
        const checkboxes = this.modal.querySelectorAll(this.config.checkboxSelector);
        checkboxes.forEach(cb => cb.checked = false);
        this.updateCounter();
    }

    loadSelectedItems() {
        if (!this.config.hiddenInputId) return;
        
        const hiddenInput = document.getElementById(this.config.hiddenInputId);
        if (!hiddenInput || !hiddenInput.value) return;

        const selectedIds = hiddenInput.value.split(',').filter(id => id.trim() !== '');
        const checkboxes = this.modal.querySelectorAll(this.config.checkboxSelector);
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectedIds.includes(checkbox.value);
        });
    }

    apply() {
        const selectedItems = [];
        const checkboxes = this.modal.querySelectorAll(`${this.config.checkboxSelector}:checked`);
        
        checkboxes.forEach(checkbox => {
            selectedItems.push(checkbox.value);
        });

        // Limpiar inputs existentes
        const existingInputs = document.querySelectorAll(`input[name="${this.config.inputName}"]`);
        existingInputs.forEach(input => input.remove());

        // Agregar nuevos inputs ocultos
        const form = document.getElementById(this.config.formId);
        selectedItems.forEach(itemId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = this.config.inputName;
            input.value = itemId;
            form.appendChild(input);
        });

        // Actualizar campo oculto
        if (this.config.hiddenInputId) {
            document.getElementById(this.config.hiddenInputId).value = selectedItems.join(',');
        }

        // Actualizar texto del botón
        this.updateButtonText(selectedItems.length);

        // Cerrar modal y enviar formulario
        this.close();
        form.submit();
    }

    updateButtonText(count) {
        if (!this.config.buttonTextId) return;
        
        const buttonText = document.getElementById(this.config.buttonTextId);
        if (buttonText) {
            if (count > 0) {
                buttonText.textContent = `${count} ${this.config.itemType || 'elemento(s)'} seleccionado(s)`;
            } else {
                buttonText.textContent = this.config.defaultText || 'Seleccionar...';
            }
        }
    }

    // Método para inicializar el texto del botón al cargar la página
    initButtonText() {
        if (!this.config.hiddenInputId) return;
        
        const hiddenInput = document.getElementById(this.config.hiddenInputId);
        const count = hiddenInput && hiddenInput.value ? 
            hiddenInput.value.split(',').filter(id => id.trim() !== '').length : 0;
        
        this.updateButtonText(count);
    }
}

// Exportar para uso global
window.FilterModal = FilterModal;

