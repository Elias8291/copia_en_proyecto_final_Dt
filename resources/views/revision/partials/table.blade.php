<!-- Vista de tabla para desktop -->
<div class="hidden md:block overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50/80">
            <tr>
                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                    <div class="flex items-center space-x-1">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Proveedor</span>
                    </div>
                </th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                    <div class="flex items-center space-x-1">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Tipo</span>
                    </div>
                </th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                    <div class="flex items-center space-x-1">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Estado</span>
                    </div>
                </th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                    <div class="flex items-center space-x-1">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 0h6m-6 0V7a1 1 0 00-1 1v9a2 2 0 002 2h4a2 2 0 002-2V8a1 1 0 00-1-1V7" />
                        </svg>
                        <span>Fecha</span>
                    </div>
                </th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                    <div class="flex items-center space-x-1">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Revisor</span>
                    </div>
                </th>
                <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                    Acciones
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($tramites as $tramite)
                <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                    <!-- Información del Proveedor -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-sm font-medium text-gray-900 truncate">
                                    {{ $tramite->datosGenerales->razon_social ?? $tramite->proveedor->razon_social ?? 'N/A' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    RFC: {{ $tramite->datosGenerales->rfc ?? $tramite->proveedor->rfc ?? 'N/A' }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    ID: #{{ $tramite->id }}
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Tipo de Trámite -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $tramite->tipo_tramite === 'Inscripcion'
                                ? 'bg-green-100 text-green-800'
                                : ($tramite->tipo_tramite === 'Renovacion'
                                    ? 'bg-blue-100 text-blue-800'
                                    : 'bg-yellow-100 text-yellow-800') }}">
                            {{ $tramite->tipo_tramite === 'Inscripcion' ? 'Inscripción' : 
                               ($tramite->tipo_tramite === 'Renovacion' ? 'Renovación' : 'Actualización') }}
                        </span>
                    </td>

                    <!-- Estado -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $tramite->estado === 'Pendiente'
                                ? 'bg-gray-100 text-gray-800'
                                : ($tramite->estado === 'En_Revision'
                                    ? 'bg-blue-100 text-blue-800'
                                    : ($tramite->estado === 'Aprobado'
                                        ? 'bg-green-100 text-green-800'
                                        : ($tramite->estado === 'Rechazado'
                                            ? 'bg-red-100 text-red-800'
                                            : ($tramite->estado === 'Por_Cotejar'
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : ($tramite->estado === 'Para_Correccion'
                                                    ? 'bg-orange-100 text-orange-800'
                                                    : 'bg-gray-100 text-gray-800'))))) }}">
                            {{ str_replace('_', ' ', $tramite->estado) }}
                        </span>
                    </td>

                    <!-- Fecha -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div class="flex flex-col">
                            <span class="font-medium">{{ $tramite->created_at->format('d/m/Y') }}</span>
                            <span class="text-xs text-gray-400">{{ $tramite->created_at->format('H:i') }}</span>
                        </div>
                    </td>

                    <!-- Revisor -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($tramite->revisadoPor)
                            <div class="flex items-center space-x-2">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <span class="text-xs">{{ $tramite->revisadoPor->name }}</span>
                            </div>
                        @else
                            <span class="text-xs text-gray-400">Sin asignar</span>
                        @endif
                    </td>

                    <!-- Acciones -->
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                        <div class="flex items-center justify-center space-x-2">
                            <!-- Ver -->
                            <a href="{{ route('revision.show', $tramite->id) }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-green-600 hover:text-green-900 hover:bg-green-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                title="Ver trámite">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>

                            <!-- Cambiar Estado -->
                            <button type="button" x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'cambiar-estado-{{ $tramite->id }}')"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:text-blue-900 hover:bg-blue-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                title="Cambiar estado">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>

                            <!-- Revisar Datos -->
                            <a href="{{ route('revision.revisar-datos', $tramite->id) }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-orange-600 hover:text-orange-900 hover:bg-orange-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                                title="Revisar datos completos">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </a>

                           
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="empty-row">
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center space-y-3">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <div class="text-gray-500 text-sm">
                                <p class="font-medium">No se encontraron trámites</p>
                                <p class="mt-1">Ajusta tus filtros o espera nuevos trámites.</p>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Vista de tarjetas para móvil -->
<div class="md:hidden space-y-6 p-2">
    @forelse($tramites as $tramite)
        <div class="bg-white rounded-2xl border-2 border-gray-200 shadow-lg hover:shadow-xl hover:border-[#B4325E]/30 transition-all duration-300 overflow-hidden transform hover:scale-[1.02]">
            <!-- Header de la tarjeta -->
            <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-gray-50/50 to-white">
                <div class="flex items-start space-x-4">
                    <!-- Icono del proveedor -->
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center shadow-md">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Información principal -->
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-bold text-gray-900 mb-2 leading-tight">
                            {{ $tramite->datosGenerales->razon_social ?? $tramite->proveedor->razon_social ?? 'Proveedor N/A' }}
                        </h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            RFC: {{ $tramite->datosGenerales->rfc ?? $tramite->proveedor->rfc ?? 'N/A' }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            ID: #{{ $tramite->id }}
                        </p>
                    </div>
                    
                    <!-- Estado -->
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold shadow-sm border
                            {{ $tramite->estado === 'Pendiente'
                                ? 'bg-gray-100 text-gray-800 border-gray-200'
                                : ($tramite->estado === 'En_Revision'
                                    ? 'bg-blue-100 text-blue-800 border-blue-200'
                                    : ($tramite->estado === 'Aprobado'
                                        ? 'bg-green-100 text-green-800 border-green-200'
                                        : ($tramite->estado === 'Rechazado'
                                            ? 'bg-red-100 text-red-800 border-red-200'
                                            : 'bg-yellow-100 text-yellow-800 border-yellow-200'))) }}">
                            {{ str_replace('_', ' ', $tramite->estado) }}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Detalles de la tarjeta -->
            <div class="p-6 space-y-5 bg-gradient-to-b from-white to-gray-50/30">
                <!-- Fila 1: Tipo de Trámite -->
                <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100 shadow-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Tipo de Trámite</span>
                    </div>
                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-semibold border
                        {{ $tramite->tipo_tramite === 'Inscripcion'
                            ? 'bg-green-100 text-green-800 border-green-200'
                            : ($tramite->tipo_tramite === 'Renovacion'
                                ? 'bg-blue-100 text-blue-800 border-blue-200'
                                : 'bg-yellow-100 text-yellow-800 border-yellow-200') }}">
                        {{ $tramite->tipo_tramite === 'Inscripcion' ? 'Inscripción' : 
                           ($tramite->tipo_tramite === 'Renovacion' ? 'Renovación' : 'Actualización') }}
                    </span>
                </div>
                
                <!-- Fila 2: Fecha y Revisor -->
                <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100 shadow-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 0h6m-6 0V7a1 1 0 00-1 1v9a2 2 0 002 2h4a2 2 0 002-2V8a1 1 0 00-1-1V7" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Fecha</span>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-semibold text-gray-900">{{ $tramite->created_at->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $tramite->created_at->format('H:i') }}</div>
                    </div>
                </div>

                @if($tramite->revisadoPor)
                <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100 shadow-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Revisor</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ $tramite->revisadoPor->name }}</span>
                </div>
                @endif
            </div>
            
            <!-- Acciones -->
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100/50 border-t-2 border-gray-100 flex items-center justify-center space-x-3">
                <!-- Ver -->
                <a href="{{ route('revision.show', $tramite->id) }}"
                    class="flex-1 inline-flex items-center justify-center px-3 py-3 text-sm font-semibold text-green-700 bg-green-50 border-2 border-green-200 rounded-xl hover:bg-green-100 hover:border-green-300 hover:text-green-800 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transform hover:scale-105">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Ver
                </a>

                <!-- Estado -->
                <button type="button" x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'cambiar-estado-{{ $tramite->id }}')"
                    class="flex-1 inline-flex items-center justify-center px-3 py-3 text-sm font-semibold text-blue-700 bg-blue-50 border-2 border-blue-200 rounded-xl hover:bg-blue-100 hover:border-blue-300 hover:text-blue-800 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-105">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Estado
                </button>

                <!-- Revisar Datos -->
                <a href="{{ route('revision.revisar-datos', $tramite->id) }}"
                    class="flex-1 inline-flex items-center justify-center px-3 py-3 text-sm font-semibold text-orange-700 bg-orange-50 border-2 border-orange-200 rounded-xl hover:bg-orange-100 hover:border-orange-300 hover:text-orange-800 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transform hover:scale-105">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Datos
                </a>

              
            </div>
        </div>
    @empty
        <!-- Estado vacío para móvil -->
        <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
            <div class="flex flex-col items-center justify-center space-y-4">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="text-gray-500">
                    <p class="font-medium text-sm">No se encontraron trámites</p>
                    <p class="text-xs mt-1">Ajusta tus filtros o espera nuevos trámites.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>