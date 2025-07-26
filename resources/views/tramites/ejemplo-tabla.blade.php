@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Ejemplo de uso del componente -->
        <x-tramites-table :tramites="$tramites" />
    </div>
</div>
@endsection

@push('scripts')
<script>
// Ejemplo de datos para demostración
document.addEventListener('DOMContentLoaded', function() {
    // Aquí puedes agregar interactividad JavaScript si es necesario
    console.log('Tabla de trámites cargada');
});
</script>
@endpush 