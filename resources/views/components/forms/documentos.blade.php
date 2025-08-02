@props(['datos' => [], 'editable' => false])

<div class="space-y-6" {{ $attributes }}>
    <div>
        <div class="flex items-center space-x-3 mb-6">
            <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Documentos</h3>
                <p class="text-sm text-gray-500">Documentos requeridos</p>
            </div>
        </div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Documentos Requeridos
        </h4>
        
        @if(empty($datos))
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-file-alt text-gray-400 text-xl"></i>
                </div>
                <p class="text-gray-500 text-sm">No hay documentos registrados</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($datos as $index => $documento)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h5 class="text-sm font-medium text-gray-800">
                                Documento #{{ $index + 1 }}
                            </h5>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $documento['estado'] === 'Aprobado' ? 'bg-green-100 text-green-700 border-green-200' : 
                                   ($documento['estado'] === 'Rechazado' ? 'bg-red-100 text-red-700 border-red-200' : 
                                    'bg-yellow-100 text-yellow-700 border-yellow-200') }} border">
                                {{ $documento['estado'] ?? 'Pendiente' }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
                            <div class="form-group field-container">
                                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                                    Tipo de Documento
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                                        <i class="fas fa-file text-gray-500 text-xs sm:text-sm"></i>
                                    </div>
                                    <input type="text" 
                                        value="{{ $documento['tipo_documento'] ?? '' }}"
                                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm opacity-75 cursor-not-allowed"
                                        disabled>
                                </div>
                            </div>

                            <div class="form-group field-container">
                                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                                    Nombre del Archivo
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                                        <i class="fas fa-file-upload text-gray-500 text-xs sm:text-sm"></i>
                                    </div>
                                    <input type="text" 
                                        value="{{ $documento['nombre_archivo'] ?? '' }}"
                                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm opacity-75 cursor-not-allowed"
                                        disabled>
                                </div>
                            </div>

                            <div class="form-group field-container">
                                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                                    Tamaño del Archivo
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                                        <i class="fas fa-weight text-gray-500 text-xs sm:text-sm"></i>
                                    </div>
                                    <input type="text" 
                                        value="{{ $documento['tamaño'] ?? '' }}"
                                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm opacity-75 cursor-not-allowed"
                                        disabled>
                                </div>
                            </div>

                            <div class="form-group field-container">
                                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                                    Fecha de Carga
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                                        <i class="fas fa-calendar text-gray-500 text-xs sm:text-sm"></i>
                                    </div>
                                    <input type="text" 
                                        value="{{ $documento['fecha_carga'] ?? '' }}"
                                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm opacity-75 cursor-not-allowed"
                                        disabled>
                                </div>
                            </div>

                            @if(!empty($documento['comentarios']))
                                <div class="form-group field-container sm:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                                        Comentarios
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                                            <i class="fas fa-comment text-gray-500 text-xs sm:text-sm"></i>
                                        </div>
                                        <textarea 
                                            class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm opacity-75 cursor-not-allowed resize-none"
                                            rows="3"
                                            disabled>{{ $documento['comentarios'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div> 