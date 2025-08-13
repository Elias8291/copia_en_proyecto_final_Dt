<?php $__env->startSection('content'); ?>
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-full mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1H5C3.89 1 3 1.89 3 3V21C3 22.11 3.89 23 5 23H19C20.11 23 21 22.11 21 21V9M19 9H14V4H5V21H19V9Z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Roles</h1>
                        <p class="text-base text-gray-500 mt-1">Gestión de roles del sistema</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="<?php echo e(route('roles.create')); ?>" 
                       class="inline-flex items-center px-4 py-2 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nuevo Rol
                    </a>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100 mb-4 sm:mb-5 md:mb-6 lg:mb-8">
            <form method="GET" action="<?php echo e(route('roles.index')); ?>" class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8" id="searchForm">
                <input type="hidden" name="per_page" value="<?php echo e(request('per_page', 15)); ?>">
                <div class="flex flex-col lg:flex-row gap-2 sm:gap-3 md:gap-4 lg:gap-6 mb-3 sm:mb-4 md:mb-5 lg:mb-6">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2 sm:pl-3 md:pl-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   value="<?php echo e(request('search')); ?>"
                                   placeholder="Buscar por nombre o descripción..." 
                                   class="block w-full pl-7 sm:pl-10 md:pl-12 pr-3 sm:pr-4 py-2 sm:py-2.5 md:py-3 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                        </div>
                    </div>
                    <div class="flex gap-2 sm:gap-3 md:gap-4 lg:gap-6">
                        <button type="submit" 
                                class="flex-1 lg:flex-none px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-2.5 md:py-3 bg-[#9d2449] text-white text-xs sm:text-sm md:text-base font-medium rounded-md hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 flex items-center justify-center gap-1 sm:gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span class="hidden sm:inline">Buscar</span>
                        </button>
                        <a href="<?php echo e(route('roles.index')); ?>" 
                           class="flex-1 lg:flex-none px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-2.5 md:py-3 bg-gray-50 text-gray-700 text-xs sm:text-sm md:text-base font-medium rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 border border-gray-200 flex items-center justify-center gap-1 sm:gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span class="hidden sm:inline">Limpiar</span>
                        </a>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-3 sm:pt-4 md:pt-5 lg:pt-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3 mb-3 sm:mb-4 md:mb-5">
                        <div class="flex items-center gap-1.5 sm:gap-2 md:gap-3">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                            </svg>
                            <span class="text-xs sm:text-sm md:text-base font-medium text-gray-700">Filtros avanzados</span>
                        </div>
                        <button type="button" 
                                id="toggleFilters" 
                                class="text-xs sm:text-sm md:text-base text-[#9d2449] hover:text-[#8a1f40] font-medium flex items-center gap-1 transition-colors self-start sm:self-auto">
                            <span id="filterText">Mostrar filtros</span>
                            <span id="filterIcon" class="text-xs sm:text-sm md:text-base transform transition-transform duration-200">▼</span>
                        </button>
                    </div>
                        
                    <div id="filtersContainer" class="hidden max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 sm:gap-3 md:gap-4 lg:gap-6">
                            <div>
                                <label for="guard_name" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Guard</label>
                                <select name="guard_name" 
                                        id="guard_name" 
                                        class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                                    <option value="">Todos los guards</option>
                                    <option value="web" <?php echo e(request('guard_name') == 'web' ? 'selected' : ''); ?>>Web</option>
                                    <option value="api" <?php echo e(request('guard_name') == 'api' ? 'selected' : ''); ?>>API</option>
                                    <option value="admin" <?php echo e(request('guard_name') == 'admin' ? 'selected' : ''); ?>>Admin</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 mt-3 sm:mt-4 md:mt-5 pt-3 sm:pt-4 border-t border-gray-100">
                            <button type="submit" 
                                    class="w-full sm:w-auto px-3 sm:px-4 md:px-6 py-2 sm:py-2.5 md:py-3 bg-[#9d2449] text-white text-xs sm:text-sm md:text-base font-medium rounded-md hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                Aplicar filtros
                            </button>
                            <a href="<?php echo e(route('roles.index')); ?>" 
                               class="w-full sm:w-auto px-3 sm:px-4 md:px-6 py-2 sm:py-2.5 md:py-3 bg-gray-50 text-gray-700 text-xs sm:text-sm md:text-base font-medium rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 border border-gray-200 text-center flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Limpiar filtros
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="border-t border-gray-100 p-2 sm:p-3 md:p-4 lg:p-5 mb-4 sm:mb-5 md:mb-6 lg:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 md:gap-5">
                <div class="flex items-center gap-2 sm:gap-3">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-xs sm:text-sm md:text-base lg:text-lg text-gray-700">
                        <span class="font-medium text-[#9d2449]"><?php echo e($roles->total()); ?></span> 
                        <?php echo e($roles->total() == 1 ? 'rol encontrado' : 'roles encontrados'); ?>

                        <?php if($roles->hasPages()): ?>
                            <span class="text-gray-500 ml-1 sm:ml-2 md:ml-3">
                                (<?php echo e($roles->firstItem()); ?>-<?php echo e($roles->lastItem()); ?>)
                            </span>
                        <?php endif; ?>
                    </p>
                </div>

                <!-- Controles de visualización -->
                <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-3 md:gap-4">
                    <!-- Selector de elementos por página -->
                    <div class="flex items-center gap-2">
                        <label for="per_page" class="text-xs sm:text-sm md:text-base font-medium text-gray-700 whitespace-nowrap">
                            Mostrar:
                        </label>
                        <select name="per_page" 
                                id="per_page" 
                                class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200 bg-white">
                            <option value="10" <?php echo e(request('per_page', 15) == 10 ? 'selected' : ''); ?>>10</option>
                            <option value="15" <?php echo e(request('per_page', 15) == 15 ? 'selected' : ''); ?>>15</option>
                            <option value="25" <?php echo e(request('per_page', 15) == 25 ? 'selected' : ''); ?>>25</option>
                            <option value="50" <?php echo e(request('per_page', 15) == 50 ? 'selected' : ''); ?>>50</option>
                            <option value="100" <?php echo e(request('per_page', 15) == 100 ? 'selected' : ''); ?>>100</option>
                        </select>
                        <span class="text-xs sm:text-sm md:text-base text-gray-600 whitespace-nowrap">por página</span>
                    </div>

                    <!-- Filtros activos -->
                    <?php if(request()->hasAny(['search', 'guard_name'])): ?>
                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 md:gap-3">
                        <span class="text-xs sm:text-sm md:text-base font-medium text-gray-700">Filtros activos:</span>
                        
                        <?php if(request('search')): ?>
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-[#9d2449] text-white">
                            Búsqueda: "<?php echo e(request('search')); ?>"
                            <a href="<?php echo e(request()->fullUrlWithQuery(['search' => null])); ?>" class="ml-1 sm:ml-1.5 text-white hover:text-gray-200">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        <?php endif; ?>

                        <?php if(request('guard_name')): ?>
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-[#9d2449]/10 text-[#9d2449] border border-[#9d2449]/20">
                            Guard: <?php echo e(ucfirst(request('guard_name'))); ?>

                            <a href="<?php echo e(request()->fullUrlWithQuery(['guard_name' => null])); ?>" class="ml-1 sm:ml-1.5 text-[#9d2449] hover:text-[#8a1f40]">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                    </div>
    </div>

        <div class="border-t border-gray-100 overflow-hidden hidden xl:block">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-2 sm:py-3 md:py-4 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Rol</th>
                            <th class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-2 sm:py-3 md:py-4 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Guard</th>
                            <th class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-2 sm:py-3 md:py-4 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Permisos</th>
                            <th class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-2 sm:py-3 md:py-4 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Fecha Creación</th>
                            <th class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-2 sm:py-3 md:py-4 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <div class="flex items-center space-x-2 sm:space-x-3 md:space-x-4">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 bg-[#9d2449] rounded-lg flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-semibold text-xs sm:text-sm md:text-base"><?php echo e(strtoupper(substr($role->name ?? 'U', 0, 1))); ?></span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 truncate max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl text-xs sm:text-sm md:text-base" title="<?php echo e($role->name ?? 'N/A'); ?>">
                                            <?php echo e($role->name ?? 'N/A'); ?>

                                        </div>
                                        <?php if($role->description): ?>
                                            <div class="text-xs sm:text-sm text-gray-500 truncate"><?php echo e($role->description); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <?php
                                    $guardColors = [
                    'web' => 'bg-blue-50 text-blue-600 border-blue-200',
                    'api' => 'bg-green-50 text-green-600 border-green-200',
                    'admin' => 'bg-purple-50 text-purple-600 border-purple-200'
                                    ];
                                    $guardColor = $guardColors[$role->guard_name] ?? 'bg-gray-50 text-gray-600 border-gray-200';
                                ?>
                                <span class="inline-flex items-center px-2 sm:px-2.5 md:px-3 py-1 sm:py-1.5 md:py-2 rounded-full text-xs sm:text-sm font-medium border <?php echo e($guardColor); ?>">
                                    <?php echo e($role->guard_name ?? 'N/A'); ?>

                                </span>
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <?php
                                    $permissionColors = [
                    '0' => 'bg-gray-50 text-gray-600 border-gray-200',
                    '1' => 'bg-yellow-50 text-yellow-600 border-yellow-200',
                    '2' => 'bg-orange-50 text-orange-600 border-orange-200',
                    '3' => 'bg-red-50 text-red-600 border-red-200',
                    '4' => 'bg-pink-50 text-pink-600 border-pink-200',
                    '5' => 'bg-purple-50 text-purple-600 border-purple-200'
                                    ];
                                    $permissionCount = $role->permissions_count ?? 0;
                                    $permissionColor = $permissionColors[$permissionCount] ?? 'bg-gray-50 text-gray-600 border-gray-200';
                                ?>
                                <span class="inline-flex items-center px-2 sm:px-2.5 md:px-3 py-1 sm:py-1.5 md:py-2 rounded-full text-xs sm:text-sm font-medium border <?php echo e($permissionColor); ?>">
                                    <?php echo e($permissionCount); ?>

                                </span>
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <span class="text-sm text-gray-900">
                                    <?php if($role->created_at): ?>
                                        <?php echo e($role->created_at->format('d/m/Y')); ?>

                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <div class="flex items-center space-x-2 sm:space-x-3 md:space-x-4">
                                    <a href="<?php echo e(route('roles.show', $role)); ?>" 
                                       class="group inline-flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 text-[#9d2449] hover:text-white hover:bg-[#9d2449] rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                       title="Ver detalles">
                                        <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.639 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.639 0-8.573-3.007-9.963-7.178z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </a>
                                    <a href="<?php echo e(route('roles.edit', $role)); ?>" 
                                       class="group inline-flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 text-gray-600 hover:text-white hover:bg-gray-700 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                       title="Editar">
                                        <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                        </svg>
                                    </a>
                                    <form id="form-eliminar-<?php echo e($role->id); ?>" action="<?php echo e(route('roles.destroy', $role)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="button" 
                                                onclick="confirmarEliminacionRol('<?php echo e($role->id); ?>', '<?php echo e($role->name); ?>')"
                                                class="group inline-flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 text-red-600 hover:text-white hover:bg-red-600 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                                title="Eliminar">
                                            <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-8 sm:py-10 md:py-12 lg:py-16 text-center">
                                <div class="text-gray-500">
                                    <svg class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 lg:w-24 lg:h-24 mx-auto mb-4 sm:mb-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-xs sm:text-sm md:text-base lg:text-lg">No hay roles registrados</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-4 sm:pt-5 md:pt-6 lg:pt-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:hidden gap-2 sm:gap-3 md:gap-4 lg:gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-2 sm:p-3 md:p-4 lg:p-5">
                <div class="flex items-start justify-between mb-2 sm:mb-3 md:mb-4">
                    <div class="flex items-center space-x-1.5 sm:space-x-2 md:space-x-3 lg:space-x-4 min-w-0 flex-1">
                        <div class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 lg:w-8 lg:h-8 bg-[#9d2449] rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-semibold text-xs sm:text-sm md:text-base lg:text-lg"><?php echo e(strtoupper(substr($role->name ?? 'U', 0, 1))); ?></span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="text-gray-700 font-medium text-xs sm:text-sm md:text-base lg:text-lg block">ID: <?php echo e($role->id); ?></span>
                            <p class="text-xs sm:text-sm md:text-base text-gray-500 truncate"><?php echo e($role->guard_name ?? 'N/A'); ?></p>
                        </div>
                    </div>
                    <div class="flex-shrink-0 ml-1 sm:ml-2 md:ml-3">
                        <?php
                            $permissionCount = $role->permissions_count ?? 0;
                            $statusClass = match($permissionCount) {
                                0 => 'text-gray-800 bg-gray-100',
                                1, 2 => 'text-yellow-800 bg-yellow-100',
                                3, 4 => 'text-orange-800 bg-orange-100',
                                default => 'text-purple-800 bg-purple-100'
                            };
    ?>
                        <span class="px-1 sm:px-1.5 md:px-2 lg:px-2.5 py-0.5 sm:py-1 md:py-1.5 text-xs sm:text-sm md:text-base font-medium rounded-full <?php echo e($statusClass); ?> whitespace-nowrap">
                            <?php echo e($permissionCount); ?> permisos
                        </span>
                    </div>
                </div>
                <div class="space-y-1 sm:space-y-1.5 md:space-y-2 lg:space-y-3">
                    <div class="text-xs sm:text-sm md:text-base lg:text-lg font-semibold text-gray-800 truncate" title="<?php echo e($role->name ?? 'N/A'); ?>">
                        <?php echo e($role->name ?? 'N/A'); ?>

                    </div>
                    <?php if($role->description): ?>
                        <div class="text-xs sm:text-sm md:text-base text-gray-600 truncate">
                            <?php echo e($role->description); ?>

                        </div>
                    <?php endif; ?>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600">
                        Creado: <?php echo e($role->created_at ? $role->created_at->format('d/m/Y') : 'N/A'); ?>

                    </div>
                </div>
                <div class="flex space-x-2 sm:space-x-3 md:space-x-4 pt-3 sm:pt-4 md:pt-5 mt-3 sm:mt-4 md:mt-5 border-t border-gray-100">
                    <a href="<?php echo e(route('roles.show', $role)); ?>" 
                       class="flex-1 text-center px-2 sm:px-3 md:px-4 lg:px-5 py-2 sm:py-2.5 md:py-3 text-xs sm:text-sm md:text-base font-medium text-[#9d2449] bg-[#9d2449]/5 border border-[#9d2449]/20 rounded-lg hover:bg-[#9d2449] hover:text-white transition-all duration-200 truncate shadow-sm">
                        <span class="flex items-center justify-center gap-1.5 sm:gap-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.639 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.639 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Ver
                        </span>
                    </a>
                    <a href="<?php echo e(route('roles.edit', $role)); ?>" 
                       class="flex-1 text-center px-2 sm:px-3 md:px-4 lg:px-5 py-2 sm:py-2.5 md:py-3 text-xs sm:text-sm md:text-base font-medium text-gray-600 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-700 hover:text-white transition-all duration-200 truncate shadow-sm">
                        <span class="flex items-center justify-center gap-1.5 sm:gap-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                            </svg>
                            Editar
                        </span>
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 md:p-8 lg:p-10 text-center">
                <div class="text-gray-500">
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 lg:w-24 lg:h-24 mx-auto mb-4 sm:mb-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm sm:text-base md:text-lg lg:text-xl">No hay roles</p>
                </div>
            </div>
            <?php endif; ?>
            </div>
        </div>
                <?php if($roles->hasPages()): ?>
        <div class="mt-4 sm:mt-5 md:mt-6 lg:mt-8 xl:mt-10">
            <div class="flex justify-center">
                <div class="text-xs sm:text-sm md:text-base">
                    <?php echo e($roles->links()); ?>

                </div>
            </div>
        </div>
        <?php endif; ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.modals.modal-exito','data' => ['id' => 'success-modal','title' => '¡Éxito!','message' => 'La operación se realizó correctamente.','acceptText' => 'Aceptar','redirectUrl' => route('roles.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.modals.modal-exito'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'success-modal','title' => '¡Éxito!','message' => 'La operación se realizó correctamente.','acceptText' => 'Aceptar','redirectUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('roles.index'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.modals.modal-eliminar','data' => ['id' => 'modal-eliminar-rol','title' => 'Eliminar rol','message' => '¿Estás seguro de que deseas eliminar este rol? Esta acción no se puede deshacer.','confirmText' => 'Eliminar','cancelText' => 'Cancelar']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.modals.modal-eliminar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modal-eliminar-rol','title' => 'Eliminar rol','message' => '¿Estás seguro de que deseas eliminar este rol? Esta acción no se puede deshacer.','confirmText' => 'Eliminar','cancelText' => 'Cancelar']); ?>
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

<?php $__env->startPush('scripts'); ?>
<script>
// Función para confirmar eliminación de rol
function confirmarEliminacionRol(rolId, nombreRol) {
    const mensaje = `¿Estás seguro de que deseas eliminar el rol "${nombreRol}"? Esta acción no se puede deshacer.`;
    
    showDeleteModal(
        'Eliminar rol',
        mensaje,
        `form-eliminar-${rolId}`
    );
}

document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('toggleFilters');
    const container = document.getElementById('filtersContainer');
    const text = document.getElementById('filterText');
    const icon = document.getElementById('filterIcon');
    const perPageSelect = document.getElementById('per_page');
    const searchForm = document.getElementById('searchForm');
    
    // Los filtros siempre empiezan ocultos, sin importar si hay búsqueda
    
    toggle?.addEventListener('click', function() {
        const hidden = container?.classList.contains('hidden');
        if (hidden) {
            container?.classList.remove('hidden');
            container?.classList.remove('max-h-0');
            container?.classList.add('max-h-screen');
            if (text) text.textContent = 'Ocultar filtros';
            icon?.classList.add('rotate-180');
        } else {
            container?.classList.add('max-h-0');
            setTimeout(() => {
                container?.classList.add('hidden');
            }, 300);
            if (text) text.textContent = 'Mostrar filtros';
            icon?.classList.remove('rotate-180');
        }
    });

    perPageSelect?.addEventListener('change', function() {
        const hiddenPerPage = searchForm.querySelector('input[name="per_page"]');
        if (hiddenPerPage) {
            hiddenPerPage.value = this.value;
        }
        searchForm.submit();
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Elias\Documents\copia_en_proyecto_final_Dt\resources\views/roles/index.blade.php ENDPATH**/ ?>