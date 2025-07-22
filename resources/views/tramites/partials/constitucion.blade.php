@props(['tipo' => 'inscripcion', 'proveedor' => null, 'editable' => true])

<div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8" {{ $attributes }}>
    <!-- Encabezado con icono -->
    <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-100">
        <div class="flex items-center space-x-4">
            <div
                class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <i class="fas fa-gavel text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Constitución</h2>
                <p class="text-sm text-gray-500 mt-1">Información constitutiva de la empresa</p>
            </div>
        </div>
    </div>

    <div class="space-y-8">
        <!-- Primera fila: Número de Escritura y Fecha de Constitución -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                        placeholder="Ej: 12345" aria-label="Número de escritura constitutiva" required>
                </div>
            </div>

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
                        aria-label="Fecha de constitución de la empresa" required>
                </div>
            </div>
        </div>

        <!-- Segunda fila: Nombre del Notario y Entidad Federativa -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group">
                <label for="notario_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre del Notario
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-user-tie text-gray-500"></i>
                    </div>
                    <input type="text" id="notario_nombre" name="notario_nombre" value="{{ old('notario_nombre') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                        placeholder="Nombre completo del notario" aria-label="Nombre del notario" required>
                </div>
            </div>

            <div class="form-group">
                <label for="entidad_federativa" class="block text-sm font-medium text-gray-700 mb-2">
                    Entidad Federativa
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-map-marked-alt text-gray-500"></i>
                    </div>
                    <select id="entidad_federativa" name="entidad_federativa"
                        class="block w-full pl-10 pr-10 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 appearance-none shadow-sm"
                        aria-label="Entidad federativa del notario" required>
                        <option value="">Seleccione la entidad federativa</option>
                        <!-- Estados se cargarán dinámicamente -->
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tercera fila: Número de Notario y Número de Registro -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group">
                <label for="notario_numero" class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Notario
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-hashtag text-gray-500"></i>
                    </div>
                    <input type="text" id="notario_numero" name="notario_numero" value="{{ old('notario_numero') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                        placeholder="Ej: 123" aria-label="Número del notario" required>
                </div>
            </div>

            <div class="form-group">
                <label for="numero_registro" class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Registro
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-registered text-gray-500"></i>
                    </div>
                    <input type="text" id="numero_registro" name="numero_registro"
                        value="{{ old('numero_registro') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                        placeholder="Ej: REG-2024-001" aria-label="Número de registro" required>
                </div>
            </div>
        </div>

        <!-- Cuarta fila: Fecha de Inscripción -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group">
                <label for="fecha_inscripcion" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha de Inscripción
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-calendar-check text-gray-500"></i>
                    </div>
                    <input type="date" id="fecha_inscripcion" name="fecha_inscripcion"
                        value="{{ old('fecha_inscripcion') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                        aria-label="Fecha de inscripción" required>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar estados para el select de entidad federativa
            const entidadFederativaSelect = document.getElementById('entidad_federativa');

            if (entidadFederativaSelect) {
                fetch('/api/ubicacion/estados')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.data) {
                            data.data.forEach(estado => {
                                const option = document.createElement('option');
                                option.value = estado.nombre.toUpperCase();
                                option.textContent = estado.nombre;
                                entidadFederativaSelect.appendChild(option);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error al cargar estados:', error);
                    });
            }
        });
    </script>
@endpush
