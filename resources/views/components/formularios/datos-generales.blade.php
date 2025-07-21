@props(['tipo' => 'inscripcion', 'proveedor' => null, 'datosSat' => [], 'editable' => true])

@php
    $tipoPersona = $proveedor->tipo_persona ?? 'Física';
@endphp

<div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8" {{ $attributes }}>
    <!-- Encabezado con icono -->
    <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-100">
        <div class="flex items-center space-x-4">
            <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
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
            <div class="form-group">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Razón Social
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-building text-gray-500"></i>
                    </div>
                                        <input type="text" name="razon_social" {{ $editable ? 'required' : 'readonly' }}
                           value="{{ old('razon_social', $datosSat['razon_social'] ?? ($proveedor->razon_social ?? '')) }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 {{ $editable ? 'bg-white' : 'bg-gray-50' }} border border-gray-200 rounded-lg {{ $editable ? 'focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50' : 'cursor-not-allowed' }} shadow-sm"
                           aria-label="Razón social de la empresa">
                </div>
            </div>

            <div class="form-group">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    RFC
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-id-card text-gray-500"></i>
                    </div>
                    <input type="text" name="rfc" required readonly
                        value="{{ old('rfc', $datosSat['rfc'] ?? ($proveedor->rfc ?? '')) }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-600 bg-gray-50 border border-gray-200 rounded-lg cursor-not-allowed shadow-sm"
                        aria-label="RFC de la empresa">
                </div>
                <p class="mt-1 text-sm text-gray-500">El RFC no puede modificarse</p>
            </div>

            <div class="form-group">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Persona
                    <span class="text-[#9d2449]">*</span>
                </label>
                <input type="hidden" name="tipo_persona" value="{{ $tipoPersona }}">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user-tag text-gray-500"></i>
                    </div>
                    <div class="block w-full pl-10 pr-4 py-2.5 text-gray-600 bg-gray-50 border border-gray-200 rounded-lg flex items-center shadow-sm">
                        <span>{{ $tipoPersona === 'Física' ? 'Persona Física' : 'Persona Moral' }}</span>
                        <svg class="w-4 h-4 ml-auto text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <p class="mt-1 text-sm text-gray-500">Determinado automáticamente por el RFC</p>
            </div>

            @if ($tipoPersona === 'Física')
                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">CURP</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-address-card text-gray-500"></i>
                        </div>
                        <input type="text" name="curp" maxlength="18"
                            value="{{ old('curp', $datosSat['curp'] ?? ($proveedor->curp ?? '')) }}"
                            class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                            placeholder="18 caracteres"
                            aria-label="CURP de la persona física">
                    </div>
                </div>
            @endif

            <div class="form-group {{ $tipoPersona === 'Física' ? '' : 'md:col-span-2' }}">
                <label class="block text-sm font-medium text-gray-700 mb-2">Página Web</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-globe text-gray-500"></i>
                    </div>
                    <input type="url" name="pagina_web"
                        value="{{ old('pagina_web', $proveedor->pagina_web ?? '') }}"
                        placeholder="https://www.ejemplo.com"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                        aria-label="Página web de la empresa">
                </div>
            </div>

            <!-- Actividades Económicas -->
            <div class="form-group md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Actividades Económicas
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="space-y-4">
                    <!-- Buscador de actividades -->
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-500"></i>
                        </div>
                        <input type="text" id="buscador-actividad"
                            placeholder="Buscar actividad económica..."
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
                </div>
            </div>
        </div>
    </div>

    <div>
        <h4 class="text-base font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">
            Información de Contacto</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cargo</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-briefcase text-gray-500"></i>
                    </div>
                    <input type="text" name="cargo"
                        value="{{ old('cargo', $proveedor->cargo ?? '') }}"
                        placeholder="Ej: Director General, Representante Legal"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                        aria-label="Cargo del representante">
                </div>
            </div>

            <div class="form-group">
                <label class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-500"></i>
                    </div>
                    <input type="email" name="email_contacto"
                        value="{{ old('email_contacto', $proveedor->email_contacto ?? '') }}"
                        placeholder="ejemplo@correo.com"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                        aria-label="Correo electrónico de contacto">
                </div>
            </div>

            <div class="form-group md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-phone text-gray-500"></i>
                    </div>
                    <input type="tel" name="telefono"
                        value="{{ old('telefono', $proveedor->telefono ?? '') }}"
                        placeholder="Ej: 555-123-4567"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                        aria-label="Número de teléfono">
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

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