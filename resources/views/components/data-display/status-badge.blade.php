@props([
    'estado' => 'pendiente',
    'texto' => null,
    'size' => 'md', // sm, md, lg
    'customColors' => null // ['bg' => 'bg-custom-100', 'text' => 'text-custom-800']
])

@php
    // Determinar texto si no se proporciona
    $textoFinal = $texto ?: match($estado) {
        'pendiente' => 'Pendiente',
        'revision_digital' => 'Revisión Digital',
        'revision_presencial' => 'Revisión Presencial',
        'revision_domiciliaria' => 'Revisión Domiciliaria',
        'aprobado' => 'Aprobado',
        'rechazado' => 'Rechazado', 
        'para_correccion' => 'Para Corrección',
        'activo' => 'Activo',
        'inactivo' => 'Inactivo',
        'completado' => 'Completado',
        'cancelado' => 'Cancelado',
        'en_proceso' => 'En Proceso',
        // Estados antiguos (compatibilidad)
        'en_revision' => 'En Revisión',
        'por_cotejar' => 'Por Cotejar',
        default => ucfirst(str_replace('_', ' ', $estado))
    };

    // Configurar colores
    if ($customColors) {
        $colorClasses = $customColors['bg'] . ' ' . $customColors['text'];
    } else {
        $colorClasses = match($estado) {
            'pendiente' => 'bg-gray-100 text-gray-800',
            'revision_digital' => 'bg-blue-100 text-blue-800',
            'revision_presencial' => 'bg-purple-100 text-purple-800',
            'revision_domiciliaria' => 'bg-indigo-100 text-indigo-800',
            'aprobado', 'activo', 'completado' => 'bg-green-100 text-green-800',
            'rechazado', 'cancelado', 'inactivo' => 'bg-red-100 text-red-800',
            'para_correccion' => 'bg-orange-100 text-orange-800',
            'en_proceso' => 'bg-yellow-100 text-yellow-800',
            // Estados antiguos (compatibilidad)
            'en_revision' => 'bg-yellow-100 text-yellow-800',
            'por_cotejar' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-600'
        };
    }

    // Configurar tamaños
    $sizeClasses = match($size) {
        'sm' => 'px-2 py-1 text-xs',
        'md' => 'px-2.5 py-0.5 text-xs',
        'lg' => 'px-3 py-1 text-sm',
        default => 'px-2.5 py-0.5 text-xs'
    };
@endphp

<span class="inline-flex items-center {{ $sizeClasses }} rounded-full font-medium {{ $colorClasses }}">
    @if($estado === 'aprobado' || $estado === 'activo' || $estado === 'completado')
        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    @elseif($estado === 'rechazado' || $estado === 'cancelado' || $estado === 'inactivo')
        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    @elseif(in_array($estado, ['pendiente', 'revision_digital', 'revision_presencial', 'revision_domiciliaria', 'en_revision', 'en_proceso']))
        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    @elseif($estado === 'para_correccion')
        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
        </svg>
    @endif
    {{ $textoFinal }}
</span> 