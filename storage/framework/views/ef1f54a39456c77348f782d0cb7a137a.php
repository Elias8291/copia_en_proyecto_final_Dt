<?php $__env->startSection('content'); ?>
    <?php if(auth()->user()->hasRole(['Proveedor', 'Solicitante'])): ?>
        <?php echo $__env->make('dashboard.proveedor-solicitante', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                <?php else: ?>
        <?php echo $__env->make('dashboard.administrador-revisor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                        <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Elias\Documents\copia_en_proyecto_final_Dt\resources\views/dashboard.blade.php ENDPATH**/ ?>