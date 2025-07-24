@props(['tipo' => 'inscripcion', 'proveedor' => null, 'datosSat' => [], 'editable' => true])

@php
    $tipoPersona = $proveedor->tipo_persona ?? 'Física';
@endphp

<div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8" {{ $attributes }}>
    <!-- Encabezado con icono -->
    <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-100">
        <div class="flex items-center space-x-4">
            <div
                class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <i class="fas fa-user-tie text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Datos Generales</h2>
                <p class="text-sm text-gray-500 mt-1">Información básica y de contacto de la empresa</p>
            </div>
        </div>
    </div>

    <div class="space-y-8">
        <div>
            <h4 class="text-base font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">
                Información Básica</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                        Razón Social
                        <span class="text-[#9d2449]">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-building text-gray-500"></i>
                        </div>
                        <input type="text" name="razon_social" {{ $editable ? 'required' : 'readonly' }}
                            value="{{ old('razon_social', $datosSat['razon_social'] ?? ($proveedor->razon_social ?? '')) }}"
                            data-validate="required|minLength:3|maxLength:255"
                            class="block w-full pl-10 pr-4 py-2.5 text-gray-700 {{ $editable ? 'bg-white' : 'bg-gray-50' }} border border-gray-200 rounded-lg {{ $editable ? 'focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50' : 'cursor-not-allowed' }} shadow-sm"
                            aria-label="Razón social de la empresa" placeholder="Ingrese la razón social completa">
                    </div>
                </div>

                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                        RFC
                        <span class="text-[#9d2449]">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-id-card text-gray-500"></i>
                        </div>
                        @php
                            $rfcValue = old('rfc', $datosSat['rfc'] ?? (Auth::user()->rfc ?? ''));
                            // Limpiar RFC de espacios y convertir a mayúsculas
                            $rfcValue = strtoupper(trim($rfcValue));
                        @endphp
                        <input type="text" name="rfc" required value="{{ $rfcValue }}"
                            data-validate="required|rfc|rfc-persona"
                            class="validate-rfc block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                            aria-label="RFC de la empresa" placeholder="Ej: ABC123456789 (12) o ABCD123456789 (13)"
                            maxlength="13" pattern="[A-ZÑ&]{3,4}[0-9]{6}[A-V1-9A-Z0-9]{3}"
                            style="text-transform: uppercase;">
                    </div>
                    @if (!empty($rfcValue))
                        <p class="mt-1 text-sm text-green-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            RFC cargado: {{ $rfcValue }} ({{ strlen($rfcValue) }} caracteres) -
                            {{ strlen($rfcValue) === 12 ? 'Persona Moral' : (strlen($rfcValue) === 13 ? 'Persona Física' : 'Longitud incorrecta') }}
                        </p>
                    @else
                        <p class="mt-1 text-sm text-amber-600">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Ejemplos: <strong>ABC123456789</strong> (Moral) o <strong>ABCD123456789</strong> (Física)
                        </p>
                    @endif
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
                        <div
                            class="block w-full pl-10 pr-4 py-2.5 text-gray-600 bg-gray-50 border border-gray-200 rounded-lg flex items-center shadow-sm">
                            <span
                                data-tipo-persona>{{ $tipoPersona === 'Física' ? 'Persona Física' : 'Persona Moral' }}</span>
                            <svg class="w-4 h-4 ml-auto text-[#9d2449]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Determinado automáticamente por el RFC</p>
                </div>

                @if ($tipoPersona === 'Física')
                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                            CURP
                            <span class="text-[#9d2449]">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-address-card text-gray-500"></i>
                            </div>
                            <input type="text" name="curp" maxlength="18" required
                                value="{{ old('curp', $datosSat['curp'] ?? ($proveedor->curp ?? '')) }}"
                                data-validate="required|curp"
                                class="validate-curp block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
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
                            value="{{ old('pagina_web', $proveedor->pagina_web ?? '') }}" data-validate="url"
                            placeholder="https://www.ejemplo.com"
                            class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                            aria-label="Página web de la empresa">
                    </div>
                </div>

                <!-- Actividades Económicas -->
                <div class="form-group field-container md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                        Actividades Económicas
                        <span class="text-[#9d2449]">*</span>
                    </label>
                    <div class="space-y-4">
                        <!-- Buscador de actividades -->
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-500"></i>
                            </div>
                            <input type="text" id="buscador-actividad" placeholder="Buscar actividad económica..."
                                class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                                aria-label="Buscar actividades económicas">

                            <!-- Resultados de búsqueda -->
                            <div id="resultados-actividades"
                                class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden">
                                <!-- Los resultados se cargarán aquí via JavaScript -->
                            </div>
                        </div>

                        <!-- Actividades seleccionadas -->
                        <div id="actividades-seleccionadas" class="space-y-2">
                            <p class="text-sm text-gray-500">No se han seleccionado actividades económicas</p>
                        </div>

                        <!-- Campo hidden para validación de actividades -->
                        <input type="hidden" name="actividades_validation" id="actividades-validation"
                            data-validate="actividades" required class="validate-actividades">

                        <!-- Campos hidden para actividades (se llenan via JavaScript) -->
                        <div id="actividades-hidden-inputs">
                            <!-- Los inputs hidden se agregarán aquí dinámicamente por JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h4 class="text-base font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">
                Información de Contacto</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2 field-label">Cargo</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-briefcase text-gray-500"></i>
                        </div>
                        <input type="text" name="cargo" value="{{ old('cargo', $proveedor->cargo ?? '') }}"
                            data-validate="minLength:2|maxLength:100"
                            placeholder="Ej: Director General, Representante Legal"
                            class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                            aria-label="Cargo del representante">
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
                            value="{{ old('email_contacto', $proveedor->email_contacto ?? '') }}"
                            data-validate="required|email" placeholder="ejemplo@correo.com"
                            class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                            aria-label="Correo electrónico de contacto">
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
                            value="{{ old('telefono', $proveedor->telefono ?? '') }}" data-validate="required|phone"
                            class="validate-phone block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                            placeholder="Ej: 5551234567 (10 dígitos)" aria-label="Número de teléfono" maxlength="10">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
