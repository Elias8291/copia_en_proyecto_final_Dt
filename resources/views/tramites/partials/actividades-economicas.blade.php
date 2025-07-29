<div class="space-y-8">
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
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm"
                        aria-label="Buscar actividades económicas">

                    <!-- Resultados de búsqueda -->
                    <div id="resultados-actividades"
                        class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden">
                        <!-- Los resultados se cargarán aquí via JavaScript -->
                    </div>
                </div>

                <!-- Actividades seleccionadas -->
                <div id="actividades-seleccionadas" class="space-y-2 mt-4">
                    <p class="text-sm text-gray-500">No se han seleccionado actividades económicas</p>
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