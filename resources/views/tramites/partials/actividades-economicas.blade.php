<div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
    <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-100">
        <div class="flex items-center space-x-4">
            <div class="h-14 w-14 flex items-center justify-center rounded-xl bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Actividades Económicas</h2>
                <p class="text-sm text-gray-600 mt-1">Seleccione las actividades económicas que realiza su empresa</p>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Buscador de actividades -->
        <div class="space-y-4">
            <div>
                <label for="buscador-actividad" class="block text-sm font-medium text-gray-700 mb-2">
                    Buscar Actividades Económicas
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="buscador-actividad" name="buscador_actividad" placeholder="Escriba para buscar actividades económicas..."
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
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