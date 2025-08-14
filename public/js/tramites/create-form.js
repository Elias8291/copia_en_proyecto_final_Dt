/**
 * JavaScript para formulario de creación/corrección de trámites
 * Maneja la carga de datos, envío del formulario, progreso y validaciones
 */

class TramiteCreateForm {
    constructor() {
        this.tramiteForm = null;
        this.init();
    }

    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.setupForm();
            this.setupMapResize();
            this.setupTerminosValidation();
            this.setupSuccessEffects();
        });
    }

    setupForm() {
        this.tramiteForm = document.getElementById('tramite-form');
        
        if (this.tramiteForm) {
            this.tramiteForm.addEventListener('submit', (e) => {
                this.handleFormSubmit(e);
            });
        }
    }

    handleFormSubmit(e) {
        console.log('Tramite Form: Iniciando envío del formulario');
        
        const btnEnviar = document.getElementById('btn-enviar-tramite-final');
        if (btnEnviar) {
            btnEnviar.disabled = true;
            btnEnviar.classList.add('btn-enviar-loading');
            btnEnviar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Procesando...';
        }
        
        // Mostrar indicador de progreso
        this.mostrarIndicadorProgreso();
        
        // Timeout para mostrar progreso adicional
        setTimeout(() => {
            if (btnEnviar && btnEnviar.disabled) {
                console.warn('Tramite Form: El formulario está tardando más de lo esperado');
                this.actualizarIndicadorProgreso('Procesando archivos...', 75, 'Finalizando proceso...');
            }
        }, 5000);
        
        // Timeout de seguridad
        setTimeout(() => {
            if (btnEnviar && btnEnviar.disabled) {
                this.handleFormTimeout(btnEnviar);
            }
        }, 15000);
        
        // Detectar envío exitoso
        window.addEventListener('beforeunload', () => {
            console.log('Tramite Form: Formulario enviándose...');
        });
    }

    handleFormTimeout(btnEnviar) {
        console.error('Tramite Form: El formulario parece estar colgado, reactivando botón');
        
        btnEnviar.disabled = false;
        btnEnviar.classList.remove('btn-enviar-loading');
        btnEnviar.innerHTML = `
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
            </svg>
            Enviar Trámite
        `;
        
        this.ocultarIndicadorProgreso(false);
        this.mostrarMensajeTimeout();
    }

    mostrarMensajeTimeout() {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-4';
        errorDiv.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <div>
                    <p class="text-sm text-yellow-700">
                        <strong>Procesamiento lento:</strong> El envío está tardando más de lo normal. Esto puede deberse a:
                    </p>
                    <ul class="text-sm text-yellow-600 mt-1 ml-4 list-disc">
                        <li>Archivos grandes siendo procesados</li>
                        <li>Alta carga del servidor</li>
                        <li>Conexión lenta a internet</li>
                    </ul>
                    <p class="text-sm text-yellow-700 mt-2">
                        <strong>Recomendación:</strong> Espere unos segundos más. Si el problema persiste, intente nuevamente.
                    </p>
                </div>
            </div>
        `;
        
        this.tramiteForm.insertBefore(errorDiv, this.tramiteForm.firstChild);
        
        // Remover mensaje después de 15 segundos
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.remove();
            }
        }, 15000);
    }

    mostrarIndicadorProgreso() {
        const progressDiv = document.createElement('div');
        progressDiv.id = 'progress-indicator';
        progressDiv.className = 'fixed top-0 left-0 w-full bg-gradient-to-r from-blue-600 to-blue-800 text-white z-50 shadow-lg';
        progressDiv.innerHTML = `
            <div class="flex items-center justify-center py-3 px-4">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="animate-spin rounded-full h-6 w-6 border-4 border-white border-t-transparent"></div>
                        <div class="absolute inset-0 rounded-full h-6 w-6 border-2 border-blue-300 animate-pulse"></div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold" id="progress-text">Iniciando envío...</span>
                        <span class="text-xs opacity-75" id="progress-subtitle">Por favor espere...</span>
                    </div>
                </div>
                <div class="ml-6 w-40 bg-blue-700 rounded-full h-3 shadow-inner">
                    <div class="bg-white h-3 rounded-full transition-all duration-300 shadow-sm" id="progress-bar" style="width: 10%"></div>
                </div>
                <div class="ml-4 text-xs font-medium" id="progress-percentage">10%</div>
            </div>
        `;
        document.body.appendChild(progressDiv);
        
        // Animar progreso
        setTimeout(() => {
            this.actualizarIndicadorProgreso('Validando datos del formulario...', 25, 'Verificando información...');
        }, 200);
        
        setTimeout(() => {
            this.actualizarIndicadorProgreso('Procesando información del proveedor...', 45, 'Gestionando datos...');
        }, 600);
        
        setTimeout(() => {
            this.actualizarIndicadorProgreso('Guardando archivos...', 70, 'Procesando documentos...');
        }, 1000);
        
        setTimeout(() => {
            this.actualizarIndicadorProgreso('Finalizando trámite...', 90, 'Completando proceso...');
        }, 1400);
    }

    actualizarIndicadorProgreso(texto, porcentaje, subtitulo = '') {
        const progressText = document.getElementById('progress-text');
        const progressSubtitle = document.getElementById('progress-subtitle');
        const progressBar = document.getElementById('progress-bar');
        const progressPercentage = document.getElementById('progress-percentage');
        
        if (progressText) progressText.textContent = texto;
        if (progressSubtitle && subtitulo) progressSubtitle.textContent = subtitulo;
        if (progressBar) progressBar.style.width = porcentaje + '%';
        if (progressPercentage) progressPercentage.textContent = porcentaje + '%';
        
        // Efecto de pulso en el botón
        const btnEnviar = document.getElementById('btn-enviar-tramite-final');
        if (btnEnviar) {
            btnEnviar.classList.add('animate-pulse');
        }
    }

    ocultarIndicadorProgreso(conExito = false) {
        const progressDiv = document.getElementById('progress-indicator');
        if (progressDiv) {
            if (conExito) {
                progressDiv.className = 'fixed top-0 left-0 w-full bg-gradient-to-r from-green-600 to-green-800 text-white z-50 shadow-lg transition-all duration-500';
                progressDiv.innerHTML = `
                    <div class="flex items-center justify-center py-3 px-4">
                        <div class="flex items-center space-x-4">
                            <div class="text-2xl">✅</div>
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold">¡Trámite enviado exitosamente!</span>
                                <span class="text-xs opacity-75">Redirigiendo...</span>
                            </div>
                        </div>
                    </div>
                `;
                
                setTimeout(() => {
                    progressDiv.remove();
                }, 1500);
            } else {
                progressDiv.remove();
            }
        }
        
        const btnEnviar = document.getElementById('btn-enviar-tramite-final');
        if (btnEnviar) {
            btnEnviar.classList.remove('animate-pulse', 'btn-enviar-loading');
        }
    }

    setupMapResize() {
        window.addEventListener('stepChanged', (event) => {
            const currentStep = event.detail?.currentStep;
            // El step de domicilio es el step 2 (índice 2)
            if (currentStep === 2) {
                setTimeout(() => {
                    const mapContainer = document.getElementById('mapa');
                    if (mapContainer) {
                        const map = mapContainer._leaflet_map;
                        if (map && typeof map.invalidateSize === 'function') {
                            map.invalidateSize();
                        }
                    }
                }, 200);
            }
        });
    }

    setupTerminosValidation() {
        document.addEventListener('DOMContentLoaded', () => {
            const checkboxTerminos = document.getElementById('acepto_terminos');
            if (checkboxTerminos) {
                checkboxTerminos.addEventListener('change', this.validarTerminosYCondiciones);
                this.validarTerminosYCondiciones();
            }
            
            // Observar cambios en el DOM para el botón dinámico
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.type === 'childList') {
                        const btnEnviarFinal = document.getElementById('btn-enviar-tramite-final');
                        if (btnEnviarFinal) {
                            this.validarTerminosYCondiciones();
                            const checkbox = document.getElementById('acepto_terminos');
                            if (checkbox) {
                                checkbox.addEventListener('change', this.validarTerminosYCondiciones);
                            }
                        }
                    }
                });
            });
            
            const navigationContainer = document.querySelector('[data-step-navigation]');
            if (navigationContainer) {
                observer.observe(navigationContainer, { childList: true, subtree: true });
            }
        });
    }

    validarTerminosYCondiciones() {
        const checkboxTerminos = document.getElementById('acepto_terminos');
        const btnEnviarFinal = document.getElementById('btn-enviar-tramite-final');
        
        if (checkboxTerminos && btnEnviarFinal) {
            if (checkboxTerminos.checked) {
                btnEnviarFinal.disabled = false;
                btnEnviarFinal.classList.remove('bg-gray-400', 'cursor-not-allowed');
                btnEnviarFinal.classList.add('bg-[#9d2449]', 'hover:bg-[#8a1f40]');
            } else {
                btnEnviarFinal.disabled = true;
                btnEnviarFinal.classList.remove('bg-[#9d2449]', 'hover:bg-[#8a1f40]');
                btnEnviarFinal.classList.add('bg-gray-400', 'cursor-not-allowed');
            }
        }
    }

    setupSuccessEffects() {
        document.addEventListener('DOMContentLoaded', () => {
            window.tramiteId = null;
            
            // Solo mostrar efecto si viene de sesión exitosa
            if (window.tramiteCreado === true) {
                this.mostrarEfectoExito();
            }
        });
    }

    mostrarEfectoExito() {
        this.crearConfeti();
        
        const successOverlay = document.createElement('div');
        successOverlay.id = 'success-overlay';
        successOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        successOverlay.innerHTML = `
            <div class="bg-white rounded-lg p-8 max-w-md mx-4 text-center shadow-2xl transform transition-all duration-500 scale-95 success-bounce">
                <div class="text-6xl mb-4 success-bounce">🎉</div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">¡Trámite Creado Exitosamente!</h3>
                <p class="text-gray-600 mb-6">Su trámite ha sido procesado y enviado correctamente.</p>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-green-700">
                        <strong>Estado:</strong> Procesado y enviado correctamente
                    </p>
                </div>
                <button onclick="tramiteForm.cerrarEfectoExito()" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg transition-colors duration-200">
                    Continuar
                </button>
            </div>
        `;
        
        document.body.appendChild(successOverlay);
        
        setTimeout(() => {
            const modal = successOverlay.querySelector('div');
            modal.classList.remove('scale-95');
            modal.classList.add('scale-100');
        }, 100);
    }

    crearConfeti() {
        const colors = ['#f00', '#0f0', '#00f', '#ff0', '#f0f', '#0ff'];
        
        for (let i = 0; i < 50; i++) {
            setTimeout(() => {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDelay = Math.random() * 3 + 's';
                confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
                
                document.body.appendChild(confetti);
                
                setTimeout(() => {
                    if (confetti.parentNode) {
                        confetti.remove();
                    }
                }, 5000);
            }, i * 100);
        }
    }

    cerrarEfectoExito() {
        const successOverlay = document.getElementById('success-overlay');
        if (successOverlay) {
            const modal = successOverlay.querySelector('div');
            modal.classList.remove('scale-100');
            modal.classList.add('scale-95');
            
            setTimeout(() => {
                successOverlay.remove();
            }, 300);
        }
    }
}

// Funciones globales para compatibilidad
window.abrirModalTerminos = function() {
    const modal = document.getElementById('modal-terminos-servicio');
    if (modal) {
        modal.classList.remove('hidden');
    }
}

window.validarTerminosYCondicionesFinal = function() {
    if (window.tramiteForm) {
        window.tramiteForm.validarTerminosYCondiciones();
    }
}

// Inicializar
window.tramiteForm = new TramiteCreateForm();
