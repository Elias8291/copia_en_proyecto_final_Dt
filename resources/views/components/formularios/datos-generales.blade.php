@props(['tipo' => 'inscripcion', 'proveedor' => null, 'datosSat' => []])

<div class="space-y-6">
    <!-- Tipo de Proveedor -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-3">Tipo de Proveedor <span
                class="text-red-500">*</span></label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <label class="relative cursor-pointer">
                <input type="radio" name="tipo_proveedor" value="fisica" class="peer sr-only" required
                    {{ $proveedor && strtolower($proveedor->tipo_persona) === 'física' ? 'checked' : '' }}
                    {{ $proveedor ? 'disabled' : '' }}
                    {{ !$proveedor && strtolower($datosSat['tipo_persona'] ?? '') === 'física' ? 'checked' : '' }}>
                <div
                    class="p-4 border-2 rounded-lg transition-all duration-200 border-gray-200 peer-checked:border-[#9d2449] peer-checked:bg-[#9d2449]/5 hover:border-[#9d2449]/50">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-[#9d2449]/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user text-[#9d2449]"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Persona Física</h3>
                            <p class="text-sm text-gray-600">Empresario individual</p>
                        </div>
                    </div>
                </div>
            </label>
            <label class="relative cursor-pointer">
                <input type="radio" name="tipo_proveedor" value="moral" class="peer sr-only" required
                    {{ $proveedor && strtolower($proveedor->tipo_persona) === 'moral' ? 'checked' : '' }}
                    {{ $proveedor ? 'disabled' : '' }}
                    {{ !$proveedor && strtolower($datosSat['tipo_persona'] ?? '') === 'moral' ? 'checked' : '' }}>
                <div
                    class="p-4 border-2 rounded-lg transition-all duration-200 border-gray-200 peer-checked:border-[#9d2449] peer-checked:bg-[#9d2449]/5 hover:border-[#9d2449]/50">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-[#9d2449]/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-building text-[#9d2449]"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Persona Moral</h3>
                            <p class="text-sm text-gray-600">Empresa o corporación</p>
                        </div>
                    </div>
                </div>
            </label>
        </div>
    </div>

    <!-- Información básica -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- RFC -->
        <div>
            <label for="rfc" class="block text-sm font-medium text-gray-700 mb-2">RFC <span
                    class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-id-card text-gray-400"></i>
                </div>
                <input type="text" id="rfc" name="rfc"
                    value="{{ $proveedor?->rfc ?? ($datosSat['rfc'] ?? old('rfc')) }}"
                    class="w-full pl-10 pr-3 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#9d2449] focus:border-transparent focus:bg-white transition-all duration-200"
                    placeholder="Ej: XAXX010101000" maxlength="13" {{ $proveedor ? 'readonly' : '' }} required>
            </div>
            <p class="text-xs text-gray-500 mt-1" id="rfc-help">RFC sin espacios ni guiones</p>
        </div>

        <!-- Razón Social / Nombre -->
        <div>
            <label for="razon_social" class="block text-sm font-medium text-gray-700 mb-2">
                <span
                    class="razon-social-label">{{ $proveedor?->tipo_persona === 'Física' ? 'Nombre Completo' : 'Razón Social' }}</span>
                <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-building text-gray-400"></i>
                </div>
                <input type="text" id="razon_social" name="razon_social"
                    value="{{ $proveedor?->razon_social ?? ($datosSat['razon_social'] ?? old('razon_social')) }}"
                    class="w-full pl-10 pr-3 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#9d2449] focus:border-transparent focus:bg-white transition-all duration-200"
                    placeholder="Ingrese la razón social o nombre completo" required>
            </div>
        </div>
    </div>

    <!-- Información de contacto -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Teléfono -->
        <div>
            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">Teléfono <span
                    class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-phone text-gray-400"></i>
                </div>
                <input type="tel" id="telefono" name="telefono" value="{{ old('telefono') }}"
                    class="w-full pl-10 pr-3 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#9d2449] focus:border-transparent focus:bg-white transition-all duration-200"
                    placeholder="Ej: 5551234567" pattern="[0-9]{10}" maxlength="10" required>
            </div>
            <p class="text-xs text-gray-500 mt-1">10 dígitos sin espacios</p>
        </div>

        <!-- Email -->
        <div>
            <label for="email_contacto" class="block text-sm font-medium text-gray-700 mb-2">Email de Contacto <span
                    class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <input type="email" id="email_contacto" name="email_contacto" value="{{ old('email_contacto') }}"
                    class="w-full pl-10 pr-3 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#9d2449] focus:border-transparent focus:bg-white transition-all duration-200"
                    placeholder="ejemplo@empresa.com" required>
            </div>
        </div>
    </div>

    <!-- CURP (solo para Persona Física) -->
    <div id="curp-section"
        class="grid grid-cols-1 md:grid-cols-2 gap-6 {{ $proveedor?->tipo_persona === 'Moral' ? 'hidden' : '' }}">
        <div>
            <label for="curp" class="block text-sm font-medium text-gray-700 mb-2">CURP <span
                    class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-address-card text-gray-400"></i>
                </div>
                <input type="text" id="curp" name="curp" value="{{ old('curp') }}"
                    class="w-full pl-10 pr-3 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#9d2449] focus:border-transparent focus:bg-white transition-all duration-200"
                    placeholder="Ej: ABCD123456HDFGHI01" maxlength="18" pattern="[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[0-9]{2}"
                    required>
            </div>
            <p class="text-xs text-gray-500 mt-1">18 caracteres sin espacios</p>
        </div>
        <div></div> <!-- Espacio vacío para mantener el grid -->
    </div>

    <!-- Información específica para Persona Moral (se muestra/oculta dinámicamente) -->
    <div id="info-moral" class="space-y-6 {{ $proveedor?->tipo_persona !== 'Moral' ? 'hidden' : '' }}">
        <div class="border-t border-gray-200 pt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Adicional (Persona Moral)</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Representante Legal -->
                <div>
                    <label for="representante_legal"
                        class="block text-sm font-medium text-gray-700 mb-2">Representante Legal <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user-tie text-gray-400"></i>
                        </div>
                        <input type="text" id="representante_legal" name="representante_legal"
                            value="{{ old('representante_legal') }}"
                            class="w-full pl-10 pr-3 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#9d2449] focus:border-transparent focus:bg-white transition-all duration-200"
                            placeholder="Nombre completo del representante legal">
                    </div>
                </div>

                <!-- Fecha de Constitución -->
                <div>
                    <label for="fecha_constitucion" class="block text-sm font-medium text-gray-700 mb-2">Fecha de
                        Constitución</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-calendar-alt text-gray-400"></i>
                        </div>
                        <input type="date" id="fecha_constitucion" name="fecha_constitucion"
                            value="{{ old('fecha_constitucion') }}"
                            class="w-full pl-10 pr-3 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#9d2449] focus:border-transparent focus:bg-white transition-all duration-200">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actividades Económicas -->
    <div class="border-t border-gray-200 pt-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actividades Económicas</h3>
        <div class="space-y-4">
            <div>
                <label for="actividad_principal" class="block text-sm font-medium text-gray-700 mb-2">Actividad
                    Principal <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-briefcase text-gray-400"></i>
                    </div>
                    <select id="actividad_principal" name="actividad_principal"
                        class="w-full pl-10 pr-3 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#9d2449] focus:border-transparent focus:bg-white transition-all duration-200 appearance-none"
                        required>
                        <option value="">Seleccione una actividad</option>
                        <option value="construccion">Construcción</option>
                        <option value="servicios">Servicios Profesionales</option>
                        <option value="comercio">Comercio</option>
                        <option value="tecnologia">Tecnología</option>
                        <option value="manufactura">Manufactura</option>
                        <option value="otros">Otros</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
            </div>

            <div>
                <label for="descripcion_actividad" class="block text-sm font-medium text-gray-700 mb-2">Descripción de
                    la Actividad</label>
                <div class="relative">
                    <div class="absolute top-3 left-0 pl-3 flex items-start pointer-events-none">
                        <i class="fas fa-align-left text-gray-400"></i>
                    </div>
                    <textarea id="descripcion_actividad" name="descripcion_actividad" rows="3"
                        class="w-full pl-10 pr-3 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#9d2449] focus:border-transparent focus:bg-white transition-all duration-200 resize-none"
                        placeholder="Describa brevemente la actividad principal de su empresa">{{ old('descripcion_actividad') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('input[name="tipo_proveedor"]');
            const infoMoral = document.getElementById('info-moral');
            const curpSection = document.getElementById('curp-section');
            const razonSocialLabel = document.querySelector('.razon-social-label');
            const curpInput = document.getElementById('curp');

            function updateRfcValidation(tipoPersona) {
                const rfcInput = document.getElementById('rfc');
                const rfcHelp = document.getElementById('rfc-help');

                if (tipoPersona === 'moral') {
                    // Persona Moral: 12 caracteres
                    rfcInput.maxLength = 12;
                    rfcInput.pattern = '[A-Z&Ñ]{3}[0-9]{6}[A-Z0-9]{3}';
                    rfcInput.placeholder = 'Ej: ABC123456789';
                    rfcHelp.textContent = 'RFC de 12 caracteres para Persona Moral';
                } else {
                    // Persona Física: 13 caracteres
                    rfcInput.maxLength = 13;
                    rfcInput.pattern = '[A-Z&Ñ]{4}[0-9]{6}[A-Z0-9]{3}';
                    rfcInput.placeholder = 'Ej: ABCD123456789';
                    rfcHelp.textContent = 'RFC de 13 caracteres para Persona Física';
                }
            }

            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'moral') {
                        // Mostrar sección de persona moral
                        infoMoral.classList.remove('hidden');
                        razonSocialLabel.textContent = 'Razón Social';

                        // Ocultar CURP para persona moral
                        curpSection.classList.add('hidden');
                        curpInput.required = false;

                        // Hacer requeridos los campos de persona moral
                        document.getElementById('representante_legal').required = true;

                        // Actualizar validación RFC para persona moral
                        updateRfcValidation('moral');
                    } else {
                        // Ocultar sección de persona moral
                        infoMoral.classList.add('hidden');
                        razonSocialLabel.textContent = 'Nombre Completo';

                        // Mostrar CURP para persona física
                        curpSection.classList.remove('hidden');
                        curpInput.required = true;

                        // Quitar requerimiento de campos de persona moral
                        document.getElementById('representante_legal').required = false;

                        // Actualizar validación RFC para persona física
                        updateRfcValidation('fisica');
                    }
                });
            });

            // Inicializar validación RFC según el tipo seleccionado
            const tipoSeleccionado = document.querySelector('input[name="tipo_proveedor"]:checked');
            if (tipoSeleccionado) {
                updateRfcValidation(tipoSeleccionado.value);
            }

            // Validación de RFC
            const rfcInput = document.getElementById('rfc');
            rfcInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });

            // Validación de CURP
            curpInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });

            // Validación de teléfono
            const telefonoInput = document.getElementById('telefono');
            telefonoInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
            });
        });
    </script>
@endpush
