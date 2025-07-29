@props(['tipo' => 'inscripcion', 'proveedor' => null, 'editable' => true, 'tramite' => null])

@php
    // Si es una corrección y tenemos datos del trámite, obtener los datos constitutivos existentes
    $instrumentoNotarial = null;
    if ($tramite && $tramite->datosConstitutivos && $tramite->datosConstitutivos->instrumentoNotarial) {
        $instrumentoNotarial = $tramite->datosConstitutivos->instrumentoNotarial;
    }
    
    // Valores para los campos
    $numeroEscritura = old('numero_escritura', $instrumentoNotarial?->numero_escritura ?? '');
    $fechaConstitucion = old('fecha_constitucion', $instrumentoNotarial?->fecha_constitucion ? $instrumentoNotarial->fecha_constitucion->format('Y-m-d') : '');
    $notarioNombre = old('notario_nombre', $instrumentoNotarial?->nombre_notario ?? '');
    $entidadFederativa = old('entidad_federativa', $instrumentoNotarial?->entidad_federativa ?? '');
    $notarioNumero = old('notario_numero', $instrumentoNotarial?->numero_notario ?? '');
    $numeroRegistro = old('numero_registro', $instrumentoNotarial?->numero_registro_publico ?? '');
    $fechaInscripcion = old('fecha_inscripcion', $instrumentoNotarial?->fecha_inscripcion ? $instrumentoNotarial->fecha_inscripcion->format('Y-m-d') : '');
@endphp

<div class="space-y-6" {{ $attributes }} data-seccion="constitucion">
    <!-- Información del Trámite -->
   
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Información Constitutiva
        </h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Número de Escritura
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-file-contract text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text" id="numero_escritura" name="numero_escritura"
                        value="{{ $numeroEscritura }}"
                        data-validate="required|minLength:3|maxLength:255"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50"
                        placeholder="Ej: 12345" aria-label="Número de escritura constitutiva" required>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Fecha de Constitución
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-calendar-alt text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="date" id="fecha_constitucion" name="fecha_constitucion"
                        value="{{ $fechaConstitucion }}"
                        data-validate="required"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50"
                        aria-label="Fecha de constitución de la empresa" required>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Nombre del Notario
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-user-tie text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text" id="notario_nombre" name="notario_nombre" value="{{ $notarioNombre }}"
                        data-validate="required|minLength:3|maxLength:255"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50"
                        placeholder="Nombre completo del notario" aria-label="Nombre del notario" required>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Entidad Federativa
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-map-marked-alt text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <select id="entidad_federativa" name="entidad_federativa"
                        data-validate="required"
                        class="block w-full pl-8 pr-10 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-10 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 appearance-none"
                        aria-label="Entidad federativa del notario" required>
                        <option value="">Seleccione la entidad federativa</option>
                        <!-- Estados se cargarán dinámicamente -->
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Número de Notario
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-hashtag text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text" id="notario_numero" name="notario_numero" value="{{ $notarioNumero }}"
                        data-validate="required|minLength:1|maxLength:10"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50"
                        placeholder="Ej: 123" aria-label="Número del notario" required>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Número de Registro
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-registered text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text" id="numero_registro" name="numero_registro"
                        value="{{ $numeroRegistro }}"
                        data-validate="required|minLength:3|maxLength:255"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50"
                        placeholder="Ej: REG-2024-001" aria-label="Número de registro" required>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Fecha de Inscripción
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-calendar-check text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="date" id="fecha_inscripcion" name="fecha_inscripcion"
                        value="{{ $fechaInscripcion }}"
                        data-validate="required"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50"
                        aria-label="Fecha de inscripción" required>
                </div>
            </div>
        </div>
    </div>
</div>

@if($tramite)
    @include('tramites.partials.estado-seccion', ['seccion' => 'constitucion', 'tramite' => $tramite])
@endif

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
