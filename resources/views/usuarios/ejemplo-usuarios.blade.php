@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Ejemplo usando el componente reutilizable para usuarios -->
        <x-data-table 
            :data="$usuarios"
            :columns="[
                [
                    'key' => 'nombre',
                    'label' => 'Usuario',
                    'type' => 'avatar',
                    'subtitle' => 'email'
                ],
                [
                    'key' => 'rol',
                    'label' => 'Rol',
                    'type' => 'badge',
                    'colors' => [
                        'admin' => 'bg-red-100 text-red-700 border-red-200',
                        'usuario' => 'bg-blue-100 text-blue-700 border-blue-200',
                        'moderador' => 'bg-green-100 text-green-700 border-green-200'
                    ]
                ],
                [
                    'key' => 'estado',
                    'label' => 'Estado',
                    'type' => 'badge',
                    'colors' => [
                        'activo' => 'bg-green-100 text-green-700 border-green-200',
                        'inactivo' => 'bg-gray-100 text-gray-700 border-gray-200',
                        'pendiente' => 'bg-yellow-100 text-yellow-700 border-yellow-200'
                    ]
                ],
                [
                    'key' => 'fecha_registro',
                    'label' => 'Registro',
                    'type' => 'date'
                ],
                [
                    'key' => 'ultimo_acceso',
                    'label' => 'Último Acceso',
                    'type' => 'date'
                ]
            ]"
            title="Gestión de Usuarios"
            description="Administra los usuarios del sistema"
            icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
            search-placeholder="Buscar usuarios..."
            empty-message="No hay usuarios"
            empty-description="No se encontraron usuarios registrados."
            create-button-text="Nuevo Usuario"
            export-button-text="Exportar"
            :actions="['view', 'edit']"
        />
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Tabla reutilizable de usuarios cargada');
});
</script>
@endpush 