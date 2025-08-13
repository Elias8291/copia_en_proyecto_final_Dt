<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-primary to-primary-dark px-6 py-4">
            <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Mi Estado de Proveedor
            </h2>
            <p class="text-primary-100 mt-1">Información actualizada de tu registro en el padrón</p>
        </div>

        <!-- Content -->
        <div class="p-6">
            <?php if($proveedor): ?>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Información Principal -->
                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-gray-500 text-xs uppercase font-semibold tracking-wide mb-2">Razón Social</div>
                            <div class="text-lg font-semibold text-gray-900"><?php echo e($proveedor->razon_social ?? 'N/A'); ?></div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-gray-500 text-xs uppercase font-semibold tracking-wide mb-2">RFC</div>
                            <div class="text-lg font-mono text-gray-800"><?php echo e($proveedor->rfc ?? 'N/A'); ?></div>
                        </div>
                    </div>

                    <!-- Estado y Fechas -->
                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-gray-500 text-xs uppercase font-semibold tracking-wide mb-2">Estado en Padrón</div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                <?php if($proveedor->estado_padron === 'vigente'): ?> bg-green-100 text-green-800 <?php elseif($proveedor->estado_padron === 'vencido'): ?> bg-red-100 text-red-800 <?php else: ?> bg-yellow-100 text-yellow-800 <?php endif; ?>">
                                <?php if($proveedor->estado_padron === 'vigente'): ?>
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                <?php elseif($proveedor->estado_padron === 'vencido'): ?>
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                <?php else: ?>
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                <?php endif; ?>
                                <?php echo e(ucfirst($proveedor->estado_padron ?? 'Desconocido')); ?>

                            </span>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-gray-500 text-xs uppercase font-semibold tracking-wide mb-2">Fecha de Alta</div>
                            <div class="text-gray-800 font-medium"><?php echo e($proveedor->fecha_alta_padron ? $proveedor->fecha_alta_padron->format('d/m/Y') : 'N/A'); ?></div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-gray-500 text-xs uppercase font-semibold tracking-wide mb-2">Fecha de Vencimiento</div>
                            <div class="text-gray-800 font-medium"><?php echo e($proveedor->fecha_vencimiento_padron ? $proveedor->fecha_vencimiento_padron->format('d/m/Y') : 'N/A'); ?></div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <div class="bg-gray-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay datos de proveedor</h3>
                    <p class="text-gray-500">No se encontraron datos de proveedor asociados a tu usuario.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Elias\Documents\copia_en_proyecto_final_Dt\resources\views/mi_estado.blade.php ENDPATH**/ ?>