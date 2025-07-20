@props(['tipo' => 'inscripcion', 'proveedor' => null])

<div class="space-y-6">
    <!-- Solo mostrar para personas morales -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
            <p class="text-sm text-blue-700">
                Esta sección es únicamente para personas morales (empresas constituidas).
            </p>
        </div>
    </div>

    <!-- Información constitutiva -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="fecha_constitucion" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Constitución <span class="text-red-500">*</span></label>
            <input type="date" id="fecha_constitucion" name="fecha_constitucion" 
                   value="{{ old('fecha_constitucion') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                   required>
        </div>

        <div>
            <label for="numero_escritura" class="block text-sm font-medium text-gray-700 mb-2">Número de Escritura <span class="text-red-500">*</span></label>
            <input type="text" id="numero_escritura" name="numero_escritura" 
                   value="{{ old('numero_escritura') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                   placeholder="Ej: 12345"
                   required>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="notario_numero" class="block text-sm font-medium text-gray-700 mb-2">Número del Notario <span class="text-red-500">*</span></label>
            <input type="text" id="notario_numero" name="notario_numero" 
                   value="{{ old('notario_numero') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                   placeholder="Ej: 123"
                   required>
        </div>

        <div>
            <label for="notario_nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre del Notario <span class="text-red-500">*</span></label>
            <input type="text" id="notario_nombre" name="notario_nombre" 
                   value="{{ old('notario_nombre') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                   placeholder="Nombre completo del notario"
                   required>
        </div>
    </div>

    <div>
        <label for="entidad_federativa" class="block text-sm font-medium text-gray-700 mb-2">Entidad Federativa del Notario <span class="text-red-500">*</span></label>
        <select id="entidad_federativa" name="entidad_federativa" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent" 
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
    </div>

    <!-- Objeto social -->
    <div>
        <label for="objeto_social" class="block text-sm font-medium text-gray-700 mb-2">Objeto Social <span class="text-red-500">*</span></label>
        <textarea id="objeto_social" name="objeto_social" rows="4" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                  placeholder="Descripción del objeto social de la empresa según el acta constitutiva"
                  required>{{ old('objeto_social') }}</textarea>
        <p class="text-xs text-gray-500 mt-1">Describa las actividades principales para las que fue constituida la empresa</p>
    </div>

    <!-- Capital social -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="capital_minimo" class="block text-sm font-medium text-gray-700 mb-2">Capital Mínimo</label>
            <div class="relative">
                <span class="absolute left-3 top-2 text-gray-500">$</span>
                <input type="number" id="capital_minimo" name="capital_minimo" 
                       value="{{ old('capital_minimo') }}" 
                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                       placeholder="0.00"
                       min="0"
                       step="0.01">
            </div>
        </div>

        <div>
            <label for="capital_variable" class="block text-sm font-medium text-gray-700 mb-2">Capital Variable</label>
            <div class="relative">
                <span class="absolute left-3 top-2 text-gray-500">$</span>
                <input type="number" id="capital_variable" name="capital_variable" 
                       value="{{ old('capital_variable') }}" 
                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                       placeholder="0.00"
                       min="0"
                       step="0.01">
            </div>
        </div>
    </div>
</div> 