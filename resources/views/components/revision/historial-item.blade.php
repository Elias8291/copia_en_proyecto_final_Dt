@props([
    'tramiteHistorico' => null,
    'tramiteActual' => null
])

<div class="border border-gray-200 rounded-lg p-4 {{ $tramiteHistorico['id'] == $tramiteActual->id ? 'bg-blue-50 border-blue-300' : 'bg-gray-50' }}">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    @switch($tramiteHistorico['status'])
                        @case('Aprobado')
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            @break
                        @case('Rechazado')
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            @break
                        @default
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                    @endswitch
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-2">
                        <h4 class="text-sm font-medium text-gray-900">
                            Trámite #{{ $tramiteHistorico['id'] }}
                            @if($tramiteHistorico['id'] == $tramiteActual->id)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                    Actual
                                </span>
                            @endif
                        </h4>
                        <x-status-badge 
                            :estado="strtolower(str_replace(' ', '_', $tramiteHistorico['status']))"
                            :texto="$tramiteHistorico['status']"
                            size="sm"
                        />
                    </div>
                    <p class="text-sm text-gray-500 truncate">{{ $tramiteHistorico['razon_social'] }}</p>
                    <div class="flex items-center text-xs text-gray-400 mt-1">
                        <span>{{ $tramiteHistorico['tipo_tramite'] }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ $tramiteHistorico['created_at']->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
        @if($tramiteHistorico['id'] != $tramiteActual->id)
            <div class="flex-shrink-0 ml-4">
                <x-action-button 
                    tipo="outline-secondary"
                    size="sm"
                    :url="route('revisiones.ver-historico', $tramiteHistorico['id'])"
                    :icono="'<svg class=\"w-3 h-3\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">
                        <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M15 12a3 3 0 11-6 0 3 3 0 016 0z\"/>
                        <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z\"/>
                    </svg>'"
                >
                    Ver Trámite
                </x-action-button>
            </div>
        @endif
    </div>
</div> 