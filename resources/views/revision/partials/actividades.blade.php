@props(['tramite', 'actividades' => [], 'editable' => false])

    @if(count($actividades) > 0)
        <div class="space-y-4">
            @foreach($actividades as $index => $actividad)
                <div class="bg-gradient-to-r from-gray-50 to-gray-100/50 rounded-xl border border-gray-200 p-6 hover:shadow-md transition-all duration-200">
                    <div class="flex items-start justify-between">
                        <!-- Información de la actividad -->
                        <div class="flex items-start space-x-4 flex-1">
                            <!-- Icono y número -->
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center shadow-sm border border-gray-200">
                                    @if(isset($actividad['es_principal']) && $actividad['es_principal'])
                                        <i class="fas fa-star text-yellow-500 text-lg"></i>
                                    @else
                                        <span class="text-sm font-bold text-gray-600">#{{ $index + 1 }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Detalles de la actividad -->
                            <div class="flex-1 min-w-0">
                                <!-- Nombre de la actividad -->
                                <div class="mb-3">
                                    <h4 class="text-sm font-semibold text-gray-900">
                                        {{ $actividad['descripcion'] ?? $actividad['nombre'] ?? 'Actividad sin nombre' }}
                                    </h4>
                                </div>

                                <!-- Sector y Estado de validación -->
                                <div class="flex items-center space-x-2">
                                    @if(isset($actividad['sector']) || isset($actividad['categoria']))
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-indigo-100 text-indigo-800">
                                            <i class="fas fa-tag mr-1"></i>
                                            @php
                                                $sector = $actividad['sector'] ?? $actividad['categoria'] ?? 'Sin categoría';
                                                // Si es un objeto o array, extraer solo el nombre
                                                if (is_array($sector) && isset($sector['nombre'])) {
                                                    $sector = $sector['nombre'];
                                                } elseif (is_object($sector) && isset($sector->nombre)) {
                                                    $sector = $sector->nombre;
                                                }
                                            @endphp
                                            {{ $sector }}
                                        </span>
                                    @endif

                                    @if(isset($actividad['estado_validacion']))
                                        @php
                                            $estadoClass = match($actividad['estado_validacion']) {
                                                'Validada' => 'bg-green-100 text-green-800',
                                                'Rechazada' => 'bg-red-100 text-red-800',
                                                default => 'bg-yellow-100 text-yellow-800',
                                            };
                                            $estadoIcon = match($actividad['estado_validacion']) {
                                                'Validada' => 'fas fa-check-circle',
                                                'Rechazada' => 'fas fa-times-circle',
                                                default => 'fas fa-clock',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $estadoClass }}">
                                            <i class="{{ $estadoIcon }} mr-1"></i>
                                            {{ $actividad['estado_validacion'] }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            Pendiente
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Acciones -->
                        @if($editable)
                            <div class="flex items-center space-x-2 ml-4">
                                <!-- Validar -->
                                <button type="button" 
                                        class="inline-flex items-center px-2 py-2 text-xs font-medium text-green-700 bg-green-100 rounded-lg hover:bg-green-200 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                        title="Validar actividad">
                                    <i class="fas fa-check"></i>
                                </button>
                                <!-- Rechazar -->
                                <button type="button" 
                                        class="inline-flex items-center px-2 py-2 text-xs font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                        title="Rechazar actividad">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Resumen de actividades -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Total -->
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-list text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-900">Total</p>
                            <p class="text-lg font-bold text-blue-800">{{ count($actividades) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Validadas -->
                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-green-900">Validadas</p>
                            <p class="text-lg font-bold text-green-800">
                                {{ collect($actividades)->where('estado_validacion', 'Validada')->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Pendientes -->
                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-yellow-900">Pendientes</p>
                            <p class="text-lg font-bold text-yellow-800">
                                {{ collect($actividades)->whereNotIn('estado_validacion', ['Validada', 'Rechazada'])->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Estado sin actividades -->
        <div class="text-center py-12">
            <div class="flex flex-col items-center justify-center space-y-4">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-industry text-gray-400 text-2xl"></i>
                </div>
                <div class="text-gray-500">
                    <p class="font-medium text-sm">No hay actividades económicas registradas</p>
                    <p class="text-xs mt-1">Este proveedor no tiene actividades económicas definidas.</p>
                </div>
            </div>
        </div>
@endif