@props(['tipo' => 'inscripcion', 'proveedor' => null, 'datosSat' => [], 'editable' => true, 'tramite' => null])

@php
    // Obtener el estado de la sección
    $revisionSeccion = null;
    if ($tramite) {
        $revisionSeccion = \App\Models\RevisionSeccion::where('tramite_id', $tramite->id)
            ->where('seccion', 'datos_generales')
            ->first();
    }
    
    $seccionAprobada = $revisionSeccion && $revisionSeccion->aprobado === true;
    $permitirEdicion = $editable && !$seccionAprobada;
    
    // Obtener el RFC para determinar el tipo de persona
    $rfcValue = old('rfc', $datosSat['rfc'] ?? (Auth::user()->rfc ?? ''));
    $rfcValue = strtoupper(trim($rfcValue));
    
    // Si es una corrección y tenemos datos del trámite, usar esos datos
    if ($tramite && $tramite->datosGenerales) {
        $rfcValue = old('rfc', $tramite->datosGenerales->rfc ?? $rfcValue);
        $razonSocial = old('razon_social', $tramite->datosGenerales->razon_social ?? '');
        $curp = old('curp', $tramite->datosGenerales->curp ?? '');
        $paginaWeb = old('pagina_web', $tramite->datosGenerales->pagina_web ?? '');
        $telefono = old('telefono', $tramite->datosGenerales->telefono ?? '');
        $emailContacto = old('email_contacto', $tramite->contactos->first()?->correo_electronico ?? '');
        $cargo = old('cargo', $tramite->contactos->first()?->cargo ?? '');
    } else {
        $razonSocial = old('razon_social', $datosSat['razon_social'] ?? '');
        $curp = old('curp', $datosSat['curp'] ?? '');
        $paginaWeb = old('pagina_web', '');
        $telefono = old('telefono', '');
        $emailContacto = old('email_contacto', '');
        $cargo = old('cargo', '');
    }
    
    // Determinar tipo de persona
    $tipoPersona = $tipoPersona ?? 'Física';
    if ($rfcValue) {
        $tipoPersona = strlen($rfcValue) === 13 ? 'Física' : 'Moral';
    }
    
    $esPersonaMoral = $tipoPersona === 'Moral';
@endphp

<div class="space-y-6" {{ $attributes }} data-seccion="datos_generales">
    <!-- Información del Trámite -->
   
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Información Básica
        </h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Razón Social
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-building text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text" name="razon_social" 
                        value="{{ $razonSocial }}"
                        data-validate="required|minLength:3|maxLength:255"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm {{ $errors->has('razon_social') ? 'error-field border-red-500 bg-red-50' : '' }} {{ !$permitirEdicion ? 'opacity-50 cursor-not-allowed' : '' }}"
                        aria-label="Razón social de la empresa" placeholder="Ingrese la razón social completa"
                        {{ !$permitirEdicion ? 'disabled' : '' }}>
                    @if($errors->has('razon_social'))
                        <div class="error-message text-red-500 text-sm mt-1">{{ $errors->first('razon_social') }}</div>
                    @endif
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

                    <input type="text" 
                               id="rfc" 
                               name="rfc" 
                               value="{{ $rfcValue }}" 
                               data-validate="required|rfc"
                               class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm {{ $errors->has('rfc') ? 'error-field border-red-500 bg-red-50' : '' }} {{ !$permitirEdicion ? 'opacity-50 cursor-not-allowed' : '' }}"
                               placeholder="Ej: XAXX010101000"
                               aria-label="RFC de la empresa"
                               {{ !$permitirEdicion ? 'disabled' : '' }}
                               required>
                    @if($errors->has('rfc'))
                        <div class="error-message text-red-500 text-sm mt-1">{{ $errors->first('rfc') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                    Tipo de Persona
                    <span class="text-[#9d2449]">*</span>
                </label>
                <input type="hidden" name="tipo_persona" value="{{ $tipoPersona }}" required>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user-tag text-gray-500"></i>
                    </div>
                    <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tipoPersona === 'Moral' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                            {{ $tipoPersona === 'Física' ? 'Persona Física' : 'Persona Moral' }}
                        </span>
                    </div>
                </div>
                <p class="mt-1 text-sm text-gray-500">Determinado automáticamente por el RFC</p>
            </div>

            @if($tipoPersona === 'Física')
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                    CURP
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-address-card text-gray-500"></i>
                    </div>
                    <input type="text" name="curp" maxlength="18" readonly
                        value="{{ $curp }}"
                        data-validate="required|curp"
                        class="validate-curp block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg cursor-not-allowed shadow-sm font-mono"
                        placeholder="Ej: ABCD123456HDFGHI01" aria-label="CURP de la persona física"
                        style="text-transform: uppercase;">
                </div>
            </div>
            @endif

            <div class="form-group field-container {{ $tipoPersona === 'Física' ? '' : 'md:col-span-2' }}">
                <label class="block text-sm font-medium text-gray-700 mb-2 field-label">Página Web</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-globe text-gray-500"></i>
                    </div>
                    <input type="url" name="pagina_web"
                        value="{{ $paginaWeb }}" data-validate="url"
                        placeholder="https://www.ejemplo.com"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm {{ !$permitirEdicion ? 'opacity-50 cursor-not-allowed' : '' }}"
                        aria-label="Página web de la empresa"
                        {{ !$permitirEdicion ? 'disabled' : '' }}>
                </div>
            </div>


            </div>
        </div>

        <div>
            <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
                Información de Contacto</h4>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2 field-label">Cargo</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-briefcase text-gray-500"></i>
                        </div>
                        <input type="text" name="cargo" value="{{ $cargo }}"
                            data-validate="minLength:2|maxLength:100"
                            placeholder="Ej: Director General, Representante Legal"
                            class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm {{ !$permitirEdicion ? 'opacity-50 cursor-not-allowed' : '' }}"
                            aria-label="Cargo del representante"
                            {{ !$permitirEdicion ? 'disabled' : '' }}>
                    </div>
                </div>

                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                        Correo Electrónico
                        <span class="text-[#9d2449]">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-500"></i>
                        </div>
                        <input type="email" name="email_contacto" required
                            value="{{ $emailContacto }}"
                            data-validate="required|email" placeholder="ejemplo@correo.com"
                            class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm {{ $errors->has('email_contacto') ? 'error-field border-red-500 bg-red-50' : '' }} {{ !$permitirEdicion ? 'opacity-50 cursor-not-allowed' : '' }}"
                            data-error="{{ $errors->first('email_contacto') }}"
                            aria-label="Correo electrónico de contacto"
                            {{ !$permitirEdicion ? 'disabled' : '' }}>
                        @if($errors->has('email_contacto'))
                            <div class="error-message text-red-500 text-sm mt-1">{{ $errors->first('email_contacto') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-group field-container md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                        Teléfono
                        <span class="text-[#9d2449]">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-phone text-gray-500"></i>
                        </div>
                        <input type="tel" name="telefono" required
                            value="{{ $telefono }}" data-validate="required|phone"
                            class="validate-phone block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm {{ !$permitirEdicion ? 'opacity-50 cursor-not-allowed' : '' }}"
                            placeholder="Ej: 5551234567 (10 dígitos)" aria-label="Número de teléfono" maxlength="10"
                            {{ !$permitirEdicion ? 'disabled' : '' }}>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($tramite)
    @include('tramites.partials.estado-seccion', ['seccion' => 'datos_generales', 'tramite' => $tramite])
@endif
