<?php
    $isDisabled = isset($tramites[$tipo]) && is_array($tramites[$tipo]) && !($tramites[$tipo]['activo'] ?? true);
    $isPending = isset($tramites[$tipo]) && is_array($tramites[$tipo]) && ($tramites[$tipo]['pendiente'] ?? false);
    $isEnabled = !$isDisabled || $isPending; // Los trámites en proceso también están habilitados
    
    // Determinar la ruta del formulario
    if ($isPending && $tramitePendiente) {
        $formAction = route('tramites.estado.tramite', $tramitePendiente->id);
    } else {
        $formAction = $isEnabled ? route('tramites.cargar-constancia', ['tipo' => $tipo]) : '#';
    }
?>

<div class="relative group">
    <form action="<?php echo e($formAction); ?>" method="GET" class="h-full">
        <button type="submit" <?php echo e(!$isEnabled ? 'disabled' : ''); ?>

            class="relative overflow-hidden w-full h-full text-left p-6 bg-white rounded-xl shadow-md border border-gray-200/80 transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-xl <?php if(!$isEnabled): ?> filter grayscale opacity-70 cursor-not-allowed <?php endif; ?>">
            <div class="absolute top-0 left-0 w-full h-1.5 rounded-t-xl <?php echo e($isEnabled ? ($isPending ? 'bg-gradient-to-r from-amber-500 to-orange-600' : 'bg-[#9d2449]') : 'bg-gray-400'); ?> transition-all duration-300"></div>
            <?php if($isPending): ?>
                <div class="absolute -right-16 -top-16 h-40 w-40 rounded-full bg-gradient-to-br from-amber-400/20 via-orange-500/20 to-amber-600/20 blur-2xl"></div>
            <?php endif; ?>
            <div class="flex flex-col h-full">
                <div class="flex-shrink-0">
                    <div class="flex items-start justify-between">
                        <div class="p-3 <?php echo e($isEnabled ? 'bg-[#9d2449]' : 'bg-gray-400'); ?> rounded-lg shadow-sm">
                            <?php echo $icon; ?>

                        </div>
                        <?php if($isPending): ?>
                            <span class="text-xs font-semibold bg-gradient-to-r from-amber-400 to-orange-500 text-white px-2 py-1 rounded-full shadow-sm">En Proceso</span>
                        <?php elseif($isDisabled): ?>
                            <span class="text-xs font-semibold bg-gray-200 text-gray-800 px-2 py-1 rounded-full">No Disponible</span>
                        <?php endif; ?>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-gray-800 group-hover:text-[#9d2449] transition-colors duration-300"><?php echo e($title); ?></h3>
                    <div class="mt-2 text-sm text-gray-600 space-y-2"><?php echo e($description); ?></div>
                </div>
                
                <div class="mt-auto pt-4 flex-shrink-0">
                    <span class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white <?php echo e($isEnabled ? ($isPending ? 'bg-gradient-to-r from-amber-500 to-orange-600 group-hover:from-amber-600 group-hover:to-orange-700' : 'bg-[#9d2449] group-hover:bg-[#7a1a37]') : 'bg-gray-400'); ?> rounded-lg transition-all duration-300 shadow-sm">
                        <?php if($isPending): ?>
                            Ver Detalles
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <?php elseif($isDisabled): ?>
                            No Disponible
                        <?php else: ?>
                            Iniciar Trámite
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </button>
    </form>
</div>
<?php /**PATH C:\Users\Elias\Documents\copia_en_proyecto_final_Dt\resources\views/tramites/partials/tramite-card.blade.php ENDPATH**/ ?>