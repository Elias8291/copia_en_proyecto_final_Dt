<?php $__env->startSection('title', 'Trámites Disponibles'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-7xl mx-auto">
        <?php
            // Verificar si hay trámite pendiente
            $tieneTramitePendiente = false;
            $tramitePendiente = null;
            $tipoTramitePendiente = null;
            $rfc = auth()->user()->rfc ?? null;

            if ($rfc) {
                $rfcService = app(\App\Services\RfcProveedorService::class);
                $tramitePendiente = $rfcService->obtenerTramitePendiente($rfc);
                $tieneTramitePendiente = $tramitePendiente !== null;
                if ($tramitePendiente) {
                    $tipoTramitePendiente = strtolower($tramitePendiente->tipo_tramite);
                }
            }

            $brandFrom = '#9d2449';
            $brandVia  = '#8a1f40';
            $brandTo   = '#7a1a37';
        ?>

        
        <div class="space-y-2 mb-4">
            <?php if(session('warning')): ?>
                <div class="rounded-xl border border-amber-200/70 bg-amber-50/70 p-4 shadow-sm backdrop-blur supports-backdrop-blur">
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-500 text-white shadow">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        </div>
                        <p class="text-sm text-amber-800/90"><?php echo e(session('warning')); ?></p>
                    </div>
                </div>
            <?php endif; ?>
            <?php if(session('success')): ?>
                <div class="rounded-xl border border-emerald-200/70 bg-emerald-50 p-4 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500 text-white shadow">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-sm text-emerald-800/90"><?php echo e(session('success')); ?></p>
                    </div>
                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="rounded-xl border border-rose-200/70 bg-rose-50 p-4 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-500 text-white shadow">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-sm text-rose-700/90"><?php echo e(session('error')); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Contenido Principal -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="p-6 border-b border-gray-200/70">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="bg-gradient-to-br from-[<?php echo e($brandFrom); ?>] via-[<?php echo e($brandVia); ?>] to-[<?php echo e($brandTo); ?>] rounded-xl p-3 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21a12.083 12.083 0 01-6.16-10.422L12 14z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Trámites Disponibles</h1>
                            <p class="text-base text-gray-500 mt-1">
                                <?php if($tieneTramitePendiente): ?>
                                    <?php if($tipoTramitePendiente): ?>
                                        Tiene un trámite de <?php echo e(ucfirst($tipoTramitePendiente)); ?> en proceso
                                    <?php else: ?>
                                        Tiene un trámite en proceso
                                    <?php endif; ?>
                                <?php else: ?>
                                    Seleccione el tipo de trámite que desea realizar
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    
                    <div class="flex items-center">
                        <button type="button" onclick="openHistorialModal()" class="inline-flex items-center justify-center rounded-xl border border-[<?php echo e($brandFrom); ?>]/20 bg-white px-5 py-2.5 text-sm font-medium text-[<?php echo e($brandFrom); ?>] transition hover:bg-[<?php echo e($brandFrom); ?>]/5 hover:text-[<?php echo e($brandVia); ?>] focus:outline-none focus:ring-4 focus:ring-[<?php echo e($brandFrom); ?>]/20 shadow-sm">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Historial
                            <?php if($historialTramites->isNotEmpty()): ?>
                                <span class="ml-2 inline-flex items-center rounded-full bg-[<?php echo e($brandFrom); ?>] px-2 py-0.5 text-xs font-medium text-white"><?php echo e($historialTramites->count()); ?></span>
                            <?php endif; ?>
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Opciones de trámites -->
                <div class="space-y-6">
                    <div class="text-center mb-10">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Seleccione un Tipo de Trámite</h2>
                        <p class="text-gray-500 max-w-2xl mx-auto">Elija una de las siguientes opciones para proceder con su solicitud.</p>
                    </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 px-4 sm:px-6 pb-6">
                            
                            <?php echo $__env->make('tramites.partials.tramite-card', [
                                'tipo' => 'inscripcion',
                                'tramites' => $tramites,
                                'tramitePendiente' => $tramitePendiente ?? null,
                                'title' => 'Inscripción al Padrón',
                                'description' => 'Registro inicial para nuevos proveedores. Complete todos los requisitos para formar parte del padrón oficial.',
                                'gradient' => 'bg-blue-600',
                                'icon' => '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>'
                            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                            
                            <?php echo $__env->make('tramites.partials.tramite-card', [
                                'tipo' => 'renovacion',
                                'tramites' => $tramites,
                                'tramitePendiente' => $tramitePendiente ?? null,
                                'title' => 'Renovación de Registro',
                                'description' => 'Renueve su registro anual para mantener activo su estado en el padrón de proveedores.',
                                'gradient' => 'bg-emerald-600',
                                'icon' => '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>'
                            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                            
                            <?php echo $__env->make('tramites.partials.tramite-card', [
                                'tipo' => 'actualizacion',
                                'tramites' => $tramites,
                                'tramitePendiente' => $tramitePendiente ?? null,
                                'title' => 'Actualización de Datos',
                                'description' => 'Modifique su información registrada. Mantenga sus datos siempre actualizados.',
                                'gradient' => 'bg-purple-600',
                                'icon' => '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>'
                            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>

                    </div>
                </div>
            </div>


        </div>
    </div>
</div>


<div id="historialModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeHistorialModal()"></div>

        <div class="relative w-full max-w-2xl transform overflow-hidden rounded-3xl bg-white shadow-2xl transition-all">
            
            <div class="relative overflow-hidden bg-gradient-to-br from-[<?php echo e($brandFrom); ?>] via-[<?php echo e($brandVia); ?>] to-[<?php echo e($brandTo); ?>] px-5 py-4">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm shadow-lg">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-white" id="modal-title">Historial de Trámites</h3>
                        </div>
                    </div>
                    <button type="button" onclick="closeHistorialModal()" class="rounded-lg p-2 text-white/90 transition hover:bg-white/15 hover:text-white focus:outline-none focus:ring-2 focus:ring-white/30">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    </div>
                </div>

            
            <div class="max-h-[60vh] overflow-y-auto">
                <?php if($historialTramites->isNotEmpty()): ?>
                    <div class="p-6">
                        <div class="mb-6 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[<?php echo e($brandFrom); ?>]/10">
                                    <svg class="h-4 w-4 text-[<?php echo e($brandFrom); ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-sm font-semibold text-gray-700">Total de trámites</span>
                                    <span class="ml-2 inline-flex items-center rounded-full bg-[<?php echo e($brandFrom); ?>] px-3 py-1 text-xs font-bold text-white"><?php echo e($historialTramites->count()); ?></span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 text-xs text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                </svg>
                                <span>Ordenados por fecha</span>
                            </div>
                        </div>

                        <ol class="relative border-s border-gray-200">
                            <?php $__currentLoopData = $historialTramites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $tramite): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="mb-10 ms-6">
                                    <span class="absolute flex items-center justify-center w-6 h-6 bg-[<?php echo e($brandFrom); ?>]/10 rounded-full -start-3 ring-8 ring-white">
                                        <?php
                                            $estadoColors = [
                                                'Pendiente' => 'text-yellow-600',
                                                'Revision_Digital' => 'text-blue-600',
                                                'Revision_Presencial' => 'text-purple-600',
                                                'Revision_Domiciliaria' => 'text-indigo-600',
                                                'Para_Correccion' => 'text-orange-600',
                                                'Aprobado' => 'text-green-600',
                                                'Rechazado' => 'text-red-600',
                                                'Cancelado' => 'text-gray-600'
                                            ];
                                            $estadoColor = $estadoColors[$tramite['status']] ?? 'text-gray-600';
                                        ?>
                                        <svg class="w-2.5 h-2.5 <?php echo e($estadoColor); ?>" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                        </svg>
                                    </span>
                                    <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">
                                        <?php echo e($tramite['razon_social']); ?>

                                        <?php
                                            $estadoLabels = [
                                                'Pendiente' => 'Pendiente',
                                                'Revision_Digital' => 'Revisión Digital',
                                                'Revision_Presencial' => 'Revisión Presencial',
                                                'Revision_Domiciliaria' => 'Revisión Domiciliaria',
                                                'Para_Correccion' => 'Para Corrección',
                                                'Aprobado' => 'Aprobado',
                                                'Rechazado' => 'Rechazado',
                                                'Cancelado' => 'Cancelado'
                                            ];
                                            $estadoLabel = $estadoLabels[$tramite['status']] ?? $tramite['status'];
                                            $estadoBadgeColors = [
                                                'Pendiente' => 'bg-yellow-100 text-yellow-800',
                                                'Revision_Digital' => 'bg-blue-100 text-blue-800',
                                                'Revision_Presencial' => 'bg-purple-100 text-purple-800',
                                                'Revision_Domiciliaria' => 'bg-indigo-100 text-indigo-800',
                                                'Para_Correccion' => 'bg-orange-100 text-orange-800',
                                                'Aprobado' => 'bg-green-100 text-green-800',
                                                'Rechazado' => 'bg-red-100 text-red-800',
                                                'Cancelado' => 'bg-gray-100 text-gray-800'
                                            ];
                                            $estadoBadgeColor = $estadoBadgeColors[$tramite['status']] ?? 'bg-gray-100 text-gray-800';
                                        ?>
                                        <span class="bg-[<?php echo e($brandFrom); ?>]/10 text-[<?php echo e($brandFrom); ?>] text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm ms-3">
                                            <?php echo e(ucfirst($tramite['tipo_tramite'])); ?>

                                        </span>
                                        <span class="<?php echo e($estadoBadgeColor); ?> text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm ms-3">
                                            <?php echo e($estadoLabel); ?>

                                        </span>
                                    </h3>
                                    <time class="block mb-2 text-sm font-normal leading-none text-gray-400">
                                        <?php echo e($tramite['created_at']->format('d/m/Y H:i')); ?>

                                    </time>

                                    <?php if(isset($tramite['observaciones']) && !empty(trim($tramite['observaciones']))): ?>
                                        <p class="mb-4 text-base font-normal text-gray-500">
                                            <?php echo e($tramite['observaciones']); ?>

                                        </p>
                                    <?php endif; ?>

                                    
                                    <?php if(isset($tramite['oficio']) && $tramite['oficio']): ?>
                                        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    <span class="text-sm font-medium text-blue-900">Oficio: <?php echo e($tramite['oficio']['numero_oficio']); ?></span>
                                                </div>
                                                <?php if($tramite['oficio']['url']): ?>
                                                    <a href="<?php echo e($tramite['oficio']['url']); ?>" target="_blank" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700">
                                                        <svg class="w-3.5 h-3.5 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M14.707 7.793a1 1 0 0 0-1.414 0L11 10.086V1.5a1 1 0 0 0-2 0v8.586L6.707 7.793a1 1 0 1 0-1.414 1.414l4 4a1 1 0 0 0 1.416 0l4-4a1 1 0 0 0-.002-1.414Z"/>
                                                            <path d="M18 12h-2.55l-2.975 2.975a3.5 3.5 0 0 1-4.95 0L4.55 12H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2Zm-3 5a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>
                                                        </svg>
                                                        Descargar PDF
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="flex gap-2">
                                        <?php
                                            // Mostrar el botón "Ver estado" para todos los trámites
                                            $puedeVerEstado = true;
                                        ?>
                                        
                                        <?php if($puedeVerEstado): ?>
                                            
                                            <?php if(config('app.debug')): ?>
                                                <div class="text-xs text-gray-500 mb-1">
                                                    Debug: ID=<?php echo e($tramite['id']); ?>, Status=<?php echo e($tramite['status']); ?>

                                                </div>
                                            <?php endif; ?>
                                            <a href="<?php echo e(route('tramites.estado.tramite', $tramite['id'])); ?>" 
                                               onclick="console.log('Clicking on tramite ID: <?php echo e($tramite['id']); ?>')"
                                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700">
                                                <svg class="w-3.5 h-3.5 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Ver estado
                                            </a>
                                        <?php endif; ?>

                                        <?php if(isset($tramite['oficio']) && $tramite['oficio'] && $tramite['oficio']['url']): ?>
                                            <a href="<?php echo e(route('oficios.por-tramite', $tramite['id'])); ?>" class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:text-blue-800 focus:z-10 focus:ring-4 focus:outline-none focus:ring-blue-100 focus:text-blue-800">
                                                <svg class="w-3.5 h-3.5 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                Ver oficios
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ol>

                    </div>
                <?php else: ?>
                    
                    <div class="p-8 text-center">
                        <div class="mx-auto flex max-w-sm flex-col items-center gap-3">
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Sin historial</h3>
                                <p class="mt-1 text-sm text-gray-600">Aún no ha realizado ningún trámite.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            
            <div class="border-t border-gray-100 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2 text-xs text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Haga clic en "Ver detalles" para más información</span>
                    </div>
                    <button type="button" onclick="closeHistorialModal()" class="rounded-xl bg-[<?php echo e($brandFrom); ?>] px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-[<?php echo e($brandVia); ?>] focus:outline-none focus:ring-4 focus:ring-[<?php echo e($brandFrom); ?>]/20 shadow-sm">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
function openHistorialModal() {
    document.getElementById('historialModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeHistorialModal() {
    document.getElementById('historialModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Cerrar modal con Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeHistorialModal();
    }
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Elias\Documents\copia_en_proyecto_final_Dt\resources\views/tramites/index.blade.php ENDPATH**/ ?>