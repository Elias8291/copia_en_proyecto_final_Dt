@props([
    'tramite' => null,
    'tipoRevision' => '',
    'secciones' => [],
    'actionUrl' => ''
])

<!-- Panel de decisión final -->
<div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-5 mb-6 border border-gray-200">
    <div class="text-center mb-5">
        <h3 class="text-lg font-semibold text-gray-900 mb-1">Decisión Final de Revisión</h3>
        <p class="text-gray-600 text-sm">Selecciona la acción final para este trámite</p>
    </div>
    
    <form id="formRevisionCompleta" action="{{ $actionUrl }}" method="POST" class="space-y-5">
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
        <div class="bg-gray-50 rounded-md p-3 border border-gray-200">
            <h4 class="text-sm font-medium text-gray-800 mb-2 text-center">Resumen de Evaluaciones</h4>
            <div class="flex justify-center">
                <x-revision.resumen-evaluaciones :secciones="$secciones" />
            </div>
        </div>
        
        <!-- Observaciones generales -->
        <div class="bg-gray-50 rounded-md p-3 border border-gray-200">
            <label for="observaciones_generales" class="block text-sm font-medium text-gray-800 mb-2 text-center">
                Observaciones Generales
            </label>
            <textarea 
                id="observaciones_generales" 
                name="observaciones_generales" 
                rows="2" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 resize-none text-sm"
                placeholder="Observaciones generales sobre toda la revisión..."
            ></textarea>
        </div>
        
        <!-- Botones de decisión final -->
        <div class="bg-gray-50 rounded-md p-3 border border-gray-200">
            <h4 class="text-sm font-medium text-gray-800 mb-3 text-center">Acción Final</h4>
            <div class="flex justify-center">
                <x-revision.botones-decision-final 
                    :showAprobar="true"
                    :showAgendarCita="true"
                    :showCorrecciones="true"
                    :showRechazar="true"
                    textoAgendarCita="Agendar Cita"
                    layout="grid"
                />
            </div>
        </div>
        
        <!-- Campo hidden para la decisión final -->
        <input type="hidden" name="decision_final" value="">
    </form>
</div> 