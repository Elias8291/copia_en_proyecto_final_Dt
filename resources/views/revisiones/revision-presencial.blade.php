@extends('layouts.app')

@section('title', 'Revisión Presencial')

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-7xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-orange-600 via-orange-700 to-orange-800 rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">
                            Revisión Presencial - Trámite #{{ $tramite->id }}
                        </h1>
                        <p class="text-base text-gray-500 mt-1">Revisión presencial de documentos y datos del trámite</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('revisiones.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Información del trámite y revisión -->
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <span class="text-sm font-medium text-orange-800">Tipo de Trámite:</span>
                        <p class="text-orange-900">{{ $tramite->tipo_tramite }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-orange-800">Estado:</span>
                        <p class="text-orange-900">{{ $tramite->status }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-orange-800">Fecha de Creación:</span>
                        <p class="text-orange-900">{{ $tramite->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-orange-800">Tipo de Revisión:</span>
                        <p class="text-orange-900">Presencial</p>
                    </div>
                </div>
            </div>

            @if($revision)
            <!-- Información de la revisión guardada -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-green-800">Revisión Guardada</h3>
                            <p class="text-sm text-green-700">
                                Presencial - Iniciada el {{ $revision->fecha_inicio->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ $revision->estado_label }}
                    </span>
                </div>
            </div>
            @else
            <!-- Revisión temporal (no guardada) -->
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-orange-800">Revisión Presencial en Progreso</h3>
                            <p class="text-sm text-orange-700">La revisión se guardará al finalizar el proceso</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                        En Progreso
                    </span>
                </div>
            </div>
            @endif

            <!-- Mensaje específico para revisión presencial -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-yellow-800">Cotejo Presencial de Documentos</h3>
                        <p class="text-sm text-yellow-700">Se requiere la presentación física de documentos originales para su verificación y cotejo con las copias digitales.</p>
                    </div>
                </div>
            </div>

            <!-- Archivos / Documentos -->
            <div class="mb-6" data-section="archivos">
                <div class="mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Documentos para Cotejo Presencial</h2>
                    <p class="text-sm text-gray-600 mt-1">Verificación presencial de documentos originales</p>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 min-h-[600px]">
                    @include('components.forms.archivos-dinamicos', [
                        'editable' => false, 
                        'archivosRequeridos' => [],
                        'tipoPersona' => $viewModel->isPersonaMoral() ? 'Moral' : 'Física',
                        'archivosCargados' => $viewModel->getArchivos()
                    ])
                </div>
                
                <!-- Área de Decisión por Sección -->
                <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-medium text-gray-700">Decisión - Documentos</h4>
                        <span class="text-xs text-gray-500">Cotejo Presencial</span>
                    </div>
                    
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-600 mb-2">Comentarios del cotejo presencial:</label>
                        <textarea 
                            id="textarea_documentos_presencial"
                            placeholder="Observaciones del cotejo presencial de documentos originales..."
                            class="w-full text-sm px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-orange-500 focus:border-orange-500"
                            rows="3"></textarea>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="button" onclick="evaluarDocumentosPresencial('Aprobado')" 
                                class="flex-1 bg-green-100 hover:bg-green-200 text-green-700 font-medium py-2 px-4 rounded-lg transition-colors text-sm flex items-center justify-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Documentos Conformes</span>
                        </button>
                        
                        <button type="button" onclick="evaluarDocumentosPresencial('Rechazado')" 
                                class="flex-1 bg-red-100 hover:bg-red-200 text-red-700 font-medium py-2 px-4 rounded-lg transition-colors text-sm flex items-center justify-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span>Documentos No Conformes</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Panel de decisión -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6 border-t-4 border-orange-500">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Decisión de Revisión Presencial</h3>
                
                <form id="formRevisionPresencial" action="{{ route('revisiones.procesar-presencial', $tramite->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="tipo_revision" value="Presencial">
                    <input type="hidden" name="decision_documentos" id="decision_documentos" value="Pendiente">
                    
                    <!-- Campo para comentarios del cotejo presencial -->
                    <div>
                        <label for="comentarios_presencial" class="block text-sm font-medium text-gray-700 mb-2">
                            Comentarios del Cotejo Presencial
                        </label>
                        <textarea 
                            id="comentarios_presencial" 
                            name="comentarios_presencial" 
                            rows="3" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600/20 focus:border-orange-600"
                            placeholder="Observaciones específicas del cotejo presencial de documentos originales..."
                        ></textarea>
                    </div>
                    
                    <div>
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                            Observaciones Generales (opcional)
                        </label>
                        <textarea 
                            id="observaciones" 
                            name="observaciones" 
                            rows="4" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600/20 focus:border-orange-600"
                            placeholder="Observaciones generales sobre la revisión presencial..."
                        ></textarea>
                    </div>

                    <x-revision.botones-decision-final 
                        :showAprobar="true"
                        :showAgendarCita="false"
                        :showCorrecciones="false"
                        :showRechazar="true"
                        layout="flex"
                    />
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Navegación flotante simplificada -->
<div class="fixed bottom-6 right-6 space-y-2 z-40">
    <button type="button" onclick="scrollToTop()" 
            class="w-12 h-12 bg-orange-600 hover:bg-orange-700 text-white rounded-full shadow-lg flex items-center justify-center transition-colors">
        <i class="fas fa-arrow-up"></i>
    </button>
</div>

<script>
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Evaluación de documentos presenciales
function evaluarDocumentosPresencial(decision) {
    const textarea = document.getElementById('textarea_documentos_presencial');
    const comentario = textarea ? textarea.value.trim() : '';
    
    // Actualizar campo oculto
    document.getElementById('decision_documentos').value = decision;
    
    // Actualizar el campo de comentarios presencial
    const comentariosPresencial = document.getElementById('comentarios_presencial');
    if (comentariosPresencial && comentario) {
        comentariosPresencial.value = comentario;
    }
    
    // Feedback visual
    mostrarNotificacion(`Documentos evaluados como: ${decision === 'Aprobado' ? 'Conformes' : 'No Conformes'}`, 
                       decision === 'Aprobado' ? 'success' : 'warning');
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

window.scrollToTop = scrollToTop;
window.evaluarDocumentosPresencial = evaluarDocumentosPresencial;
</script>

<!-- Modal de confirmación -->
<x-ui.modals.modal-confirmacion 
    id="modal-confirmacion-decision"
    title="Confirmar Decisión"
    message="¿Está seguro que desea realizar esta acción?"
    confirmText="Confirmar"
    cancelText="Cancelar"
    confirmClass="bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500"
    cancelClass="bg-white border-gray-300 text-gray-700 hover:text-gray-500 focus:ring-emerald-500"
/>
@endsection 