@props(['tramites' => []])

<div class="w-full">
    <!-- Header simplificado -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200/70 mb-8">
        <div class="p-6 border-b border-gray-200/70">
                             <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-primary via-primary-dark to-primary-light rounded-xl p-3 shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-800">Gestión de Trámites</h1>
                        <p class="text-sm md:text-base text-gray-500">Administra y revisa el estado de los trámites de proveedores</p>
                    </div>
                </div>
                                                  <div class="flex flex-col lg:flex-row items-center space-y-3 lg:space-y-0 lg:space-x-3">
                     <button class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-primary to-primary-dark rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                         <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                         </svg>
                         Nuevo Trámite
                     </button>
                 </div>
            </div>
        </div>
    </div>

         <!-- Filtros elegantes y compactos -->
     <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
         <div class="p-4 bg-gradient-to-r from-gray-50/80 to-gray-100/60">
             <!-- Filtros en línea compactos -->
             <div class="flex flex-wrap items-center gap-3">
                 <!-- Búsqueda principal -->
                 <div class="relative flex-1 min-w-[200px]">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                         <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                         </svg>
                     </div>
                     <input type="text" id="search-filter"
                         class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-lg bg-white/90 backdrop-blur-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary/50 transition-all duration-200 hover:bg-white hover:shadow-sm"
                         placeholder="Buscar trámites...">
                 </div>

                 <!-- Tipo de Trámite -->
                 <div class="relative">
                     <select id="tipo-tramite-filter"
                         class="appearance-none bg-white border border-gray-200 rounded-lg pl-9 pr-8 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary/50 transition-all duration-200 hover:bg-white hover:shadow-sm min-w-[130px]">
                         <option value="">Tipo</option>
                         <option value="Inscripcion">Inscripción</option>
                         <option value="Renovacion">Renovación</option>
                         <option value="Actualizacion">Actualización</option>
                     </select>
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                         <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                         </svg>
                     </div>
                     <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                         <svg class="h-3 w-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                         </svg>
                     </div>
                 </div>

                 <!-- RFC -->
                 <div class="relative">
                     <input type="text" id="rfc-filter"
                         class="bg-white border border-gray-200 rounded-lg pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary/50 transition-all duration-200 hover:bg-white hover:shadow-sm min-w-[100px]"
                         placeholder="RFC">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                         <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                         </svg>
                     </div>
                 </div>

                 <!-- Estado -->
                 <div class="relative">
                     <select id="estado-filter"
                         class="appearance-none bg-white border border-gray-200 rounded-lg pl-9 pr-8 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary/50 transition-all duration-200 hover:bg-white hover:shadow-sm min-w-[110px]">
                         <option value="">Estado</option>
                         <option value="Pendiente">Pendiente</option>
                         <option value="En_Revision">En Revisión</option>
                         <option value="Aprobado">Aprobado</option>
                         <option value="Rechazado">Rechazado</option>
                         <option value="Por_Cotejar">Por Cotejar</option>
                         <option value="Para_Correccion">Para Corrección</option>
                         <option value="Cancelado">Cancelado</option>
                     </select>
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                         <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                         </svg>
                     </div>
                     <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                         <svg class="h-3 w-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                         </svg>
                     </div>
                 </div>

                 <!-- Botón limpiar -->
                 <button id="clear-filters"
                     class="inline-flex items-center px-3 py-2.5 border border-gray-200 rounded-lg text-sm font-medium text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all duration-200 hover:shadow-sm">
                     <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                     </svg>
                     Limpiar
                 </button>

                 <!-- Contador de resultados -->
                 <div class="ml-auto">
                     <div id="results-count"
                         class="text-xs text-gray-500 font-medium bg-white/80 backdrop-blur-sm rounded-full px-3 py-1.5 border border-gray-200/60">
                         {{ count($tramites) }} trámites
                     </div>
                 </div>
             </div>
         </div>
     </div>

    <!-- Tabla simplificada -->
    <div class="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
        <!-- Header de tabla -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Lista de Trámites</h2>
            </div>
        </div>

        <!-- Contenido de tabla -->
        <div class="overflow-x-auto">
            <table class="w-full">
                                 <thead class="hidden lg:table-header-group bg-gray-50">
                    <tr>
                                                 <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Proveedor</th>
                         <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tipo</th>
                         <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Estado</th>
                         <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Fecha</th>
                         <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($tramites as $tramite)
                                                 <!-- Desktop -->
                         <tr class="hidden lg:table-row hover:bg-gray-50/50 transition-all duration-200">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                                                         <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-dark rounded-xl flex items-center justify-center mr-4">
                                         <span class="text-white font-bold text-sm">{{ strtoupper(substr($tramite->proveedor->user->nombre ?? 'P', 0, 1)) }}</span>
                                     </div>
                                     <div>
                                         <div class="text-sm font-semibold text-gray-900">{{ $tramite->proveedor->user->nombre ?? 'N/A' }}</div>
                                         <div class="text-xs text-gray-500">RFC: {{ $tramite->proveedor->rfc ?? 'N/A' }}</div>
                                     </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $tipoColors = [
                                        'Inscripcion' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'Renovacion' => 'bg-green-100 text-green-700 border-green-200',
                                        'Actualizacion' => 'bg-purple-100 text-purple-700 border-purple-200'
                                    ];
                                    $color = $tipoColors[$tramite->tipo_tramite] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                @endphp
                                <span class="px-3 py-1.5 rounded-full text-xs font-semibold border {{ $color }}">{{ $tramite->tipo_tramite }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $estadoColors = [
                                        'Pendiente' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                        'En_Revision' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'Aprobado' => 'bg-green-100 text-green-700 border-green-200',
                                        'Rechazado' => 'bg-red-100 text-red-700 border-red-200',
                                        'Por_Cotejar' => 'bg-orange-100 text-orange-700 border-orange-200',
                                        'Para_Correccion' => 'bg-pink-100 text-pink-700 border-pink-200',
                                        'Cancelado' => 'bg-gray-100 text-gray-700 border-gray-200'
                                    ];
                                    $color = $estadoColors[$tramite->estado] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                @endphp
                                <span class="px-3 py-1.5 rounded-full text-xs font-semibold border {{ $color }}">{{ str_replace('_', ' ', $tramite->estado) }}</span>
                            </td>
                                                         <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                 {{ $tramite->fecha_inicio ? $tramite->fecha_inicio->format('d/m/Y') : 'N/A' }}
                             </td>
                             <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <button class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-all duration-200" title="Ver detalles">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200" title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                                                 <!-- Móvil -->
                         <div class="lg:hidden bg-white border border-gray-200 rounded-xl p-6 mb-4 shadow-sm hover:shadow-lg transition-all duration-300">
                            <div class="flex items-start justify-between mb-4">
                                                                 <div class="flex items-center">
                                     <div class="w-12 h-12 bg-gradient-to-br from-primary to-primary-dark rounded-xl flex items-center justify-center mr-4">
                                         <span class="text-white font-bold text-sm">{{ strtoupper(substr($tramite->proveedor->user->nombre ?? 'P', 0, 1)) }}</span>
                                     </div>
                                     <div>
                                         <div class="text-base font-semibold text-gray-900">{{ $tramite->proveedor->user->nombre ?? 'N/A' }}</div>
                                         <div class="text-sm text-gray-500">RFC: {{ $tramite->proveedor->rfc ?? 'N/A' }}</div>
                                     </div>
                                 </div>
                                <div class="flex items-center space-x-1">
                                    <button class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-all duration-200" title="Ver detalles">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2 block">Tipo</span>
                                    @php $color = $tipoColors[$tramite->tipo_tramite] ?? 'bg-gray-100 text-gray-700 border-gray-200'; @endphp
                                    <span class="px-3 py-1.5 rounded-full text-xs font-semibold border {{ $color }}">{{ $tramite->tipo_tramite }}</span>
                                </div>
                                <div>
                                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2 block">Estado</span>
                                    @php $color = $estadoColors[$tramite->estado] ?? 'bg-gray-100 text-gray-700 border-gray-200'; @endphp
                                    <span class="px-3 py-1.5 rounded-full text-xs font-semibold border {{ $color }}">{{ str_replace('_', ' ', $tramite->estado) }}</span>
                                </div>
                            </div>

                                                         <div class="flex items-center justify-between">
                                 <div class="text-sm text-gray-600">
                                     <span class="font-medium">Inicio:</span> {{ $tramite->fecha_inicio ? $tramite->fecha_inicio->format('d/m/Y') : 'N/A' }}
                                 </div>
                             </div>
                        </div>
                                         @empty
                                                  <tr class="lg:table-row">
                             <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mb-6">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">No hay trámites</h3>
                                    <p class="text-gray-500 text-base mb-6 max-w-md">No se encontraron trámites registrados. Comienza creando el primer trámite.</p>
                                    <button class="px-6 py-3 bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Crear primer trámite
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación simplificada -->
        @if(count($tramites) > 0)
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                                 <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                         <div class="text-sm text-gray-700 mb-4 lg:mb-0">
                        Mostrando <span class="font-semibold">{{ count($tramites) }}</span> de <span class="font-semibold">{{ count($tramites) }}</span> resultados
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Anterior
                        </button>
                        <span class="px-4 py-2 text-sm font-semibold text-primary bg-primary/10 border border-primary/20 rounded-lg">1</span>
                        <button class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                            Siguiente
                            <svg class="w-4 h-4 ml-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
                 @endif
     </div>
 </div>

 <script>
 document.addEventListener('DOMContentLoaded', function() {
           // Función para filtrar la tabla
      function filterTable() {
          const searchTerm = document.getElementById('search-filter').value.toLowerCase();
          const rfcFilter = document.getElementById('rfc-filter').value.toLowerCase();
          const tipoFilter = document.getElementById('tipo-tramite-filter').value;
          const estadoFilter = document.getElementById('estado-filter').value;
         
                   const rows = document.querySelectorAll('tbody tr, tbody div.lg\\:hidden');
         let visibleCount = 0;
         
                   rows.forEach(row => {
              const text = row.textContent.toLowerCase();
              const tipoMatch = !tipoFilter || text.includes(tipoFilter.toLowerCase());
              const estadoMatch = !estadoFilter || text.includes(estadoFilter.toLowerCase());
              const searchMatch = !searchTerm || text.includes(searchTerm);
              const rfcMatch = !rfcFilter || text.includes(rfcFilter);
              
              if (tipoMatch && estadoMatch && searchMatch && rfcMatch) {
                  row.style.display = '';
                  visibleCount++;
              } else {
                  row.style.display = 'none';
              }
          });
         
         // Actualizar contador
         const resultsCount = document.getElementById('results-count');
         if (resultsCount) {
             resultsCount.textContent = `Mostrando ${visibleCount} trámites`;
         }
     }
     
           // Event listeners para filtros
      document.getElementById('search-filter').addEventListener('input', filterTable);
      document.getElementById('rfc-filter').addEventListener('input', filterTable);
      document.getElementById('tipo-tramite-filter').addEventListener('change', filterTable);
      document.getElementById('estado-filter').addEventListener('change', filterTable);
     
           // Botón limpiar filtros
      document.getElementById('clear-filters').addEventListener('click', function() {
          document.getElementById('search-filter').value = '';
          document.getElementById('rfc-filter').value = '';
          document.getElementById('tipo-tramite-filter').value = '';
          document.getElementById('estado-filter').value = '';
          filterTable();
      });
 });
 </script> 