<div class="mt-6 pt-4 border-t border-gray-100">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <!-- Header del panel de revisión -->
        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-100 rounded-t-lg">
            <div class="flex items-center space-x-2">
                <div class="w-6 h-6 bg-slate-600 rounded-md flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Revisión</span>
            </div>
            <span id="estado_visual_{{ $seccion }}" class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                <div class="w-2 h-2 bg-amber-400 rounded-full mr-1.5"></div>
                Pendiente
            </span>
        </div>
        
        <!-- Comentario existente -->
        <div id="comentario_box_{{ $seccion }}" class="px-4 py-3 bg-blue-50 border-l-3 border-blue-400" style="display:none;">
            <div class="flex items-start space-x-2">
                <div id="icono_comentario_{{ $seccion }}" class="flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-medium text-blue-800 mb-1">Observación</p>
                    <p id="comentario_texto_{{ $seccion }}" class="text-xs text-blue-700"></p>
                </div>
            </div>
        </div>
        
        <!-- Contenido del panel de revisión -->
        <div class="p-4 space-y-3">
            <!-- Botones de acción -->
            <div class="flex items-center justify-center space-x-2">
                <button type="button" id="btn_aprobar_{{ $seccion }}" 
                    class="inline-flex items-center px-3 py-1.5 bg-white border border-emerald-300 text-emerald-700 rounded-md font-medium text-xs hover:bg-emerald-50 focus:outline-none focus:ring-1 focus:ring-emerald-400 transition-colors"
                    onclick="setAprobado('{{ $seccion }}', true)">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Aprobar
                </button>
                <button type="button" id="btn_rechazar_{{ $seccion }}" 
                    class="inline-flex items-center px-3 py-1.5 bg-white border border-red-300 text-red-700 rounded-md font-medium text-xs hover:bg-red-50 focus:outline-none focus:ring-1 focus:ring-red-400 transition-colors"
                    onclick="setAprobado('{{ $seccion }}', false)">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Rechazar
                </button>
                <input type="hidden" id="aprobado_{{ $seccion }}" value="" data-seccion="{{ $seccion }}">
            </div>
            
            <!-- Área de comentarios -->
            <div class="space-y-2">
                <label for="comentario_{{ $seccion }}" class="block text-xs font-medium text-gray-600">Observaciones</label>
                <div class="relative">
                    <textarea id="comentario_{{ $seccion }}" data-seccion="{{ $seccion }}" rows="3" 
                        class="block w-full px-3 py-2 pr-16 border border-gray-300 rounded-md text-xs placeholder-gray-400 focus:ring-1 focus:ring-[#9D2449] focus:border-[#9D2449] focus:outline-none resize-none" 
                        placeholder="Escriba sus observaciones..."></textarea>
                    <button type="button" 
                        class="absolute bottom-2 right-2 inline-flex items-center px-2 py-1 bg-[#9D2449] text-white rounded text-xs font-medium hover:bg-[#7A1D3A] focus:outline-none transition-colors" 
                        onclick="guardarComentarioSeccion('{{ $seccion }}')">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Guardar
                    </button>
                </div>
            </div>
            
            <!-- Estado del comentario -->
            <div id="estado_comentario_{{ $seccion }}" class="text-xs text-center font-medium"></div>
        </div>
    </div>
</div> 