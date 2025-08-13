<!-- Modal para Agendar Cita de Revisión -->
<div id="modal-agendar-cita" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <!-- Header del Modal -->
        <div class="flex items-center justify-between p-4 border-b">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-[#9d2449] to-[#7a1a37] rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l6-6m0 0l6 6m-6-6v12"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Agendar Cita de Revisión</h3>
                    <p class="text-sm text-gray-600">Seleccionar revisor y fecha para la cita</p>
                </div>
            </div>
            <button type="button" onclick="cerrarModalCita()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Contenido del Modal -->
        <div class="p-6">
            <!-- Información del Trámite -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-blue-800">Trámite:</span>
                        <p class="text-blue-900" id="info-tramite-id">#{{ $tramite->id ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-blue-800">RFC:</span>
                        <p class="text-blue-900" id="info-tramite-rfc">{{ $tramite->proveedor->rfc ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-blue-800">Razón Social:</span>
                        <p class="text-blue-900" id="info-tramite-razon">{{ $tramite->proveedor->razon_social ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Formulario de Agendamiento -->
            <form id="form-agendar-cita">
                @csrf
                <input type="hidden" name="tramite_id" id="tramite-id-input" value="{{ $tramite->id ?? '' }}">
                
                <!-- Tipo de Revisión -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Revisión</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="tipo_revision" value="Digital" class="mr-3 text-[#9d2449] focus:ring-[#9d2449]" checked>
                            <span class="text-sm font-medium">Digital</span>
                        </label>
                        <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="tipo_revision" value="Presencial" class="mr-3 text-[#9d2449] focus:ring-[#9d2449]">
                            <span class="text-sm font-medium">Presencial</span>
                        </label>
                        <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="tipo_revision" value="Domiciliaria" class="mr-3 text-[#9d2449] focus:ring-[#9d2449]">
                            <span class="text-sm font-medium">Domiciliaria</span>
                        </label>
                    </div>
                </div>

                <!-- Lista de Revisores Disponibles -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Revisores Disponibles</label>
                    <div id="lista-revisores" class="space-y-3 max-h-60 overflow-y-auto">
                        <!-- Los revisores se cargarán dinámicamente aquí -->
                        <div class="flex items-center justify-center py-8">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-[#9d2449]"></div>
                            <span class="ml-2 text-gray-600">Cargando revisores...</span>
                        </div>
                    </div>
                </div>

                <!-- Fecha y Hora -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="fecha-cita" class="block text-sm font-medium text-gray-700 mb-2">Fecha de la Cita</label>
                        <input type="date" 
                               id="fecha-cita" 
                               name="fecha_cita" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449]"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               required>
                    </div>
                    <div>
                        <label for="hora-cita" class="block text-sm font-medium text-gray-700 mb-2">Hora de la Cita</label>
                        <select id="hora-cita" 
                                name="hora_cita" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449]"
                                required>
                            <option value="">Seleccionar hora</option>
                            <option value="09:00">09:00 AM</option>
                            <option value="09:30">09:30 AM</option>
                            <option value="10:00">10:00 AM</option>
                            <option value="10:30">10:30 AM</option>
                            <option value="11:00">11:00 AM</option>
                            <option value="11:30">11:30 AM</option>
                            <option value="12:00">12:00 PM</option>
                            <option value="12:30">12:30 PM</option>
                            <option value="13:00">01:00 PM</option>
                            <option value="13:30">01:30 PM</option>
                        </select>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="mb-6">
                    <label for="observaciones-cita" class="block text-sm font-medium text-gray-700 mb-2">Observaciones (Opcional)</label>
                    <textarea id="observaciones-cita" 
                              name="observaciones" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] resize-none"
                              placeholder="Agregar observaciones sobre la cita..."></textarea>
                </div>

                <!-- Botones de Acción -->
                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button type="button" 
                            onclick="cerrarModalCita()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200">
                        Cancelar
                    </button>
                    <button type="submit" 
                            id="btn-agendar-cita"
                            class="px-6 py-2 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2 hidden" id="spinner-agendar" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span id="texto-btn-agendar">Agendar Cita</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Estilos adicionales para el modal */
.revisor-card {
    transition: all 0.2s ease-in-out;
}

.revisor-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.revisor-card.selected {
    border-color: #9d2449;
    background-color: #fef7f7;
}

.distancia-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
}

.distancia-cerca {
    background-color: #d1fae5;
    color: #065f46;
}

.distancia-media {
    background-color: #fef3c7;
    color: #92400e;
}

.distancia-lejos {
    background-color: #fee2e2;
    color: #991b1b;
}

.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
