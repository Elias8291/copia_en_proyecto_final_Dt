<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Proveedor - <?php echo e($proveedor->razon_social ?? $proveedor->rfc); ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        .primary-red { color: #9D2449; }
        .primary-red-bg { background-color: #9D2449; }
        .primary-red-bg-light { background-color: #FEF2F2; }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('<?php echo e(asset("images/logoNegro.png")); ?>');
            background-repeat: repeat;
            background-size: 150px 150px;
            opacity: 0.05;
            filter: blur(0.2px); 
            z-index: -1;
            pointer-events: none;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <div class="min-h-screen">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-12">
            

        <!-- Información Completa -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8 max-w-3xl mx-auto">
            <!-- Header unido -->
            <div class="primary-red-bg px-6 py-6 rounded-t-lg">
                <div class="text-center">
                    <h1 class="text-2xl font-bold text-white mb-2">Información del Proveedor</h1>
                </div>
            </div>
            
            <div class="p-6">
                <!-- Información del Proveedor -->
                <div class="mb-8">
                    <!-- Información Principal -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <!-- Razón Social -->
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex items-center mb-2">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Razón Social</span>
                            </div>
                            <p class="text-sm font-semibold text-gray-900 leading-relaxed"><?php echo e($proveedor->razon_social ?? 'No especificada'); ?></p>
                        </div>
                        
                        <!-- RFC -->
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex items-center mb-2">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">RFC</span>
                            </div>
                            <p class="text-sm font-mono font-semibold text-gray-900"><?php echo e($proveedor->rfc); ?></p>
                        </div>
                        
                        <!-- Número PV -->
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex items-center mb-2">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Número PV</span>
                            </div>
                            <p class="text-lg font-bold text-gray-900"><?php echo e($proveedor->pv_numero ?? 'Pendiente'); ?></p>
                        </div>
                    </div>

                    <!-- Estado y Tipo -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <!-- Estado -->
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Estado</span>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                    <?php echo e($proveedor->estado_padron === 'Activo' ? 'bg-emerald-100 text-emerald-800' : 
                                       ($proveedor->estado_padron === 'Pendiente' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800')); ?>">
                                    <?php echo e($proveedor->estado_padron ?? 'No especificado'); ?>

                                </span>
                            </div>
                        </div>
                        
                        <!-- Tipo de Persona -->
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Tipo</span>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold <?php echo e($proveedor->tipo_persona === 'Moral' ? 'bg-purple-100 text-purple-800' : 'bg-emerald-100 text-emerald-800'); ?>">
                                    <?php echo e($proveedor->tipo_persona); ?>

                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Fechas -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Registro -->
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex items-center mb-2">
                                <div class="w-6 h-6 bg-gray-100 rounded-lg flex items-center justify-center mr-2">
                                    <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Registro</span>
                            </div>
                            <p class="text-sm font-medium text-gray-900"><?php echo e($proveedor->fecha_registro ? $proveedor->fecha_registro->format('d/m/Y') : 'No especificada'); ?></p>
                        </div>
                        
                        <?php if($proveedor->fecha_vencimiento_padron): ?>
                        <!-- Inicio Vigencia -->
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex items-center mb-2">
                                <div class="w-6 h-6 bg-gray-100 rounded-lg flex items-center justify-center mr-2">
                                    <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Inicio</span>
                            </div>
                            <p class="text-sm font-medium text-gray-900"><?php echo e($proveedor->fecha_vencimiento_padron->subYear()->format('d/m/Y')); ?></p>
                        </div>
                        
                        <!-- Fin Vigencia -->
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex items-center mb-2">
                                <div class="w-6 h-6 bg-gray-100 rounded-lg flex items-center justify-center mr-2">
                                    <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Fin</span>
                            </div>
                            <p class="text-sm font-medium text-gray-900"><?php echo e($proveedor->fecha_vencimiento_padron->format('d/m/Y')); ?></p>
                        </div>
                        
                        <!-- Tiempo Restante -->
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex items-center mb-2">
                                <div class="w-6 h-6 bg-gray-100 rounded-lg flex items-center justify-center mr-2">
                                    <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Restante</span>
                            </div>
                            <?php
                                $fechaVencimiento = \Carbon\Carbon::parse($proveedor->fecha_vencimiento_padron);
                                $ahora = \Carbon\Carbon::now();
                                $diferencia = $ahora->diff($fechaVencimiento);
                                
                                if ($ahora->gt($fechaVencimiento)) {
                                    $tiempoRestante = 'Vencido';
                                    $colorClase = 'text-rose-600';
                                } else {
                                    $partes = [];
                                    if ($diferencia->y > 0) {
                                        $partes[] = $diferencia->y . ' año' . ($diferencia->y > 1 ? 's' : '');
                                    }
                                    if ($diferencia->m > 0) {
                                        $partes[] = $diferencia->m . ' mes' . ($diferencia->m > 1 ? 'es' : '');
                                    }
                                    if ($diferencia->d > 0) {
                                        $partes[] = $diferencia->d . ' día' . ($diferencia->d > 1 ? 's' : '');
                                    }
                                    if ($diferencia->h > 0) {
                                        $partes[] = $diferencia->h . ' hora' . ($diferencia->h > 1 ? 's' : '');
                                    }
                                    
                                    $tiempoRestante = implode(', ', $partes);
                                    
                                    // Determinar color basado en días totales
                                    $diasTotales = $ahora->diffInDays($fechaVencimiento, false);
                                    if ($diasTotales > 30) {
                                        $colorClase = 'text-emerald-600';
                                    } elseif ($diasTotales > 7) {
                                        $colorClase = 'text-amber-600';
                                    } else {
                                        $colorClase = 'text-rose-600';
                                    }
                                }
                            ?>
                            <p class="text-sm font-semibold <?php echo e($colorClase); ?>">
                                <?php echo e($tiempoRestante); ?>

                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Línea divisoria -->
                <div class="border-t border-gray-200 my-6"></div>

                <!-- Domicilio -->
                <?php if($direcciones->count() > 0): ?>
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Domicilio</h3>
                    <div class="space-y-2">
                        <?php $__currentLoopData = $direcciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $direccion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border-l-4 border-primary-red pl-3 py-1">
                            <p class="text-sm text-gray-900">
                                <?php echo e($direccion->calle); ?> <?php echo e($direccion->numero_exterior); ?>

                                <?php if($direccion->numero_interior): ?>
                                    Int. <?php echo e($direccion->numero_interior); ?>

                                <?php endif; ?>
                                , <?php echo e($direccion->colonia_asentamiento); ?>, <?php echo e($direccion->municipio); ?>, <?php echo e($direccion->estado->nombre ?? ''); ?> C.P. <?php echo e($direccion->codigo_postal); ?>

                            </p>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Línea divisoria -->
                <?php if($direcciones->count() > 0 && $actividades->count() > 0): ?>
                <div class="border-t border-gray-200 my-6"></div>
                <?php endif; ?>

                <!-- Actividades Económicas -->
                <?php if($actividades->count() > 0): ?>
                <div>
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Actividades Económicas</h3>
                    <div class="space-y-2">
                        <?php $__currentLoopData = $actividades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $actividadPivot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($actividadPivot->actividad): ?>
                            <div class="border-l-4 border-primary-red pl-3 py-1">
                                <p class="text-sm text-gray-900"><?php echo e($actividadPivot->actividad->nombre); ?></p>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Footer Minimalista -->
        <div class="mt-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 max-w-3xl mx-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="text-center">
                        <h3 class="text-sm font-medium text-gray-900">Información Oficial</h3>
                        <p class="text-xs text-gray-500 mt-1">Esta información es de carácter público y oficial</p>
                    </div>
                </div>
                <div class="px-6 py-3 text-center">
                    <p class="text-xs text-gray-400">Sistema de Gestión de Proveedores - <?php echo e(now()->format('Y')); ?></p>
                    <p class="text-xs text-gray-400 mt-1">Validado el <?php echo e(now()->format('d/m/Y H:i:s')); ?></p>
                </div>
            </div>
        </div>
    </div>
    </div>

</body>
</html> <?php /**PATH C:\Users\Elias\Documents\copia_en_proyecto_final_Dt\resources\views/proveedores/publico.blade.php ENDPATH**/ ?>