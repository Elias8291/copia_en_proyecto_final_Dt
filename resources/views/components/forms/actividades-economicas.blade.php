@props(['datos' => [], 'editable' => false])

<div class="space-y-6" {{ $attributes }}>
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Actividades Económicas
        </h4>
        
        @if(empty($datos))
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-industry text-gray-400 text-xl"></i>
                </div>
                <p class="text-gray-500 text-sm">No hay actividades económicas registradas</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($datos as $index => $actividad)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h5 class="text-sm font-medium text-gray-800">
                                Actividad #{{ $index + 1 }}
                            </h5>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
                            <div class="form-group field-container">
                                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                                    Código
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                                        <i class="fas fa-code text-gray-500 text-xs sm:text-sm"></i>
                                    </div>
                                    <input type="text" 
                                        value="{{ $actividad['codigo'] ?? '' }}"
                                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm opacity-75 cursor-not-allowed font-mono"
                                        disabled>
                                </div>
                            </div>

                            <div class="form-group field-container">
                                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                                    Descripción
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                                        <i class="fas fa-info-circle text-gray-500 text-xs sm:text-sm"></i>
                                    </div>
                                    <input type="text" 
                                        value="{{ $actividad['descripcion'] ?? '' }}"
                                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm opacity-75 cursor-not-allowed"
                                        disabled>
                                </div>
                            </div>

                            <div class="form-group field-container">
                                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                                    Porcentaje
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                                        <i class="fas fa-percentage text-gray-500 text-xs sm:text-sm"></i>
                                    </div>
                                    <input type="text" 
                                        value="{{ $actividad['porcentaje'] ?? '' }}"
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