@extends('layouts.app')

@section('content')
@include('components.alert')
@include('components.modal-exito')
@include('components.modal-confirmacion')
@include('components.loading-modal')
<div class="min-h-screen py-4 sm:py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="bg-gradient-to-br from-[#B4325E] via-[#93264B] to-[#7a1d37] rounded-xl p-3 shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Revisión Presencial de Documentos</h1>
                        <p class="text-sm text-gray-500">Trámite #{{ $tramite->id }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    <span class="px-2 sm:px-3 py-1 rounded-full text-xs font-medium
                        {{ $tramite->estado === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                           ($tramite->estado === 'En_Revision' ? 'bg-blue-100 text-blue-800' : 
                           ($tramite->estado === 'Aprobado' ? 'bg-green-100 text-green-800' : 
                           ($tramite->estado === 'Por_Cotejar' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800'))) }}">
                        {{ str_replace('_', ' ', $tramite->estado) }}
                    </span>
                    <a href="{{ route('revision.revisar', [$tramite->id, 'seleccion-tipo']) }}" class="inline-flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-lg hover:shadow-md transition-all">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m7 7l-7 7z" />
                        </svg>
                        <span class="hidden sm:inline">Volver</span>
                        <span class="sm:hidden">←</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Información del Trámite -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h2 class="text-base sm:text-lg font-bold text-gray-900 truncate">
                        {{ $tramite->datosGenerales->razon_social ?? $tramite->proveedor->razon_social ?? 'Proveedor N/A' }}
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-600">
                        RFC: {{ $tramite->datosGenerales->rfc ?? $tramite->proveedor->rfc ?? 'N/A' }} • 
                        {{ $tramite->tipo_tramite === 'Inscripcion' ? 'Inscripción' : 
                           ($tramite->tipo_tramite === 'Renovacion' ? 'Renovación' : 'Actualización') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Documentos -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center gap-2 sm:gap-3 mb-4 sm:mb-6">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-gray-900">Documentos del Trámite</h3>
                    <p class="text-xs sm:text-sm text-gray-600">Revisa, comenta y aprueba/rechaza los documentos físicos</p>
                </div>
            </div>

            @php
                $documentos = $tramite->archivos->map(function($archivo) {
                    return [
                        'id' => $archivo->id,
                        'nombre_original' => $archivo->nombre_original,
                        'nombre' => $archivo->catalogoArchivo->nombre ?? 'Documento',
                        'ruta_archivo' => $archivo->ruta_archivo,
                        'tamaño' => $archivo->tamaño ?? 'N/A',
                        'tamaño_formateado' => $archivo->tamaño_formateado ?? 'N/A',
                        'fecha_carga' => $archivo->created_at ? $archivo->created_at->format('d/m/Y H:i') : 'N/A',
                        'created_at' => $archivo->created_at ? $archivo->created_at->format('d/m/Y H:i') : 'N/A',
                        'aprobado' => $archivo->aprobado ?? null,
                        'observaciones' => $archivo->observaciones ?? null,
                        'fecha_cotejo' => $archivo->fecha_cotejo ?? null,
                        'catalogo' => [
                            'nombre' => $archivo->catalogoArchivo->nombre ?? 'Documento'
                        ]
                    ];
                })->toArray();
            @endphp

            @include('revision.partials.documentos', [
                'tramite' => $tramite,
                'documentos' => $documentos,
                'editable' => true
            ])
        </div>

        <!-- Comentario General -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-8 h-8 bg-white border border-gray-300 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Comentario General</h3>
                    <p class="text-sm text-gray-500">Observaciones generales del trámite</p>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200">
                <div class="p-4">
                    <div class="space-y-2">
                        <label for="comentario_general" class="block text-sm font-medium text-gray-700">
                            Comentario General del Trámite
                        </label>
                        <div class="relative">
                            <textarea id="comentario_general" name="comentario" rows="4"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md text-sm placeholder-gray-400 focus:ring-1 focus:ring-[#9D2449] focus:border-[#9D2449] focus:outline-none resize-none"
                                placeholder="Escriba un comentario general sobre el trámite...">{{ old('comentario', $tramite->comentarios_revision ?? 'Observación de revisión presencial: ') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 mt-6 sm:mt-8">
            <form id="form-aprobar-tramite" action="{{ route('api.revision.aprobar', $tramite->id) }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="comentario_general" id="comentario_general_aprobar">
                <button type="button" onclick="confirmarAprobacion()"
                    class="inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-emerald-600 to-green-600 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="hidden sm:inline">Aprobar Trámite</span>
                    <span class="sm:hidden">Aprobar</span>
                </button>
            </form>
            
            <form id="form-cancelar-tramite" action="{{ route('revision.cambiar-estado', $tramite) }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="nuevo_estado" value="Cancelado">
                <input type="hidden" name="observaciones" id="observaciones_cancelar">
                <input type="hidden" name="comentario_general" id="comentario_general_cancelar">
                <button type="button" onclick="confirmarCancelacion()"
                    class="inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-red-700 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span class="hidden sm:inline">Cancelar Trámite</span>
                    <span class="sm:hidden">Cancelar</span>
                </button>
            </form>
            
            <form id="form-regendar-cita" action="{{ route('api.revision.agendar-cita', $tramite->id) }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="comentario_general" id="comentario_general_regendar">
                <button type="button" onclick="confirmarRegendarCita()"
                    class="inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="hidden sm:inline">Agendar Cita Automática</span>
                    <span class="sm:hidden">Agendar</span>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Scripts necesarios para la funcionalidad de documentos -->
<script src="{{ asset('js/revision/documentos.js') }}"></script>

<script>
    // Variables globales necesarias para los scripts
    window.tramiteId = {{ $tramite->id }};
    window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    window.revisionSeccionComentarioRoute = '/revision/seccion/comentario';

    function confirmarAprobacion() {
        // Capturar comentario general
        const comentarioGeneral = document.getElementById('comentario_general').value;
        document.getElementById('comentario_general_aprobar').value = comentarioGeneral;
        
        showConfirmModal(
            'Confirmar Aprobación',
            '¿Está seguro que desea aprobar este trámite? Esta acción no se puede deshacer.',
            null,
            function() {
                // Mostrar modal de carga cuando se confirma (sin progreso)
                showLoading(
                    'Aprobando Trámite',
                    'Procesando la aprobación del trámite. Por favor espere...',
                    0
                );
                
                // Enviar el formulario después de un pequeño delay para que se vea el modal
                setTimeout(() => {
                    document.getElementById('form-aprobar-tramite').submit();
                }, 100);
            }
        );
    }

    function confirmarCancelacion() {
        // Capturar comentario general
        const comentarioGeneral = document.getElementById('comentario_general').value;
        document.getElementById('comentario_general_cancelar').value = comentarioGeneral;
        
        showConfirmModal(
            'Confirmar Cancelación',
            '¿Está seguro que desea cancelar este trámite? Esta acción no se puede deshacer.',
            null,
            function() {
                // Mostrar modal de carga cuando se confirma (sin progreso)
                showLoading(
                    'Cancelando Trámite',
                    'Procesando la cancelación del trámite. Por favor espere...',
                    0
                );
                
                // Enviar el formulario después de un pequeño delay para que se vea el modal
                setTimeout(() => {
                    document.getElementById('form-cancelar-tramite').submit();
                }, 100);
            }
        );
    }

    function confirmarRegendarCita() {
        // Capturar comentario general
        const comentarioGeneral = document.getElementById('comentario_general').value;
        document.getElementById('comentario_general_regendar').value = comentarioGeneral;
        
        showConfirmModal(
            'Confirmar Agenda de Cita Automática',
            '¿Está seguro que desea agendar una cita automática para este trámite? Se asignará el próximo horario disponible.',
            null,
            function() {
                // Mostrar modal de carga cuando se confirma
                showLoading(
                    'Agendando Cita Automática',
                    'Procesando la agenda de cita automática. Por favor espere...',
                    0
                );
                
                // Enviar el formulario después de un pequeño delay
                setTimeout(() => {
                    document.getElementById('form-regendar-cita').submit();
                }, 100);
            }
        );
    }



    // Función para mostrar notificaciones
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Animar entrada
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Animar salida
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Inicializar funcionalidades cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        // Cargar estados y comentarios existentes de documentos
        const documentos = document.querySelectorAll('[data-documento-id]');
        documentos.forEach(documento => {
            const documentoId = documento.getAttribute('data-documento-id');
            
            // Cargar estado y comentario existente
            fetch(`/revision/documento/${documentoId}/estado`)
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.data) {
                        // Actualizar estado visual en el partial de documentos
                        const estadoSpan = documento.querySelector('.estado-documento');
                        if (estadoSpan) {
                            if (data.data.aprobado === true) {
                                estadoSpan.className = 'estado-documento inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700 sm:px-2';
                                estadoSpan.innerHTML = '<svg class="w-2.5 h-2.5 mr-0.5 sm:w-3 sm:h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="hidden sm:inline">Aprobado</span><span class="sm:hidden">OK</span>';
                            } else if (data.data.aprobado === false) {
                                estadoSpan.className = 'estado-documento inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700 sm:px-2';
                                estadoSpan.innerHTML = '<svg class="w-2.5 h-2.5 mr-0.5 sm:w-3 sm:h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg><span class="hidden sm:inline">Rechazado</span><span class="sm:hidden">X</span>';
                            }
                        }
                        
                        // Actualizar comentario si existe
                        if (data.data.observaciones) {
                            const comentarioBox = documento.querySelector('.comentario-box');
                            const comentarioTexto = documento.querySelector('.comentario-texto');
                            if (comentarioBox && comentarioTexto) {
                                comentarioTexto.textContent = data.data.observaciones;
                                comentarioBox.style.display = 'block';
                            }
                            
                            // También actualizar el textarea si existe
                            const textarea = documento.querySelector('textarea[name="comentario"]');
                            if (textarea) {
                                textarea.value = data.data.observaciones;
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error cargando estado del documento:', error);
                });
        });
    });
</script>
@endsection