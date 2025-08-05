@php
    $tramite = $tramites[$tipo];
    $isActive = $tramite['activo'];
    $isPending = $tramite['pendiente'] ?? false;
    
    // Determinar el texto y URL de la acción según el tipo y estado
    if ($isPending) {
        $actionText = 'Ver Trámite Pendiente';
        $actionUrl = route('tramites.estado');
        $isActive = true; // Permitir clic para ver estado
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
        $actionUrl = $isActive ? $actionUrl : '#';
    }
@endphp

<x-tramite-card
    :title="$title"
    :description="$description"
    :isActive="$isActive"
    :isPending="$isPending"
    :gradient="$gradient"
    :actionText="$actionText"
    :actionUrl="$actionUrl"
>
    <x-slot name="icon">
        {!! $icon !!}
    </x-slot>
    
    @if(!$isActive && !$isPending)
        <x-slot name="disabledReason">
            {{ $tramite['motivo'] }}
        </x-slot>
    @endif
    
    @if($isPending)
        <x-slot name="pendingReason">
            {{ $tramite['motivo'] }}
        </x-slot>
    @endif
</x-tramite-card> 