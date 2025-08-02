@props(['datos' => [], 'editable' => false])

<div class="space-y-6" {{ $attributes }}>
    <div>
        <div class="flex items-center space-x-3 mb-6">
            <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Accionistas</h3>
                <p class="text-sm text-gray-500">Información de socios y accionistas</p>
            </div>
        </div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Información de Accionistas
        </h4>
        
        @if(empty($datos))
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-gray-400 text-xl"></i>
                </div>
                <p class="text-gray-500 text-sm">No hay accionistas registrados</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($datos as $index => $accionista)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h5 class="text-sm font-medium text-gray-800">
                                Accionista #{{ $index + 1 }}
                            </h5>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
                            <div class="form-group field-container">
                                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                                    Nombre Completo
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                                        <i class="fas fa-user text-gray-500 text-xs sm:text-sm"></i>
                                    </div>
                                    <input type="text" 
                                        value="{{ $accionista['nombre_completo'] ?? '' }}"
                                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm opacity-75 cursor-not-allowed"
                                        disabled>
                                </div>
                            </div>

                            <div class="form-group field-container">
                                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                                    RFC
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                                        <i class="fas fa-id-card text-gray-500 text-xs sm:text-sm"></i>
                                    </div>
                                    <input type="text" 
                                        value="{{ $accionista['rfc'] ?? '' }}"
                                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm opacity-75 cursor-not-allowed font-mono"
                                        disabled>
                                </div>
                            </div>

                            <div class="form-group field-container">
                                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                                    CURP
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                                        <i class="fas fa-id-badge text-gray-500 text-xs sm:text-sm"></i>
                                    </div>
                                    <input type="text" 
                                        value="{{ $accionista['curp'] ?? '' }}"
                                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm opacity-75 cursor-not-allowed font-mono"
                                        disabled>
                                </div>
                            </div>

                            <div class="form-group field-container">
                                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                                    Porcentaje de Participación
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                                        <i class="fas fa-percentage text-gray-500 text-xs sm:text-sm"></i>
                                    </div>
                                    <input type="text" 
                                        value="{{ $accionista['porcentaje_participacion'] ?? '' }}"
                                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm opacity-75 cursor-not-allowed"
                                        disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div> 