<?php $__env->startSection('content'); ?>
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-7xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Mis Notificaciones</h1>
                        <p class="text-base text-gray-500 mt-1">Gestiona todas tus notificaciones del sistema</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <?php if(isset($estadisticas) && $estadisticas['no_leidas'] > 0): ?>
                    <form method="POST" action="<?php echo e(route('notificaciones.marcar-todas-leidas')); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500/50 transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Marcar todas como leídas
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Información de resultados -->
        <div class="border-t border-gray-100 p-2 sm:p-3 md:p-4 lg:p-5 mb-4 sm:mb-5 md:mb-6 lg:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 md:gap-5">
                <div class="flex items-center gap-2 sm:gap-3">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5c0 0-5-5-5-5h5v-5z"/>
                    </svg>
                    <p class="text-xs sm:text-sm md:text-base lg:text-lg text-gray-700">
                        <span class="font-medium text-[#9d2449]"><?php echo e($notificaciones->total()); ?></span> 
                        <?php echo e($notificaciones->total() == 1 ? 'notificación encontrada' : 'notificaciones encontradas'); ?>

                        <?php if($notificaciones->hasPages()): ?>
                            <span class="text-gray-500 ml-1 sm:ml-2 md:ml-3">
                                (<?php echo e($notificaciones->firstItem()); ?>-<?php echo e($notificaciones->lastItem()); ?>)
                            </span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Lista de notificaciones -->
        <div class="border-t border-gray-100">
            <?php $__empty_1 = true; $__currentLoopData = $notificaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notificacion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="border-b border-gray-100 p-4 hover:bg-gray-50 transition-colors duration-200 <?php echo e(!$notificacion->leida ? 'bg-blue-50/30' : ''); ?>">
                <div class="flex items-start space-x-4">
                    <!-- Icono de tipo -->
                    <div class="flex-shrink-0">
                        <?php
                            $iconColors = [
                                'exito' => 'bg-emerald-100 text-emerald-600',
                                'advertencia' => 'bg-amber-100 text-amber-600',
                                'error' => 'bg-red-100 text-red-600',
                                'Tramite' => 'bg-blue-100 text-blue-600',
                                'Cita' => 'bg-purple-100 text-purple-600',
                                'informativo' => 'bg-gray-100 text-gray-600'
                            ];
                            $iconColor = $iconColors[$notificacion->tipo] ?? $iconColors['informativo'];
                        ?>
                        <div class="w-10 h-10 rounded-lg <?php echo e($iconColor); ?> flex items-center justify-center">
                            <?php if($notificacion->tipo === 'exito'): ?>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            <?php elseif($notificacion->tipo === 'error'): ?>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            <?php elseif($notificacion->tipo === 'advertencia'): ?>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-.993.883L9 6v3a1 1 0 001.993.117L11 9V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            <?php elseif($notificacion->tipo === 'Tramite'): ?>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                </svg>
                            <?php elseif($notificacion->tipo === 'Cita'): ?>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                            <?php else: ?>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Contenido de la notificación -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="text-sm font-semibold text-gray-900 truncate"><?php echo e($notificacion->titulo); ?></h4>
                                    <?php if(!$notificacion->leida): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Nueva
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-sm text-gray-600 line-clamp-2"><?php echo e($notificacion->mensaje); ?></p>
                                <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                    <span>
                                        <?php echo e($notificacion->created_at->diffForHumans()); ?>

                                    </span>
                                    <?php
                                        $badgeColors = [
                                            'exito' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'advertencia' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'error' => 'bg-red-100 text-red-700 border-red-200',
                                            'Tramite' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'Cita' => 'bg-purple-100 text-purple-700 border-purple-200',
                                            'informativo' => 'bg-gray-100 text-gray-700 border-gray-200'
                                        ];
                                        $badgeColor = $badgeColors[$notificacion->tipo] ?? $badgeColors['informativo'];
                                    ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border <?php echo e($badgeColor); ?>">
                                        <?php echo e(ucfirst($notificacion->tipo)); ?>

                                    </span>
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="flex items-center gap-2 ml-4">
                                <?php if(!$notificacion->leida): ?>
                                <form method="POST" action="<?php echo e(route('notificaciones.marcar-leida', $notificacion)); ?>" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all duration-200"
                                            title="Marcar como leída">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </form>
                                <?php endif; ?>
                                
                                <?php if($notificacion->accion_url): ?>
                                <a href="<?php echo e($notificacion->accion_url); ?>" 
                                   class="inline-flex items-center justify-center w-8 h-8 text-[#9d2449] hover:text-white hover:bg-[#9d2449] rounded-lg transition-all duration-200"
                                   title="Ver detalles">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                                <?php endif; ?>
                                
                                <form id="form-eliminar-<?php echo e($notificacion->id); ?>" method="POST" action="<?php echo e(route('notificaciones.eliminar', $notificacion)); ?>" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="button" 
                                            onclick="confirmarEliminacion('<?php echo e($notificacion->id); ?>', '<?php echo e($notificacion->titulo); ?>')"
                                            class="inline-flex items-center justify-center w-8 h-8 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200"
                                            title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-8 text-center">
                <div class="text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 17h5l-5 5c0 0-5-5-5-5h5v-5z"/>
                    </svg>
                    <p class="text-lg">No tienes notificaciones</p>
                    <p class="text-sm text-gray-400 mt-1">Cuando recibas notificaciones aparecerán aquí</p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Paginación -->
        <?php if($notificaciones->hasPages()): ?>
        <div class="mt-4 sm:mt-5 md:mt-6 lg:mt-8 xl:mt-10 px-6 pb-6">
            <div class="flex justify-center">
                <div class="text-xs sm:text-sm md:text-base">
                    <?php echo e($notificaciones->links()); ?>

                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de error -->
<?php if (isset($component)) { $__componentOriginal26483687382a4a1d6d89ce91486bee08 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal26483687382a4a1d6d89ce91486bee08 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.modals.error-modal','data' => ['id' => 'error-modal','title' => 'Error','message' => 'Ha ocurrido un error. Por favor, inténtalo de nuevo.','buttonText' => 'OK']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.modals.error-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'error-modal','title' => 'Error','message' => 'Ha ocurrido un error. Por favor, inténtalo de nuevo.','buttonText' => 'OK']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal26483687382a4a1d6d89ce91486bee08)): ?>
<?php $attributes = $__attributesOriginal26483687382a4a1d6d89ce91486bee08; ?>
<?php unset($__attributesOriginal26483687382a4a1d6d89ce91486bee08); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal26483687382a4a1d6d89ce91486bee08)): ?>
<?php $component = $__componentOriginal26483687382a4a1d6d89ce91486bee08; ?>
<?php unset($__componentOriginal26483687382a4a1d6d89ce91486bee08); ?>
<?php endif; ?>

<!-- Modal de éxito -->
<?php if (isset($component)) { $__componentOriginal765bfa0583680d927bcd8d764b0d499a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal765bfa0583680d927bcd8d764b0d499a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.modals.modal-exito','data' => ['id' => 'success-modal','title' => '¡Éxito!','message' => 'La operación se realizó correctamente.','acceptText' => 'Aceptar','redirectUrl' => route('notificaciones.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.modals.modal-exito'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'success-modal','title' => '¡Éxito!','message' => 'La operación se realizó correctamente.','acceptText' => 'Aceptar','redirectUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('notificaciones.index'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal765bfa0583680d927bcd8d764b0d499a)): ?>
<?php $attributes = $__attributesOriginal765bfa0583680d927bcd8d764b0d499a; ?>
<?php unset($__attributesOriginal765bfa0583680d927bcd8d764b0d499a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal765bfa0583680d927bcd8d764b0d499a)): ?>
<?php $component = $__componentOriginal765bfa0583680d927bcd8d764b0d499a; ?>
<?php unset($__componentOriginal765bfa0583680d927bcd8d764b0d499a); ?>
<?php endif; ?>

<!-- Modal de confirmación para eliminar -->
<?php if (isset($component)) { $__componentOriginalc91d0ab739b398559cac19dad054b944 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc91d0ab739b398559cac19dad054b944 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.modals.modal-eliminar','data' => ['id' => 'modal-eliminar-notificacion','title' => 'Eliminar notificación','message' => '¿Estás seguro de que deseas eliminar esta notificación? Esta acción no se puede deshacer.','confirmText' => 'Eliminar','cancelText' => 'Cancelar']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.modals.modal-eliminar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modal-eliminar-notificacion','title' => 'Eliminar notificación','message' => '¿Estás seguro de que deseas eliminar esta notificación? Esta acción no se puede deshacer.','confirmText' => 'Eliminar','cancelText' => 'Cancelar']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc91d0ab739b398559cac19dad054b944)): ?>
<?php $attributes = $__attributesOriginalc91d0ab739b398559cac19dad054b944; ?>
<?php unset($__attributesOriginalc91d0ab739b398559cac19dad054b944); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc91d0ab739b398559cac19dad054b944)): ?>
<?php $component = $__componentOriginalc91d0ab739b398559cac19dad054b944; ?>
<?php unset($__componentOriginalc91d0ab739b398559cac19dad054b944); ?>
<?php endif; ?>

<!-- Mostrar modal de error si hay error de sesión -->
<?php if(session('error')): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    showErrorModal('error-modal', 'Error', '<?php echo e(session('error')); ?>');
});
</script>
<?php endif; ?>
                            
<!-- Mostrar modal de éxito si hay éxito de sesión -->
<?php if(session('success')): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    showSuccessModal('success-modal', '¡Éxito!', '<?php echo e(session('success')); ?>');
});
</script>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Función para confirmar eliminación de notificación
function confirmarEliminacion(notificacionId, titulo) {
    const mensaje = `¿Estás seguro de que deseas eliminar la notificación "${titulo}"? Esta acción no se puede deshacer.`;
    
    showDeleteModal(
        'Eliminar notificación',
        mensaje,
        `form-eliminar-${notificacionId}`
    );
}

document.addEventListener('DOMContentLoaded', function() {
    try {
        // Removed filter and search related JavaScript
    } catch (error) {
        console.warn('Error initializing notifications page JavaScript:', error);
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Elias\Documents\copia_en_proyecto_final_Dt\resources\views/notificaciones/index.blade.php ENDPATH**/ ?>