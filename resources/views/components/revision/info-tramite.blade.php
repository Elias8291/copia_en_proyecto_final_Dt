@props([
    'tramite' => null,
    'tipoRevision' => '',
    'revision' => null
])

@php
    $tipoRevisionLabel = match($tipoRevision) {
        'Digital' => 'Revisión Digital',
        'Presencial' => 'Revisión Presencial', 
        'Domiciliaria' => 'Revisión Domiciliaria',
        default => 'Revisión'
    };
@endphp

<!-- Información del trámite -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <span class="text-sm font-medium text-blue-800">Tipo de Trámite:</span>
            <p class="text-blue-900">{{ $tramite->tipo_tramite }}</p>
        </div>
        <div>
            <span class="text-sm font-medium text-blue-800">Estado:</span>
            <p class="text-blue-900">{{ $tramite->status }}</p>
        </div>
        <div>
            <span class="text-sm font-medium text-blue-800">Fecha de Creación:</span>
            <p class="text-blue-900">{{ $tramite->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div>
            <span class="text-sm font-medium text-blue-800">Tipo de Revisión:</span>
            <p class="text-blue-900">{{ $tipoRevisionLabel }}</p>
        </div>
    </div>
</div>

@if($revision)
<!-- Información de la revisión guardada -->
<div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-green-800">Revisión Guardada</h3>
                <p class="text-sm text-green-700">
                    {{ $revision->tipo_revision_label }} - Iniciada el {{ $revision->fecha_inicio->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
            {{ $revision->estado_label }}
        </span>
    </div>
</div>
@else
<!-- Revisión temporal (no guardada) -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-blue-800">{{ $tipoRevisionLabel }} en Progreso</h3>
                <p class="text-sm text-blue-700">La revisión se guardará al finalizar el proceso</p>
            </div>
        </div>
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
            En Progreso
        </span>
    </div>
</div>
@endif 