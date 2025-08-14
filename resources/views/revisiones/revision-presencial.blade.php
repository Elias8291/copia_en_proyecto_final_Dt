@extends('layouts.app')

@section('title', 'Revisión Presencial')

<meta name="tramite-id" content="{{ $tramite->id }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="w-full mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        

        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-orange-600 via-orange-700 to-orange-800 rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Revisión Presencial - Trámite #{{ $tramite->id }}</h1>
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

        <div class="p-4 sm:p-6 lg:p-8">
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 sm:p-6 mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <span class="text-sm font-medium text-orange-800">Trámite #{{ $tramite->id }}</span>
                        <p class="text-orange-900 font-semibold">{{ ucfirst($tramite->tipo_tramite) }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-orange-800">RFC:</span>
                        <p class="text-orange-900 font-mono text-sm">{{ $tramite->proveedor->rfc ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-orange-800">Razón Social:</span>
                        <p class="text-orange-900 text-sm">{{ $viewModel->getDatosGenerales()['razon_social'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-orange-800">CURP:</span>
                        <p class="text-orange-900 text-sm font-mono">{{ $viewModel->getDatosGenerales()['curp'] ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="mb-6" data-section="archivos">
                <div class="mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Documentos para Cotejo Presencial</h2>
                </div>
                
                <div class="space-y-4 mb-6">
                    @foreach($archivosSubidos as $archivo)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow" data-archivo-id="{{ $archivo['id'] ?? 0 }}">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <div class="flex-shrink-0">
                                    @if(str_contains(strtolower($archivo['tipo_archivo'] ?? ''), 'pdf'))
                                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @elseif(str_contains(strtolower($archivo['tipo_archivo'] ?? ''), 'image'))
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $archivo['nombre_original'] ?? 'Sin nombre' }}</p>
                                    <p class="text-xs text-gray-500">{{ $archivo['tipo_archivo'] ?? 'Sin tipo' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <span id="estado_archivo_{{ $archivo['id'] ?? 0 }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    Pendiente
                                </span>
                                <a href="{{ route('revisiones.mostrar-archivo', $archivo['id'] ?? 0) }}" 
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Ver
                                </a>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <textarea 
                                id="textarea_archivo_{{ $archivo['id'] ?? 0 }}"
                                placeholder="Comentario sobre este archivo..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 resize-none text-sm"
                                rows="3"
                            ></textarea>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <button onclick="window.evaluarArchivo?.({{ $archivo['id'] ?? 0 }}, 'Aprobado')" 
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Aprobar
                            </button>
                            <button onclick="window.evaluarArchivo?.({{ $archivo['id'] ?? 0 }}, 'Rechazado')" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Rechazar
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6 mt-6">
                    <div class="mb-3">
                        <h4 class="text-sm font-medium text-gray-700">Comentarios - Archivos</h4>
                    </div>
                    <textarea 
                        id="comentario_archivos"
                        placeholder="Agregar observaciones sobre los archivos..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 resize-none text-sm"
                        rows="3"
                    ></textarea>
                </div>
                    
                <x-revision.botones-evaluacion 
                    seccion="archivos"
                    titulo="Archivos"
                    style="compact"
                    textoAprobar="Aprobar Sección"
                    textoRechazar="Rechazar Sección"
                />
            </div>

            <div class="mt-8">
                <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Comentarios Generales</h3>
                        <p class="text-sm text-gray-600">Observaciones generales sobre toda la revisión</p>
                    </div>
                    <textarea 
                        id="comentario_general"
                        placeholder="Agregar observaciones generales sobre el trámite..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 resize-none text-sm"
                        rows="4"
                    ></textarea>
                </div>
            </div>

            <div class="mt-8">
                <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Decisión Final</h3>
                        <p class="text-sm text-gray-600">Tomar decisión final sobre el trámite</p>
                    </div>
                    
                    <x-revision.botones-decision-final 
                        :tramiteId="$tramite->id"
                        :showAprobar="true"
                        :showCorrecciones="false"
                        :showRechazar="true"
                        textoAprobar="Aprobar Trámite"
                        textoRechazar="Rechazar Trámite"
                        layout="flex"
                    />
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.seccion-aprobada {
    border-left: 4px solid #10b981;
    background-color: #f0fdf4;
}

.seccion-rechazada {
    border-left: 4px solid #ef4444;
    background-color: #fef2f2;
}

.seccion-pendiente {
    border-left: 4px solid #eab308;
    background-color: #fefce8;
}
</style>

<script src="{{ asset('js/revision/evaluacion-secciones.js') }}"></script>
<script src="{{ asset('js/revision/archivos-tiempo-real.js') }}"></script>
<script src="{{ asset('js/revision/cargar-estados.js') }}"></script>

<script>
function mostrarNotificacion(mensaje, tipo = 'info') {     
    let mensajeCorto = mensaje;
    if (mensaje.length > 50) {
        mensajeCorto = mensaje.substring(0, 47) + '...';
    }
    
    const div = document.createElement('div');
    div.className = `fixed top-4 right-4 p-3 rounded-lg shadow-lg z-50 text-white transition-all duration-300 max-w-xs text-sm opacity-0 ${
        tipo === 'success' ? 'bg-green-500' :
        tipo === 'warning' ? 'bg-yellow-500' :
        tipo === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    div.textContent = mensajeCorto;
    
    document.body.appendChild(div);
    
    setTimeout(() => div.classList.remove('opacity-0'), 100);
 
    setTimeout(() => {
        div.classList.add('opacity-0');
        setTimeout(() => document.body.removeChild(div), 300);
    }, 2500);
}

window.mostrarNotificacion = mostrarNotificacion;

document.addEventListener('DOMContentLoaded', function() {
    const tramiteId = document.querySelector('meta[name="tramite-id"]')?.getAttribute('content');
    if (tramiteId && window.RevisionDigitalEstados) {
        window.revisionDigitalEstados = new RevisionDigitalEstados(tramiteId);
    }
});
</script>

<x-ui.modals.modal-confirmacion 
    id="modal-confirmacion-decision"
    title="Confirmar acción"
    message="¿Está seguro que desea realizar esta acción?"
    confirmText="Confirmar"
    cancelText="Cancelar"
    confirmClass="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500"
    cancelClass="bg-white border-gray-300 text-gray-700 hover:text-gray-500 focus:ring-blue-500"
/>

@endsection