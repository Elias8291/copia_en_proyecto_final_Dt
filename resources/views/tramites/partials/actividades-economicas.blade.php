@props(['tipo' => 'inscripcion', 'proveedor' => null, 'editable' => true, 'tramite' => null])
@php
    // Obtener el estado de la sección
    $revisionSeccion = null;
    if ($tramite) {
        $revisionSeccion = \App\Models\RevisionSeccion::where('tramite_id', $tramite->id)
            ->where('seccion', 'actividades')
            ->first();
    }
    
    $seccionAprobada = $revisionSeccion && $revisionSeccion->aprobado === true;
    $permitirEdicion = $editable && !$seccionAprobada;
    
    $actividadesExistentes = [];
    if ($tramite && $tramite->actividades) {
        $actividadesExistentes = $tramite->actividades->map(function($actividad) {
            return [
                'id' => $actividad->id,
                'nombre' => $actividad->nombre,
                'codigo' => $actividad->codigo,
                'sector' => $actividad->sector?->nombre ?? 'N/A' // Null-safe access
            ];
        })->toArray();
    }
@endphp

<div class="space-y-6" {{ $attributes }} data-seccion="actividades">
    <!-- Información del Trámite -->
   
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Actividades Económicas
        </h4>
        <div class="space-y-6">
            <div class="form-group field-container">
                <label for="buscador-actividad" class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Buscar Actividades Económicas
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="buscador-actividad" name="buscador_actividad" placeholder="Escriba para buscar actividades económicas..."
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm {{ !$permitirEdicion ? 'opacity-50 cursor-not-allowed' : '' }}"
                        aria-label="Buscar actividades económicas"
                        {{ !$permitirEdicion ? 'disabled' : '' }}>

                    <!-- Resultados de búsqueda -->
                    <div id="resultados-actividades"
                        class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden">
                        <!-- Los resultados se cargarán aquí via JavaScript -->
                    </div>
                </div>

                <!-- Actividades seleccionadas -->
                <div id="actividades-seleccionadas" class="space-y-2 mt-4">
                    @if(!empty($actividadesExistentes))
                        @foreach($actividadesExistentes as $actividad)
                            <div class="actividad-item flex items-center justify-between p-3 bg-blue-50 border border-blue-200 rounded-lg" data-actividad-id="{{ $actividad['id'] }}">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-medium text-blue-800">{{ $actividad['codigo'] }}</span>
                                        <span class="text-sm text-gray-700">{{ $actividad['nombre'] }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $actividad['sector'] }}</div>
                                </div>
                                <button type="button" onclick="eliminarActividad({{ $actividad['id'] }})" 
                                        class="ml-2 text-red-500 hover:text-red-700 {{ !$permitirEdicion ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ !$permitirEdicion ? 'disabled' : '' }}>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                                <input type="hidden" name="actividades[]" value="{{ $actividad['id'] }}">
                            </div>
                        @endforeach
                    @else
                        <p class="text-sm text-gray-500">No se han seleccionado actividades económicas</p>
                    @endif
                </div>

                <!-- Campo hidden para validación de actividades -->
                <input type="hidden" name="actividades_validation" id="actividades-validation"
                    data-validate="actividades" required class="validate-actividades">

                <!-- Campos hidden para actividades (se llenan via JavaScript) -->
                <div id="actividades-hidden-inputs">
                    <!-- Los inputs hidden se agregarán aquí dinámicamente por JavaScript -->
                </div>
            </div>

            <!-- Instrucciones -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                <h3 class="text-sm font-semibold text-gray-800 mb-3">Instrucciones</h3>
                <div class="space-y-2 text-xs text-gray-700">
                    <div class="flex items-start space-x-2">
                        <span class="text-blue-600 font-medium">1.</span>
                        <p>Escriba para buscar actividades económicas.</p>
                    </div>
                    <div class="flex items-start space-x-2">
                        <span class="text-blue-600 font-medium">2.</span>
                        <p>Haga clic para agregar a su selección.</p>
                    </div>
                    <div class="flex items-start space-x-2">
                        <span class="text-blue-600 font-medium">3.</span>
                        <p>Si no encuentra su actividad, puede crear una nueva.</p>
                    </div>
                    <div class="flex items-start space-x-2">
                        <span class="text-blue-600 font-medium">4.</span>
                        <p>Use la X para eliminar actividades.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($tramite)
    @include('tramites.partials.estado-seccion', ['seccion' => 'actividades', 'tramite' => $tramite])
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Marcar que ya hay actividades cargadas si existen
    if ({{ !empty($actividadesExistentes) ? 'true' : 'false' }}) {
        window.actividadesCargadas = true;
        
        // Actualizar el contador de actividades
        const contadorTexto = document.querySelector('#actividades-seleccionadas p');
        if (contadorTexto) {
            contadorTexto.textContent = `${document.querySelectorAll('.actividad-item').length} actividad(es) seleccionada(s)`;
        }
    }
});
</script>
@endpush 