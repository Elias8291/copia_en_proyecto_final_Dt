/**
 * Sistema centralizado para manejar estados de carga con efectos elegantes
 * Proporciona indicadores visuales atractivos y concisos
 */

// Funciones generales para cualquier botón
function mostrarEstadoCarga(botonId, textoId, loadingId) {
    const btn = document.getElementById(botonId);
    const btnText = document.getElementById(textoId);
    const btnLoading = document.getElementById(loadingId);
    
    if (btn) {
        btn.disabled = true;
        btn.classList.add('opacity-80', 'cursor-not-allowed', 'scale-95');
        btn.classList.remove('hover:-translate-y-0.5', 'transform-gpu', 'hover:scale-105');
        
        if (btnText) btnText.classList.add('hidden');
        if (btnLoading) btnLoading.classList.remove('hidden');
    }
}

function ocultarEstadoCarga(botonId, textoId, loadingId) {
    const btn = document.getElementById(botonId);
    const btnText = document.getElementById(textoId);
    const btnLoading = document.getElementById(loadingId);
    
    if (btn) {
        btn.disabled = false;
        btn.classList.remove('opacity-80', 'cursor-not-allowed', 'scale-95');
        btn.classList.add('hover:-translate-y-0.5', 'transform-gpu', 'hover:scale-105');
        
        if (btnText) btnText.classList.remove('hidden');
        if (btnLoading) btnLoading.classList.add('hidden');
    }
}

// Funciones específicas para cada sección (mantener compatibilidad)
function mostrarEstadoCargaFormulario(seccion) {
    const btn = document.getElementById(`btn-guardar-${seccion}`);
    const btnAlt = document.getElementById(`btn-guardar-${seccion}-alt`);
    const btnText = document.getElementById(`btn-text-${seccion}`);
    const btnTextAlt = document.getElementById(`btn-text-${seccion}-alt`);
    const btnLoading = document.getElementById(`btn-loading-${seccion}`);
    const btnLoadingAlt = document.getElementById(`btn-loading-${seccion}-alt`);
    
    if (btn) {
        btn.disabled = true;
        btn.classList.add('opacity-80', 'cursor-not-allowed', 'scale-95');
        btn.classList.remove('hover:-translate-y-0.5', 'transform-gpu', 'hover:scale-105');
        if (btnText) btnText.classList.add('hidden');
        if (btnLoading) btnLoading.classList.remove('hidden');
    }
    
    if (btnAlt) {
        btnAlt.disabled = true;
        btnAlt.classList.add('opacity-80', 'cursor-not-allowed', 'scale-95');
        btnAlt.classList.remove('hover:-translate-y-0.5', 'transform-gpu', 'hover:scale-105');
        if (btnTextAlt) btnTextAlt.classList.add('hidden');
        if (btnLoadingAlt) btnLoadingAlt.classList.remove('hidden');
    }
}

function ocultarEstadoCargaFormulario(seccion) {
    const btn = document.getElementById(`btn-guardar-${seccion}`);
    const btnAlt = document.getElementById(`btn-guardar-${seccion}-alt`);
    const btnText = document.getElementById(`btn-text-${seccion}`);
    const btnTextAlt = document.getElementById(`btn-text-${seccion}-alt`);
    const btnLoading = document.getElementById(`btn-loading-${seccion}`);
    const btnLoadingAlt = document.getElementById(`btn-loading-${seccion}-alt`);
    
    if (btn) {
        btn.disabled = false;
        btn.classList.remove('opacity-80', 'cursor-not-allowed', 'scale-95');
        btn.classList.add('hover:-translate-y-0.5', 'transform-gpu', 'hover:scale-105');
        if (btnText) btnText.classList.remove('hidden');
        if (btnLoading) btnLoading.classList.add('hidden');
    }
    
    if (btnAlt) {
        btnAlt.disabled = false;
        btnAlt.classList.remove('opacity-80', 'cursor-not-allowed', 'scale-95');
        btnAlt.classList.add('hover:-translate-y-0.5', 'transform-gpu', 'hover:scale-105');
        if (btnTextAlt) btnTextAlt.classList.remove('hidden');
        if (btnLoadingAlt) btnLoadingAlt.classList.add('hidden');
    }
}

// Función para mostrar notificación elegante y breve
function mostrarNotificacionCarga(mensaje = 'Guardando...', tipo = 'loading') {
    // Crear elemento de notificación si no existe
    let notificacion = document.getElementById('loading-notification');
    if (!notificacion) {
        notificacion = document.createElement('div');
        notificacion.id = 'loading-notification';
        document.body.appendChild(notificacion);
    }
    
    // Configurar estilos según el tipo
    const configuraciones = {
        loading: {
            bg: 'bg-blue-600',
            icon: `<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>`,
            duration: 0 // No auto-ocultar
        },
        success: {
            bg: 'bg-green-600',
            icon: `<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`,
            duration: 2000
        },
        error: {
            bg: 'bg-red-600',
            icon: `<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`,
            duration: 3000
        }
    };
    
    const config = configuraciones[tipo] || configuraciones.loading;
    
    // Aplicar estilos y contenido
    notificacion.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg ${config.bg} text-white text-sm font-medium flex items-center space-x-2 transition-all duration-300 transform translate-x-full`;
    
    notificacion.innerHTML = `
        ${config.icon}
        <span>${mensaje}</span>
    `;
    
    // Mostrar con animación
    setTimeout(() => {
        notificacion.classList.remove('translate-x-full');
        notificacion.classList.add('translate-x-0');
    }, 100);
    
    // Auto-ocultar si tiene duración
    if (config.duration > 0) {
        setTimeout(() => {
            ocultarNotificacionCarga();
        }, config.duration);
    }
}

function ocultarNotificacionCarga() {
    const notificacion = document.getElementById('loading-notification');
    if (notificacion) {
        notificacion.classList.remove('translate-x-0');
        notificacion.classList.add('translate-x-full');
        setTimeout(() => {
            if (notificacion.parentNode) {
                notificacion.parentNode.removeChild(notificacion);
            }
        }, 300);
    }
}

// Función para crear loader inline elegante
function crearLoaderInline() {
    return `
        <div class="flex items-center justify-center space-x-1">
            <div class="w-2 h-2 bg-current rounded-full animate-bounce" style="animation-delay: 0ms"></div>
            <div class="w-2 h-2 bg-current rounded-full animate-bounce" style="animation-delay: 150ms"></div>
            <div class="w-2 h-2 bg-current rounded-full animate-bounce" style="animation-delay: 300ms"></div>
        </div>
    `;
}

// Función para crear spinner elegante
function crearSpinnerElegante() {
    return `
        <div class="w-5 h-5 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
    `;
}

// Función para interceptar envíos de formularios con efectos elegantes
function interceptarFormularios() {
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.tagName === 'FORM') {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                // Obtener texto original
                const originalText = submitBtn.innerHTML;
                const isIcon = submitBtn.querySelector('svg, i');
                
                // Aplicar estado de carga
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-80', 'cursor-not-allowed', 'scale-95');
                submitBtn.classList.remove('hover:scale-105');
                
                // Contenido de carga elegante
                if (isIcon) {
                    // Si tiene icono, usar loader de puntos
                    submitBtn.innerHTML = crearLoaderInline();
                } else {
                    // Si es texto, usar spinner + texto breve
                    submitBtn.innerHTML = `
                        <div class="flex items-center justify-center space-x-2">
                            ${crearSpinnerElegante()}
                            <span>Enviando...</span>
                        </div>
                    `;
                }
                
                // Mostrar notificación
                mostrarNotificacionCarga('Procesando...', 'loading');
                
                // Restaurar estado original en caso de error (timeout)
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-80', 'cursor-not-allowed', 'scale-95');
                        submitBtn.classList.add('hover:scale-105');
                        submitBtn.innerHTML = originalText;
                        ocultarNotificacionCarga();
                        mostrarNotificacionCarga('Error de conexión', 'error');
                    }
                }, 10000); // 10 segundos de timeout
            }
        }
    });
}

// Función para simular carga exitosa (usar en callbacks de éxito)
function mostrarExito(mensaje = 'Guardado exitosamente') {
    ocultarNotificacionCarga();
    setTimeout(() => {
        mostrarNotificacionCarga(mensaje, 'success');
    }, 200);
}

// Función para simular error (usar en callbacks de error)
function mostrarError(mensaje = 'Error al procesar') {
    ocultarNotificacionCarga();
    setTimeout(() => {
        mostrarNotificacionCarga(mensaje, 'error');
    }, 200);
}

// Función para crear overlay de carga para toda la página
function mostrarOverlayCarga(mensaje = 'Cargando...') {
    const overlay = document.createElement('div');
    overlay.id = 'loading-overlay';
    overlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    overlay.innerHTML = `
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3 shadow-xl">
            <div class="w-6 h-6 border-3 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
            <span class="text-gray-800 font-medium">${mensaje}</span>
        </div>
    `;
    document.body.appendChild(overlay);
}

function ocultarOverlayCarga() {
    const overlay = document.getElementById('loading-overlay');
    if (overlay) {
        overlay.remove();
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    interceptarFormularios();
    
    // Agregar estilos CSS personalizados
    const styles = document.createElement('style');
    styles.textContent = `
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .loading-pulse {
            animation: pulse-soft 1.5s ease-in-out infinite;
        }
        
        .loading-dots div:nth-child(1) { animation-delay: 0ms; }
        .loading-dots div:nth-child(2) { animation-delay: 150ms; }
        .loading-dots div:nth-child(3) { animation-delay: 300ms; }
    `;
    document.head.appendChild(styles);
});

// Exportar funciones para uso global
window.mostrarEstadoCarga = mostrarEstadoCarga;
window.ocultarEstadoCarga = ocultarEstadoCarga;
window.mostrarEstadoCargaFormulario = mostrarEstadoCargaFormulario;
window.ocultarEstadoCargaFormulario = ocultarEstadoCargaFormulario;
window.mostrarNotificacionCarga = mostrarNotificacionCarga;
window.ocultarNotificacionCarga = ocultarNotificacionCarga;
window.mostrarExito = mostrarExito;
window.mostrarError = mostrarError;
window.mostrarOverlayCarga = mostrarOverlayCarga;
window.ocultarOverlayCarga = ocultarOverlayCarga;
window.crearLoaderInline = crearLoaderInline;
window.crearSpinnerElegante = crearSpinnerElegante;