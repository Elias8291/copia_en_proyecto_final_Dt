/**
 * SATModal - Modal para mostrar datos extraídos del SAT
 */
class SATModal {
    constructor(options = {}) {
        this.options = {
            showUrl: false,
            onContinue: null,
            onClose: null,
            ...options
        };
        
        this.modal = null;
        this.isVisible = false;
        this.currentData = null;
        
        this.createModal();
    }

    createModal() {
        // Crear el modal dinámicamente
        const modalHTML = `
            <div id="satDataModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full mx-4 p-6 relative max-h-[90vh] overflow-y-auto">
                    <button type="button" class="sat-modal-close absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <div class="text-center mb-4">
                        <div class="w-12 h-12 mx-auto mb-3 bg-gradient-to-br from-primary/10 to-primary-dark/10 rounded-full p-2 flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">Datos de la Constancia Fiscal</h2>
                        <p class="text-xs text-gray-600 mt-1">Verifica los datos extraídos de tu constancia</p>
                    </div>
                    <div id="satDataContent" class="space-y-3 text-sm text-gray-700">
                        <!-- SAT data will be populated here -->
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" class="sat-modal-close px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 text-sm font-medium">
                            Cancelar
                        </button>
                        <button type="button" class="sat-modal-continue px-4 py-2 bg-gradient-to-r from-primary to-primary-dark text-white rounded-lg hover:from-primary-dark hover:to-primary transition-all duration-300 text-sm font-medium">
                            Confirmar y Continuar
                        </button>
                    </div>
                </div>
            </div>
        `;

        // Agregar al DOM si no existe
        if (!document.getElementById('satDataModal')) {
            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }

        this.modal = document.getElementById('satDataModal');
        this.bindEvents();
    }

    bindEvents() {
        if (!this.modal) return;

        // Botones de cerrar
        const closeButtons = this.modal.querySelectorAll('.sat-modal-close');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', () => this.hide());
        });

        // Botón continuar
        const continueButton = this.modal.querySelector('.sat-modal-continue');
        if (continueButton) {
            continueButton.addEventListener('click', () => {
                if (typeof this.options.onContinue === 'function') {
                    this.options.onContinue(this.currentData);
                }
            });
        }

        // Cerrar con ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isVisible) {
                this.hide();
            }
        });

        // Cerrar al hacer clic fuera
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.hide();
            }
        });
    }

    show(satData, qrUrl = null) {
        if (!this.modal) return;

        this.currentData = { satData, qrUrl };
        this.populateData(satData, qrUrl);
        
        this.modal.classList.remove('hidden');
        this.isVisible = true;

        // Animación de entrada
        setTimeout(() => {
            this.modal.style.opacity = '1';
        }, 10);
    }

    hide() {
        if (!this.modal) return;

        this.modal.classList.add('hidden');
        this.isVisible = false;
        this.currentData = null;

        if (typeof this.options.onClose === 'function') {
            this.options.onClose();
        }
    }

    populateData(satData, qrUrl = null) {
        const content = this.modal.querySelector('#satDataContent');
        if (!content) return;

        const fields = [
            { label: 'RFC', key: 'rfc' },
            { label: 'Razón Social', key: 'nombre' },
            { label: 'CURP', key: 'curp' },
            { label: 'Régimen Fiscal', key: 'regimen_fiscal' },
            { label: 'Estatus', key: 'estatus' },
            { label: 'Entidad Federativa', key: 'entidad_federativa' },
            { label: 'Municipio', key: 'municipio' },
            { label: 'Email', key: 'email' },
            { label: 'Código Postal', key: 'cp' },
            { label: 'Colonia', key: 'colonia' },
            { label: 'Calle', key: 'nombre_vialidad' },
            { label: 'Número Exterior', key: 'numero_exterior' },
            { label: 'Número Interior', key: 'numero_interior' }
        ];

        let html = '';

        // Mostrar URL si está habilitado
        if (this.options.showUrl && qrUrl) {
            html += `
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <div class="flex items-start space-x-2">
                        <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-blue-800 text-xs">URL del QR:</p>
                            <p class="text-blue-600 text-xs break-all">${qrUrl}</p>
                        </div>
                    </div>
                </div>
            `;
        }

        // Mostrar datos del SAT
        fields.forEach(field => {
            const value = satData[field.key];
            if (value && value.trim()) {
                html += `
                    <div class="flex justify-between items-start py-2 border-b border-gray-100 last:border-b-0">
                        <span class="font-medium text-gray-600 text-xs">${field.label}:</span>
                        <span class="text-gray-800 text-xs text-right max-w-xs break-words">${value}</span>
                    </div>
                `;
            }
        });

        if (!html) {
            html = '<p class="text-gray-500 text-center text-xs">No se encontraron datos para mostrar</p>';
        }

        content.innerHTML = html;
    }
}

// Exportar para uso global
window.SATModal = SATModal;