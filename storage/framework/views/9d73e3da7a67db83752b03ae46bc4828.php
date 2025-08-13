<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['editable' => false, 'archivosRequeridos' => [], 'tipoPersona' => 'Física', 'archivosCargados' => null, 'soloLectura' => false]));

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

foreach (array_filter((['editable' => false, 'archivosRequeridos' => [], 'tipoPersona' => 'Física', 'archivosCargados' => null, 'soloLectura' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    // Asegurar que archivosRequeridos sea una colección
    $archivosRequeridosCollection = collect($archivosRequeridos ?? []);
    $archivosFiltrados = $archivosRequeridosCollection->filter(function($archivo) use ($tipoPersona) {
        $tipo = is_array($archivo) ? ($archivo['tipo_persona'] ?? null) : ($archivo->tipo_persona ?? null);
        return $tipo === 'Ambas' || $tipo === $tipoPersona;
    });
?>

<div class="space-y-6" <?php echo e($attributes); ?>>
    <div class="flex items-center space-x-3 mb-6">
        <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Archivos Requeridos</h3>
            <p class="text-sm text-gray-500">Documentación obligatoria para <?php echo e($tipoPersona === 'Física' ? 'Persona Física' : 'Persona Moral'); ?></p>
        </div>
    </div>

    <?php if($editable && $archivosFiltrados->count() > 0): ?>
        <div class="bg-[#9D2449]/10 border border-[#9D2449]/20 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-[#9D2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-[#9D2449]">
                        Archivos Obligatorios
                    </h3>
                    <div class="mt-2 text-sm text-[#9D2449]/80">
                        <p>Complete la carga de todos los archivos marcados como obligatorios para continuar con el trámite.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <?php $__currentLoopData = $archivosFiltrados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $archivo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-[#9D2449] transition-colors">
                    <div class="text-center">
                        <!-- Icono según tipo de archivo -->
                        <div class="w-12 h-12 bg-[#9D2449]/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <?php switch($archivo->tipo_archivo):
                                case ('pdf'): ?>
                                    <svg class="w-6 h-6 text-[#9D2449]" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                    </svg>
                                    <?php break; ?>
                                <?php case ('mp4'): ?>
                                    <svg class="w-6 h-6 text-[#9D2449]" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M4,2H20A2,2 0 0,1 22,4V16A2,2 0 0,1 20,18H13.9L10.2,21.71C10,21.9 9.75,22 9.5,22V22H9A1,1 0 0,1 8,21V18H4A2,2 0 0,1 2,16V4A2,2 0 0,1 4,2M5,5V11H19V5H5Z"/>
                                    </svg>
                                    <?php break; ?>
                                <?php case ('png'): ?>
                                    <svg class="w-6 h-6 text-[#9D2449]" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8.5,13.5L11,16.5L14.5,12L19,18H5M21,19V5C21,3.89 20.1,3 19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19Z"/>
                                    </svg>
                                    <?php break; ?>
                                <?php case ('mp3'): ?>
                                    <svg class="w-6 h-6 text-[#9D2449]" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12,3V13.55C11.41,13.21 10.73,13 10,13A3,3 0 0,0 7,16A3,3 0 0,0 10,19A3,3 0 0,0 13,16V7H18V3H12Z"/>
                                    </svg>
                                    <?php break; ?>
                                <?php default: ?>
                                    <svg class="w-6 h-6 text-[#9D2449]" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                    </svg>
                            <?php endswitch; ?>
                        </div>
                        
                        <!-- Nombre del archivo -->
                        <h5 class="font-medium text-gray-900 mb-2 text-sm">
                            <?php echo e($archivo->nombre); ?> <span class="text-red-500">*</span>
                        </h5>
                        
                        <!-- Descripción expandible -->
                        <div class="mb-3">
                            <div class="text-xs text-gray-500 description-container" data-archivo-id="<?php echo e($archivo->id); ?>">
                                <div class="description-preview line-clamp-2"><?php echo e($archivo->descripcion); ?></div>
                                <div class="description-full hidden"><?php echo e($archivo->descripcion); ?></div>
                                <?php if(strlen($archivo->descripcion) > 100): ?>
                                    <button type="button" class="text-[#9D2449] hover:text-[#9D2449]/80 text-xs font-medium mt-1 description-toggle" data-archivo-id="<?php echo e($archivo->id); ?>">
                                        Ver más
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Tipo de archivo -->
                        <div class="mb-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-[#9D2449]/10 text-[#9D2449] border border-[#9D2449]/20">
                                <?php echo e(strtoupper($archivo->tipo_archivo)); ?>

                            </span>
                        </div>
                        
                        <!-- Botón de carga -->
                        <label class="cursor-pointer group archivo-container">
                            <input type="file"
                                name="documentos[<?php echo e(Str::slug($archivo->nombre)); ?>]"
                                data-archivo-id="<?php echo e($archivo->id); ?>"
                                data-catalogo-id="<?php echo e($archivo->id); ?>"
                                accept=".<?php echo e($archivo->tipo_archivo); ?>"
                                class="hidden <?php echo e($errors->has('documentos.' . Str::slug($archivo->nombre)) ? 'border-red-500' : ''); ?>"
                                onchange="updateFileName(this, '<?php echo e(Str::slug($archivo->nombre)); ?>-name')"
                                required>
                            <span class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-gray-300 to-gray-400 hover:from-gray-400 hover:to-gray-500 text-gray-800 text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Subir Archivo
                            </span>
                        </label>
                        
                        <!-- Nombre del archivo seleccionado -->
                        <p id="<?php echo e(Str::slug($archivo->nombre)); ?>-name" class="text-xs text-[#9D2449] mt-2 hidden font-medium"></p>
                        
                        <!-- Error individual para cada archivo -->
                        <?php $__errorArgs = ['documentos.' . Str::slug($archivo->nombre)];
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
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <?php $__errorArgs = ['documentos.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        <?php $__errorArgs = ['archivos_faltantes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    <p class="text-sm text-red-700">
                        <strong>Error:</strong> <?php echo e($message); ?>

                    </p>
                </div>
            </div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>



        <!-- Información adicional -->
        <div class="mt-6 bg-gray-50 rounded-lg p-4">
            <h5 class="font-medium text-gray-900 mb-2">Formatos permitidos:</h5>
            <ul class="text-sm text-gray-600 space-y-1">
                <li>• <strong>PDF:</strong> Para documentos legales (máx. 10MB)</li>
                <li>• <strong>MP4:</strong> Para videos (máx. 50MB)</li>
                <li>• <strong>PNG:</strong> Para imágenes (máx. 5MB)</li>
                <li>• <strong>MP3:</strong> Para audio (máx. 10MB)</li>
            </ul>
        </div>
    <?php elseif(!$editable): ?>
        <?php if($archivosCargados && (is_array($archivosCargados) ? count($archivosCargados) : $archivosCargados->count()) > 0): ?>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-800">
                            Archivos Cargados
                        </h3>
                        <div class="mt-2 text-sm text-gray-700">
                            <p>Archivos que han sido cargados para este trámite.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <?php $__currentLoopData = $archivosCargados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $archivo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white border border-gray-300 rounded-lg p-4 flex flex-col h-full">
                        <div class="text-center flex-grow">
                            <!-- Icono según tipo de archivo -->
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <?php
                                    $extension = is_array($archivo) ? ($archivo['extension'] ?? '') : ($archivo->extension ?? '');
                                ?>
                                <?php switch($extension):
                                    case ('pdf'): ?>
                                        <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                        </svg>
                                        <?php break; ?>
                                    <?php case ('jpg'): ?>
                                    <?php case ('jpeg'): ?>
                                    <?php case ('png'): ?>
                                        <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8.5,13.5L11,16.5L14.5,12L19,18H5M21,19V5C21,3.89 20.1,3 19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19Z"/>
                                        </svg>
                                        <?php break; ?>
                                    <?php default: ?>
                                        <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                        </svg>
                                <?php endswitch; ?>
                            </div>
                            
                            <!-- Nombre del archivo -->
                            <h5 class="font-medium text-gray-900 mb-2 text-sm">
                                <?php echo e(is_array($archivo) ? ($archivo['nombre_catalogo'] ?? $archivo['nombre_original'] ?? 'Archivo') : ($archivo->catalogoArchivo->nombre ?? $archivo->nombre_original)); ?>

                            </h5>
                            
                            <!-- Nombre original -->
                            <p class="text-xs text-gray-500 mb-3"><?php echo e(is_array($archivo) ? ($archivo['nombre_original'] ?? '') : ($archivo->nombre_original ?? '')); ?></p>
                            
                            <!-- Tipo de archivo -->
                            <div class="mb-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                    <?php echo e(strtoupper($extension)); ?>

                                </span>
                            </div>
                            
                            <!-- Tamaño del archivo -->
                            <p class="text-xs text-gray-500 mb-3">
                                <?php echo e(number_format((is_array($archivo) ? ($archivo['tamaño'] ?? 0) : ($archivo->tamaño ?? 0)) / 1024, 2)); ?> KB
                            </p>
                            
                            <!-- Botón para ver/descargar -->
                            <a href="<?php echo e(route('revisiones.mostrar-archivo', is_array($archivo) ? ($archivo['id'] ?? '') : ($archivo->id ?? ''))); ?>" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Ver Archivo
                            </a>
                        </div>
                        
                        <?php if(!$soloLectura): ?>
                        <!-- Área de Decisión por Archivo -->
                        <div class="border-t border-gray-200 pt-3 mt-3">
                            <div class="mb-3">
                                <label class="block text-xs font-medium text-gray-600 mb-2 text-left">
                                    Revisión de archivo:
                                </label>
                                <textarea 
                                    placeholder="Comentarios sobre este archivo..."
                                    class="w-full text-xs px-2 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                    rows="2"></textarea>
                            </div>
                            
                            <div class="flex gap-1">
                                <button type="button" class="flex-1 bg-green-50 hover:bg-green-100 text-green-700 font-medium py-1.5 px-2 rounded-md transition-colors text-xs flex items-center justify-center space-x-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Aprobar</span>
                                </button>
                                
                                <button type="button" class="flex-1 bg-red-50 hover:bg-red-100 text-red-700 font-medium py-1.5 px-2 rounded-md transition-colors text-xs flex items-center justify-center space-x-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span>Rechazar</span>
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-800">
                            Sin Archivos
                        </h3>
                        <p class="text-gray-600">
                            No se han cargado archivos para este trámite.
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-yellow-800">
                        No hay archivos configurados
                    </h3>
                    <p class="text-yellow-700">
                        No se encontraron archivos requeridos para su tipo de persona.
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.description-container {
    position: relative;
}

.description-preview, .description-full {
    line-height: 1.4;
    word-wrap: break-word;
}

.description-toggle {
    transition: all 0.2s ease;
    border: none;
    background: none;
    cursor: pointer;
    padding: 0;
    margin: 0;
}

.description-toggle:hover {
    text-decoration: underline;
}

.description-full {
    white-space: pre-wrap;
}
</style>

<script>


function updateFileName(input, elementId) {
    const fileNameElement = document.getElementById(elementId);
    if (fileNameElement && input.files && input.files[0]) {
        fileNameElement.textContent = input.files[0].name;
        fileNameElement.classList.remove('hidden');
        
        // Cambiar el estilo del contenedor del archivo
        const archivoContainer = input.closest('.archivo-container')?.parentElement;
        if (archivoContainer) {
            archivoContainer.classList.remove('border-gray-300', 'hover:border-[#9D2449]');
            archivoContainer.classList.add('border-green-300', 'bg-green-50');
        }
    } else if (fileNameElement) {
        fileNameElement.classList.add('hidden');
        
        // Restaurar el estilo original del contenedor
        const archivoContainer = input.closest('.archivo-container')?.parentElement;
        if (archivoContainer) {
            archivoContainer.classList.remove('border-green-300', 'bg-green-50');
            archivoContainer.classList.add('border-gray-300', 'hover:border-[#9D2449]');
        }
    }
}

// Función para configurar descripciones expandibles
function setupDescriptionToggles() {
    const toggleButtons = document.querySelectorAll('.description-toggle');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const archivoId = this.getAttribute('data-archivo-id');
            const container = document.querySelector(`.description-container[data-archivo-id="${archivoId}"]`);
            
            if (container) {
                const preview = container.querySelector('.description-preview');
                const full = container.querySelector('.description-full');
                const toggle = container.querySelector('.description-toggle');
                
                if (preview && full && toggle) {
                    if (preview.classList.contains('hidden')) {
                        // Contraer
                        preview.classList.remove('hidden');
                        full.classList.add('hidden');
                        toggle.textContent = 'Ver más';
                    } else {
                        // Expandir
                        preview.classList.add('hidden');
                        full.classList.remove('hidden');
                        toggle.textContent = 'Ver menos';
                    }
                }
            }
        });
    });
}

// Función para validar archivos antes del envío
function validateArchivosOnSubmit() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    let todosCargados = true;
    
    fileInputs.forEach(input => {
        if (input.hasAttribute('required') && (!input.files || input.files.length === 0)) {
            todosCargados = false;
            // Resaltar el input faltante
            const archivoContainer = input.closest('.archivo-container')?.parentElement;
            if (archivoContainer) {
                archivoContainer.classList.add('border-red-300', 'bg-red-50');
            }
        }
    });
    
    return todosCargados;
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Agregar event listeners a todos los inputs de archivo
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Event listener para cambios en archivos
        });
    });
    
    // Configurar descripciones expandibles
    setupDescriptionToggles();
    
    // Agregar validación al envío del formulario
    const form = document.querySelector('#tramite-form') || document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const archivosValidos = validateArchivosOnSubmit();
            if (!archivosValidos) {
                e.preventDefault();
                e.stopPropagation();
                
                // El mensaje de error será manejado por el FormController
                // para evitar duplicación de mensajes
                
                return false;
            }
        });
    }
    
    // Hacer las funciones disponibles globalmente
    window.validateArchivosOnSubmit = validateArchivosOnSubmit;
});
</script> <?php /**PATH C:\Users\Elias\Documents\copia_en_proyecto_final_Dt\resources\views/components/forms/archivos-dinamicos.blade.php ENDPATH**/ ?>