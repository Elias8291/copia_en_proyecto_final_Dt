@php
    $tramite = $tramites[$tipo];
    $isActive = $tramite['activo'];
    $isPending = $tramite['pendiente'] ?? false;
    $accion = $tramite['accion'] ?? '';
    
    // Determinar el texto y URL de la acción según el tipo y estado
    if ($isPending && $accion === 'tramite_pendiente') {
        $actionText = 'Ver Estado del Trámite';
        $actionUrl = route('tramites.estado');
        $isActive = true; // Permitir clic para ver estado
        $showPendingOverlay = false; // No mostrar overlay, mostrar botón
    } else {
        // Si se proporciona actionText y actionUrl directamente, usarlos
        if (isset($actionText) && isset($actionUrl)) {
            // No hacer nada, usar los valores proporcionados
        } else {
            switch ($tipo) {
                case 'inscripcion':
                    $actionText = 'Comenzar Inscripción';
                    $actionUrl = route('tramites.cargar-constancia', 'inscripcion');
                    break;
                case 'renovacion':
                    $actionText = 'Renovar Registro';
                    $actionUrl = route('tramites.cargar-constancia', 'renovacion');
                    break;
                case 'actualizacion':
                    $actionText = 'Actualizar Datos';
                    $actionUrl = route('tramites.cargar-constancia', 'actualizacion');
                    break;
                default:
                    $actionText = 'Comenzar';
                    $actionUrl = '#';
            }
        }
        $actionUrl = $isActive ? $actionUrl : '#';
        $showPendingOverlay = $isPending; // Mostrar overlay si está pendiente pero no es el trámite activo
    }
@endphp

<x-data-display.tramite-card
    :title="$title"
    :description="$description"
    :isActive="$isActive"
    :isPending="$isPending"
    :gradient="$gradient"
    :actionText="$actionText"
    :actionUrl="$actionUrl"
    :showPendingOverlay="$showPendingOverlay"
    :formData="$formData ?? null"
>
    <x-slot name="icon">
        {!! $icon !!}
    </x-slot>
    
    @if(!$isActive && !$isPending)
        <x-slot name="disabledReason">
            {{ $tramite['motivo'] }}
        </x-slot>
    @endif
    
    @if($isPending && $accion === 'tramite_pendiente')
        <x-slot name="pendingReason">
            {{ $tramite['motivo'] }}
        </x-slot>
    @endif
</x-data-display.tramite-card> 