@php
    $accionistas = $tramite->accionistas;
@endphp

<div class="space-y-4">
    @if($accionistas && $accionistas->count() > 0)
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-medium text-gray-700">Lista de Accionistas</h4>
                    <span class="text-xs text-gray-500">{{ $accionistas->count() }} accionista(s)</span>
                </div>
            </div>
            
            <div class="divide-y divide-gray-200">
                @foreach($accionistas as $index => $accionista)
                    <div class="px-4 py-3 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-sm font-medium text-blue-600">{{ $index + 1 }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $accionista->nombre_completo ?? 'Accionista ' . ($index + 1) }}
                                        </p>
                                        @if($accionista->rfc)
                                            <p class="text-xs text-gray-500 font-mono">{{ $accionista->rfc }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ number_format($accionista->porcentaje_participacion, 2) }}%
                                    </p>
                                    <p class="text-xs text-gray-500">Participación</p>
                                </div>
                                @if($accionista->activo)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Inactivo
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Resumen de participaciones -->
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Total de Participaciones</span>
                    <span class="text-sm font-semibold text-gray-900">
                        {{ number_format($accionistas->sum('porcentaje_participacion'), 2) }}%
                    </span>
                </div>
                @php
                    $totalParticipacion = $accionistas->sum('porcentaje_participacion');
                @endphp
                @if($totalParticipacion != 100)
                    <div class="mt-2 flex items-center space-x-2">
                        @if($totalParticipacion < 100)
                            <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            <span class="text-xs text-yellow-700">
                                Participación incompleta ({{ number_format(100 - $totalParticipacion, 2) }}% faltante)
                            </span>
                        @else
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            <span class="text-xs text-red-700">
                                Participación excede 100% ({{ number_format($totalParticipacion - 100, 2) }}% extra)
                            </span>
                        @endif
                    </div>
                @else
                    <div class="mt-2 flex items-center space-x-2">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-xs text-green-700">Participación completa (100%)</span>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <span class="text-sm font-medium text-yellow-800">Sin accionistas registrados</span>
            </div>
            <p class="text-sm text-yellow-700 mt-1">No se encontraron accionistas registrados para este trámite.</p>
        </div>
    @endif
</div> 