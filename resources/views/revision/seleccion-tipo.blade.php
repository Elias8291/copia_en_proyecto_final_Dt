@extends('layouts.app')

@section('content')
    <div class="min-h-screen ">
        <div class="w-full max-w-full mx-auto px-3 py-2 sm:max-w-7xl sm:px-4 sm:py-4 lg:px-6 lg:py-6">
            <!-- Header Section -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200/70">
                <!-- Encabezado -->
                <div class="p-4 sm:p-6 border-b border-gray-200/70">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                        <div class="flex items-center space-x-3 sm:space-x-4">
                            <div class="bg-gradient-to-br from-[#B4325E] via-[#93264B] to-[#7a1d37] rounded-xl p-2.5 sm:p-3 shadow-md">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-lg sm:text-2xl font-bold text-gray-800">Seleccionar Tipo de Revisión</h1>
                                <p class="text-xs sm:text-sm text-gray-500">Elige el método de revisión para el trámite #{{ $tramite->id }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            <span class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-1.5 rounded-full text-xs sm:text-sm font-medium shadow-sm border
                                {{ $tramite->estado === 'Pendiente'
                                    ? 'bg-yellow-100 text-yellow-800 border-yellow-200'
                                    : ($tramite->estado === 'En_Revision'
                                        ? 'bg-blue-100 text-blue-800 border-blue-200'
                                        : ($tramite->estado === 'Aprobado'
                                            ? 'bg-green-100 text-green-800 border-green-200'
                                            : 'bg-red-100 text-red-800 border-red-200')) }}">
                                {{ str_replace('_', ' ', $tramite->estado) }}
                            </span>
                            <a href="{{ route('revision.index') }}"
                                class="inline-flex items-center justify-center px-3 py-2 sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:ring-offset-2">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m7 7l-7 7z" />
                                </svg>
                                <span class="hidden sm:inline">Volver</span>
                                <span class="sm:hidden">←</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Trámite -->
            <div class="mt-4 sm:mt-6 bg-white rounded-2xl shadow-lg border border-gray-200/70 p-4 sm:p-6">
                <div class="flex items-center space-x-3 sm:space-x-4 mb-4 sm:mb-6">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="text-base sm:text-xl font-bold text-gray-900 truncate">
                            {{ $tramite->datosGenerales->razon_social ?? $tramite->proveedor->razon_social ?? 'Proveedor N/A' }}
                        </h2>
                        <p class="text-xs sm:text-sm text-gray-600 truncate">
                            RFC: {{ $tramite->datosGenerales->rfc ?? $tramite->proveedor->rfc ?? 'N/A' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Trámite #{{ $tramite->id }} • {{ $tramite->tipo_tramite === 'Inscripcion' ? 'Inscripción' : 
                               ($tramite->tipo_tramite === 'Renovacion' ? 'Renovación' : 'Actualización') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Opciones de Revisión -->
            <div class="mt-4 sm:mt-6 grid grid-cols-1 gap-4 sm:gap-6 lg:grid-cols-2">
                <!-- Revisión Digital -->
                <div class="group relative bg-white rounded-2xl shadow-lg border-2 border-gray-200 hover:border-[#B4325E]/30 transition-all duration-300 transform hover:scale-105 hover:shadow-xl overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#B4325E]/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative p-4 sm:p-6">
                        <div class="flex items-center space-x-3 sm:space-x-4 mb-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-[#B4325E] to-[#7a1d37] rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900">Revisión Digital</h3>
                                <p class="text-xs sm:text-sm text-gray-600">Revisa los documentos y datos en línea</p>
                            </div>
                        </div>
                        
                        <div class="space-y-2 sm:space-y-3 mb-4 sm:mb-6">
                            <div class="flex items-start space-x-2 text-xs sm:text-sm text-gray-600">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Acceso completo a todos los documentos</span>
                            </div>
                            <div class="flex items-start space-x-2 text-xs sm:text-sm text-gray-600">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Revisión detallada de datos y formularios</span>
                            </div>
                            <div class="flex items-start space-x-2 text-xs sm:text-sm text-gray-600">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Comentarios y observaciones en tiempo real</span>
                            </div>
                            <div class="flex items-start space-x-2 text-xs sm:text-sm text-gray-600">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Proceso completo de aprobación/rechazo</span>
                            </div>
                        </div>

                        <a href="/revision/{{ $tramite->id }}/revisar-datos"
                            class="inline-flex items-center justify-center w-full px-4 py-3 sm:px-6 sm:py-3 text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-xl shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:ring-offset-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="hidden sm:inline">Iniciar Revisión Digital</span>
                            <span class="sm:hidden">Revisión Digital</span>
                        </a>
                    </div>
                </div>

                <!-- Revisión Presencial -->
                <div class="group relative bg-white rounded-2xl shadow-lg border-2 border-gray-200 hover:border-[#B4325E]/30 transition-all duration-300 transform hover:scale-105 hover:shadow-xl overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#B4325E]/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative p-4 sm:p-6">
                        <div class="flex items-center space-x-3 sm:space-x-4 mb-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-[#B4325E] to-[#7a1d37] rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900">Revisión Presencial</h3>
                                <p class="text-xs sm:text-sm text-gray-600">Revisa documentos físicos en oficina</p>
                            </div>
                        </div>
                        
                        <div class="space-y-2 sm:space-y-3 mb-4 sm:mb-6">
                            <div class="flex items-start space-x-2 text-xs sm:text-sm text-gray-600">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Revisión de documentos físicos originales</span>
                            </div>
                            <div class="flex items-start space-x-2 text-xs sm:text-sm text-gray-600">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Verificación de autenticidad de documentos</span>
                            </div>
                            <div class="flex items-start space-x-2 text-xs sm:text-sm text-gray-600">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Entrevista presencial si es necesario</span>
                            </div>
                            <div class="flex items-start space-x-2 text-xs sm:text-sm text-gray-600">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Registro de observaciones en el sistema</span>
                            </div>
                        </div>

                        <!-- Documentos del Trámite (solo para Revisión Presencial) -->
                        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center space-x-2 mb-2">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-sm font-semibold text-green-900">Documentos del Trámite</span>
                            </div>
                            
                            <!-- Información compacta del solicitante -->
                            <div class="mb-3 p-2 bg-blue-50 rounded border border-blue-200">
                                <div class="flex items-center justify-between text-xs text-blue-800">
                                    <span><strong>{{ $tramite->datosGenerales->razon_social ?? $tramite->proveedor->razon_social ?? 'N/A' }}</strong></span>
                                    <span>{{ $tramite->datosGenerales->rfc ?? $tramite->proveedor->rfc ?? 'N/A' }}</span>
                                    <span class="text-blue-600">{{ $tramite->proveedor->tipo_persona ?? 'Física' }}</span>
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                @if($tramite->archivos->count() > 0)
                                    @foreach($tramite->archivos as $archivo)
                                        <div class="flex items-center justify-between p-2 bg-white rounded border border-gray-200">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-6 h-6 bg-green-100 rounded flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-medium text-gray-900">{{ $archivo->nombre_original ?? $archivo->catalogoArchivo->nombre ?? 'Documento' }}</p>
                                                    <p class="text-xs text-gray-500">{{ strtoupper($archivo->catalogoArchivo->tipo_archivo ?? 'PDF') }}</p>
                                                </div>
                                            </div>
                                            <a href="{{ $archivo->getUrlVisualizacionAttribute() }}" target="_blank"
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded hover:from-[#9D2449] hover:to-[#7A1D3A] focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:ring-offset-2 transition-all duration-200">
                                                <svg class="w-2 h-2 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Ver
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-2">
                                        <p class="text-xs text-gray-500">No hay documentos disponibles</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-3">
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

            <!-- Información Adicional -->
            <div class="mt-6 sm:mt-8 bg-blue-50 rounded-2xl border border-blue-200 p-4 sm:p-6">
                <div class="flex items-start space-x-3">
                    <div class="w-5 h-5 sm:w-6 sm:h-6 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs sm:text-sm font-semibold text-blue-900 mb-2">Información Importante</h4>
                        <ul class="text-xs sm:text-sm text-blue-800 space-y-1">
                            <li class="flex items-start space-x-2">
                                <span class="text-blue-600 font-medium">•</span>
                                <span><strong>Revisión Digital:</strong> Permite revisar todos los documentos y datos del trámite de forma completa en línea.</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="text-blue-600 font-medium">•</span>
                                <span><strong>Revisión Presencial:</strong> Requiere que los documentos físicos estén disponibles en la oficina para su verificación.</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="text-blue-600 font-medium">•</span>
                                <span>Ambas opciones permiten registrar comentarios y tomar decisiones de aprobación/rechazo.</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="text-blue-600 font-medium">•</span>
                                <span>El sistema mantendrá un registro completo de todas las acciones realizadas.</span>
                            </li>
                        </ul>
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