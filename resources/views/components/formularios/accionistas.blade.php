<!-- SECCIÓN 4: ACCIONISTAS (Solo Morales) -->
<div id="seccion-4" class="section-content p-4 sm:p-8 border-t border-gray-200 mt-8 persona-moral-section">
    <div class="mb-4">
        <h2 class="text-xl font-bold text-gray-900 mb-2 flex items-center">
            <div class="w-8 h-8 bg-[#9d2449]/10 rounded-lg flex items-center justify-center mr-3">
                <span class="text-[#9d2449] font-bold text-sm">4</span>
            </div>
            Accionistas
        </h2>
        <p class="text-gray-600 text-sm">Información de los accionistas de la empresa</p>
    </div>
    
    <!-- Panel de resumen de accionistas -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 mb-6 border border-gray-200">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <!-- Contador de accionistas -->
            <div class="flex items-center">
                <div class="w-10 h-10 bg-[#9d2449]/10 rounded-full flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Accionistas registrados</p>
                    <div class="flex items-center">
                        <span id="contador-accionistas" class="text-xl font-bold text-[#9d2449]">1</span>
                        <span class="text-sm text-gray-500 ml-2">accionista(s)</span>
                    </div>
                </div>
            </div>
            
            <!-- Información sobre porcentajes -->
            <div class="flex items-center bg-white px-4 py-3 rounded-lg border border-gray-200 shadow-sm">
                <div class="mr-3">
                    <svg class="w-8 h-8 text-[#9d2449]/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Porcentaje total</p>
                    <div id="porcentaje-total" class="flex items-center">
                        <span class="text-xl font-bold text-[#9d2449]">0%</span> 
                        <span class="text-sm text-gray-500 ml-2">(Faltan: 100%)</span>
                    </div>
                </div>
            </div>
            
            <!-- Botón para agregar accionista -->
            <button type="button" id="agregar-accionista" 
                    class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-[#9d2449] to-[#be1558] text-white font-semibold rounded-xl hover:shadow-lg transition-all duration-300 text-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Agregar Accionista
            </button>
        </div>
    </div>
    
    <!-- Contenedor de accionistas (acordeón) -->
    <div id="accionistas-container" class="space-y-4">
        <!-- Primer accionista (inicialmente colapsado) -->
        <div class="accionista-item bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" data-id="0">
            <!-- Cabecera del acordeón -->
            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center cursor-pointer accordion-header">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-[#9d2449] rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                        1
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Accionista #1</h3>
                        <p class="text-xs text-gray-500">Información requerida</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium text-[#9d2449] mr-3 porcentaje-display">0%</span>
                    <button type="button" class="eliminar-accionista text-red-500 hover:text-red-700 transition-colors mr-3" style="display: none;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Contenido del acordeón (inicialmente oculto) -->
            <div class="px-6 py-5 accordion-content hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <x-form-input 
                        name="accionistas[0][rfc]" 
                        label="RFC" 
                        placeholder="Ej: XAXX010101000"
                        required 
                        data-required="true"
                    />
                    <x-form-input 
                        type="number"
                        name="accionistas[0][porcentaje]" 
                        label="Porcentaje de Participación" 
                        placeholder="0.00"
                        min="0"
                        max="100"
                        step="0.01"
                        suffix="%"
                        required 
                        data-required="true"
                        class="porcentaje-input"
                    />
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <x-form-input 
                        name="accionistas[0][nombre]" 
                        label="Nombre" 
                        placeholder="Nombre"
                        required 
                        data-required="true"
                    />
                    <x-form-input 
                        name="accionistas[0][apellido_paterno]" 
                        label="Apellido Paterno" 
                        placeholder="Apellido paterno"
                        required 
                        data-required="true"
                    />
                    <x-form-input 
                        name="accionistas[0][apellido_materno]" 
                        label="Apellido Materno" 
                        placeholder="Apellido materno"
                    />
                </div>
                
                <!-- Mensaje de primer accionista -->
                <div class="mt-6 text-sm text-gray-500 flex items-center bg-gray-50 p-3 rounded-lg">
                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Se requiere al menos un accionista con información completa
                </div>
            </div>
        </div>
        
        <!-- Aquí se agregarán dinámicamente más accionistas -->
    </div>
    
    <!-- Nota informativa -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h4 class="font-medium text-blue-900 text-sm">Información sobre porcentajes</h4>
                <p class="text-blue-700 text-xs mt-1">La suma de los porcentajes de participación debe ser igual a 100%. Asegúrese de distribuir correctamente los porcentajes entre todos los accionistas.</p>
            </div>
        </div>
    </div>
</div> 