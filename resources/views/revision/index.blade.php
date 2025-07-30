@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    @php
        $columns = [
            [
                'label' => 'Proveedor',
                'field' => 'proveedor.user.nombre',
                'type' => 'avatar',
                'subfield' => 'proveedor.rfc',
                'subfield_label' => 'RFC'
            ],
            [
                'label' => 'Tipo de Trámite',
                'field' => 'tipo_tramite',
                'type' => 'badge',
                'colors' => [
                    'Constancia' => 'bg-blue-100 text-blue-700 border-blue-200',
                    'Licencia' => 'bg-green-100 text-green-700 border-green-200',
                    'Permiso' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                    'Registro' => 'bg-purple-100 text-purple-700 border-purple-200',
                    'Inscripcion' => 'bg-indigo-100 text-indigo-700 border-indigo-200'
                ]
            ],
            [
                'label' => 'Estado',
                'field' => 'estado',
                'type' => 'badge',
                'colors' => [
                    'Pendiente' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                    'En_Revision' => 'bg-blue-100 text-blue-700 border-blue-200',
                    'Por_Cotejar' => 'bg-orange-100 text-orange-700 border-orange-200',
                    'Aprobado' => 'bg-green-100 text-green-700 border-green-200',
                    'Rechazado' => 'bg-red-100 text-red-700 border-red-200',
                    'Para_Correccion' => 'bg-pink-100 text-pink-700 border-pink-200',
                    'Cancelado' => 'bg-gray-100 text-gray-700 border-gray-200'
                ]
            ],
            [
                'label' => 'Fecha de Solicitud',
                'field' => 'created_at',
                'type' => 'date'
            ],

            [
                'label' => 'Correcciones',
                'field' => 'correcciones_count',
                'type' => 'badge',
                'colors' => [
                    '0' => 'bg-gray-100 text-gray-700 border-gray-200',
                    '1' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                    '2' => 'bg-orange-100 text-orange-700 border-orange-200',
                    '3' => 'bg-red-100 text-red-700 border-red-200'
                ],
                'format' => function($value, $row) {
                    return $row->correcciones_texto;
                }
            ]
        ];

        $filters = [
            [
                'id' => 'tipo-tramite',
                'type' => 'select',
                'placeholder' => 'Tipo de Trámite',
                'options' => [
                    'Constancia' => 'Constancia',
                    'Licencia' => 'Licencia',
                    'Permiso' => 'Permiso',
                    'Registro' => 'Registro',
                    'Inscripcion' => 'Inscripción'
                ]
            ],
            [
                'id' => 'estado',
                'type' => 'select',
                'placeholder' => 'Estado',
                'options' => [
                    'Pendiente' => 'Pendiente',
                    'En_Revision' => 'En Revisión',
                    'Por_Cotejar' => 'Por Cotejar',
                    'Aprobado' => 'Aprobado',
                    'Rechazado' => 'Rechazado',
                    'Para_Correccion' => 'Para Corrección',
                    'Cancelado' => 'Cancelado'
                ]
            ]
        ];

        $actions = [
            'review' => [
                'label' => 'Ver Revisión',
                'color' => 'text-green-600',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>',
                'url' => 'revision.revisar',
                'params' => ['tramite' => '$item->id', 'tipo' => 'seleccion-tipo']
            ]
        ];
    @endphp

    <x-simple-data-table 
        title="Gestión de Revisiones"
        description="Administra y revisa los trámites pendientes de revisión"
        :data="$tramites"
        :columns="$columns"
        :filters="$filters"
        :actions="$actions"
        searchPlaceholder="Buscar revisiones por proveedor, RFC o tipo de trámite..."
    />

    <x-modal-exito 
        id="modal-exito"
        title="¡Trámite procesado!"
        message="El trámite ha sido procesado exitosamente."
        accept-text="Entendido"
        :redirect-url="route('revision.index')"
    />
</div>
@endsection
