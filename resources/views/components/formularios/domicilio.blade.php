<!-- SECCIÓN 2: DOMICILIO -->
<div id="seccion-2" class="section-content p-4 sm:p-8 border-t border-gray-200 mt-8">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-2 flex items-center">
            <div class="w-8 h-8 bg-[#9d2449]/10 rounded-lg flex items-center justify-center mr-3">
                <span class="text-[#9d2449] font-bold text-sm">2</span>
            </div>
            Domicilio
        </h2>
        <p class="text-gray-600 text-sm">Dirección completa del proveedor</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <x-form-input 
            name="codigo_postal" 
            label="Código Postal" 
            placeholder="12345"
            maxlength="5"
            required 
            id="codigo_postal_input"
        />
        <x-form-select 
            name="estado" 
            label="Estado" 
            placeholder="Seleccione un estado"
            :options="[]"
            required 
            disabled
            id="estado_select"
        />
        <x-form-select 
            name="municipio" 
            label="Municipio" 
            placeholder="Seleccione un municipio"
            :options="[]"
            required 
            disabled
            id="municipio_select"
        />
        <div class="lg:col-span-2">
            <label for="asentamiento_select" class="block text-sm font-semibold text-gray-700 mb-2">
                Asentamiento/Colonia
                <span class="text-red-500">*</span>
            </label>
            
            <div class="relative">
                <select 
                    name="asentamiento" 
                    id="asentamiento_select"
                    disabled
                    required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] focus:outline-none transition-colors duration-200 text-sm bg-white disabled:bg-gray-100 disabled:cursor-not-allowed"
                >
                    <option value="">Seleccione un asentamiento</option>
                </select>
                
                <!-- Campo oculto para el ID del asentamiento -->
                <input type="hidden" name="asentamiento_id" id="asentamiento_id" />
                <!-- Campo oculto para la localidad -->
                <input type="hidden" name="localidad" id="localidad_input" />
            </div>
            
            <!-- Mensaje de información -->
            <div id="asentamiento_info" class="hidden mt-2 text-sm text-gray-600 bg-blue-50 border border-blue-200 rounded-lg p-3">
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-blue-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="asentamiento_info_text"></span>
                </div>
            </div>
            
            <!-- Información de localidad -->
            <div id="localidad_info" class="hidden mt-2 text-sm text-green-600 bg-green-50 border border-green-200 rounded-lg p-3">
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span id="localidad_info_text"></span>
                </div>
            </div>
        </div>
        <x-form-input 
            name="calle" 
            label="Calle" 
            placeholder="Nombre de la calle"
            required 
        />
        <x-form-input 
            name="numero_exterior" 
            label="Número Exterior" 
            placeholder="Ej: 123"
            required 
        />
        <x-form-input 
            name="numero_interior" 
            label="Número Interior" 
            placeholder="Ej: A, 1, 201"
        />
        <x-form-input 
            name="entre_calle" 
            label="Entre Calle" 
            placeholder="Primera calle de referencia"
        />
        <x-form-input 
            name="y_calle" 
            label="Y Calle" 
            placeholder="Segunda calle de referencia"
        />
    </div>
</div> 