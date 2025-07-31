@props(['tipo' => 'inscripcion', 'proveedor' => null, 'editable' => true, 'tramite' => null])

@php
    // Si es una corrección y tenemos datos del trámite, obtener los datos del apoderado existente
    $apoderado = null;
    $instrumentoNotarial = null;
    if ($tramite && $tramite->apoderadoLegal) {
        $apoderado = $tramite->apoderadoLegal;
        if ($apoderado->instrumentoNotarial) {
            $instrumentoNotarial = $apoderado->instrumentoNotarial;
        }
    }
    
    // Valores para los campos
    $apoderadoNombre = old('apoderado_nombre', $apoderado?->nombre_apoderado ?? '');
    $apoderadoRfc = old('apoderado_rfc', $apoderado?->rfc ?? '');
    $poderNumeroEscritura = old('poder_numero_escritura', $instrumentoNotarial?->numero_escritura ?? '');
    $poderFechaConstitucion = old('poder_fecha_constitucion', $instrumentoNotarial?->fecha_constitucion ? $instrumentoNotarial->fecha_constitucion->format('Y-m-d') : '');
    $poderNotarioNombre = old('poder_notario_nombre', $instrumentoNotarial?->nombre_notario ?? '');
    $poderEntidadFederativa = old('poder_entidad_federativa', $instrumentoNotarial?->entidad_federativa ?? '');
    $poderNotarioNumero = old('poder_notario_numero', $instrumentoNotarial?->numero_notario ?? '');
    $poderNumeroRegistro = old('poder_numero_registro', $instrumentoNotarial?->numero_registro_publico ?? '');
@endphp

<div class="space-y-6" {{ $attributes }} data-seccion="apoderado">
    <!-- Información del Trámite -->
   
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Información del Apoderado Legal
        </h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Nombre Completo
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-user text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text" id="apoderado_nombre" name="apoderado_nombre" 
                           value="{{ $apoderadoNombre }}"
                           data-validate="minLength:3|maxLength:255"
                           class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 {{ $errors->has('apoderado_nombre') ? 'border-red-500 bg-red-50' : '' }}"
                           placeholder="Nombre completo del apoderado legal"
                           aria-label="Nombre completo del apoderado">
                                   @error('apoderado_nombre')
                   <div class="mt-2 flex items-center text-red-600">
                       <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                           <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                       </svg>
                       <span class="text-sm font-medium">{{ $message }}</span>
                   </div>
               @enderror
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    RFC
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-id-card text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text" id="apoderado_rfc" name="apoderado_rfc" 
                           value="{{ $apoderadoRfc }}"
                           data-validate="rfc"
                           class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 font-mono {{ $errors->has('apoderado_rfc') ? 'border-red-500 bg-red-50' : '' }}"
                           placeholder="RFC del apoderado"
                           maxlength="13"
                           pattern="[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{3}"
                           aria-label="RFC del apoderado"
                           style="text-transform: uppercase;">
                                   @error('apoderado_rfc')
                   <div class="mt-2 flex items-center text-red-600">
                       <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                           <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                       </svg>
                       <span class="text-sm font-medium">{{ $message }}</span>
                   </div>
               @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Poder Notarial -->
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Información del Poder Notarial
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
                    <input type="text" id="poder_numero_escritura" name="poder_numero_escritura" 
                           value="{{ $poderNumeroEscritura }}"
                           data-validate="minLength:3|maxLength:255"
                           class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 {{ $errors->has('poder_numero_escritura') ? 'border-red-500 bg-red-50' : '' }}"
                           placeholder="Número de escritura del poder"
                           aria-label="Número de escritura del poder">
                                   @error('poder_numero_escritura')
                   <div class="mt-2 flex items-center text-red-600">
                       <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                           <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                       </svg>
                       <span class="text-sm font-medium">{{ $message }}</span>
                   </div>
               @enderror
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
                    <input type="date" id="poder_fecha_constitucion" name="poder_fecha_constitucion" 
                           value="{{ $poderFechaConstitucion }}"
                           class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 {{ $errors->has('poder_fecha_constitucion') ? 'border-red-500 bg-red-50' : '' }}"
                           aria-label="Fecha de constitución del poder">
                                   @error('poder_fecha_constitucion')
                   <div class="mt-2 flex items-center text-red-600">
                       <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                           <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                       </svg>
                       <span class="text-sm font-medium">{{ $message }}</span>
                   </div>
               @enderror
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
                    <input type="text" id="poder_notario_nombre" name="poder_notario_nombre" 
                           value="{{ $poderNotarioNombre }}"
                           data-validate="minLength:3|maxLength:255"
                           class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 {{ $errors->has('poder_notario_nombre') ? 'border-red-500 bg-red-50' : '' }}"
                           placeholder="Nombre completo del notario"
                           aria-label="Nombre del notario">
                                   @error('poder_notario_nombre')
                   <div class="mt-2 flex items-center text-red-600">
                       <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                           <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                       </svg>
                       <span class="text-sm font-medium">{{ $message }}</span>
                   </div>
               @enderror
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
                    <select id="poder_entidad_federativa" name="poder_entidad_federativa" 
                            class="block w-full pl-8 pr-10 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-10 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 appearance-none {{ $errors->has('poder_entidad_federativa') ? 'border-red-500 bg-red-50' : '' }}"
                            aria-label="Entidad federativa del notario">
                        <option value="">Seleccione la entidad federativa</option>
                        <!-- Estados se cargarán dinámicamente -->
                    </select>
                                   @error('poder_entidad_federativa')
                   <div class="mt-2 flex items-center text-red-600">
                       <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                           <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                       </svg>
                       <span class="text-sm font-medium">{{ $message }}</span>
                   </div>
               @enderror
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
                    <input type="text" id="poder_notario_numero" name="poder_notario_numero" 
                           value="{{ $poderNotarioNumero }}"
                           data-validate="minLength:1|maxLength:10"
                           class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 {{ $errors->has('poder_notario_numero') ? 'border-red-500 bg-red-50' : '' }}"
                           placeholder="Ej: 123"
                           aria-label="Número del notario">
                                   @error('poder_notario_numero')
                   <div class="mt-2 flex items-center text-red-600">
                       <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                           <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                       </svg>
                       <span class="text-sm font-medium">{{ $message }}</span>
                   </div>
               @enderror
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
                    <input type="text" id="poder_numero_registro" name="poder_numero_registro" 
                           value="{{ $poderNumeroRegistro }}"
                           data-validate="minLength:3|maxLength:255"
                           class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 {{ $errors->has('poder_numero_registro') ? 'border-red-500 bg-red-50' : '' }}"
                           placeholder="Ej: REG-2024-001"
                           aria-label="Número de registro">
                                   @error('poder_numero_registro')
                   <div class="mt-2 flex items-center text-red-600">
                       <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                           <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                       </svg>
                       <span class="text-sm font-medium">{{ $message }}</span>
                   </div>
               @enderror
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar estados para el select de entidad federativa del poder
            const poderEntidadFederativaSelect = document.getElementById('poder_entidad_federativa');
            const entidadFederativaValue = '{{ $poderEntidadFederativa }}';
            
            if (poderEntidadFederativaSelect) {
                console.log('Valor de entidad federativa a seleccionar:', entidadFederativaValue);
                
                fetch('/api/ubicacion/estados')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.data) {
                            data.data.forEach(estado => {
                                const option = document.createElement('option');
                                option.value = estado.nombre.toUpperCase();
                                option.textContent = estado.nombre;
                                poderEntidadFederativaSelect.appendChild(option);
                            });
                            
                            // Seleccionar la entidad federativa correcta DESPUÉS de cargar las opciones
                            if (entidadFederativaValue) {
                                console.log('Intentando seleccionar:', entidadFederativaValue);
                                for (let option of poderEntidadFederativaSelect.options) {
                                    console.log('Opción disponible:', option.value, 'vs', entidadFederativaValue);
                                    if (option.value === entidadFederativaValue) {
                                        option.selected = true;
                                        console.log('¡Seleccionado correctamente!');
                                        break;
                                    }
                                }
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error al cargar estados:', error);
                    });
            }
        });
    </script>
@endpush

@if($tramite)
    @include('tramites.partials.estado-seccion', ['seccion' => 'apoderado', 'tramite' => $tramite, 'editable' => $editable])
@endif