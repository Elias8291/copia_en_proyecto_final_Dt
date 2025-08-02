@extends('layouts.app')

@section('content')
<div class="w-full max-w-none mx-auto py-8">

    @php
        $columns = [
            [
                'label' => 'Usuario',
                'field' => 'nombre',
                'type' => 'avatar',
                'subfield' => 'email',
                'subfield_label' => 'Email'
            ],
            [
                'label' => 'RFC',
                'field' => 'rfc',
                'type' => 'text'
            ],
            [
                'label' => 'Rol',
                'field' => 'rol',
                'type' => 'badge',
                'colors' => [
                    'admin' => 'bg-red-100 text-red-700 border-red-200',
                    'user' => 'bg-blue-100 text-blue-700 border-blue-200',
                    'moderator' => 'bg-green-100 text-green-700 border-green-200'
                ]
            ],
            [
                'label' => 'Estado',
                'field' => 'estado',
                'type' => 'badge',
                'colors' => [
                    'activo' => 'bg-green-100 text-green-700 border-green-200',
                    'inactivo' => 'bg-gray-100 text-gray-700 border-gray-200',
                    'pendiente' => 'bg-yellow-100 text-yellow-700 border-yellow-200'
                ]
            ],
            [
                'label' => 'Fecha Registro',
                'field' => 'created_at',
                'type' => 'date'
            ]
        ];

        $filters = [
            [
                'id' => 'rol',
                'type' => 'select',
                'placeholder' => 'Rol',
                'options' => [
                    'admin' => 'Administrador',
                    'user' => 'Usuario',
                    'moderator' => 'Moderador'
                ]
            ],
            [
                'id' => 'estado',
                'type' => 'select',
                'placeholder' => 'Estado',
                'options' => [
                    'activo' => 'Activo',
                    'inactivo' => 'Inactivo',
                    'pendiente' => 'Pendiente'
                ]
            ]
        ];

        $actions = [
            'create' => [
                'label' => 'Nuevo Usuario',
                'color' => 'text-white',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>',
                'url' => route('users.create')
            ],
            'view' => [
                'label' => 'Ver detalles',
                'color' => 'text-primary',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>',
                'url' => 'users.show'
            ],
            'edit' => [
                'label' => 'Editar',
                'color' => 'text-blue-600',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>',
                'url' => 'users.edit'
            ],
            'delete' => [
                'label' => 'Eliminar',
                'color' => 'text-red-600',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>',
                'url' => 'users.destroy',
                'method' => 'DELETE',
                'modalTitle' => 'Eliminar Usuario',
                'modalMessage' => '¿Está seguro de que desea eliminar este usuario? Esta acción no se puede deshacer.',
                'confirmText' => 'Eliminar Usuario',
                'cancelText' => 'Cancelar',
                'itemType' => 'usuario'
            ]
        ];
    @endphp

    <x-simple-data-table 
        title="Gestión de Usuarios"
        description="Administra y revisa los usuarios del sistema"
        :data="$users"
        :columns="$columns"
        :filters="$filters"
        :actions="$actions"
        searchPlaceholder="Buscar usuarios por nombre, email, RFC o rol..."
        routeKeyName="user"
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
    :redirectUrl="route('users.index')"
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
