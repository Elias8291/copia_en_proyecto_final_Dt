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
    if ($datosConstancia instanceof \App\ViewModels\TramiteViewModel) {
        $datosFinales = $datosConstancia->getDatosDomicilioForm($datos);
    } elseif ($datosConstancia instanceof \App\ViewModels\FormDataViewModel) {
        // Para FormDataViewModel (datos de revisión) - usar getDatosDomicilioForm para incluir coordenadas
        $datosFinales = $datosConstancia->getDatosDomicilioForm();
    } else {
        $datosFinales = $datosConstancia ? [
            'codigo_postal' => $datosConstancia['domicilio']['codigo_postal'] ?? ($datos['codigo_postal'] ?? ''),
            'estado' => $datosConstancia['domicilio']['entidad_federativa'] ?? ($datos['estado'] ?? ''),
            'municipio' => $datosConstancia['domicilio']['municipio'] ?? ($datos['municipio'] ?? ''),
            'asentamiento' => $datosConstancia['domicilio']['colonia'] ?? ($datos['asentamiento'] ?? ''),
            'calle' => $datosConstancia['domicilio']['calle'] ?? ($datos['calle'] ?? ''),
            'numero_exterior' => $datosConstancia['domicilio']['numero_exterior'] ?? ($datos['numero_exterior'] ?? ''),
            'numero_interior' => $datosConstancia['domicilio']['numero_interior'] ?? ($datos['numero_interior'] ?? ''),
            'latitud' => $datosConstancia['domicilio']['latitud'] ?? ($datos['latitud'] ?? ''),
            'longitud' => $datosConstancia['domicilio']['longitud'] ?? ($datos['longitud'] ?? ''),
            // Los campos entre_calle y y_calle SOLO vienen de los datos del formulario, no de la constancia
            'entre_calle' => !empty($datos['entre_calle']) ? $datos['entre_calle'] : '',
            'y_calle' => !empty($datos['y_calle']) ? $datos['y_calle'] : '',
        ] : $datos;
    }
    
    // Asegurar que entre_calle y y_calle siempre vengan de los datos del formulario
    $datosFinales['entre_calle'] = !empty($datos['entre_calle']) ? $datos['entre_calle'] : ($datosFinales['entre_calle'] ?? '');
    $datosFinales['y_calle'] = !empty($datos['y_calle']) ? $datos['y_calle'] : ($datosFinales['y_calle'] ?? '');
    
    // Asegurar que las coordenadas estén disponibles
    $latitud = $datosFinales['latitud'] ?? $datos['latitud'] ?? null;
    $longitud = $datosFinales['longitud'] ?? $datos['longitud'] ?? null;
?>

<div class="space-y-6" <?php echo e($attributes); ?> data-seccion="domicilio">
    <!-- Título de la sección -->
    <div class="flex items-center space-x-3 mb-6">
        <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Domicilio</h3>
            <p class="text-sm text-gray-500">Dirección completa del solicitante</p>
        </div>
    </div>

    <?php if($editable): ?>
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Información de Domicilio
        </h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
            <!-- Campo: Código Postal -->
            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Código Postal <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-mail-bulk text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text"
                        name="codigo_postal"
                        id="codigo_postal"
                        value="<?php echo e(old('codigo_postal', $datosFinales['codigo_postal'] ?? '')); ?>"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary font-mono <?php echo e($errors->has('codigo_postal') ? 'border-red-500' : ''); ?>"
                        placeholder="12345"
                        maxlength="5">
                    <div id="loading-cp" class="absolute inset-y-0 right-0 pr-3 flex items-center hidden">
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-spinner fa-spin"></i> Buscando...
                        </span>
                    </div>
                </div>
                <?php $__errorArgs = ['codigo_postal'];
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

            <!-- Campo: Estado -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Estado <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-map-marked-alt text-gray-500"></i>
                    </div>
                    <select name="estado_id" id="estado_id"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary <?php echo e($errors->has('estado_id') ? 'border-red-500' : ''); ?>">
                        <option value="">Cargando estados...</option>
                    </select>
                    <input type="hidden" name="estado_id_old" value="<?php echo e(old('estado_id', $datosFinales['estado_id'] ?? '')); ?>">
                    <div id="loading-estados" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="fas fa-spinner fa-spin text-gray-400"></i>
                    </div>
                </div>
                <?php $__errorArgs = ['estado_id'];
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

            <!-- Campo: Municipio -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Municipio/Delegación <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-city text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="municipio"
                        id="municipio"
                        value="<?php echo e(old('municipio', $datosFinales['municipio'] ?? '')); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary <?php echo e($errors->has('municipio') ? 'border-red-500' : ''); ?>"
                        placeholder="Ingrese municipio o delegación">
                </div>
                <?php $__errorArgs = ['municipio'];
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

            <!-- Campo: Asentamiento -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Asentamiento/Colonia <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-home text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="asentamiento"
                        id="asentamiento"
                        value="<?php echo e(old('asentamiento', $datosFinales['asentamiento'] ?? '')); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary <?php echo e($errors->has('asentamiento') ? 'border-red-500' : ''); ?>"
                        placeholder="Ingrese colonia o asentamiento">
                </div>
                <?php $__errorArgs = ['asentamiento'];
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

            <!-- Campo: Calle       -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Calle <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-road text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="calle"
                        id="calle"
                        value="<?php echo e(old('calle', $datosFinales['calle'] ?? '')); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary <?php echo e($errors->has('calle') ? 'border-red-500' : ''); ?>"
                        placeholder="Ingrese nombre de la calle">
                </div>
                <?php $__errorArgs = ['calle'];
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

            <!-- Campo: Entre Calle -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Entre Calle <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-road text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="entre_calle"
                        id="entre_calle"
                        value="<?php echo e(old('entre_calle') ?: ($datosFinales['entre_calle'] ?? '')); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary <?php echo e($errors->has('entre_calle') ? 'border-red-500' : ''); ?>"
                        placeholder="Ingrese primera calle de referencia">
                </div>
                <?php $__errorArgs = ['entre_calle'];
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

            <!-- Campo: Y Calle -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Y Calle <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-road text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="y_calle"
                        id="y_calle"
                        value="<?php echo e(old('y_calle') ?: ($datosFinales['y_calle'] ?? '')); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary <?php echo e($errors->has('y_calle') ? 'border-red-500' : ''); ?>"
                        placeholder="Ingrese segunda calle de referencia">
                </div>
                <?php $__errorArgs = ['y_calle'];
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

            <!-- Campo: Número Exterior -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Número Exterior <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-hashtag text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="numero_exterior"
                        id="numero_exterior"
                        value="<?php echo e(old('numero_exterior', $datosFinales['numero_exterior'] ?? '')); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary <?php echo e($errors->has('numero_exterior') ? 'border-red-500' : ''); ?>"
                        placeholder="123 o A-1">
                </div>
                <?php $__errorArgs = ['numero_exterior'];
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

            <!-- Campo: Número Interior -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Número Interior
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-door-open text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="numero_interior"
                        id="numero_interior"
                        value="<?php echo e(old('numero_interior', $datosFinales['numero_interior'] ?? '')); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary <?php echo e($errors->has('numero_interior') ? 'border-red-500' : ''); ?>"
                        placeholder="Apto 5 o Local 2">
                </div>
                <?php $__errorArgs = ['numero_interior'];
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

            <!-- Campo: Latitud -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Latitud <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-map-marker-alt text-gray-500"></i>
                    </div>
                    <input type="number"
                        name="latitud"
                        id="latitud-manual"
                        value="<?php echo e(old('latitud', $latitud ?? '')); ?>"
                        step="0.000001"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary <?php echo e($errors->has('latitud') ? 'border-red-500' : ''); ?>"
                        placeholder="19.4326">
                </div>
                <?php $__errorArgs = ['latitud'];
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

            <!-- Campo: Longitud -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Longitud <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-map-marker-alt text-gray-500"></i>
                    </div>
                    <input type="number"
                        name="longitud"
                        id="longitud-manual"
                        value="<?php echo e(old('longitud', $longitud ?? '')); ?>"
                        step="0.000001"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary <?php echo e($errors->has('longitud') ? 'border-red-500' : ''); ?>"
                        placeholder="-99.1332">
                </div>
                <?php $__errorArgs = ['longitud'];
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

            <!-- Campo oculto para colonia (usa el valor de asentamiento) -->
                            <input type="hidden" name="colonia" value="<?php echo e(old('asentamiento', $datosFinales['asentamiento'] ?? '')); ?>">
        </div>
    </div>
    <?php else: ?>
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Información de Domicilio
        </h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
            <!-- Campo: Código Postal -->
            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Código Postal
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-mail-bulk text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text"
                        value="<?php echo e($datosFinales['codigo_postal'] ?? ''); ?>"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm cursor-not-allowed font-mono"
                        readonly>
                </div>
            </div>

            <!-- Campo: Estado -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Estado
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-map-marked-alt text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="<?php echo e($datosFinales['estado'] ?? ''); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm cursor-not-allowed"
                        readonly>
                </div>
            </div>

            <!-- Campo: Municipio -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Municipio/Delegación
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-city text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="<?php echo e($datosFinales['municipio'] ?? ''); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm cursor-not-allowed"
                        readonly>
                </div>
            </div>

            <!-- Campo: Asentamiento -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Asentamiento/Colonia
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-home text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="<?php echo e($datosFinales['asentamiento'] ?? ''); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm cursor-not-allowed"
                        readonly>
                </div>
            </div>

            <!-- Campo: Calle -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Calle
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-road text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="<?php echo e($datosFinales['calle'] ?? ''); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm cursor-not-allowed"
                        readonly>
                </div>
            </div>

            <!-- Campo: Entre Calle -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Entre Calle
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-road text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="<?php echo e($datosFinales['entre_calle'] ?? ''); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm cursor-not-allowed"
                        readonly>
                </div>
            </div>

            <!-- Campo: Y Calle -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Y Calle
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-road text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="<?php echo e($datosFinales['y_calle'] ?? ''); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm cursor-not-allowed"
                        readonly>
                </div>
            </div>

            <!-- Campo: Número Exterior -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Número Exterior
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-hashtag text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="<?php echo e($datosFinales['numero_exterior'] ?? ''); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm cursor-not-allowed"
                        readonly>
                </div>
            </div>

            <!-- Campo: Número Interior -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Número Interior
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-door-open text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="<?php echo e($datosFinales['numero_interior'] ?? ''); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm cursor-not-allowed"
                        readonly>
                </div>
            </div>

            <!-- Campo: Latitud -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Latitud
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-map-marker-alt text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="<?php echo e($latitud ?? ''); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm cursor-not-allowed"
                        readonly>
                </div>
            </div>

            <!-- Campo: Longitud -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Longitud
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-map-marker-alt text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="<?php echo e($longitud ?? ''); ?>"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm cursor-not-allowed"
                        readonly>
                </div>
            </div>
        </div>

        <!-- Dirección concatenada como resumen -->
        <div class="mt-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <i class="fas fa-map-marked-alt text-blue-600 text-lg mt-1"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-blue-600 font-medium mb-1">Dirección Completa:</p>
                    <p class="text-sm text-blue-800 leading-relaxed">
                        <?php echo e($datosFinales['calle'] ?? ''); ?>

                        <?php if(!empty($datosFinales['numero_exterior'] ?? '')): ?> #<?php echo e($datosFinales['numero_exterior']); ?> <?php endif; ?>
                        <?php if(!empty($datosFinales['numero_interior'] ?? '')): ?> Int. <?php echo e($datosFinales['numero_interior']); ?> <?php endif; ?>
                        <?php if(!empty($datosFinales['entre_calle'] ?? '') && !empty($datosFinales['y_calle'] ?? '')): ?> , Entre <?php echo e($datosFinales['entre_calle']); ?> y <?php echo e($datosFinales['y_calle']); ?> <?php endif; ?>
                        <?php if(!empty($datosFinales['asentamiento'] ?? '')): ?> , <?php echo e($datosFinales['asentamiento']); ?> <?php endif; ?>
                        <?php if(!empty($datosFinales['codigo_postal'] ?? '')): ?> , C.P. <?php echo e($datosFinales['codigo_postal']); ?> <?php endif; ?>
                        <?php if(!empty($datosFinales['municipio'] ?? '')): ?> , <?php echo e($datosFinales['municipio']); ?> <?php endif; ?>
                        <?php if(!empty($datosFinales['estado'] ?? '')): ?> , <?php echo e($datosFinales['estado']); ?> <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Mapa siempre visible -->
    <div class="mt-6 mapa-container">
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Ubicación en Mapa
        </h4>
        <?php if (isset($component)) { $__componentOriginal1e40a3a162a1d5b0ab4672c0548c53e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1e40a3a162a1d5b0ab4672c0548c53e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.forms.openstreet-map','data' => ['lat' => $latitud,'lng' => $longitud,'editable' => $editable,'height' => '300px']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.forms.openstreet-map'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['lat' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($latitud),'lng' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($longitud),'editable' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($editable),'height' => '300px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1e40a3a162a1d5b0ab4672c0548c53e8)): ?>
<?php $attributes = $__attributesOriginal1e40a3a162a1d5b0ab4672c0548c53e8; ?>
<?php unset($__attributesOriginal1e40a3a162a1d5b0ab4672c0548c53e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1e40a3a162a1d5b0ab4672c0548c53e8)): ?>
<?php $component = $__componentOriginal1e40a3a162a1d5b0ab4672c0548c53e8; ?>
<?php unset($__componentOriginal1e40a3a162a1d5b0ab4672c0548c53e8); ?>
<?php endif; ?>
        
        <?php if($editable): ?>
        <div class="mt-2 text-sm text-gray-600">
            <span id="coordenadas-display-domicilio">
                <?php if(!empty($latitud) && !empty($longitud)): ?>
                    Coordenadas seleccionadas: <?php echo e($latitud); ?>, <?php echo e($longitud); ?>

                <?php else: ?>
                    Haz clic en el mapa o ingresa las coordenadas manualmente
                <?php endif; ?>
            </span>
        </div>
        
        <!-- Enlace dinámico a Google Maps para modo editable -->
        <div id="google-maps-link-container" class="mt-4 flex justify-center">
            <?php if(!empty($latitud) && !empty($longitud)): ?>
            <a id="google-maps-link" 
               href="https://www.google.com/maps?q=<?php echo e($latitud); ?>,<?php echo e($longitud); ?>" 
               target="_blank" 
               rel="noopener noreferrer"
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Ver en Google Maps
                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <!-- Enlace a Google Maps para modo solo lectura -->
        <?php if(!empty($latitud) && !empty($longitud)): ?>
        <div class="mt-4 flex justify-center">
            <a href="https://www.google.com/maps?q=<?php echo e($latitud); ?>,<?php echo e($longitud); ?>" 
               target="_blank" 
               rel="noopener noreferrer"
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Ver en Google Maps
                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>
        </div>
        <?php endif; ?>
        <?php endif; ?>


    </div>
</div>

<?php if($editable): ?>
<!-- Incluir los archivos JavaScript necesarios -->
<script src="<?php echo e(asset('js/utils/codigo-postal-autocomplete.js')); ?>"></script>
<script src="<?php echo e(asset('js/utils/coordenadas-handler.js')); ?>"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const $ = id => document.getElementById(id);
    
    // 📥 Cargar estados
    fetch('/api/ubicacion/estados')
        .then(res => res.json())
        .then(data => {
            const loadingEstados = $('loading-estados');
            loadingEstados.classList.add('hidden');
            
            if (data.success) {
                const estadoSelect = $('estado_id');
                estadoSelect.innerHTML = `<option value="">Seleccione estado</option>` +
                    data.data.map(e => `<option value="${e.id}">${e.nombre}</option>`).join('');
                
                // Restaurar valor old si existe
                const estadoIdOld = document.querySelector('input[name="estado_id_old"]').value;
                if (estadoIdOld) {
                    estadoSelect.value = estadoIdOld;
                }
                
                // Inicializar autocompletado de código postal
                const cpAutocomplete = crearAutocompletadoCP({
                    codigoPostalInput: $('codigo_postal'),
                    estadoSelect: estadoSelect,
                    municipioInput: $('municipio'),
                    asentamientoInput: $('asentamiento'),
                    loadingElement: $('loading-cp')
                });
                
                // Si ya hay un código postal, buscar datos
                if ($('codigo_postal').value.trim().length === 5) {
                    cpAutocomplete.buscarCP($('codigo_postal').value.trim());
                }
            } else {
                throw new Error('Error al cargar estados');
            }
        })
        .catch((error) => {
            console.error('Error cargando estados:', error);
            $('loading-estados').classList.add('hidden');
            $('estado_id').innerHTML = `<option value="">Error al cargar estados</option>`;
        });

    // 📍 Inicializar manejador de coordenadas
    const coordenadasHandler = crearCoordenadasHandler({
        latitudInput: $('latitud-manual'),
        longitudInput: $('longitud-manual'),
        coordenadasDisplay: $('coordenadas-display-domicilio'),
        googleMapsLinkContainer: $('google-maps-link-container')
    });
    
    // Hacer disponible globalmente para el evento del mapa
    window.coordenadasHandlerDomicilio = coordenadasHandler;

    // Sincronizar campo oculto del estado cuando cambie la selección
    $('estado_id').addEventListener('change', function() {
        const hiddenInput = document.querySelector('input[name="estado_id_old"]');
        if (hiddenInput) {
            hiddenInput.value = this.value;
        }
    });

    // Asegurar que el mapa se inicialice correctamente
    setTimeout(() => {
        const mapContainer = document.getElementById('mapa');
        if (mapContainer && !mapContainer._leaflet_map) {
            window.dispatchEvent(new CustomEvent('forceMapInitialization'));
        }
    }, 500);
});

// Escuchar eventos del mapa OpenStreetMap
window.addEventListener('coordenadasActualizadas', function(event) {
    const coordenadasHandler = window.coordenadasHandlerDomicilio;
    if (coordenadasHandler) {
        coordenadasHandler.actualizarCoordenadas(event.detail.lat, event.detail.lng);
    }
});
</script>
<?php endif; ?>
<?php /**PATH C:\Users\Elias\Documents\copia_en_proyecto_final_Dt\resources\views/components/forms/domicilio.blade.php ENDPATH**/ ?>