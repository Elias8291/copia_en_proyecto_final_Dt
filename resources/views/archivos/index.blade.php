@extends('layouts.app')

@section('content')
<div class="w-full max-w-none mx-auto py-8">

    @php
        $columns = [
            [
                'label' => 'Archivo',
                'field' => 'nombre',
                'type' => 'avatar'
            ],
            [
                'label' => 'Tipo Persona',
                'field' => 'tipo_persona',
                'type' => 'badge',
                'colors' => [
                    'Física' => 'bg-blue-50 text-blue-600 border-blue-200',
                    'Moral' => 'bg-green-50 text-green-600 border-green-200',
                    'Ambas' => 'bg-purple-50 text-purple-600 border-purple-200'
                ]
            ],
            [
                'label' => 'Tipo Archivo',
                'field' => 'tipo_archivo',
                'type' => 'badge',
                'colors' => [
                    'pdf' => 'bg-red-50 text-red-600 border-red-200',
                    'png' => 'bg-blue-50 text-blue-600 border-blue-200',
                    'mp3' => 'bg-yellow-50 text-yellow-600 border-yellow-200',
                    'mp4' => 'bg-purple-50 text-purple-600 border-purple-200'
                ]
            ],
            [
                'label' => 'Estado',
                'field' => 'estado',
                'type' => 'badge',
                'colors' => [
                    'activo' => 'bg-green-50 text-green-600 border-green-200',
                    'inactivo' => 'bg-gray-50 text-gray-600 border-gray-200'
                ]
            ],
            [
                'label' => 'Fecha Creación',
                'field' => 'created_at',
                'type' => 'date'
            ]
        ];

        $filters = [
            [
                'id' => 'tipo_persona',
                'type' => 'select',
                'placeholder' => 'Tipo Persona',
                'options' => [
                    'Física' => 'Física',
                    'Moral' => 'Moral',
                    'Ambas' => 'Ambas'
                ]
            ],
            [
                'id' => 'tipo_archivo',
                'type' => 'select',
                'placeholder' => 'Tipo Archivo',
                'options' => [
                    'pdf' => 'PDF',
                    'png' => 'PNG',
                    'mp3' => 'MP3',
                    'mp4' => 'MP4'
                ]
            ],
            [
                'id' => 'es_visible',
                'type' => 'select',
                'placeholder' => 'Estado',
                'options' => [
                    'true' => 'Activo',
                    'false' => 'Inactivo'
                ]
            ]
        ];

        $actions = [
            'create' => [
                'label' => 'Nuevo Archivo',
                'color' => 'text-white',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>',
                'url' => route('archivos.create')
            ],
            'view' => [
                'label' => 'Ver detalles',
                'color' => 'text-primary',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>',
                'url' => 'archivos.show'
            ],
            'edit' => [
                'label' => 'Editar',
                'color' => 'text-blue-600',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>',
                'url' => 'archivos.edit'
            ],
            'delete' => [
                'label' => 'Eliminar',
                'color' => 'text-red-600',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>',
                'url' => 'archivos.destroy',
                'method' => 'DELETE',
                'modalTitle' => 'Eliminar Archivo',
                'modalMessage' => '¿Está seguro de que desea eliminar este archivo? Esta acción no se puede deshacer.',
                'confirmText' => 'Eliminar Archivo',
                'cancelText' => 'Cancelar',
                'itemType' => 'archivo'
            ]
        ];
    @endphp

    <x-simple-data-table 
        title="Gestión de Archivos"
        description="Administra y revisa los archivos del catálogo"
        :data="$archivos"
        :columns="$columns"
        :filters="$filters"
        :actions="$actions"
        searchPlaceholder="Buscar archivos por nombre o descripción..."
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
    :redirectUrl="route('archivos.index')"
/>

<!-- Mostrar modal de error si hay error de sesión -->
@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    showErrorModal('error-modal', 'Error', '{{ session('error') }}');
});
</script>
@endif
@endsection
