<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['datos' => [], 'editable' => false, 'actividadesSeleccionadas' => null]));

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

foreach (array_filter((['datos' => [], 'editable' => false, 'actividadesSeleccionadas' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    // Obtener actividades del old() si hay errores de validación
    $actividadesOld = old('actividades_seleccionadas');
    $actividadesArray = [];
    
    if ($actividadesOld) {
        // Si viene como JSON string, decodificarlo
        if (is_string($actividadesOld)) {
            $actividadesArray = json_decode($actividadesOld, true) ?: [];
        } elseif (is_array($actividadesOld)) {
            $actividadesArray = $actividadesOld;
        }
    } elseif ($actividadesSeleccionadas instanceof \App\ViewModels\FormDataViewModel) {
        // Para FormDataViewModel (datos de revisión)
        $actividadesArray = $actividadesSeleccionadas->getActividades();
    } elseif (!empty($datos)) {
        // Si no hay old() pero hay datos, usar los datos
        $actividadesArray = $datos;
    }
    
    // Normalizar formato de actividades para JavaScript
    $actividadesNormalizadas = [];
    foreach ($actividadesArray as $actividad) {
        if (is_array($actividad)) {
            $actividadesNormalizadas[] = [
                'id' => $actividad['id'] ?? $actividad['actividad_id'] ?? null,
                'nombre' => $actividad['nombre'] ?? $actividad['descripcion'] ?? $actividad['actividad']['nombre'] ?? 'Actividad',
            ];
        } elseif (is_object($actividad)) {
            $actividadesNormalizadas[] = [
                'id' => $actividad->id ?? $actividad->actividad_id ?? null,
                'nombre' => $actividad->nombre ?? $actividad->descripcion ?? $actividad->actividad->nombre ?? 'Actividad',
            ];
        } else {
            // Si es solo un string
            $actividadesNormalizadas[] = [
                'id' => null,
                'nombre' => (string) $actividad,
            ];
        }
    }
?>

<div class="space-y-6" <?php echo e($attributes); ?>>
    <!-- Título de la sección -->
    <div class="flex items-center space-x-3 mb-6">
        <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Actividades Económicas</h3>
            <p class="text-sm text-gray-500"><?php echo e($editable ? 'Seleccione las actividades que realiza' : 'Actividades del trámite'); ?></p>
        </div>
    </div>

    <div class="space-y-4">
        <div class="border border-gray-200 rounded-lg p-4">
            <h4 class="text-sm font-semibold text-gray-800 mb-3"><?php echo e($editable ? 'Seleccionar Actividades' : 'Actividades Seleccionadas'); ?></h4>
            
            <?php if($editable): ?>
            <!-- Búsqueda en tiempo real -->
            <div class="mb-4">
                <label for="buscar-actividad" class="block text-sm font-medium text-gray-700 mb-2">
                    Buscar actividad económica
                </label>
                <div class="relative">
                    <input type="text" 
                           id="buscar-actividad" 
                           class="w-full px-3 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary"
                           placeholder="Escriba para buscar actividades...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
                
                <!-- Resultados de búsqueda -->
                <div id="resultados-busqueda" class="mt-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg hidden">
                    <!-- Los resultados se cargarán aquí dinámicamente -->
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Actividades seleccionadas -->
            <div id="actividades-seleccionadas" class="space-y-2">
                <!-- Las actividades seleccionadas se mostrarán aquí -->
            </div>
            
            <?php if($editable): ?>
            <!-- Input oculto para enviar datos -->
            <input type="hidden" name="actividades_seleccionadas" id="actividades-json" value="<?php echo e(old('actividades_seleccionadas', '[]')); ?>">
            <?php $__errorArgs = ['actividades'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            
            <!-- Mensaje de error para actividades vacías -->
            <div id="error-actividades-vacias" class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg hidden">
                <p class="text-sm text-red-600">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Debe seleccionar al menos una actividad económica
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buscarInput = document.getElementById('buscar-actividad');
            const resultadosDiv = document.getElementById('resultados-busqueda');
            const actividadesSeleccionadas = document.getElementById('actividades-seleccionadas');
            const actividadesJson = document.getElementById('actividades-json');
            const errorActividadesVacias = document.getElementById('error-actividades-vacias');
            
            // Check if required elements exist
            const esEditable = <?php echo json_encode($editable, 15, 512) ?>;
            if (!actividadesSeleccionadas) {
                console.warn('Required elements for actividades-economicas not found');
                return;
            }
            
            // En modo no editable, algunos elementos pueden no existir
            if (esEditable && (!buscarInput || !resultadosDiv || !actividadesJson)) {
                console.warn('Required elements for editable mode not found');
                return;
            }
            
            let timeoutId;
            let actividadesSeleccionadasArray = [];
            
            // Cargar actividades existentes desde old() o datos
            <?php if(!empty($actividadesNormalizadas)): ?>
                actividadesSeleccionadasArray = <?php echo json_encode($actividadesNormalizadas, 15, 512) ?>;
                actualizarActividadesSeleccionadas();
            <?php endif; ?>
            
            // Búsqueda en tiempo real (solo en modo editable)
            if (esEditable && buscarInput) {
                buscarInput.addEventListener('input', function() {
                clearTimeout(timeoutId);
                const query = this.value.trim();
                
                if (query.length < 2) {
                    resultadosDiv.classList.add('hidden');
                    return;
                }
                
                timeoutId = setTimeout(() => {
                    fetch(`/api/catalogo/actividades?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            mostrarResultados(data);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }, 300);
                });
                
                function mostrarResultados(actividades) {
                    if (actividades.length === 0) {
                        resultadosDiv.innerHTML = '<div class="p-3 text-sm text-gray-500">No se encontraron actividades</div>';
                    } else {
                        resultadosDiv.innerHTML = actividades.map(actividad => `
                            <div class="p-2 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 actividad-item" 
                                 data-id="${actividad.id}" 
                                 data-nombre="${actividad.nombre}">
                                <div class="text-sm text-gray-900">${actividad.nombre}</div>
                            </div>
                        `).join('');
                    }
                    resultadosDiv.classList.remove('hidden');
                }
                
                // Seleccionar actividad
                resultadosDiv.addEventListener('click', function(e) {
                    if (e.target.closest('.actividad-item')) {
                        const item = e.target.closest('.actividad-item');
                        const id = item.dataset.id;
                        const nombre = item.dataset.nombre;
                        
                        if (!actividadesSeleccionadasArray.find(a => a.id == id)) {
                            actividadesSeleccionadasArray.push({id, nombre});
                            actualizarActividadesSeleccionadas();
                        }
                        
                        buscarInput.value = '';
                        resultadosDiv.classList.add('hidden');
                    }
                });
                
                // Ocultar resultados al hacer clic fuera
                document.addEventListener('click', function(e) {
                    if (buscarInput && resultadosDiv && !buscarInput.contains(e.target) && !resultadosDiv.contains(e.target)) {
                        resultadosDiv.classList.add('hidden');
                    }
                });
            }
            
            function actualizarActividadesSeleccionadas() {
                const esEditable = <?php echo json_encode($editable, 15, 512) ?>;
                
                if (actividadesSeleccionadasArray.length === 0) {
                    actividadesSeleccionadas.innerHTML = `
                        <div class="text-sm text-gray-500 italic p-2">
                            ${esEditable ? 'No hay actividades seleccionadas' : 'No hay actividades registradas'}
                        </div>
                    `;
                } else {
                    actividadesSeleccionadas.innerHTML = actividadesSeleccionadasArray.map((actividad, index) => {
                        // Las actividades ya vienen normalizadas
                        const nombre = actividad.nombre || 'Actividad sin nombre';
                        const id = actividad.id || index;
                        
                        return `
                            <div class="flex items-center justify-between p-2 bg-blue-50 border border-blue-200 rounded-lg">
                                <span class="text-sm text-blue-900">${nombre}</span>
                                ${esEditable ? `
                                <button type="button" 
                                        class="text-red-500 hover:text-red-700 text-sm"
                                        onclick="removerActividad('${id}')">
                                    <i class="fas fa-times"></i>
                                </button>
                                ` : ''}
                            </div>
                        `;
                    }).join('');
                }
                
                if (actividadesJson) {
                    actividadesJson.value = JSON.stringify(actividadesSeleccionadasArray);
                    // Disparar evento de cambio para activar validación
                    actividadesJson.dispatchEvent(new Event('input', { bubbles: true }));
                }
                
                // Verificar actividades seleccionadas solo si es editable y existe el elemento de error
                if (esEditable && errorActividadesVacias) {
                    if (actividadesSeleccionadasArray.length === 0) {
                        errorActividadesVacias.classList.remove('hidden');
                    } else {
                        errorActividadesVacias.classList.add('hidden');
                    }
                }
            }
            
            // Función global para remover actividad (solo en modo editable)
            if (esEditable) {
                window.removerActividad = function(id) {
                    actividadesSeleccionadasArray = actividadesSeleccionadasArray.filter((a, index) => {
                        const actividadId = a.id || index;
                        return actividadId != id;
                    });
                    actualizarActividadesSeleccionadas();
                };
            }
        });
        </script>
</div> <?php /**PATH C:\Users\Elias\Documents\copia_en_proyecto_final_Dt\resources\views/components/forms/actividades-economicas.blade.php ENDPATH**/ ?>