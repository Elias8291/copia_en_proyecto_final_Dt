@props(['tipo' => 'inscripcion', 'proveedor' => null])

<div class="space-y-8 bg-white p-6 rounded-2xl shadow-sm" {{ $attributes }}>
    <!-- Información del apoderado -->
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-amber-600 mr-2"></i>
            <p class="text-sm text-amber-700">
                Información del representante legal autorizado para actuar en nombre de la empresa.
            </p>
        </div>
    </div>

    <!-- Datos personales del apoderado -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="apoderado_nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo <span class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                    <i class="fas fa-user text-gray-400"></i>
                </div>
                <input type="text" id="apoderado_nombre" name="apoderado_nombre" 
                       value="{{ old('apoderado_nombre') }}" 
                       class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md"
                       placeholder="Nombre completo del apoderado legal"
                       required>
            </div>
        </div>

        <div>
            <label for="apoderado_rfc" class="block text-sm font-medium text-gray-700 mb-2">RFC <span class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                    <i class="fas fa-id-card text-gray-400"></i>
                </div>
                <input type="text" id="apoderado_rfc" name="apoderado_rfc" 
                       value="{{ old('apoderado_rfc') }}" 
                       class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md"
                       placeholder="RFC del apoderado"
                       maxlength="13"
                       pattern="[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{3}"
                       required>
            </div>
        </div>
    </div>

    <!-- Información del poder notarial -->
    <div class="border-t border-gray-200 pt-6">
        <div class="flex items-center mb-4">
            <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-gavel text-white text-sm"></i>
            </div>
            <h4 class="text-lg font-semibold text-gray-900">Información del Poder Notarial</h4>
        </div>
        
        <!-- Primera fila: Número de Escritura y Fecha de Constitución -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="poder_numero_escritura" class="block text-sm font-medium text-gray-700 mb-2">Número de Escritura <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-file-contract text-gray-400"></i>
                    </div>
                    <input type="text" id="poder_numero_escritura" name="poder_numero_escritura" 
                           value="{{ old('poder_numero_escritura') }}" 
                           class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md"
                           placeholder="Número de escritura del poder"
                           required>
                </div>
            </div>

            <div>
                <label for="poder_fecha_constitucion" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Constitución <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-calendar-alt text-gray-400"></i>
                    </div>
                    <input type="date" id="poder_fecha_constitucion" name="poder_fecha_constitucion" 
                           value="{{ old('poder_fecha_constitucion') }}" 
                           class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md"
                           required>
                </div>
            </div>
        </div>

        <!-- Segunda fila: Nombre del Notario y Entidad Federativa -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <label for="poder_notario_nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre del Notario <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-user-tie text-gray-400"></i>
                    </div>
                    <input type="text" id="poder_notario_nombre" name="poder_notario_nombre" 
                           value="{{ old('poder_notario_nombre') }}" 
                           class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md"
                           placeholder="Nombre completo del notario"
                           required>
                </div>
            </div>

            <div>
                <label for="poder_entidad_federativa" class="block text-sm font-medium text-gray-700 mb-2">Entidad Federativa <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-map-marked-alt text-gray-400"></i>
                    </div>
                    <select id="poder_entidad_federativa" name="poder_entidad_federativa" 
                            class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md appearance-none"
                            required>
                        <option value="">Seleccione la entidad federativa</option>
                        <option value="AGUASCALIENTES">Aguascalientes</option>
                        <option value="BAJA CALIFORNIA">Baja California</option>
                        <option value="BAJA CALIFORNIA SUR">Baja California Sur</option>
                        <option value="CAMPECHE">Campeche</option>
                        <option value="CHIAPAS">Chiapas</option>
                        <option value="CHIHUAHUA">Chihuahua</option>
                        <option value="CIUDAD DE MEXICO">Ciudad de México</option>
                        <option value="COAHUILA">Coahuila</option>
                        <option value="COLIMA">Colima</option>
                        <option value="DURANGO">Durango</option>
                        <option value="GUANAJUATO">Guanajuato</option>
                        <option value="GUERRERO">Guerrero</option>
                        <option value="HIDALGO">Hidalgo</option>
                        <option value="JALISCO">Jalisco</option>
                        <option value="MEXICO">México</option>
                        <option value="MICHOACAN">Michoacán</option>
                        <option value="MORELOS">Morelos</option>
                        <option value="NAYARIT">Nayarit</option>
                        <option value="NUEVO LEON">Nuevo León</option>
                        <option value="OAXACA">Oaxaca</option>
                        <option value="PUEBLA">Puebla</option>
                        <option value="QUERETARO">Querétaro</option>
                        <option value="QUINTANA ROO">Quintana Roo</option>
                        <option value="SAN LUIS POTOSI">San Luis Potosí</option>
                        <option value="SINALOA">Sinaloa</option>
                        <option value="SONORA">Sonora</option>
                        <option value="TABASCO">Tabasco</option>
                        <option value="TAMAULIPAS">Tamaulipas</option>
                        <option value="TLAXCALA">Tlaxcala</option>
                        <option value="VERACRUZ">Veracruz</option>
                        <option value="YUCATAN">Yucatán</option>
                        <option value="ZACATECAS">Zacatecas</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tercera fila: Número de Notario y Número de Registro -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <label for="poder_notario_numero" class="block text-sm font-medium text-gray-700 mb-2">Número de Notario <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-hashtag text-gray-400"></i>
                    </div>
                    <input type="text" id="poder_notario_numero" name="poder_notario_numero" 
                           value="{{ old('poder_notario_numero') }}" 
                           class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md"
                           placeholder="Número del notario"
                           required>
                </div>
            </div>

            <div>
                <label for="poder_numero_registro" class="block text-sm font-medium text-gray-700 mb-2">Número de Registro <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-registered text-gray-400"></i>
                    </div>
                    <input type="text" id="poder_numero_registro" name="poder_numero_registro" 
                           value="{{ old('poder_numero_registro') }}" 
                           class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md"
                           placeholder="Número de registro"
                           required>
                </div>
            </div>
        </div>
    </div>
</div>