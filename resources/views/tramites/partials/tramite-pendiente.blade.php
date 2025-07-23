@if ($globalTramites['tiene_tramite_pendiente'] && $globalTramites['tramite_pendiente'])
    @php
        $detalles = $globalTramites['tramite_pendiente'];
        $tramite = $detalles['tramite'];
    @endphp

    <div class="relative bg-white rounded-2xl shadow-lg border border-gray-200/70 overflow-hidden mb-6">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#9D2449] to-[#B91C1C]"></div>

        <div class="p-5">
            <div class="flex items-center space-x-4 mb-4">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-[#9D2449] to-[#B91C1C] rounded-xl flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-gray-800">Trámite en Proceso</h2>
                    <p class="text-sm text-gray-600">Espere a que su información ha sido cotejada. Le notificaremos
                        cualquier actualización.</p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div class="text-center p-3 bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg">
                    <div class="text-xs text-gray-500 font-medium mb-1">Folio</div>
                    <div class="text-sm font-bold text-[#9D2449]">#{{ str_pad($tramite->id, 4, '0', STR_PAD_LEFT) }}
                    </div>
                </div>

                <div class="text-center p-3 bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg">
                    <div class="text-xs text-gray-500 font-medium mb-1">Tipo</div>
                    <div class="text-sm font-bold text-gray-800">{{ $tramite->tipo_tramite }}</div>
                </div>

                <div class="text-center p-3 bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg">
                    <div class="text-xs text-gray-500 font-medium mb-1">Tiempo</div>
                    <div class="text-sm font-bold text-gray-800">{{ $tramite->tiempo_transcurrido }}</div>
                </div>

                <div class="text-center p-3 bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg">
                    <div class="text-xs text-gray-500 font-medium mb-1">Estado</div>
                    <span
                        class="inline-block px-2 py-1 rounded-full text-xs font-semibold {{ $detalles['estado_color'] }}">
                        {{ $tramite->estado }}
                    </span>
                </div>
            </div>

            <div
                class="bg-gradient-to-r from-[#9D2449]/5 to-[#B91C1C]/5 rounded-xl p-4 mb-4 border border-[#9D2449]/10">
                <div class="flex items-start space-x-3">
                    <div class="w-2 h-2 bg-[#9D2449] rounded-full mt-2 flex-shrink-0"></div>
                    <div>
                        <h4 class="font-semibold text-gray-800 text-sm mb-1">Estado Actual</h4>
                        <p class="text-gray-700 text-xs leading-relaxed">{{ $detalles['estado_descripcion'] }}</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 justify-center">
                <a href="{{ route('tramites.verificar.completo', $tramite->id) }}"
                    class="bg-[#9D2449] hover:bg-[#B91C1C] text-white px-6 py-2.5 rounded-lg font-medium text-center transition-colors duration-200 shadow-sm hover:shadow-md text-sm">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                        <span>Ver Detalles</span>
                    </div>
                </a>

                @if ($tramite->estado === 'Para_Correccion')
                    <a href="{{ route('tramites.formulario', strtolower($tramite->tipo_tramite)) }}"
                        class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2.5 rounded-lg font-medium text-center transition-colors duration-200 shadow-sm hover:shadow-md text-sm">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            <span>Corregir Trámite</span>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </div>
@endif
