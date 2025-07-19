<!-- SECCIÓN 1: DATOS GENERALES -->
<div id="seccion-1" class="section-content p-4 sm:p-8">
    <div class="mb-8 border-b border-gray-200 pb-8">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-2 flex items-center">
                <div class="w-8 h-8 bg-[#9d2449]/10 rounded-lg flex items-center justify-center mr-3">
                    <span class="text-[#9d2449] font-bold text-sm">1</span>
                </div>
                Datos Generales
            </h2>
            <p class="text-gray-600 text-sm">Información básica del proveedor</p>
        </div>
        
        <!-- Tipo de Proveedor (como indicador, no como sección) -->
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Proveedor <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="relative">
                    <input type="radio" name="tipo_proveedor" value="fisica" class="peer sr-only" required 
                           {{ $proveedor && strtolower($proveedor->tipo_persona) === 'física' ? 'checked' : '' }}
                           {{ $proveedor ? 'disabled' : '' }}>
                    <div class="p-3 border-2 rounded-lg transition-all duration-200
                        {{ $proveedor && strtolower($proveedor->tipo_persona) === 'física' 
                            ? 'border-[#9d2449] bg-[#9d2449]/5' 
                            : ($proveedor ? 'border-gray-200 bg-gray-50 opacity-60' : 'border-gray-200 cursor-pointer hover:border-[#9d2449]/50') }}
                        {{ !$proveedor ? 'peer-checked:border-[#9d2449] peer-checked:bg-[#9d2449]/5' : '' }}">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-[#9d2449]/10 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">Persona Física</h3>
                                <p class="text-xs text-gray-600">Empresario individual</p>
                            </div>
                        </div>
                    </div>
                </label>
                <label class="relative">
                    <input type="radio" name="tipo_proveedor" value="moral" class="peer sr-only" required 
                           {{ $proveedor && strtolower($proveedor->tipo_persona) === 'moral' ? 'checked' : '' }}
                           {{ $proveedor ? 'disabled' : '' }}>
                    <div class="p-3 border-2 rounded-lg transition-all duration-200
                        {{ $proveedor && strtolower($proveedor->tipo_persona) === 'moral' 
                            ? 'border-[#9d2449] bg-[#9d2449]/5' 
                            : ($proveedor ? 'border-gray-200 bg-gray-50 opacity-60' : 'border-gray-200 cursor-pointer hover:border-[#9d2449]/50') }}
                        {{ !$proveedor ? 'peer-checked:border-[#9d2449] peer-checked:bg-[#9d2449]/5' : '' }}">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-[#9d2449]/10 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0H3m16 0v-3.87a3.37 3.37 0 00-.94-2.61c-.4-.4-.85-.71-1.38-.94"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">Persona Moral</h3>
                                <p class="text-xs text-gray-600">Empresa constituida</p>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
            
            @if($proveedor)
                <p class="text-xs text-gray-500 mt-2 flex items-center">
                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    El tipo de persona no se puede modificar una vez registrado
                </p>
            @endif
        </div>

        <!-- Campos principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <x-form-input 
                name="rfc" 
                label="RFC" 
                placeholder="Ej: ABC123456789"
                value="{{ $proveedor->rfc ?? '' }}"
                :readonly="$proveedor ? true : false"
                required 
            />
            <x-form-input 
                name="curp" 
                label="CURP" 
                placeholder="Ej: ABCD123456HDFGHI09"
                value="{{ $proveedor->curp ?? '' }}"
                :readonly="$proveedor ? true : false"
                id="curp_input"
                container_class="campo-persona-fisica"
            />
        </div>

        <x-form-input 
            name="nombre_razon_social" 
            label="Nombre Completo / Razón Social" 
            placeholder="Ingrese el nombre completo o razón social"
            value="{{ $proveedor->razon_social ?? '' }}"
            :readonly="$proveedor ? true : false"
            container_class="mb-6"
            required 
        />
        
        <div class="mb-6">
            <x-form-input 
                name="giro" 
                label="Giro" 
                placeholder="Ingrese el giro de su empresa"
                required 
            />
        </div>
        
        <div class="mb-6">
            <x-form-input 
                type="url"
                name="pagina_web" 
                label="Página Web" 
                placeholder="https://www.ejemplo.com"
            />
        </div>
    </div>

    <!-- SECCIÓN: ACTIVIDADES ECONÓMICAS -->
    <div class="mb-8 border-b border-gray-200 pb-8">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-2 flex items-center">
                <div class="w-8 h-8 bg-[#9d2449]/10 rounded-lg flex items-center justify-center mr-3">
                    <span class="text-[#9d2449] font-bold text-sm">3</span>
                </div>
                Actividades Económicas
            </h2>
            <p class="text-gray-600 text-sm">Seleccione las actividades económicas que realiza</p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                <svg class="w-4 h-4 text-[#9d2449] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Actividades Económicas <span class="text-red-500">*</span>
            </label>
            
            <!-- Buscador de actividades -->
            <div class="relative mb-4">
                <input 
                    type="text" 
                    id="buscar-actividades"
                    placeholder="Buscar actividades económicas por código o descripción..." 
                    class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#9d2449] focus:border-transparent transition-all duration-200 bg-white shadow-sm hover:shadow-md">
                <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Contenedor de resultados de búsqueda -->
            <div id="resultados-actividades" class="hidden absolute mt-1 bg-white border border-gray-200 rounded-xl shadow-xl max-h-64 overflow-y-auto z-50 w-full"></div>
            
            <!-- Actividades seleccionadas -->
            <div id="actividades-seleccionadas" class="mt-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-[#9d2449] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Actividades seleccionadas</span>
                    </div>
                    <span id="contador-actividades" class="hidden px-2 py-1 bg-[#9d2449] text-white text-xs font-medium rounded-full">0</span>
                </div>
                
                <!-- Contenedor principal de actividades -->
                <div id="lista-actividades-seleccionadas" class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-dashed border-gray-300 min-h-[120px] transition-all duration-300">
                    <!-- Estado vacío -->
                    <div id="estado-vacio" class="flex flex-col items-center justify-center h-full py-8 px-4">
                        <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm font-medium mb-1">No hay actividades seleccionadas</p>
                        <p class="text-gray-400 text-xs text-center">Utilice el buscador para agregar actividades económicas</p>
                    </div>
                    
                    <!-- Contenedor de actividades (se llena dinámicamente) -->
                    <div id="contenedor-actividades" class="hidden p-4 space-y-3"></div>
                </div>
                
                <!-- Información adicional -->
                <div class="mt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div class="flex items-center text-xs text-gray-500">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Puede seleccionar múltiples actividades
                    </div>
                    <button type="button" id="limpiar-actividades" class="hidden text-xs text-[#9d2449] hover:text-[#7a1d3a] font-medium transition-colors duration-200">
                        Limpiar todas
                    </button>
                </div>
            </div>
            
            <!-- Campo oculto para enviar los IDs de actividades -->
            <input type="hidden" name="actividades_economicas_ids" id="actividades-economicas-ids" required>
        </div>
    </div>

    <!-- SECCIÓN: DATOS DE CONTACTO -->
    <div class="mb-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-2 flex items-center">
                <div class="w-8 h-8 bg-[#9d2449]/10 rounded-lg flex items-center justify-center mr-3">
                    <span class="text-[#9d2449] font-bold text-sm">4</span>
                </div>
                Datos de Contacto
            </h2>
            <p class="text-gray-600 text-sm">Información de la persona de contacto</p>
        </div>

        <div class="bg-gray-50 rounded-xl p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-form-input 
                    name="contacto_nombre" 
                    label="Nombre del Contacto" 
                    placeholder="Nombre completo del contacto"
                    required 
                />
                <x-form-input 
                    name="contacto_cargo" 
                    label="Cargo" 
                    placeholder="Cargo o puesto"
                    required 
                />
                <x-form-input 
                    type="email"
                    name="contacto_correo" 
                    label="Correo Electrónico" 
                    placeholder="correo@ejemplo.com"
                    required 
                />
                <x-form-input 
                    type="tel"
                    name="contacto_telefono" 
                    label="Teléfono" 
                    placeholder="(55) 1234-5678"
                    required 
                />
            </div>
        </div>
    </div>
</div> 

