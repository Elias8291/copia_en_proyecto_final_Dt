<?php $__env->startSection('code', '403'); ?>
<?php $__env->startSection('title', 'Acceso No Autorizado'); ?>

<?php $__env->startSection('header-message'); ?>
    Acceso no autorizado <span class="sparkle">🔒</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <p class="text-gray-700 text-sm sm:text-base leading-relaxed">
        No dispone de los permisos necesarios para acceder a esta página. 
        <br><span class="highlight-text">Contacte al administrador</span> si considera que debería tener acceso a este recurso.
    </p>
    
    <div class="mt-4 text-xs sm:text-sm text-gray-600">
        <p>
            <span class="font-medium">Posibles causas:</span>
        </p>
        <ul class="list-disc list-inside mt-2 space-y-1">
            <li>Su cuenta no tiene los permisos requeridos</li>
            <li>El recurso está restringido a ciertos roles</li>
            <li>Su sesión pudo haber expirado</li>
        </ul>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('buttons'); ?>
    <button onclick="window.history.back()" 
            class="btn-back inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-[#9d2449] to-[#7a1d37] hover:from-[#7a1d37] hover:to-[#9d2449] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#9d2449] transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span class="relative">Regresar</span>
    </button>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('errors.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Elias\Documents\copia_en_proyecto_final_Dt\resources\views/errors/403.blade.php ENDPATH**/ ?>