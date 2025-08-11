/**
 * Página de índice de proveedores
 * Inicialización y configuración de todos los componentes
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // ========== CONFIGURACIÓN DE FILTROS AVANZADOS ==========
    initAdvancedFilters();
    
    // ========== CONFIGURACIÓN DE MODALES DE FILTRO ==========
    initFilterModals();
    
    // ========== CONFIGURACIÓN DE MODAL DE EXPORTACIÓN ==========
    initExportModal();
    
    // ========== CONFIGURACIÓN DE PAGINACIÓN ==========
    initPagination();

    /**
     * Inicializar filtros avanzados (toggle de mostrar/ocultar)
     */
    function initAdvancedFilters() {
        const toggle = document.getElementById('toggleFilters');
        const container = document.getElementById('filtersContainer');
        const text = document.getElementById('filterText');
        const icon = document.getElementById('filterIcon');
        
        toggle?.addEventListener('click', function() {
            const hidden = container?.classList.contains('hidden');
            if (hidden) {
                container?.classList.remove('hidden', 'max-h-0');
                container?.classList.add('max-h-screen');
                if (text) text.textContent = 'Ocultar filtros';
                icon?.classList.add('rotate-180');
            } else {
                container?.classList.add('max-h-0');
                setTimeout(() => container?.classList.add('hidden'), 300);
                if (text) text.textContent = 'Mostrar filtros';
                icon?.classList.remove('rotate-180');
            }
        });
    }

    /**
     * Inicializar modales de filtro reutilizables
     */
    function initFilterModals() {
        // Modal de Estados del País
        const estadosModal = new FilterModal('modalEstados', {
            checkboxSelector: '.estado-checkbox',
            counterSelector: '[data-counter]',
            searchSelector: '[data-search]',
            selectAllSelector: '[data-select-all]',
            clearAllSelector: '[data-clear-all]',
            applySelector: '[data-apply]',
            hiddenInputId: 'estadosSeleccionados',
            buttonTextId: 'estadosSeleccionadosTexto',
            inputName: 'estado_geografico[]',
            itemType: 'estado(s)',
            defaultText: 'Seleccionar estados...'
        });

        // Modal de Sectores Económicos
        const sectoresModal = new FilterModal('modalSectores', {
            checkboxSelector: '.sector-checkbox',
            counterSelector: '[data-counter]',
            searchSelector: '[data-search]',
            selectAllSelector: '[data-select-all]',
            clearAllSelector: '[data-clear-all]',
            applySelector: '[data-apply]',
            hiddenInputId: 'sectoresSeleccionados',
            buttonTextId: 'sectoresSeleccionadosTexto',
            inputName: 'sector[]',
            itemType: 'sector(es)',
            defaultText: 'Seleccionar sectores...'
        });

        // Modal de Actividades Económicas (más complejo debido a agrupación por sectores)
        initActividadesModal();

        // Configurar botones para abrir modales
        document.getElementById('btnAbrirModalEstados')?.addEventListener('click', () => estadosModal.open());
        document.getElementById('btnAbrirModalSectores')?.addEventListener('click', () => sectoresModal.open());

        // Inicializar textos de botones
        estadosModal.initButtonText();
        sectoresModal.initButtonText();
    }

    /**
     * Inicializar modal de actividades (caso especial por agrupación)
     */
    function initActividadesModal() {
        const actividadesModal = new ModalManager('modalActividades');
        const contadorActividades = document.getElementById('contadorSeleccionadas');
        
        // Eventos específicos del modal de actividades
        document.getElementById('btnAbrirModalActividades')?.addEventListener('click', () => {
            actividadesModal.open();
            loadSelectedActividades();
            updateActividadesCounter();
        });

        // Seleccionar todas las actividades
        document.getElementById('seleccionarTodasActividades')?.addEventListener('click', () => {
            document.querySelectorAll('.actividad-checkbox').forEach(cb => cb.checked = true);
            updateActividadesCounter();
        });

        // Limpiar todas las actividades
        document.getElementById('limpiarSeleccionActividades')?.addEventListener('click', () => {
            document.querySelectorAll('.actividad-checkbox').forEach(cb => cb.checked = false);
            updateActividadesCounter();
        });

        // Buscador de actividades
        document.getElementById('buscarActividad')?.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            document.querySelectorAll('.actividad-item').forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(term) ? 'block' : 'none';
            });
        });

        // Aplicar selección
        document.getElementById('aplicarSeleccion')?.addEventListener('click', () => {
            applyActividadesSelection();
            actividadesModal.close();
        });

        // Actualizar contador cuando cambie una checkbox
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('actividad-checkbox')) {
                updateActividadesCounter();
            }
        });

        function loadSelectedActividades() {
            const selectedIds = document.getElementById('actividadesSeleccionadas').value;
            if (selectedIds) {
                const ids = selectedIds.split(',').filter(id => id.trim() !== '');
                document.querySelectorAll('.actividad-checkbox').forEach(checkbox => {
                    checkbox.checked = ids.includes(checkbox.value);
                });
            }
        }

        function updateActividadesCounter() {
            const checked = document.querySelectorAll('.actividad-checkbox:checked');
            if (contadorActividades) {
                contadorActividades.textContent = checked.length;
            }
        }

        function applyActividadesSelection() {
            const selectedIds = [];
            document.querySelectorAll('.actividad-checkbox:checked').forEach(cb => {
                selectedIds.push(cb.value);
            });

            // Limpiar inputs existentes
            document.querySelectorAll('input[name="actividad_economica[]"]').forEach(input => input.remove());

            // Agregar nuevos inputs
            const form = document.getElementById('searchForm');
            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'actividad_economica[]';
                input.value = id;
                form.appendChild(input);
            });

            // Actualizar campo oculto y texto del botón
            document.getElementById('actividadesSeleccionadas').value = selectedIds.join(',');
            updateActividadesButtonText(selectedIds.length);

            // Enviar formulario
            form.submit();
        }

        function updateActividadesButtonText(count) {
            const buttonText = document.getElementById('actividadesSeleccionadasTexto');
            if (buttonText) {
                if (count > 0) {
                    buttonText.textContent = `${count} actividad(es) seleccionada(s)`;
                } else {
                    buttonText.textContent = 'Seleccionar actividades...';
                }
            }
        }

        // Inicializar texto del botón de actividades
        const selectedIds = document.getElementById('actividadesSeleccionadas').value;
        const count = selectedIds ? selectedIds.split(',').filter(id => id.trim() !== '').length : 0;
        updateActividadesButtonText(count);
    }

    /**
     * Inicializar modal de exportación
     */
    function initExportModal() {
        const exportUrl = document.body.getAttribute('data-export-url');
        const exportModal = new ExportModal('modalExportColumnas', exportUrl);
        
        document.getElementById('btnAbrirModalExport')?.addEventListener('click', () => {
            exportModal.open();
        });
    }

    /**
     * Inicializar control de paginación
     */
    function initPagination() {
        const perPageSelect = document.getElementById('per_page');
        const searchForm = document.getElementById('searchForm');
        
        perPageSelect?.addEventListener('change', function() {
            const hiddenPerPage = searchForm.querySelector('input[name="per_page"]');
            if (hiddenPerPage) {
                hiddenPerPage.value = this.value;
            }
            searchForm.submit();
        });
    }
});

