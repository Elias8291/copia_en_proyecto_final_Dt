@props(['tramite', 'direccion' => null, 'editable' => false])

<div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8" {{ $attributes }}>
    <!-- Encabezado con icono -->
    <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-100">
        <div class="flex items-center space-x-4">
            <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <i class="fas fa-map-marker-alt text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Domicilio</h2>
                <p class="text-sm text-gray-500 mt-1">Dirección fiscal del proveedor</p>
            </div>
        </div>
        @if($editable)
            <div class="flex items-center space-x-2">
                <button type="button" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors">
                    <i class="fas fa-edit mr-1"></i>
                    Editar
                </button>
            </div>
        @endif
    </div>

    @if($direccion)
        <div class="space-y-8">
            <!-- Dirección Principal -->
            <div>
                <h4 class="text-base font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">
                    Dirección Principal
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Calle -->
                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                            Calle
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-road text-gray-500"></i>
                            </div>
                            <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                                {{ $direccion->calle ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <!-- Entre Calles -->
                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                            Entre Calles
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-arrows-alt-h text-gray-500"></i>
                            </div>
                            <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                                {{ $direccion->entre_calles ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <!-- Número Exterior -->
                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                            Número Exterior
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-hashtag text-gray-500"></i>
                            </div>
                            <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                                {{ $direccion->numero_exterior ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <!-- Número Interior -->
                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                            Número Interior
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-door-open text-gray-500"></i>
                            </div>
                            <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                                {{ $direccion->numero_interior ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ubicación -->
            <div>
                <h4 class="text-base font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">
                    Ubicación
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Código Postal -->
                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                            Código Postal
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-mail-bulk text-gray-500"></i>
                            </div>
                            <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm font-mono">
                                {{ $direccion->codigo_postal ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <!-- Colonia/Asentamiento -->
                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                            Colonia/Asentamiento
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-home text-gray-500"></i>
                            </div>
                            <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                                {{ $direccion->colonia_asentamiento ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <!-- Municipio -->
                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                            Municipio
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-city text-gray-500"></i>
                            </div>
                            <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                                {{ $direccion->municipio ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                            Estado
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-map text-gray-500"></i>
                            </div>
                            <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                                {{ $direccion->estado->nombre ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coordenadas (si existen) -->
            @if($direccion->coordenadas)
            <div>
                <h4 class="text-base font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">
                    Coordenadas Geográficas
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Latitud -->
                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                            Latitud
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-crosshairs text-gray-500"></i>
                            </div>
                            <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm font-mono">
                                {{ $direccion->coordenadas->latitud ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <!-- Longitud -->
                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                            Longitud
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-crosshairs text-gray-500"></i>
                            </div>
                            <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm font-mono">
                                {{ $direccion->coordenadas->longitud ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Dirección Completa -->
            <div>
                <h4 class="text-base font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">
                    Dirección Completa
                </h4>
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-map-marked-alt text-[#9d2449] text-lg mt-1"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-800 leading-relaxed">
                                {{ $direccion->calle ?? '' }}
                                @if($direccion->numero_exterior) #{{ $direccion->numero_exterior }} @endif
                                @if($direccion->numero_interior) Int. {{ $direccion->numero_interior }} @endif
                                @if($direccion->colonia_asentamiento), {{ $direccion->colonia_asentamiento }} @endif
                                @if($direccion->codigo_postal), C.P. {{ $direccion->codigo_postal }} @endif
                                @if($direccion->municipio), {{ $direccion->municipio }} @endif
                                @if($direccion->estado), {{ $direccion->estado->nombre ?? '' }} @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Estado sin dirección -->
        <div class="text-center py-12">
            <div class="flex flex-col items-center justify-center space-y-4">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-gray-400 text-2xl"></i>
                </div>
                <div class="text-gray-500">
                    <p class="font-medium text-sm">No hay información de domicilio</p>
                    <p class="text-xs mt-1">La dirección no ha sido registrada para este trámite.</p>
                </div>
            </div>
        </div>
    @endif
</div>