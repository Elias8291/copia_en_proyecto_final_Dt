@extends('layouts.app')

@section('content')
<div class="w-full max-w-none mx-auto py-8">

    @php
        $columns = [
            [
                'label' => 'Tipo',
                'field' => 'tipo',
                'type' => 'badge',
                'colors' => [
                    'exito' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'advertencia' => 'bg-amber-100 text-amber-700 border-amber-200',
                    'error' => 'bg-red-100 text-red-700 border-red-200',
                    'Tramite' => 'bg-blue-100 text-blue-700 border-blue-200',
                    'Cita' => 'bg-purple-100 text-purple-700 border-purple-200',
                    'informativo' => 'bg-gray-100 text-gray-700 border-gray-200'
                ]
            ],
            [
                'label' => 'Mensaje',
                'field' => 'mensaje',
                'type' => 'text'
            ],
            [
                'label' => 'Estado',
                'field' => 'leida',
                'type' => 'badge',
                'colors' => [
                    '0' => 'bg-blue-100 text-blue-700 border-blue-200',
                    '1' => 'bg-gray-100 text-gray-700 border-gray-200'
                ]
            ],
            [
                'label' => 'Fecha',
                'field' => 'created_at',
                'type' => 'datetime'
            ]
        ];

        $filters = [
            [
                'id' => 'status',
                'type' => 'select',
                'placeholder' => 'Estado',
                'options' => [
                    'no_leidas' => 'No leídas',
                    'leidas' => 'Leídas'
                ]
            ],
            [
                'id' => 'type',
                'type' => 'select',
                'placeholder' => 'Tipo',
                'options' => [
                    'informativo' => 'Informativo',
                    'advertencia' => 'Advertencia',
                    'error' => 'Error',
                    'exito' => 'Éxito',
                    'Tramite' => 'Trámite',
                    'Cita' => 'Cita'
                ]
            ]
        ];

        $actions = [
            'mark-read' => [
                'label' => 'Marcar como leída',
                'color' => 'text-gray-600',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>',
                'url' => 'notificaciones.marcar-leida',
                'method' => 'POST',
                'showIf' => '!leida'
            ],
            'delete' => [
                'label' => 'Eliminar',
                'color' => 'text-red-600',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>',
                'url' => 'notificaciones.eliminar',
                'method' => 'DELETE',
                'modalTitle' => 'Eliminar Notificación',
                'modalMessage' => '¿Está seguro de que desea eliminar esta notificación?',
                'confirmText' => 'Eliminar',
                'cancelText' => 'Cancelar',
                'itemType' => 'notificación'
            ]
        ];
    @endphp

    <x-simple-data-table 
        title="Mis Notificaciones"
        description="Gestiona todas tus notificaciones personales del sistema"
        :data="$notificaciones"
        :columns="$columns"
        :filters="$filters"
        :actions="$actions"
        searchPlaceholder="Buscar en notificaciones..."
        :showActions="true"
    />
                    </div>

<!-- Modal de error -->
<x-error-modal 
    id="error-modal"
    title="Error"
    message="Ha ocurrido un error. Por favor, inténtalo de nuevo."
    buttonText="OK"
/>

<!-- Modal de éxito -->
<x-modal-exito 
    id="success-modal"
    title="¡Éxito!"
    message="La operación se realizó correctamente."
    acceptText="Aceptar"
    :redirectUrl="route('notificaciones.index')"
/>

<!-- Mostrar modal de error si hay error de sesión -->
@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    showErrorModal('error-modal', 'Error', '{{ session('error') }}');
});
</script>
                            @endif
                            
<!-- Mostrar modal de éxito si hay éxito de sesión -->
@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    showSuccessModal('success-modal', '¡Éxito!', '{{ session('success') }}');
});
</script>
@endif
@endsection
