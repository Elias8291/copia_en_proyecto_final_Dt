@extends('layouts.app')

@section('title', 'Citas')

@section('content')
<div class="container mx-auto px-4 py-8">
    @php
        $columns = [
            [
                'label' => 'Usuario',
                'field' => 'user.nombre',
                'type' => 'avatar',
                'subfield' => 'user.rfc',
                'subfield_label' => 'RFC'
            ],
            [
                'label' => 'Tipo',
                'field' => 'tipo_cita',
                'type' => 'badge',
                'colors' => [
                    'Revisión' => 'bg-blue-100 text-blue-800 border-blue-200',
                    'Cotejo' => 'bg-green-100 text-green-800 border-green-200',
                    'Otro' => 'bg-gray-100 text-gray-700 border-gray-200'
                ]
            ],
            [
                'label' => 'Trámite',
                'field' => 'id_tramite',
                'type' => 'custom',
                'template' => 'citas.tramite-link'
            ],
            [
                'label' => 'Estado',
                'field' => 'estado',
                'type' => 'badge',
                'colors' => [
                    'Programada' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                    'Confirmada' => 'bg-green-100 text-green-800 border-green-200',
                    'Cancelada' => 'bg-red-100 text-red-800 border-red-200'
                ]
            ],
            [
                'label' => 'Fecha',
                'field' => 'fecha_cita',
                'type' => 'date'
            ],
            [
                'label' => 'Hora',
                'field' => 'fecha_cita',
                'type' => 'time'
            ]
        ];

        $filters = [
            [
                'id' => 'hoy',
                'type' => 'select',
                'placeholder' => 'Filtrar por fecha',
                'options' => [
                    '' => 'Todas las citas',
                    '1' => 'Solo hoy'
                ]
            ],
            [
                'id' => 'fecha',
                'type' => 'date',
                'placeholder' => 'Seleccionar fecha'
            ],
            [
                'id' => 'tipo_cita',
                'type' => 'select',
                'placeholder' => 'Tipo de Cita',
                'options' => [
                    'Revisión' => 'Revisión',
                    'Cotejo' => 'Cotejo',
                    'Otro' => 'Otro'
                ]
            ],
            [
                'id' => 'estado',
                'type' => 'select',
                'placeholder' => 'Estado',
                'options' => [
                    'Programada' => 'Programada',
                    'Confirmada' => 'Confirmada',
                    'Cancelada' => 'Cancelada'
                ]
            ]
        ];

        $actions = [
            'create' => [
                'label' => 'Nueva Cita',
                'color' => 'text-white',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>',
                'url' => route('citas.create')
            ],
            'view' => [
                'label' => 'Ver',
                'color' => 'text-blue-600',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>',
                'url' => 'citas.show'
            ],
            'edit' => [
                'label' => 'Editar',
                'color' => 'text-green-600',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>',
                'url' => 'citas.edit'
            ],
            'delete' => [
                'label' => 'Eliminar',
                'color' => 'text-red-600',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>',
                'url' => 'citas.destroy'
            ]
        ];
    @endphp

    <x-simple-data-table
        title="Gestión de Citas"
        description="Administra y revisa las citas registradas"
        :data="$citas"
        :columns="$columns"
        :filters="$filters"
        :actions="$actions"
        searchPlaceholder="Buscar por usuario, tipo o estado..."
    />
</div>
@endsection 