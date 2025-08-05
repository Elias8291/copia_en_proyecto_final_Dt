@props(['datos' => [], 'editable' => false, 'datosConstancia' => null])

@php
    // Usar view model si está disponible
    if ($datosConstancia instanceof \App\ViewModels\TramiteViewModel) {
        $datosFinales = $datosConstancia->getDatosGenerales($datos);
        $camposNoEditables = !$datosConstancia->sonCamposEditables();
        $tipoPersona = $datosConstancia->determinarTipoPersona($datosFinales['rfc'] ?? '');
    } elseif ($datosConstancia instanceof \App\ViewModels\FormDataViewModel) {
        // Para FormDataViewModel (datos de revisión)
        $datosFinales = $datosConstancia->getDatosGenerales();
        $camposNoEditables = !$editable;
        $tipoPersona = $datosFinales['tipo_persona'] ?? 'Física';
    } else {
        // Fallback para compatibilidad
        $datosFinales = $datosConstancia ? [
            'razon_social' => $datosConstancia['razon_social'] ?? ($datos['razon_social'] ?? ''),
            'rfc' => $datosConstancia['rfc'] ?? ($datos['rfc'] ?? ''),
            'tipo_persona' => $datosConstancia['tipo_persona'] ?? ($datos['tipo_persona'] ?? ''),
            'curp' => $datosConstancia['curp'] ?? ($datos['curp'] ?? ''),
        ] : $datos;
        
        $rfcValue = $datosFinales['rfc'] ?? '';
        $tipoPersona = 'Física';
        if ($rfcValue) {
            $rfcService = app(\App\Services\RfcProveedorService::class);
            $tipoPersona = $rfcService->determinarTipoPersona($rfcValue);
        }
        
        $camposNoEditables = $datosConstancia ? true : false;
    }
    
    // Obtener valores para los campos, priorizando los valores de constancia cuando están bloqueados
    $razonSocial = $camposNoEditables ? ($datosFinales['razon_social'] ?? '') : (old('razon_social') ?: ($datosFinales['razon_social'] ?? ''));
    $rfc = $camposNoEditables ? ($datosFinales['rfc'] ?? '') : (old('rfc') ?: ($datosFinales['rfc'] ?? ''));
    $curp = $camposNoEditables ? ($datosFinales['curp'] ?? '') : (old('curp') ?: ($datosFinales['curp'] ?? ''));
    $tipoPersonaValue = $camposNoEditables ? $tipoPersona : (old('tipo_persona') ?: $tipoPersona);
@endphp

<div class="space-y-6" {{ $attributes }}>
    <div class="flex items-center space-x-3 mb-6">
        <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Datos Generales</h3>
            <p class="text-sm text-gray-500">Información personal y de contacto</p>
        </div>
    </div>
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Información Básica
        </h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Razón Social <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-building text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text"
                        name="razon_social"
                        value="{{ $razonSocial }}"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ ($editable && !$camposNoEditables) ? 'bg-white' : 'bg-gray-50 cursor-not-allowed' }} {{ $errors->has('razon_social') ? 'border-red-500' : '' }}"
                        {{ (!$editable || $camposNoEditables) ? 'disabled' : '' }}
                        placeholder="Ingrese la razón social">
                    @if($camposNoEditables)
                        <input type="hidden" name="razon_social_hidden" value="{{ $razonSocial }}">
                    @endif
                </div>
                @error('razon_social')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    RFC <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-id-card text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text"
                        name="rfc"
                        value="{{ $rfc }}"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary font-mono {{ ($editable && !$camposNoEditables) ? 'bg-white' : 'bg-gray-50 cursor-not-allowed' }} {{ $errors->has('rfc') ? 'border-red-500' : '' }}"
                        {{ (!$editable || $camposNoEditables) ? 'disabled' : '' }}
                        placeholder="Ingrese el RFC">
                    @if($camposNoEditables)
                        <input type="hidden" name="rfc_hidden" value="{{ $rfc }}">
                    @endif
                </div>
                @error('rfc')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                    Tipo de Persona <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user-tag text-gray-500"></i>
                    </div>
                    <select name="tipo_persona"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ ($editable && !$camposNoEditables) ? 'bg-white' : 'bg-gray-50 cursor-not-allowed' }} {{ $errors->has('tipo_persona') ? 'border-red-500' : '' }}"
                        {{ (!$editable || $camposNoEditables) ? 'disabled' : '' }}>
                        <option value="">Seleccione tipo</option>
                        <option value="Física" {{ $tipoPersonaValue == 'Física' ? 'selected' : '' }}>Persona Física</option>
                        <option value="Moral" {{ $tipoPersonaValue == 'Moral' ? 'selected' : '' }}>Persona Moral</option>
                    </select>
                    @if($camposNoEditables)
                        <input type="hidden" name="tipo_persona_hidden" value="{{ $tipoPersonaValue }}">
                    @endif
                </div>
                @error('tipo_persona')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group field-container" id="curp-field" style="display: none;">
                <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                    CURP
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-address-card text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="curp"
                        value="{{ $curp }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary font-mono {{ ($editable && !$camposNoEditables) ? 'bg-white' : 'bg-gray-50 cursor-not-allowed' }} {{ $errors->has('curp') ? 'border-red-500' : '' }}"
                        {{ (!$editable || $camposNoEditables) ? 'disabled' : '' }}
                        placeholder="Ingrese la CURP">
                    @if($camposNoEditables)
                        <input type="hidden" name="curp_hidden" value="{{ $curp }}">
                    @endif
                </div>
                @error('curp')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2 field-label">Página Web</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-globe text-gray-500"></i>
                    </div>
                    <input type="url"
                        name="pagina_web"
                        value="{{ $datos['pagina_web'] ?? old('pagina_web') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $editable ? 'bg-white' : 'bg-gray-50 cursor-not-allowed' }} {{ $errors->has('pagina_web') ? 'border-red-500' : '' }}"
                        {{ !$editable ? 'disabled' : '' }}
                        placeholder="https://ejemplo.com">
                </div>
                @error('pagina_web')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Información de Contacto del Proveedor
        </h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                    Teléfono del Proveedor <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-phone text-gray-500"></i>
                    </div>
                    <input type="tel"
                        name="telefono"
                        value="{{ $datosFinales['telefono'] ?? old('telefono') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $editable ? 'bg-white' : 'bg-gray-50 cursor-not-allowed' }} {{ $errors->has('telefono') ? 'border-red-500' : '' }}"
                        {{ !$editable ? 'disabled' : '' }}
                        placeholder="(55) 1234-5678">
                </div>
                @error('telefono')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Información del Contacto Principal
        </h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                    Nombre del Contacto <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="nombre_contacto"
                        value="{{ $datosFinales['nombre_contacto'] ?? old('nombre_contacto') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $editable ? 'bg-white' : 'bg-gray-50 cursor-not-allowed' }} {{ $errors->has('nombre_contacto') ? 'border-red-500' : '' }}"
                        {{ !$editable ? 'disabled' : '' }}
                        placeholder="Nombre completo del contacto">
                </div>
                @error('nombre_contacto')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                    Cargo del Contacto <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-briefcase text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="cargo"
                        value="{{ $datosFinales['cargo'] ?? old('cargo') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $editable ? 'bg-white' : 'bg-gray-50 cursor-not-allowed' }} {{ $errors->has('cargo') ? 'border-red-500' : '' }}"
                        {{ !$editable ? 'disabled' : '' }}
                        placeholder="Ej: Gerente, Director, etc.">
                </div>
                @error('cargo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                    Teléfono del Contacto <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-phone text-gray-500"></i>
                    </div>
                    <input type="tel"
                        name="telefono_contacto"
                        value="{{ $datosFinales['telefono_contacto'] ?? old('telefono_contacto') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $editable ? 'bg-white' : 'bg-gray-50 cursor-not-allowed' }} {{ $errors->has('telefono_contacto') ? 'border-red-500' : '' }}"
                        {{ !$editable ? 'disabled' : '' }}
                        placeholder="(55) 1234-5678">
                </div>
                @error('telefono_contacto')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                    Correo del Contacto <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-500"></i>
                    </div>
                    <input type="email"
                        name="correo_contacto"
                        value="{{ $datosFinales['correo_contacto'] ?? old('correo_contacto') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $editable ? 'bg-white' : 'bg-gray-50 cursor-not-allowed' }} {{ $errors->has('correo_contacto') ? 'border-red-500' : '' }}"
                        {{ !$editable ? 'disabled' : '' }}
                        placeholder="contacto@ejemplo.com">
                </div>
                @error('correo_contacto')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>

@if($editable)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoPersonaSelect = document.querySelector('select[name="tipo_persona"]');
    const curpField = document.getElementById('curp-field');
    
    if (tipoPersonaSelect && curpField) {
        function toggleCurpField() {
            if (tipoPersonaSelect.value === 'Física') {
                curpField.style.display = 'block';
            } else {
                curpField.style.display = 'none';
            }
        }
        
        // Solo agregar event listener si el campo es editable
        if (!tipoPersonaSelect.disabled) {
            tipoPersonaSelect.addEventListener('change', toggleCurpField);
        }
        
        // Ejecutar una vez al cargar para mostrar/ocultar CURP
        toggleCurpField();
    }
});
</script>
@endif 