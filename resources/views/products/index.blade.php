@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <x-data-table 
        title="Catálogo de Productos"
        description="Gestiona el inventario de productos"
        :data="$products"
        :columns="[
            [
                'label' => 'Producto',
                'field' => 'nombre',
                'type' => 'avatar',
                'subfield' => 'codigo',
                'subfield_label' => 'Código'
            ],
            [
                'label' => 'Categoría',
                'field' => 'categoria',
                'type' => 'badge',
                'colors' => [
                    'electronica' => 'bg-blue-100 text-blue-700 border-blue-200',
                    'ropa' => 'bg-purple-100 text-purple-700 border-purple-200',
                    'hogar' => 'bg-green-100 text-green-700 border-green-200',
                    'deportes' => 'bg-orange-100 text-orange-700 border-orange-200'
                ]
            ],
            [
                'label' => 'Stock',
                'field' => 'stock',
                'type' => 'badge',
                'colors' => [
                    'disponible' => 'bg-green-100 text-green-700 border-green-200',
                    'agotado' => 'bg-red-100 text-red-700 border-red-200',
                    'bajo' => 'bg-yellow-100 text-yellow-700 border-yellow-200'
                ]
            ],
            [
                'label' => 'Precio',
                'field' => 'precio'
            ],
            [
                'label' => 'Fecha Creación',
                'field' => 'created_at',
                'type' => 'date'
            ]
        ]"
        :filters="[
            [
                'id' => 'categoria',
                'type' => 'select',
                'placeholder' => 'Categoría',
                'options' => [
                    'electronica' => 'Electrónica',
                    'ropa' => 'Ropa',
                    'hogar' => 'Hogar',
                    'deportes' => 'Deportes'
                ]
            ],
            [
                'id' => 'stock',
                'type' => 'select',
                'placeholder' => 'Stock',
                'options' => [
                    'disponible' => 'Disponible',
                    'agotado' => 'Agotado',
                    'bajo' => 'Stock Bajo'
                ]
            ],
            [
                'id' => 'precio',
                'type' => 'input',
                'placeholder' => 'Precio mínimo'
            ]
        ]"
        searchPlaceholder="Buscar productos por nombre, código o categoría..."
        :actions="[
            'create' => [
                'label' => 'Nuevo Producto',
                'color' => 'text-white',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>'
            ],
            'view' => [
                'label' => 'Ver detalles',
                'color' => 'text-primary',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>'
            ],
            'edit' => [
                'label' => 'Editar',
                'color' => 'text-blue-600',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>'
            ],
            'delete' => [
                'label' => 'Eliminar',
                'color' => 'text-red-600',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>'
            ]
        ]"
    />
</div>
@endsection 