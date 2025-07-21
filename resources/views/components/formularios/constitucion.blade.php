@props(['tipo' => 'inscripcion', 'proveedor' => null, 'editable' => true])

<div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8" {{ $attributes }}>
    <!-- Encabezado con icono -->
    <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-100">
        <div class="flex items-center space-x-4">
            <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <i class="fas fa-gavel text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Constitución</h2>
                <p class="text-sm text-gray-500 mt-1">Información constitutiva de la empresa</p>
            </div>
        </div>
    </div>

    <!-- Solo mostrar para personas morales -->
    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-8">
        <div class="flex items-center space-x-3">
            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 text-white shadow-sm flex-shrink-0">
                <i class="fas fa-info-circle text-sm"></i>
            </div>
            <p class="text-sm text-purple-700">
                Esta sección es únicamente para personas morales (empresas constituidas).
            </p>
        </div>
    </div>

    <div class="space-y-8">

        <!-- Información constitutiva -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group">
                <label for="fecha_constitucion" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha de Constitución
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-calendar-alt text-gray-500"></i>
                    </div>
                    <input type="date" id="fecha_constitucion" name="fecha_constitucion" 
                           value="{{ old('fecha_constitucion') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           aria-label="Fecha de constitución de la empresa"
                           required>
                </div>
            </div>

            <div class="form-group">
                <label for="numero_escritura" class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Escritura
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-file-contract text-gray-500"></i>
                    </div>
                    <input type="text" id="numero_escritura" name="numero_escritura" 
                           value="{{ old('numero_escritura') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           placeholder="Ej: 12345"
                           aria-label="Número de escritura constitutiva"
                           required>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group">
                <label for="notario_numero" class="block text-sm font-medium text-gray-700 mb-2">
                    Número del Notario
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-hashtag text-gray-500"></i>
                    </div>
                    <input type="text" id="notario_numero" name="notario_numero" 
                           value="{{ old('notario_numero') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           placeholder="Ej: 123"
                           aria-label="Número del notario"
                           required>
                </div>
            </div>

            <div class="form-group">
                <label for="notario_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre del Notario
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-user-tie text-gray-500"></i>
                    </div>
                    <input type="text" id="notario_nombre" name="notario_nombre" 
                           value="{{ old('notario_nombre') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           placeholder="Nombre completo del notario"
                           aria-label="Nombre del notario"
                           required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="entidad_federativa" class="block text-sm font-medium text-gray-700 mb-2">
                Entidad Federativa del Notario
                <span class="text-[#9d2449]">*</span>
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                    <i class="fas fa-map-marked-alt text-gray-500"></i>
                </div>
                <select id="entidad_federativa" name="entidad_federativa" 
                        class="block w-full pl-10 pr-10 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 appearance-none shadow-sm"
                        aria-label="Entidad federativa del notario"
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
                    <i class="fas fa-chevron-down text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Objeto social -->
        <div class="form-group">
            <label for="objeto_social" class="block text-sm font-medium text-gray-700 mb-2">
                Objeto Social
                <span class="text-[#9d2449]">*</span>
            </label>
            <div class="relative group">
                <div class="absolute top-3 left-0 pl-3 flex items-start pointer-events-none z-10">
                    <i class="fas fa-bullseye text-gray-500"></i>
                </div>
                <textarea id="objeto_social" name="objeto_social" rows="4" 
                          class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 resize-none shadow-sm"
                      placeholder="Descripción del objeto social de la empresa según el acta constitutiva"
                      required>{{ old('objeto_social') }}</textarea>
        </div>
        <p class="text-xs text-gray-500 mt-1.5">Describa las actividades principales para las que fue constituida la empresa</p>
    </div>

    <!-- Capital social -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="form-group">
            <label for="capital_minimo" class="block text-sm font-medium text-gray-700 mb-2">Capital Mínimo</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                    <i class="fas fa-dollar-sign text-gray-500"></i>
                </div>
                <input type="number" id="capital_minimo" name="capital_minimo" 
                       value="{{ old('capital_minimo') }}"
                       class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                       placeholder="0.00"
                       min="0"
                       step="0.01"
                       aria-label="Capital mínimo">
            </div>
        </div>

        <div class="form-group">
            <label for="capital_variable" class="block text-sm font-medium text-gray-700 mb-2">Capital Variable</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                    <i class="fas fa-chart-line text-gray-500"></i>
                </div>
                <input type="number" id="capital_variable" name="capital_variable" 
                       value="{{ old('capital_variable') }}"
                       class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                       placeholder="0.00"
                       min="0"
                       step="0.01"
                       aria-label="Capital variable">
            </div>
        </div>
    </div>
    </div>
</div>

    <!-- Información adicional para renovación/actualización -->
    @if($tipo !== 'inscripcion')
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2 mt-0.5"></i>
            <div>
                <h4 class="text-base font-semibold text-yellow-800 mb-1">Verificación de Datos Constitutivos</h4>
                <p class="text-sm text-yellow-700">
                    @if($tipo === 'renovacion')
                        Verifique que los datos constitutivos coincidan con su acta constitutiva vigente.
                    @else
                        Solo modifique los campos que han cambiado desde su último registro.
                    @endif
                </p>
            </div>
        </div>
    </div>
    @endif

<style>
.h-12 {
    position: relative;
    overflow: hidden;
}
.h-12::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        45deg,
        transparent,
        rgba(255, 255, 255, 0.1),
        transparent
    );
    transform: rotate(45deg);
    animation: shine 3s infinite;
}
@keyframes shine {
    0% {
        transform: translateX(-100%) rotate(45deg);
    }
    20%, 100% {
        transform: translateX(100%) rotate(45deg);
    }
}
.form-group:hover input:not([readonly]),
.form-group:hover select,
.form-group:hover textarea {
    @apply border-[#9d2449]/30;
}
input:focus:not([readonly]), 
select:focus,
textarea:focus {
    @apply ring-2 ring-[#9d2449]/20 border-[#9d2449];
    box-shadow: 0 0 0 1px rgba(157, 36, 73, 0.1), 
                0 2px 4px rgba(157, 36, 73, 0.05);
}
input[readonly] {
    @apply bg-gray-50;
}
input, select, textarea {
    @apply transition-all duration-300 bg-white shadow-sm;
}
input:focus:not([readonly]), 
select:focus, 
textarea:focus {
    @apply transform -translate-y-px shadow-md bg-white;
}
.form-group {
    @apply relative;
}
</style>