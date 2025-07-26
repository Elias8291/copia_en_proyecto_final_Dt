@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Ejemplo usando el componente reutilizable para trámites -->
        <x-data-table 
            :data="$tramites"
            :columns="[
                [
                    'key' => 'proveedor.nombre',
                    'label' => 'Proveedor',
                    'type' => 'avatar',
                    'subtitle' => 'proveedor_id'
                ],
                [
                    'key' => 'tipo_tramite',
                    'label' => 'Tipo',
                    'type' => 'badge',
                    'colors' => [
                        'Inscripcion' => 'bg-blue-100 text-blue-700 border-blue-200',
                        'Renovacion' => 'bg-green-100 text-green-700 border-green-200',
                        'Actualizacion' => 'bg-purple-100 text-purple-700 border-purple-200'
                    ]
                ],
                [
                    'key' => 'estado',
                    'label' => 'Estado',
                    'type' => 'badge',
                    'colors' => [
                        'Pendiente' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                        'En_Revision' => 'bg-blue-100 text-blue-700 border-blue-200',
                        'Aprobado' => 'bg-green-100 text-green-700 border-green-200',
                        'Rechazado' => 'bg-red-100 text-red-700 border-red-200',
                        'Por_Cotejar' => 'bg-orange-100 text-orange-700 border-orange-200',
                        'Para_Correccion' => 'bg-pink-100 text-pink-700 border-pink-200',
                        'Cancelado' => 'bg-gray-100 text-gray-700 border-gray-200'
                    ]
                ],
                [
                    'key' => 'fecha_inicio',
                    'label' => 'Fecha',
                    'type' => 'date'
                ],
                [
                    'key' => 'paso_actual',
                    'label' => 'Progreso',
                    'type' => 'progress',
                    'max' => 5
                ]
            ]"
            title="Gestión de Trámites"
            description="Administra y revisa el estado de los trámites de proveedores"
            icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
            search-placeholder="Buscar trámites..."
            empty-message="No hay trámites"
            empty-description="No se encontraron trámites registrados. Comienza creando el primer trámite."
            create-button-text="Nuevo Trámite"
            export-button-text="Exportar"
            :actions="['view', 'edit', 'delete']"
        />
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Tabla reutilizable de trámites cargada');
});
</script>
@endpush 