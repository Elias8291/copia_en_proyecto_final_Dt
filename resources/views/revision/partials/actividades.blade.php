@props(['tramite', 'actividades' => [], 'editable' => false])

@if(count($actividades) > 0)
    <div class="mb-3 flex flex-col space-y-1.5 sm:mb-4 sm:flex-row sm:space-x-2 sm:space-y-0 sm:justify-end lg:space-x-3">
        <div class="flex items-center bg-gray-50 border border-gray-200 rounded px-2 py-1 text-xs font-medium text-gray-700 shadow-sm">
            <i class="fas fa-list mr-1 text-gray-500 text-xs"></i>
            Total: <span class="ml-1 font-bold">{{ count($actividades) }}</span>
        </div>
        <div class="flex items-center bg-gray-50 border border-gray-200 rounded px-2 py-1 text-xs font-medium text-gray-700 shadow-sm">
            <i class="fas fa-check-circle mr-1 text-gray-500 text-xs"></i>
            Validadas: <span class="ml-1 font-bold">{{ collect($actividades)->where('estado_validacion', 'Validada')->count() }}</span>
        </div>
        <div class="flex items-center bg-gray-50 border border-gray-200 rounded px-2 py-1 text-xs font-medium text-gray-700 shadow-sm">
            <i class="fas fa-clock mr-1 text-gray-500 text-xs"></i>
            Pendientes: <span class="ml-1 font-bold">{{ collect($actividades)->whereNotIn('estado_validacion', ['Validada', 'Rechazada'])->count() }}</span>
        </div>
    </div>
    <div class="space-y-3 sm:space-y-4">
        @foreach($actividades as $index => $actividad)
            <div class="bg-white rounded-lg border border-gray-200 p-3 hover:shadow-sm transition-all duration-200 sm:rounded-xl sm:p-4 lg:p-6">
                <div class="flex flex-col space-y-2 sm:flex-row sm:items-start sm:justify-between sm:space-y-0 sm:space-x-3">
                    <div class="flex items-start space-x-2 flex-1 sm:space-x-3 lg:space-x-4">
                        @if(isset($actividad['es_principal']) && $actividad['es_principal'])
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center shadow-sm border border-gray-200 sm:w-10 sm:h-10 lg:w-12 lg:h-12">
                                    <i class="fas fa-star text-gray-500 text-sm sm:text-base lg:text-lg"></i>
                                </div>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="mb-2 sm:mb-3">
                                <h4 class="text-sm font-medium text-gray-900 leading-tight sm:text-base">
                                    {{ $actividad['descripcion'] ?? $actividad['nombre'] ?? 'Actividad sin nombre' }}
                                </h4>
                            </div>
                            <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-x-2 sm:space-y-0">
                                @if(isset($actividad['sector']) || isset($actividad['categoria']))
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700">
                                        <i class="fas fa-tag mr-1"></i>
                                        @php
                                            $sector = $actividad['sector'] ?? $actividad['categoria'] ?? 'Sin categoría';
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
                                            'Validada' => 'bg-gray-100 text-gray-600',
                                            'Rechazada' => 'bg-gray-100 text-gray-600',
                                            default => 'bg-gray-100 text-gray-600',
                                        };
                                        $estadoIcon = match($actividad['estado_validacion']) {
                                            'Validada' => 'fas fa-check-circle text-green-500',
                                            'Rechazada' => 'fas fa-times-circle text-red-500',
                                            default => 'fas fa-clock text-yellow-500',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $estadoClass }}">
                                        <i class="{{ $estadoIcon }} mr-1"></i>
                                        {{ $actividad['estado_validacion'] }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-600">
                                        <i class="fas fa-clock text-yellow-500 mr-1"></i>
                                        Pendiente
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($editable && (!isset($actividad['estado_validacion']) || $actividad['estado_validacion'] === 'Pendiente'))
                        <div class="flex items-center space-x-2 sm:ml-4">
                            <button type="button" 
                                    class="inline-flex items-center px-2 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                                    title="Validar actividad">
                                <i class="fas fa-check"></i>
                            </button>
                            <button type="button" 
                                    class="inline-flex items-center px-2 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                                    title="Rechazar actividad">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
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