<!-- Vista de tabla para desktop -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Proveedor</span>
                        </div>
                    </th>
                    <th scope="col" class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span>Tipo</span>
                        </div>
                    </th>
                    <th scope="col" class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Estado</span>
                        </div>
                    </th>
                    <th scope="col" class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 0h6m-6 0V7a1 1 0 00-1 1v9a2 2 0 002 2h4a2 2 0 002-2V8a1 1 0 00-1-1V7" />
                            </svg>
                            <span>Fecha</span>
                        </div>
                    </th>
                    <th scope="col" class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Revisor</span>
                        </div>
                    </th>
                    <th scope="col" class="px-4 sm:px-6 py-3 sm:py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($tramites as $tramite)
                    <tr class="hover:bg-gray-50/50">
                        <!-- Información del Proveedor -->
                        <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="flex items-start space-x-2 sm:space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-lg p-1.5 sm:p-2">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $tramite->tipo_tramite === 'Inscripcion' ? 'Inscripción' : 
                                   ($tramite->tipo_tramite === 'Renovacion' ? 'Renovación' : 'Actualización') }}
                            </span>
                        </td>

                        <!-- Estado -->
                        <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ str_replace('_', ' ', $tramite->estado) }}
                            </span>
                        </td>

                        <!-- Fecha -->
                        <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex flex-col">
                                <span class="font-medium">{{ $tramite->created_at->format('d/m/Y') }}</span>
                                <span class="text-xs text-gray-400">{{ $tramite->created_at->format('H:i') }}</span>
                            </div>
                        </td>

                        <!-- Revisor -->
                        <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($tramite->revisadoPor)
                                <div class="flex items-center space-x-2">
                                    <div class="bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-full p-1">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center">
                                <!-- Revisar Datos -->
                                <a href="{{ route('revision.seleccion-tipo', $tramite->id) }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-[#B4325E] hover:text-[#7a1d37] hover:bg-[#B4325E]/10 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:ring-offset-2"
                                    title="Iniciar revisión">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
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
</div>

<!-- Vista de tarjetas para móvil -->
<div class="md:hidden space-y-3 p-2">
    @forelse($tramites as $tramite)
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md overflow-hidden">
            <!-- Header de la tarjeta -->
            <div class="p-3 border-b border-gray-100">
                <div class="flex items-start gap-3">
                    <!-- Icono del proveedor -->
                    <div class="flex-shrink-0">
                        <div class="bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-lg p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Información principal -->
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold text-gray-900 mb-1 leading-tight truncate">
                            {{ $tramite->datosGenerales->razon_social ?? $tramite->proveedor->razon_social ?? 'Proveedor N/A' }}
                        </h3>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            RFC: {{ $tramite->datosGenerales->rfc ?? $tramite->proveedor->rfc ?? 'N/A' }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            ID: #{{ $tramite->id }}
                        </p>
                    </div>
                    
                    <!-- Estado -->
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-800">
                            {{ str_replace('_', ' ', $tramite->estado) }}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Detalles de la tarjeta -->
            <div class="p-3 space-y-2">
                <!-- Fila 1: Tipo de Trámite -->
                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-2">
                        <div class="bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-lg p-1">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700">Tipo</span>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-800">
                        {{ $tramite->tipo_tramite === 'Inscripcion' ? 'Inscripción' : 
                           ($tramite->tipo_tramite === 'Renovacion' ? 'Renovación' : 'Actualización') }}
                    </span>
                </div>
                
                <!-- Fila 2: Fecha y Revisor -->
                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-2">
                        <div class="bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-lg p-1">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 0h6m-6 0V7a1 1 0 00-1 1v9a2 2 0 002 2h4a2 2 0 002-2V8a1 1 0 00-1-1V7" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700">Fecha</span>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-semibold text-gray-900">{{ $tramite->created_at->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $tramite->created_at->format('H:i') }}</div>
                    </div>
                </div>

                @if($tramite->revisadoPor)
                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-2">
                        <div class="bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-lg p-1">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700">Revisor</span>
                    </div>
                    <span class="text-xs font-semibold text-gray-900">{{ $tramite->revisadoPor->name }}</span>
                </div>
                @endif
            </div>
            
            <!-- Acciones -->
            <div class="px-3 py-2 bg-gray-50 border-t border-gray-100 flex items-center justify-center">
                <!-- Revisar Datos -->
                <a href="{{ route('revision.seleccion-tipo', $tramite->id) }}"
                    class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-[#B4325E] bg-[#B4325E]/10 border border-[#B4325E]/20 rounded-lg hover:bg-[#B4325E]/20 hover:border-[#B4325E]/30 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:ring-offset-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Iniciar Revisión
                </a>
            </div>
        </div>
    @empty
        <!-- Estado vacío para móvil -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 text-center">
            <div class="flex flex-col items-center justify-center space-y-3">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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