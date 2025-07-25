@extends('layouts.app')

@section('content')
<div class="min-h-screen py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-lg p-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Seleccionar Tipo de Revisión</h1>
                        <p class="text-sm text-gray-600">Trámite #{{ $tramite->id }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        {{ $tramite->estado === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                           ($tramite->estado === 'En_Revision' ? 'bg-blue-100 text-blue-800' : 
                           ($tramite->estado === 'Aprobado' ? 'bg-green-100 text-green-800' : 
                           ($tramite->estado === 'Por_Cotejar' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800'))) }}">
                        {{ str_replace('_', ' ', $tramite->estado) }}
                    </span>
                    <a href="{{ route('revision.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-lg hover:shadow-md transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m7 7l-7 7z" />
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <!-- Información del Trámite -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">
                        {{ $tramite->datosGenerales->razon_social ?? $tramite->proveedor->razon_social ?? 'Proveedor N/A' }}
                    </h2>
                    <p class="text-sm text-gray-600">
                        RFC: {{ $tramite->datosGenerales->rfc ?? $tramite->proveedor->rfc ?? 'N/A' }} • 
                        {{ $tramite->tipo_tramite === 'Inscripcion' ? 'Inscripción' : 
                           ($tramite->tipo_tramite === 'Renovacion' ? 'Renovación' : 'Actualización') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Opciones de Revisión -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Revisión Digital -->
            <div class="bg-white rounded-xl shadow-sm border-2 border-gray-200 hover:border-[#B4325E]/30 transition-all duration-300 p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Revisión Digital</h3>
                        <p class="text-sm text-gray-600">Revisa documentos y datos en línea</p>
                    </div>
                </div>
                
                <div class="space-y-2 mb-6">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Acceso completo a documentos</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Revisión detallada de datos</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Comentarios en tiempo real</span>
                    </div>
                </div>

                <a href="/revision/{{ $tramite->id }}/revisar-datos"
                    class="inline-flex items-center justify-center w-full px-4 py-3 text-sm font-semibold text-white bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-lg hover:shadow-md transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Iniciar Revisión Digital
                </a>
            </div>

            <!-- Revisión Presencial -->
            <div class="bg-white rounded-xl shadow-sm border-2 {{ $tramite->estado === 'Por_Cotejar' ? 'border-orange-300 bg-orange-50' : 'border-gray-200' }} hover:border-[#B4325E]/30 transition-all duration-300 p-6 relative">
                @if($tramite->estado === 'Por_Cotejar')
                    <div class="absolute -top-2 -right-2 bg-orange-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                        Requerido
                    </div>
                @endif
                
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Revisión Presencial</h3>
                        <p class="text-sm text-gray-600">Revisa documentos físicos</p>
                    </div>
                </div>
                
                <div class="space-y-2 mb-6">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Documentos físicos originales</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Verificación de autenticidad</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Entrevista si es necesario</span>
                    </div>
                </div>

                <!-- Documentos del Trámite -->
                @if($tramite->archivos->count() > 0)
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-sm font-semibold text-green-900">Documentos Disponibles</span>
                        </div>
                        
                        <div class="space-y-2">
                            @foreach($tramite->archivos->take(3) as $archivo)
                                <div class="flex items-center justify-between p-2 bg-white rounded border border-gray-200">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 bg-green-100 rounded flex items-center justify-center">
                                            <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-900">{{ $archivo->nombre_original ?? $archivo->catalogoArchivo->nombre ?? 'Documento' }}</span>
                                    </div>
                                    <a href="{{ $archivo->getUrlVisualizacionAttribute() }}" target="_blank"
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded hover:shadow-sm transition-all">
                                        <svg class="w-2 h-2 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Ver
                                    </a>
                                </div>
                            @endforeach
                            @if($tramite->archivos->count() > 3)
                                <div class="text-center py-1">
                                    <span class="text-xs text-gray-500">+{{ $tramite->archivos->count() - 3 }} más</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Botones de acción -->
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="confirmarIdentificacion({{ $tramite->id }})"
                        class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold text-white bg-gradient-to-r from-green-600 to-green-700 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500/50 focus:ring-offset-2">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="hidden sm:inline">Confirmar</span>
                        <span class="sm:hidden">✓</span>
                    </button>
                    
                    <button type="button" onclick="cancelarRevision({{ $tramite->id }})"
                        class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold text-white bg-gradient-to-r from-red-600 to-red-700 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:ring-offset-2">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span class="hidden sm:inline">Cancelar</span>
                        <span class="sm:hidden">✕</span>
                    </button>
                    
                    <button type="button" onclick="regendarCita({{ $tramite->id }})"
                        class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:ring-offset-2">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="hidden sm:inline">Regendar</span>
                        <span class="sm:hidden">📅</span>
                    </button>
                </div>
            </div>
        </div>


    </div>
</div>

<script>
    function confirmarIdentificacion(tramiteId) {
        alert('Función de confirmar identificación en desarrollo. Por favor, contacte al administrador del sistema.');
    }
    
    function cancelarRevision(tramiteId) {
        alert('Función de cancelar revisión en desarrollo. Por favor, contacte al administrador del sistema.');
    }
    
    function regendarCita(tramiteId) {
        alert('Función de regendar cita en desarrollo. Por favor, contacte al administrador del sistema.');
    }
</script>
@endsection 