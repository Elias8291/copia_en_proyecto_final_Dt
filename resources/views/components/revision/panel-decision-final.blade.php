@props([
    'tramite' => null,
    'tipoRevision' => '',
    'secciones' => [],
    'actionUrl' => ''
])

<!-- Panel de decisión final -->
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6 border-t-4 border-blue-500">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Decisión Final de Revisión</h3>
    
    <form id="formRevisionCompleta" action="{{ $actionUrl }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="tipo_revision" value="{{ $tipoRevision }}">
        
        <!-- Campos ocultos para las decisiones de cada sección -->
        <div id="decisiones-secciones" class="hidden">
            @foreach($secciones as $seccion)
            <input type="hidden" name="secciones[{{ $seccion }}][decision]" id="decision_{{ $seccion }}" value="Pendiente">
            <input type="hidden" name="secciones[{{ $seccion }}][comentario]" id="comentario_{{ $seccion }}" value="">
            @endforeach
        </div>
        
        <!-- Resumen de decisiones por sección -->
        <x-revision.resumen-evaluaciones :secciones="$secciones" />
        
        <!-- Observaciones generales -->
        <div>
            <label for="observaciones_generales" class="block text-sm font-medium text-gray-700 mb-2">
                Observaciones Generales (opcional)
            </label>
            <textarea 
                id="observaciones_generales" 
                name="observaciones_generales" 
                rows="4" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600"
                placeholder="Observaciones generales sobre toda la revisión..."
            ></textarea>
        </div>
        
        <!-- Botones de decisión final -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
            <button 
                type="submit" 
                name="decision_final" 
                value="aprobado"
                class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center space-x-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Aprobar Trámite</span>
            </button>
            
            <button 
                type="submit" 
                name="decision_final" 
                value="agendar_cita"
                class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center space-x-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Agendar Cita</span>
            </button>
            
            <button 
                type="submit" 
                name="decision_final" 
                value="correcciones"
                class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center space-x-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.084 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <span>Para Corrección</span>
            </button>
            
            <button 
                type="submit" 
                name="decision_final" 
                value="rechazado"
                class="bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center space-x-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span>Rechazar Trámite</span>
            </button>
        </div>
        
        <!-- Campo hidden para la decisión final -->
        <input type="hidden" name="decision_final" value="">
    </form>
</div> 