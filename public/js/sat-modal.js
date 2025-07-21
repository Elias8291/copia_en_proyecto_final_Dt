
/**
 * SATModal - Modal Reutilizable para Datos Fiscales
 * Se puede usar en cualquier vista junto con ConstanciaExtractor
 * 
 * Uso:
 * const modal = new SATModal();
 * modal.show(satData);
 */

// Evitar redeclaración si ya existe
if (window.SATModal) {
    console.log('⚠️ SATModal ya está definido, saltando redeclaración');
} else {

class SATModal {
    constructor(options = {}) {
        this.options = {
            modalId: 'sat-modal-dynamic',
            showUrl: true,
            onContinue: null,
            onClose: null,
            ...options
        };

        this.modal = null;
        this.isInitialized = false;
        
        // Auto-inicializar si el DOM ya está listo
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.init());
        } else {
            this.init();
        }
    }

    /**
     * Inicializa el modal creándolo dinámicamente
     */
    init() {
        if (this.isInitialized) return;

        this.createModal();
        this.bindEvents();
        this.isInitialized = true;
        
        console.log('✅ SATModal inicializado');
    }

    /**
     * Crea el HTML del modal dinámicamente
     */
    createModal() {
        // Remover modal existente si hay uno
        const existing = document.getElementById(this.options.modalId);
        if (existing) {
            existing.remove();
        }

        const modalHTML = `
        <div id="${this.options.modalId}" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
            <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/5 xl:w-1/2 shadow-lg rounded-xl bg-white">
                <div class="mt-3">
                    <!-- Header del modal -->
                    <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">📊 Datos Fiscales Extraídos</h3>
                                <p class="text-sm text-gray-600">Información obtenida de su constancia del SAT</p>
                            </div>
                        </div>
                        <button id="${this.options.modalId}-close" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- URL del QR (opcional) -->
                    <div id="${this.options.modalId}-url-section" class="mt-4 mb-4 ${this.options.showUrl ? '' : 'hidden'}">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">🔗 URL de Verificación SAT:</h4>
                            <p id="${this.options.modalId}-url" class="text-xs font-mono bg-white p-2 rounded border break-all text-gray-600"></p>
                        </div>
                    </div>

                    <!-- Contenido principal -->
                    <div class="mt-4" id="${this.options.modalId}-content">
                        <!-- Se llena dinámicamente -->
                    </div>

                    <!-- Footer del modal -->
                    <div class="flex flex-col sm:flex-row justify-end pt-4 border-t border-gray-200 mt-6 space-y-2 sm:space-y-0 sm:space-x-3">
                        <button id="${this.options.modalId}-close-btn" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-400 transition-colors">
                            Cerrar
                        </button>
                        <button id="${this.options.modalId}-continue-btn" 
                                class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-md">
                            Continuar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHTML);
        this.modal = document.getElementById(this.options.modalId);
    }

    /**
     * Vincula eventos del modal
     */
    bindEvents() {
        // Botones de cerrar
        const closeBtn = document.getElementById(`${this.options.modalId}-close`);
        const closeBtnFooter = document.getElementById(`${this.options.modalId}-close-btn`);
        const continueBtn = document.getElementById(`${this.options.modalId}-continue-btn`);

        if (closeBtn) closeBtn.addEventListener('click', () => this.hide());
        if (closeBtnFooter) closeBtnFooter.addEventListener('click', () => this.hide());
        if (continueBtn) continueBtn.addEventListener('click', () => this.continue());

        // Cerrar con ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !this.modal.classList.contains('hidden')) {
                this.hide();
            }
        });

        // Cerrar al hacer clic fuera del modal
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.hide();
            }
        });
    }

    /**
     * Muestra el modal con datos SAT
     * @param {Object} satData - Datos del SAT
     * @param {string} qrUrl - URL del QR (opcional)
     */
    show(satData, qrUrl = null) {
        if (!this.isInitialized) {
            console.error('❌ SATModal no está inicializado');
            return;
        }

        // Llenar contenido
        this.populateContent(satData);

        // Llenar URL si se proporciona
        if (qrUrl && this.options.showUrl) {
            const urlElement = document.getElementById(`${this.options.modalId}-url`);
            if (urlElement) {
                urlElement.textContent = qrUrl;
            }
        }

        // Mostrar modal
        this.modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevenir scroll del body

        console.log('📊 SATModal mostrado con datos:', satData);
    }

    /**
     * Oculta el modal
     */
    hide() {
        if (this.modal) {
            this.modal.classList.add('hidden');
            document.body.style.overflow = ''; // Restaurar scroll

            // Callback de cierre
            if (typeof this.options.onClose === 'function') {
                this.options.onClose();
            }
        }
    }

    /**
     * Maneja el botón "Continuar"
     */
    continue() {
        // Callback personalizado
        if (typeof this.options.onContinue === 'function') {
            this.options.onContinue();
        } else {
            // Comportamiento por defecto: solo cerrar
            this.hide();
        }
    }

    /**
     * Genera el contenido del modal con los datos SAT
     * @param {Object} satData 
     */
    populateContent(satData) {
        const content = document.getElementById(`${this.options.modalId}-content`);
        if (!content) return;

        const campos = [
            { clave: 'nombre', etiqueta: 'Nombre/Razón Social', icono: '🏢', importante: true },
            { clave: 'rfc', etiqueta: 'RFC', icono: '🆔', importante: true },
            { clave: 'curp', etiqueta: 'CURP', icono: '👤', importante: false },
            { clave: 'regimen_fiscal', etiqueta: 'Régimen Fiscal', icono: '🏛️', importante: true },
            { clave: 'estatus', etiqueta: 'Estatus', icono: '📌', importante: true },
            { clave: 'tipo_persona', etiqueta: 'Tipo de Persona', icono: '🏷️', importante: false },
            { clave: 'entidad_federativa', etiqueta: 'Estado', icono: '📍', importante: false },
            { clave: 'municipio', etiqueta: 'Municipio', icono: '🏘️', importante: false },
            { clave: 'cp', etiqueta: 'Código Postal', icono: '📮', importante: false },
            { clave: 'colonia', etiqueta: 'Colonia', icono: '🏡', importante: false },
            { clave: 'nombre_vialidad', etiqueta: 'Calle', icono: '🛣️', importante: false },
            { clave: 'numero_exterior', etiqueta: 'Número Exterior', icono: '🔢', importante: false },
            { clave: 'numero_interior', etiqueta: 'Número Interior', icono: '🔢', importante: false },
            { clave: 'email', etiqueta: 'Email Fiscal', icono: '📧', importante: false }
        ];

        // Separar campos importantes y otros
        const importantes = campos.filter(c => c.importante);
        const otros = campos.filter(c => !c.importante);

        let html = `
        <!-- Datos Importantes -->
        <div class="mb-6">
            <h4 class="text-md font-semibold text-gray-800 mb-3">📋 Información Principal</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        `;

        importantes.forEach(campo => {
            const valor = satData[campo.clave] || 'No disponible';
            const isEmpty = !satData[campo.clave];
            
            html += `
                <div class="bg-gradient-to-r ${isEmpty ? 'from-gray-50 to-gray-100' : 'from-blue-50 to-blue-100'} p-3 rounded-lg border ${isEmpty ? 'border-gray-200' : 'border-blue-200'}">
                    <label class="block text-sm font-medium ${isEmpty ? 'text-gray-600' : 'text-blue-800'} mb-1">
                        ${campo.icono} ${campo.etiqueta}
                    </label>
                    <p class="text-sm ${isEmpty ? 'text-gray-500 italic' : 'text-gray-900 font-semibold'}">${valor}</p>
                </div>
            `;
        });

        html += '</div></div>';

        // Verificar si hay otros datos
        const hasOtherData = otros.some(campo => satData[campo.clave]);
        
        if (hasOtherData) {
            html += `
            <!-- Otros Datos -->
            <div>
                <h4 class="text-md font-semibold text-gray-800 mb-3">📍 Información Adicional</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            `;

            otros.forEach(campo => {
                const valor = satData[campo.clave];
                if (valor) { // Solo mostrar si tiene valor
                    html += `
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                ${campo.icono} ${campo.etiqueta}
                            </label>
                            <p class="text-sm text-gray-900 font-medium">${valor}</p>
                        </div>
                    `;
                }
            });

            html += '</div></div>';
        }

        content.innerHTML = html;
    }

    /**
     * Destruye el modal (limpieza)
     */
    destroy() {
        if (this.modal) {
            this.modal.remove();
            this.modal = null;
            this.isInitialized = false;
            document.body.style.overflow = '';
        }
    }
}

// Exportar para uso global
window.SATModal = SATModal;

} // Fin del if de protección contra redeclaración

// Crear instancia global por defecto (fuera del if para que esté siempre disponible)
if (!window.satModalGlobal) {
    window.satModalGlobal = new SATModal({
        onContinue: function() {
            // Comportamiento por defecto: buscar formulario de registro
            const registrationForm = document.getElementById('registrationForm');
            if (registrationForm) {
                registrationForm.classList.remove('hidden');
            }
            this.hide();
        }
    });
} 