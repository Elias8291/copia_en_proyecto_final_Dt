@php
    $tramite = $tramites[$tipo];
    $isActive = $tramite['activo'];
    $finalActionUrl = $isActive ? $actionUrl : '#';
@endphp

<x-tramite-card
    :title="$title"
    :description="$description"
    :isActive="$isActive"
    :gradient="$gradient"
    :actionText="$actionText"
    :actionUrl="$finalActionUrl"
>
    <x-slot name="icon">
        {!! $icon !!}
    </x-slot>
    
    @if(!$isActive)
        <x-slot name="disabledReason">
            {{ $tramite['motivo'] }}
        </x-slot>
    @endif
</x-tramite-card> 