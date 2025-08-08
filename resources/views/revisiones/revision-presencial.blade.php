@extends('layouts.app')

@section('title', 'Revisión Presencial')

@section('content')
<!-- Container principal con padding responsive -->
<div class="p-2 xs:p-3 sm:p-4 md:p-6 lg:p-8 xl:p-10">
    <!-- Contenedor principal con ancho máximo y sombra -->
    <div class="max-w-7xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <!-- Header con información del trámite -->
        <div class="p-3 xs:p-4 sm:p-5 md:p-6 lg:p-8 border-b border-gray-200/70">
            <!-- Layout flexible que se adapta a diferentes tamaños -->
            <div class="flex flex-col xs:flex-row xs:items-center xs:justify-between gap-3 xs:gap-4 sm:gap-6">
                <!-- Área del título e icono -->
                <div class="flex flex-col xs:flex-row xs:items-center gap-3 xs:gap-4 sm:gap-6">
                    <!-- Icono con gradiente -->
                    <div class="bg-gradient-to-br from-orange-600 via-orange-700 to-orange-800 rounded-xl p-2 xs:p-3 sm:p-4 shadow-lg self-start xs:self-auto">
                        <svg class="w-4 h-4 xs:w-5 xs:h-5 sm:w-6 sm:h-6 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <!-- Información del título -->
                    <div class="min-w-0 flex-1">
                        <h1 class="text-base xs:text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold text-gray-800 break-words leading-tight">
                            Revisión Presencial - Trámite #{{ $tramite->id }}
                        </h1>
                        <p class="text-xs xs:text-sm sm:text-base lg:text-lg text-gray-500 mt-1 xs:mt-2">Revisión presencial de documentos y datos del trámite</p>
                    </div>
                </div>
                <!-- Botón de volver -->
                <div class="flex items-center gap-2 xs:gap-3 sm:gap-4 self-start xs:self-auto">
                    <a href="{{ route('revisiones.index') }}" 
                       class="inline-flex items-center px-2 xs:px-3 sm:px-4 md:px-5 py-2 xs:py-2.5 sm:py-3 bg-gray-600 hover:bg-gray-700 text-white text-xs xs:text-sm sm:text-base font-medium rounded-lg transition-all duration-200 shadow-sm min-h-[44px]">
                        <svg class="w-3 h-3 xs:w-4 xs:h-4 sm:w-5 sm:h-5 mr-1 xs:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="hidden xs:inline">Volver</span>
                        <span class="xs:hidden">←</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="p-3 xs:p-4 sm:p-5 md:p-6 lg:p-8">
            <!-- Datos Generales -->
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 xs:p-4 sm:p-5 md:p-6 mb-4 xs:mb-5 sm:mb-6 lg:mb-8">
                <div class="grid grid-cols-1 xs:grid-cols-2 lg:grid-cols-4 gap-3 xs:gap-4 sm:gap-5 md:gap-6">
                    <div class="min-w-0">
                        <span class="text-xs xs:text-sm sm:text-base font-medium text-orange-800">Trámite #{{ $tramite->id }}</span>
                        <p class="text-orange-900 font-semibold text-sm xs:text-base sm:text-lg break-words leading-tight">{{ ucfirst($tramite->tipo_tramite) }}</p>
                    </div>
                    <div class="min-w-0">
                        <span class="text-xs xs:text-sm sm:text-base font-medium text-orange-800">RFC:</span>
                        <p class="text-orange-900 font-mono text-xs xs:text-sm sm:text-base break-all leading-tight">{{ $tramite->proveedor->rfc ?? 'N/A' }}</p>
                    </div>
                    <div class="min-w-0">
                        <span class="text-xs xs:text-sm sm:text-base font-medium text-orange-800">Razón Social:</span>
                        <p class="text-orange-900 text-xs xs:text-sm sm:text-base break-words leading-tight">{{ $viewModel->getDatosGenerales()['razon_social'] ?? 'N/A' }}</p>
                    </div>
                    <div class="min-w-0">
                        <span class="text-xs xs:text-sm sm:text-base font-medium text-orange-800">CURP:</span>
                        <p class="text-orange-900 text-xs xs:text-sm sm:text-base font-mono break-all leading-tight">{{ $viewModel->getDatosGenerales()['curp'] ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>



            <!-- Archivos -->
            <div class="mb-4 xs:mb-5 sm:mb-6 lg:mb-8" data-section="archivos">
                <div class="mb-3 xs:mb-4 sm:mb-5">
                    <h2 class="text-base xs:text-lg sm:text-xl md:text-2xl font-bold text-gray-800 leading-tight">Documentos para Cotejo Presencial</h2>
                    <p class="text-xs xs:text-sm sm:text-base text-gray-600 mt-1 xs:mt-2">Verificación presencial de documentos originales</p>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3 xs:p-4 sm:p-5 md:p-6 lg:p-8">
                    <x-revision.evaluacion-archivos 
                        :archivosSubidos="$archivosSubidos"
                        seccion="archivos"
                    />
                </div>
                
                <!-- Área de comentarios -->
                <div class="bg-white border border-gray-200 rounded-lg p-3 xs:p-4 sm:p-5 mt-3 xs:mt-4 sm:mt-5">
                    <div class="mb-2 xs:mb-3 sm:mb-4">
                        <h4 class="text-xs xs:text-sm sm:text-base font-medium text-gray-700">Comentarios - Cotejo Presencial</h4>
                    </div>
                    <textarea 
                        id="comentario_archivos"
                        placeholder="Agregar observaciones sobre el cotejo presencial de documentos..."
                        class="w-full px-2 xs:px-3 sm:px-4 py-2 xs:py-2.5 sm:py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 resize-none text-xs xs:text-sm sm:text-base"
                        rows="3"
                    ></textarea>
                </div>
                    
                <!-- Botones de decisión -->
                <div class="flex flex-col xs:flex-row justify-end gap-2 xs:gap-3 sm:gap-4 mt-3 xs:mt-4 sm:mt-5">
                    <button type="button" 
                            onclick="evaluarSeccion('archivos', 'Rechazado')"
                            class="px-3 xs:px-4 sm:px-5 py-2 xs:py-2.5 sm:py-3 bg-red-600 text-white text-xs xs:text-sm sm:text-base font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/50 transition-all duration-200 shadow-sm min-h-[44px]">
                        <svg class="w-3 h-3 xs:w-4 xs:h-4 sm:w-5 sm:h-5 mr-1 xs:mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span class="hidden xs:inline">Rechazar Sección</span>
                        <span class="xs:hidden">Rechazar</span>
                    </button>
                    <button type="button" 
                            onclick="evaluarSeccion('archivos', 'Aprobado')"
                            class="px-3 xs:px-4 sm:px-5 py-2 xs:py-2.5 sm:py-3 bg-green-600 text-white text-xs xs:text-sm sm:text-base font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500/50 transition-all duration-200 shadow-sm min-h-[44px]">
                        <svg class="w-3 h-3 xs:w-4 xs:h-4 sm:w-5 sm:h-5 mr-1 xs:mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="hidden xs:inline">Aprobar Sección</span>
                        <span class="xs:hidden">Aprobar</span>
                    </button>
                </div>
            </div>



            <!-- Botones de Decisión Final -->
            <div class="bg-white border border-gray-200 rounded-lg p-3 xs:p-4 sm:p-5 md:p-6 lg:p-8 mb-4 xs:mb-5 sm:mb-6 lg:mb-8">
                <div class="mb-3 xs:mb-4 sm:mb-5">
                    <h3 class="text-base xs:text-lg sm:text-xl md:text-2xl font-semibold text-gray-800 leading-tight">Decisión Final</h3>
                    <p class="text-xs xs:text-sm sm:text-base text-gray-600 mt-1 xs:mt-2">Tomar decisión final sobre el trámite</p>
                </div>
                
                <div class="flex flex-col xs:flex-row justify-end gap-2 xs:gap-3 sm:gap-4 lg:gap-6">
                    <!-- Botón Aprobar y Asignar Proveedor -->
                    <button type="button" 
                            onclick="aprobarYAsignarProveedor()"
                            class="inline-flex items-center justify-center px-3 xs:px-4 sm:px-6 md:px-8 py-2 xs:py-2.5 sm:py-3 md:py-4 bg-emerald-600 text-white text-xs xs:text-sm sm:text-base md:text-lg font-medium rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 transition-all duration-200 shadow-sm min-h-[44px]">
                        <svg class="w-3 h-3 xs:w-4 xs:h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 mr-1 xs:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="hidden xs:inline">Aprobar y Asignar Proveedor</span>
                        <span class="xs:hidden">Aprobar</span>
                    </button>
                    
                    <!-- Botón Rechazar -->
                    <button type="button" 
                            onclick="rechazarTramite()"
                            class="inline-flex items-center justify-center px-3 xs:px-4 sm:px-6 md:px-8 py-2 xs:py-2.5 sm:py-3 md:py-4 bg-red-600 text-white text-xs xs:text-sm sm:text-base md:text-lg font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/50 transition-all duration-200 shadow-sm min-h-[44px]">
                        <svg class="w-3 h-3 xs:w-4 xs:h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 mr-1 xs:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Rechazar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Navegación flotante simplificada -->
<div class="fixed bottom-4 xs:bottom-5 sm:bottom-6 md:bottom-8 right-4 xs:right-5 sm:right-6 md:right-8 space-y-2 z-40">
    <button type="button" onclick="scrollToTop()" 
            class="w-10 h-10 xs:w-11 xs:h-11 sm:w-12 sm:h-12 md:w-14 md:h-14 bg-orange-600 hover:bg-orange-700 text-white rounded-full shadow-lg flex items-center justify-center transition-colors min-h-[44px]">
        <i class="fas fa-arrow-up text-sm xs:text-base sm:text-lg md:text-xl"></i>
    </button>
</div>

<script>
// Variable global para el ID del trámite
const tramiteId = {{ $tramite->id }};

function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Evaluación de secciones (como en revisión digital)
function evaluarSeccion(seccion, decision) {
    const textarea = document.getElementById(`comentario_${seccion}`);
    const comentario = textarea ? textarea.value.trim() : '';
    
    // Verificar automáticamente el estado de los documentos
    const archivosEstados = document.querySelectorAll('[id^="estado_archivo_"]');
    let tieneRechazados = false;
    let tienePendientes = false;
    
    archivosEstados.forEach(estadoEl => {
        const estado = estadoEl.textContent.trim();
        if (estado === 'Rechazado') {
            tieneRechazados = true;
        } else if (estado === 'Pendiente') {
            tienePendientes = true;
        }
    });
    
    // Si hay documentos rechazados, automáticamente rechazar la sección
    if (tieneRechazados) {
        decision = 'Rechazado';
        mostrarNotificacion('La sección se ha marcado automáticamente como Rechazada porque hay documentos rechazados.', 'warning');
    }
    // Si hay documentos pendientes y se intenta aprobar, no permitir
    else if (tienePendientes && decision === 'Aprobado') {
        mostrarNotificacion('No se puede aprobar la sección porque hay documentos pendientes de evaluación.', 'error');
        return;
    }
    
    // Actualizar UI visualmente
    const sectionElement = document.querySelector(`[data-section="${seccion}"]`);
    if (sectionElement) {
        sectionElement.classList.remove('seccion-aprobada', 'seccion-rechazada');
        if (decision === 'Aprobado') {
            sectionElement.classList.add('seccion-aprobada');
        } else if (decision === 'Rechazado') {
            sectionElement.classList.add('seccion-rechazada');
        }
    }
    
    // Feedback visual
    mostrarNotificacion(`Sección ${seccion} evaluada como: ${decision}`, 
                       decision === 'Aprobado' ? 'success' : 'warning');
    
    // Guardar en localStorage para persistencia
    localStorage.setItem(`revision_presencial_${seccion}_decision`, decision);
    localStorage.setItem(`revision_presencial_${seccion}_comentario`, comentario);
}

// Evaluación de documentos presenciales (mantener compatibilidad)
function evaluarDocumentosPresencial(decision) {
    evaluarSeccion('archivos', decision);
}

function mostrarNotificacion(mensaje, tipo = 'info') {
    const div = document.createElement('div');
    div.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 text-white transition-opacity duration-300 ${
        tipo === 'success' ? 'bg-green-500' :
        tipo === 'warning' ? 'bg-yellow-500' :
        tipo === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    div.textContent = mensaje;
    
    document.body.appendChild(div);
    
    // Mostrar notificación
    setTimeout(() => div.classList.add('opacity-100'), 100);
    
    // Ocultar después de 3 segundos
    setTimeout(() => {
        div.classList.add('opacity-0');
        setTimeout(() => document.body.removeChild(div), 300);
    }, 3000);
}

// Validar que los documentos hayan sido evaluados antes de enviar
document.getElementById('formRevisionPresencial').addEventListener('submit', function(e) {
    const decisionDocumentos = document.getElementById('decision_documentos').value;
    
    if (decisionDocumentos === 'Pendiente') {
        e.preventDefault();
        mostrarNotificacion('Debe evaluar los documentos antes de finalizar la revisión presencial', 'warning');
        return false;
    }
});

// Funciones para decisiones finales
function aprobarYAsignarProveedor() {
    // Verificar que no haya archivos rechazados
    const archivosRechazados = document.querySelectorAll('[id^="estado_archivo_"]');
    let tieneRechazados = false;
    
    archivosRechazados.forEach(estadoEl => {
        if (estadoEl.textContent.trim() === 'Rechazado') {
            tieneRechazados = true;
        }
    });
    
    if (tieneRechazados) {
        mostrarNotificacion('No se puede aprobar el trámite porque hay documentos rechazados. Debe corregir todos los documentos antes de aprobar.', 'error');
        return;
    }
    
    // Mostrar modal de confirmación específico para aprobar
    const modalAprobar = document.getElementById('modal-confirmacion-aprobar');
    if (modalAprobar) {
        modalAprobar.classList.remove('hidden');
        
        // Configurar el botón de confirmar
        const confirmBtn = modalAprobar.querySelector('[data-behavior="commit"]');
        const cancelBtns = modalAprobar.querySelectorAll('[data-behavior="cancel"]');
        
        // Remover event listeners previos
        confirmBtn.replaceWith(confirmBtn.cloneNode(true));
        cancelBtns.forEach(btn => btn.replaceWith(btn.cloneNode(true)));
        
        // Obtener los nuevos elementos
        const newConfirmBtn = modalAprobar.querySelector('[data-behavior="commit"]');
        const newCancelBtns = modalAprobar.querySelectorAll('[data-behavior="cancel"]');
        
        // Agregar event listener para confirmar
        newConfirmBtn.addEventListener('click', function() {
            const comentarios = document.getElementById('comentario_archivos').value;
            
            fetch(`/revisiones/${tramiteId}/aprobar-asignar-proveedor`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    comentario_general: comentarios
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarNotificacion(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = '/revisiones';
                    }, 2000);
                } else {
                    mostrarNotificacion(data.message, 'error');
                }
            })
            .catch(error => {
                mostrarNotificacion('Error al procesar la solicitud', 'error');
            });
            
            modalAprobar.classList.add('hidden');
        });
        
        // Agregar event listeners para cancelar
        newCancelBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                modalAprobar.classList.add('hidden');
            });
        });
        
        // Cerrar modal al hacer clic fuera
        modalAprobar.addEventListener('click', function(e) {
            if (e.target === modalAprobar) {
                modalAprobar.classList.add('hidden');
            }
        });
        
        // Cerrar modal con Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modalAprobar.classList.contains('hidden')) {
                modalAprobar.classList.add('hidden');
            }
        });
    }
}

function rechazarTramite() {
    // Mostrar modal de confirmación específico para rechazar
    const modalRechazar = document.getElementById('modal-confirmacion-rechazar');
    if (modalRechazar) {
        modalRechazar.classList.remove('hidden');
        
        // Configurar el botón de confirmar
        const confirmBtn = modalRechazar.querySelector('[data-behavior="commit"]');
        const cancelBtns = modalRechazar.querySelectorAll('[data-behavior="cancel"]');
        
        // Remover event listeners previos
        confirmBtn.replaceWith(confirmBtn.cloneNode(true));
        cancelBtns.forEach(btn => btn.replaceWith(btn.cloneNode(true)));
        
        // Obtener los nuevos elementos
        const newConfirmBtn = modalRechazar.querySelector('[data-behavior="commit"]');
        const newCancelBtns = modalRechazar.querySelectorAll('[data-behavior="cancel"]');
        
        // Agregar event listener para confirmar
        newConfirmBtn.addEventListener('click', function() {
            const comentarios = document.getElementById('comentario_archivos').value;
            
            fetch(`/revisiones/${tramiteId}/rechazar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    comentario_general: comentarios
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarNotificacion(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = '/revisiones';
                    }, 2000);
                } else {
                    mostrarNotificacion(data.message, 'error');
                }
            })
            .catch(error => {
                mostrarNotificacion('Error al procesar la solicitud', 'error');
            });
            
            modalRechazar.classList.add('hidden');
        });
        
        // Agregar event listeners para cancelar
        newCancelBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                modalRechazar.classList.add('hidden');
            });
        });
        
        // Cerrar modal al hacer clic fuera
        modalRechazar.addEventListener('click', function(e) {
            if (e.target === modalRechazar) {
                modalRechazar.classList.add('hidden');
            }
        });
        
        // Cerrar modal con Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modalRechazar.classList.contains('hidden')) {
                modalRechazar.classList.add('hidden');
            }
        });
    }
}

// Cargar datos guardados al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    cargarDatosGuardados();
    
    // Configurar observador para cambios automáticos en estados de documentos
    configurarObservadorEstadosDocumentos();
});

// Configurar observador para detectar cambios en estados de documentos
function configurarObservadorEstadosDocumentos() {
    // Observar cambios en elementos que contengan estados de archivos
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' || mutation.type === 'characterData') {
                // Verificar si el cambio afecta a elementos de estado de archivos
                const target = mutation.target;
                if (target.id && target.id.startsWith('estado_archivo_')) {
                    // Pequeño delay para asegurar que el cambio se haya completado
                    setTimeout(() => {
                        actualizarEstadoSeccionAutomaticamente();
                    }, 100);
                }
            }
        });
    });
    
    // Observar todos los elementos de estado de archivos
    const elementosEstado = document.querySelectorAll('[id^="estado_archivo_"]');
    elementosEstado.forEach(elemento => {
        observer.observe(elemento, {
            childList: true,
            characterData: true,
            subtree: true
        });
    });
}

function cargarDatosGuardados() {
    const secciones = ['archivos'];
    
    secciones.forEach(seccion => {
        const decision = localStorage.getItem(`revision_presencial_${seccion}_decision`);
        const comentario = localStorage.getItem(`revision_presencial_${seccion}_comentario`);
        
        if (decision) {
            const textarea = document.getElementById(`comentario_${seccion}`);
            if (textarea && comentario) {
                textarea.value = comentario;
            }
            
            // Aplicar estado visual
            const sectionElement = document.querySelector(`[data-section="${seccion}"]`);
            if (sectionElement) {
                sectionElement.classList.remove('seccion-aprobada', 'seccion-rechazada');
                if (decision === 'Aprobado') {
                    sectionElement.classList.add('seccion-aprobada');
                } else if (decision === 'Rechazado') {
                    sectionElement.classList.add('seccion-rechazada');
                }
            }
        }
    });
}

function limpiarDatosGuardados() {
    const secciones = ['archivos'];
    secciones.forEach(seccion => {
        localStorage.removeItem(`revision_presencial_${seccion}_decision`);
        localStorage.removeItem(`revision_presencial_${seccion}_comentario`);
    });
}

// Función para verificar el estado de los archivos
function verificarEstadoArchivos() {
    const archivosEstados = document.querySelectorAll('[id^="estado_archivo_"]');
    let aprobados = 0;
    let rechazados = 0;
    let pendientes = 0;
    
    archivosEstados.forEach(estadoEl => {
        const estado = estadoEl.textContent.trim();
        switch (estado) {
            case 'Aprobado':
                aprobados++;
                break;
            case 'Rechazado':
                rechazados++;
                break;
            default:
                pendientes++;
                break;
        }
    });
    
    // Mostrar resumen en consola para debugging
    console.log(`Archivos: ${aprobados} aprobados, ${rechazados} rechazados, ${pendientes} pendientes`);
    
    return { aprobados, rechazados, pendientes };
}

// Función para verificar si se puede aprobar
function sePuedeAprobar() {
    const estado = verificarEstadoArchivos();
    return estado.rechazados === 0 && estado.pendientes === 0;
}

// Función para actualizar automáticamente el estado de la sección cuando cambian los documentos
function actualizarEstadoSeccionAutomaticamente() {
    const archivosEstados = document.querySelectorAll('[id^="estado_archivo_"]');
    let tieneRechazados = false;
    let tienePendientes = false;
    let todosAprobados = true;
    
    archivosEstados.forEach(estadoEl => {
        const estado = estadoEl.textContent.trim();
        if (estado === 'Rechazado') {
            tieneRechazados = true;
            todosAprobados = false;
        } else if (estado === 'Pendiente') {
            tienePendientes = true;
            todosAprobados = false;
        }
    });
    
    // Si hay documentos rechazados, automáticamente rechazar la sección
    if (tieneRechazados) {
        evaluarSeccion('archivos', 'Rechazado');
    }
    // Si todos están aprobados, automáticamente aprobar la sección
    else if (todosAprobados && archivosEstados.length > 0) {
        evaluarSeccion('archivos', 'Aprobado');
    }
    // Si hay pendientes, mantener estado neutral
    else if (tienePendientes) {
        const sectionElement = document.querySelector('[data-section="archivos"]');
        if (sectionElement) {
            sectionElement.classList.remove('seccion-aprobada', 'seccion-rechazada');
        }
        localStorage.removeItem('revision_presencial_archivos_decision');
    }
}

window.scrollToTop = scrollToTop;
window.evaluarSeccion = evaluarSeccion;
window.evaluarDocumentosPresencial = evaluarDocumentosPresencial;
window.aprobarYAsignarProveedor = aprobarYAsignarProveedor;
window.rechazarTramite = rechazarTramite;
window.cargarDatosGuardados = cargarDatosGuardados;
window.limpiarDatosGuardados = limpiarDatosGuardados;
window.verificarEstadoArchivos = verificarEstadoArchivos;
window.sePuedeAprobar = sePuedeAprobar;
window.actualizarEstadoSeccionAutomaticamente = actualizarEstadoSeccionAutomaticamente;
</script>

<!-- Modal de confirmación para Aprobar -->
<x-ui.modals.modal-confirmacion 
    id="modal-confirmacion-aprobar"
    title="Confirmar Aprobación"
    message="¿Está seguro que desea aprobar este trámite y asignar un proveedor? Esta acción no se puede deshacer."
    confirmText="Aprobar"
    cancelText="Cancelar"
    confirmClass="bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500"
    cancelClass="bg-white border-gray-300 text-gray-700 hover:text-gray-500 focus:ring-emerald-500"
/>

<!-- Modal de confirmación para Rechazar -->
<x-ui.modals.modal-confirmacion 
    id="modal-confirmacion-rechazar"
    title="Confirmar Rechazo"
    message="¿Está seguro que desea rechazar este trámite? Esta acción no se puede deshacer."
    confirmText="Rechazar"
    cancelText="Cancelar"
    confirmClass="bg-red-600 hover:bg-red-700 focus:ring-red-500"
    cancelClass="bg-white border-gray-300 text-gray-700 hover:text-gray-500 focus:ring-red-500"
/>

<!-- CSS para estados de sección -->
<style>
.seccion-aprobada {
    border-left: 4px solid #10b981;
    background-color: #f0fdf4;
}

.seccion-rechazada {
    border-left: 4px solid #ef4444;
    background-color: #fef2f2;
}

/* Responsive improvements for very small screens */
@media (max-width: 480px) {
    .seccion-aprobada,
    .seccion-rechazada {
        border-left-width: 3px;
    }
}

/* Ensure text doesn't overflow on small screens */
.break-words {
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.break-all {
    word-break: break-all;
}

/* Improve button touch targets on mobile */
@media (max-width: 640px) {
    button {
        min-height: 44px; /* Minimum touch target size */
    }
}

/* Custom breakpoint for extra small screens */
@media (min-width: 475px) {
    .xs\:p-3 { padding: 0.75rem; }
    .xs\:p-4 { padding: 1rem; }
    .xs\:p-5 { padding: 1.25rem; }
    .xs\:p-6 { padding: 1.5rem; }
    .xs\:p-8 { padding: 2rem; }
    .xs\:px-2 { padding-left: 0.5rem; padding-right: 0.5rem; }
    .xs\:px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
    .xs\:px-4 { padding-left: 1rem; padding-right: 1rem; }
    .xs\:px-5 { padding-left: 1.25rem; padding-right: 1.25rem; }
    .xs\:px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
    .xs\:py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
    .xs\:py-2\.5 { padding-top: 0.625rem; padding-bottom: 0.625rem; }
    .xs\:py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
    .xs\:py-4 { padding-top: 1rem; padding-bottom: 1rem; }
    .xs\:py-5 { padding-top: 1.25rem; padding-bottom: 1.25rem; }
    .xs\:py-6 { padding-top: 1.5rem; padding-bottom: 1.5rem; }
    .xs\:pb-3 { padding-bottom: 0.75rem; }
    .xs\:pb-4 { padding-bottom: 1rem; }
    .xs\:pb-5 { padding-bottom: 1.25rem; }
    .xs\:pb-6 { padding-bottom: 1.5rem; }
    .xs\:mb-4 { margin-bottom: 1rem; }
    .xs\:mb-5 { margin-bottom: 1.25rem; }
    .xs\:mt-1 { margin-top: 0.25rem; }
    .xs\:mt-2 { margin-top: 0.5rem; }
    .xs\:mt-3 { margin-top: 0.75rem; }
    .xs\:mt-4 { margin-top: 1rem; }
    .xs\:mt-5 { margin-top: 1.25rem; }
    .xs\:gap-2 { gap: 0.5rem; }
    .xs\:gap-3 { gap: 0.75rem; }
    .xs\:gap-4 { gap: 1rem; }
    .xs\:gap-5 { gap: 1.25rem; }
    .xs\:space-y-3 { margin-top: 0.75rem; }
    .xs\:space-y-4 { margin-top: 1rem; }
    .xs\:space-y-5 { margin-top: 1.25rem; }
    .xs\:space-y-6 { margin-top: 1.5rem; }
    .xs\:text-xs { font-size: 0.75rem; line-height: 1rem; }
    .xs\:text-sm { font-size: 0.875rem; line-height: 1.25rem; }
    .xs\:text-base { font-size: 1rem; line-height: 1.5rem; }
    .xs\:text-lg { font-size: 1.125rem; line-height: 1.75rem; }
    .xs\:text-xl { font-size: 1.25rem; line-height: 1.75rem; }
    .xs\:text-2xl { font-size: 1.5rem; line-height: 2rem; }
    .xs\:text-3xl { font-size: 1.875rem; line-height: 2.25rem; }
    .xs\:text-4xl { font-size: 2.25rem; line-height: 2.5rem; }
    .xs\:w-4 { width: 1rem; }
    .xs\:h-4 { height: 1rem; }
    .xs\:w-5 { width: 1.25rem; }
    .xs\:h-5 { height: 1.25rem; }
    .xs\:w-8 { width: 2rem; }
    .xs\:h-8 { height: 2rem; }
    .xs\:w-10 { width: 2.5rem; }
    .xs\:h-10 { height: 2.5rem; }
    .xs\:w-11 { width: 2.75rem; }
    .xs\:h-11 { height: 2.75rem; }
    .xs\:w-12 { width: 3rem; }
    .xs\:h-12 { height: 3rem; }
    .xs\:w-16 { width: 4rem; }
    .xs\:h-16 { height: 4rem; }
    .xs\:bottom-5 { bottom: 1.25rem; }
    .xs\:right-5 { right: 1.25rem; }
    .xs\:flex-row { flex-direction: row; }
    .xs\:items-center { align-items: center; }
    .xs\:justify-between { justify-content: space-between; }
    .xs\:justify-end { justify-content: flex-end; }
    .xs\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .xs\:inline { display: inline; }
    .xs\:hidden { display: none; }
    .xs\:text-center { text-align: center; }
    .xs\:text-right { text-align: right; }
    .xs\:flex-shrink-0 { flex-shrink: 0; }
    .xs\:min-w-0 { min-width: 0; }
    .xs\:flex-1 { flex: 1 1 0%; }
}

/* Additional responsive improvements */
@media (max-width: 374px) {
    .text-base { font-size: 0.875rem; }
    .text-lg { font-size: 1rem; }
    .text-xl { font-size: 1.125rem; }
    .p-3 { padding: 0.5rem; }
    .p-4 { padding: 0.75rem; }
    .px-3 { padding-left: 0.5rem; padding-right: 0.5rem; }
    .px-4 { padding-left: 0.75rem; padding-right: 0.75rem; }
    .py-2 { padding-top: 0.375rem; padding-bottom: 0.375rem; }
    .py-3 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
    .gap-3 { gap: 0.5rem; }
    .gap-4 { gap: 0.75rem; }
    .mb-4 { margin-bottom: 0.75rem; }
    .mb-6 { margin-bottom: 1rem; }
    .mt-3 { margin-top: 0.5rem; }
    .mt-4 { margin-top: 0.75rem; }
}

/* Landscape orientation adjustments for mobile */
@media (max-width: 768px) and (orientation: landscape) {
    .p-3 { padding: 0.5rem; }
    .p-4 { padding: 0.75rem; }
    .mb-4 { margin-bottom: 0.75rem; }
    .mb-6 { margin-bottom: 1rem; }
    .text-lg { font-size: 1rem; }
    .text-xl { font-size: 1.125rem; }
}

/* High DPI displays */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
    .border { border-width: 0.5px; }
    .border-l-4 { border-left-width: 2px; }
}
</style>
@endsection 