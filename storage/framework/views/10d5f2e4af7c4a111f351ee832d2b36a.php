<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['datos' => [], 'editable' => false, 'datosConstancia' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['datos' => [], 'editable' => false, 'datosConstancia' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
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
    
    // Obtener valores para los campos, priorizando old() sobre los valores de constancia
    $razonSocial = $camposNoEditables ? ($datosFinales['razon_social'] ?? '') : (old('razon_social', $datosFinales['razon_social'] ?? ''));
    $rfc = $camposNoEditables ? ($datosFinales['rfc'] ?? '') : (old('rfc', $datosFinales['rfc'] ?? ''));
    $curp = $camposNoEditables ? ($datosFinales['curp'] ?? '') : (old('curp', $datosFinales['curp'] ?? ''));
    $tipoPersonaValue = $camposNoEditables ? $tipoPersona : (old('tipo_persona', $tipoPersona));
?>

<div class="space-y-6" <?php echo e($attributes); ?>>
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
                        value="<?php echo e($razonSocial); ?>"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary <?php echo e(($editable && !$camposNoEditables) ? 'bg-white' : 'bg-gray-50 cursor-not-allowed'); ?> <?php echo e($errors->has('razon_social') ? 'border-red-500' : ''); ?>"
                        <?php echo e((!$editable || $camposNoEditables) ? 'disabled' : ''); ?>

                        placeholder="Ingrese la razón social">
                    <?php if($camposNoEditables): ?>
                        <input type="hidden" name="razon_social_hidden" value="<?php echo e($razonSocial); ?>">
                    <?php endif; ?>
                </div>
                <?php $__errorArgs = ['razon_social'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                        value="<?php echo e($rfc); ?>"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary font-mono <?php echo e(($editable && !$camposNoEditables) ? 'bg-white' : 'bg-gray-50 cursor-not-allowed'); ?> <?php echo e($errors->has('rfc') ? 'border-red-500' : ''); ?>"
                        <?php echo e((!$editable || $camposNoEditables) ? 'disabled' : ''); ?>

                        placeholder="Ingrese el RFC">
                    <?php if($camposNoEditables): ?>
                        <input type="hidden" name="rfc_hidden" value="<?php echo e($rfc); ?>">
                    <?php endif; ?>
                    <!-- Campo oculto adicional para asegurar que el RFC se envíe -->
                    <input type="hidden" name="rfc_fallback" value="<?php echo e($rfc); ?>">
                </div>
                <?php $__errorArgs = ['rfc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary <?php echo e(($editable && !$camposNoEditables) ? 'bg-white' : 'bg-gray-50 cursor-not-allowed'); ?> <?php echo e($errors->has('tipo_persona') ? 'border-red-500' : ''); ?>"
                        <?php echo e((!$editable || $camposNoEditables) ? 'disabled' : ''); ?>>
                        <option value="">Seleccione tipo</option>
                        <option value="Física" <?php echo e($tipoPersonaValue == 'Física' ? 'selected' : ''); ?>>Persona Física</option>
                        <option value="Moral" <?php echo e($tipoPersonaValue == 'Moral' ? 'selected' : ''); ?>>Persona Moral</option>
                    </select>
                    <?php if($camposNoEditables): ?>
                        <input type="hidden" name="tipo_persona_hidden" value="<?php echo e($tipoPersonaValue); ?>">
                    <?php endif; ?>
                    <!-- Campo oculto adicional para asegurar que el tipo de persona se envíe -->
                    <input type="hidden" name="tipo_persona_fallback" value="<?php echo e($tipoPersonaValue); ?>">
                </div>
                <?php $__errorArgs = ['tipo_persona'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                        value="<?php echo e($curp); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary font-mono <?php echo e(($editable && !$camposNoEditables) ? 'bg-white' : 'bg-gray-50 cursor-not-allowed'); ?> <?php echo e($errors->has('curp') ? 'border-red-500' : ''); ?>"
                        <?php echo e((!$editable || $camposNoEditables) ? 'disabled' : ''); ?>

                        placeholder="Ingrese la CURP">
                    <?php if($camposNoEditables): ?>
                        <input type="hidden" name="curp_hidden" value="<?php echo e($curp); ?>">
                    <?php endif; ?>
                    <!-- Campo oculto adicional para asegurar que la CURP se envíe -->
                    <input type="hidden" name="curp_fallback" value="<?php echo e($curp); ?>">
                </div>
                <?php $__errorArgs = ['curp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2 field-label">Página Web</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-globe text-gray-500"></i>
                    </div>
                    <input type="url"
                        name="pagina_web"
                        value="<?php echo e(old('pagina_web', $datosFinales['pagina_web'] ?? '')); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary <?php echo e($editable ? 'bg-white' : 'bg-gray-50 cursor-not-allowed'); ?> <?php echo e($errors->has('pagina_web') ? 'border-red-500' : ''); ?>"
                        <?php echo e(!$editable ? 'disabled' : ''); ?>

                        placeholder="https://ejemplo.com">
                </div>
                <?php $__errorArgs = ['pagina_web'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                        value="<?php echo e(old('telefono', $datosFinales['telefono'] ?? '')); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary <?php echo e($editable ? 'bg-white' : 'bg-gray-50 cursor-not-allowed'); ?> <?php echo e($errors->has('telefono') ? 'border-red-500' : ''); ?>"
                        <?php echo e(!$editable ? 'disabled' : ''); ?>

                        placeholder="(55) 1234-5678">
                    <!-- Campo oculto adicional para asegurar que el teléfono se envíe -->
                    <input type="hidden" name="telefono_fallback" value="<?php echo e(old('telefono', $datosFinales['telefono'] ?? '')); ?>">
                </div>
                <?php $__errorArgs = ['telefono'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                        value="<?php echo e(old('nombre_contacto', $datosFinales['nombre_contacto'] ?? '')); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary <?php echo e($editable ? 'bg-white' : 'bg-gray-50 cursor-not-allowed'); ?> <?php echo e($errors->has('nombre_contacto') ? 'border-red-500' : ''); ?>"
                        <?php echo e(!$editable ? 'disabled' : ''); ?>

                        placeholder="Nombre completo del contacto">
                </div>
                <?php $__errorArgs = ['nombre_contacto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                        value="<?php echo e(old('cargo', $datosFinales['cargo'] ?? '')); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary <?php echo e($editable ? 'bg-white' : 'bg-gray-50 cursor-not-allowed'); ?> <?php echo e($errors->has('cargo') ? 'border-red-500' : ''); ?>"
                        <?php echo e(!$editable ? 'disabled' : ''); ?>

                        placeholder="Ej: Gerente, Director, etc.">
                </div>
                <?php $__errorArgs = ['cargo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                        value="<?php echo e(old('telefono_contacto', $datosFinales['telefono_contacto'] ?? '')); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary <?php echo e($editable ? 'bg-white' : 'bg-gray-50 cursor-not-allowed'); ?> <?php echo e($errors->has('telefono_contacto') ? 'border-red-500' : ''); ?>"
                        <?php echo e(!$editable ? 'disabled' : ''); ?>

                        placeholder="(55) 1234-5678">
                </div>
                <?php $__errorArgs = ['telefono_contacto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                        value="<?php echo e(old('correo_contacto', $datosFinales['correo_contacto'] ?? '')); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary <?php echo e($editable ? 'bg-white' : 'bg-gray-50 cursor-not-allowed'); ?> <?php echo e($errors->has('correo_contacto') ? 'border-red-500' : ''); ?>"
                        <?php echo e(!$editable ? 'disabled' : ''); ?>

                        placeholder="contacto@ejemplo.com">
                </div>
                <?php $__errorArgs = ['correo_contacto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>
    </div>
</div>

<?php if($editable): ?>
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
<?php endif; ?> <?php /**PATH C:\Users\Elias\Documents\copia_en_proyecto_final_Dt\resources\views/components/forms/datos-generales.blade.php ENDPATH**/ ?>