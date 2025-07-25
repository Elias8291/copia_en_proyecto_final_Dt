@props(['tramite', 'actividades' => [], 'editable' => false])

@if(count($actividades) > 0)
    <div class="mb-4 flex flex-col sm:flex-row sm:space-x-3 space-y-2 sm:space-y-0 justify-end">
        <div class="flex items-center bg-blue-50 border border-blue-100 rounded-md px-2.5 py-1 text-xs font-medium text-blue-800 shadow-sm">
            <i class="fas fa-list mr-1 text-blue-400"></i>
            Total: <span class="ml-1 font-bold">{{ count($actividades) }}</span>
        </div>
        <div class="flex items-center bg-green-50 border border-green-100 rounded-md px-2.5 py-1 text-xs font-medium text-green-800 shadow-sm">
            <i class="fas fa-check-circle mr-1 text-green-400"></i>
            Validadas: <span class="ml-1 font-bold">{{ collect($actividades)->where('estado_validacion', 'Validada')->count() }}</span>
        </div>
        <div class="flex items-center bg-yellow-50 border border-yellow-100 rounded-md px-2.5 py-1 text-xs font-medium text-yellow-800 shadow-sm">
            <i class="fas fa-clock mr-1 text-yellow-400"></i>
            Pendientes: <span class="ml-1 font-bold">{{ collect($actividades)->whereNotIn('estado_validacion', ['Validada', 'Rechazada'])->count() }}</span>
        </div>
    </div>
    <div class="space-y-4">
        @foreach($actividades as $index => $actividad)
            <div class="bg-gradient-to-r from-gray-50 to-gray-100/50 rounded-xl border border-gray-200 p-6 hover:shadow-md transition-all duration-200">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4 flex-1">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center shadow-sm border border-gray-200">
                                @if(isset($actividad['es_principal']) && $actividad['es_principal'])
                                    <i class="fas fa-star text-yellow-500 text-lg"></i>
                                @else
                                    <span class="text-[10px] font-semibold text-gray-400">#{{ $index + 1 }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="mb-3">
                                <h4 class="text-sm font-semibold text-gray-900">
                                    {{ $actividad['descripcion'] ?? $actividad['nombre'] ?? 'Actividad sin nombre' }}
                                </h4>
                            </div>
                    
                            <div class="flex items-center space-x-2">
                                @if(isset($actividad['sector']) || isset($actividad['categoria']))
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-indigo-100 text-indigo-800">
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
                    @if($editable && (!isset($actividad['estado_validacion']) || $actividad['estado_validacion'] === 'Pendiente'))
                        <div class="flex items-center space-x-2 ml-4">
                            <button type="button" 
                                    class="inline-flex items-center px-2 py-2 text-xs font-medium text-green-700 bg-green-100 rounded-lg hover:bg-green-200 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                    title="Validar actividad">
                                <i class="fas fa-check"></i>
                            </button>
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