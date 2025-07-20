@extends('layouts.app')

@section('title', 'Inscripción de Proveedor - Prueba')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Inscripción de Proveedor</h1>
            <p class="text-gray-600 mt-1">Formulario de prueba para inscribir un nuevo proveedor usando el sistema de versionado</p>
        </div>
    </div>

    <!-- Mensajes de éxito/error -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <strong>¡Éxito!</strong> {{ session('success')['mensaje'] }}
                    <div class="text-sm mt-1">
                        <p>ID Proveedor: {{ session('success')['proveedor_id'] }}</p>
                        <p>ID Trámite: {{ session('success')['tramite_id'] }}</p>
                        <p>Versión: {{ session('success')['version'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <strong>Errores encontrados:</strong>
                    <ul class="list-disc list-inside mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Formulario -->
    <form action="{{ route('test.proveedor.inscribir') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Datos Generales -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Datos Generales</h2>
            </div>
            <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre/Razón Social *
                    </label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ej: Empresa S.A. de C.V." required>
                </div>
                <div>
                    <label for="rfc" class="block text-sm font-medium text-gray-700 mb-2">
                        RFC *
                    </label>
                    <input type="text" id="rfc" name="rfc" value="{{ old('rfc') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="12 caracteres" maxlength="12" required>
                </div>
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                        Teléfono *
                    </label>
                    <input type="tel" id="telefono" name="telefono" value="{{ old('telefono') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="555-123-4567" required>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email *
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="empresa@ejemplo.com" required>
                </div>
            </div>
        </div>

        <!-- Dirección Fiscal -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Dirección Fiscal</h2>
            </div>
            <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label for="direccion_calle" class="block text-sm font-medium text-gray-700 mb-2">
                        Calle *
                    </label>
                    <input type="text" id="direccion_calle" name="direccion_calle" value="{{ old('direccion_calle') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Av. Principal" required>
                </div>
                <div>
                    <label for="direccion_numero" class="block text-sm font-medium text-gray-700 mb-2">
                        Número *
                    </label>
                    <input type="text" id="direccion_numero" name="direccion_numero" value="{{ old('direccion_numero') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="123" required>
                </div>
                <div>
                    <label for="direccion_colonia" class="block text-sm font-medium text-gray-700 mb-2">
                        Colonia *
                    </label>
                    <input type="text" id="direccion_colonia" name="direccion_colonia" value="{{ old('direccion_colonia') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Centro" required>
                </div>
                <div>
                    <label for="direccion_municipio" class="block text-sm font-medium text-gray-700 mb-2">
                        Municipio *
                    </label>
                    <input type="text" id="direccion_municipio" name="direccion_municipio" value="{{ old('direccion_municipio') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ciudad de México" required>
                </div>
                <div>
                    <label for="direccion_estado" class="block text-sm font-medium text-gray-700 mb-2">
                        Estado *
                    </label>
                    <input type="text" id="direccion_estado" name="direccion_estado" value="{{ old('direccion_estado') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="CDMX" required>
                </div>
                <div>
                    <label for="direccion_cp" class="block text-sm font-medium text-gray-700 mb-2">
                        Código Postal *
                    </label>
                    <input type="text" id="direccion_cp" name="direccion_cp" value="{{ old('direccion_cp') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="01000" maxlength="5" required>
                </div>
            </div>
        </div>

        <!-- Contacto Principal -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Contacto Principal</h2>
            </div>
            <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="contacto_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre Completo *
                    </label>
                    <input type="text" id="contacto_nombre" name="contacto_nombre" value="{{ old('contacto_nombre') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Juan Pérez García" required>
                </div>
                <div>
                    <label for="contacto_cargo" class="block text-sm font-medium text-gray-700 mb-2">
                        Cargo *
                    </label>
                    <input type="text" id="contacto_cargo" name="contacto_cargo" value="{{ old('contacto_cargo') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Gerente General" required>
                </div>
                <div>
                    <label for="contacto_telefono" class="block text-sm font-medium text-gray-700 mb-2">
                        Teléfono *
                    </label>
                    <input type="tel" id="contacto_telefono" name="contacto_telefono" value="{{ old('contacto_telefono') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="555-987-6543" required>
                </div>
                <div>
                    <label for="contacto_email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email *
                    </label>
                    <input type="email" id="contacto_email" name="contacto_email" value="{{ old('contacto_email') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="contacto@empresa.com" required>
                </div>
            </div>
        </div>

        <!-- Actividades Económicas -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Actividades Económicas</h2>
                <p class="text-sm text-gray-600">Agrega al menos una actividad económica</p>
            </div>
            <div class="px-6 py-4">
                <div id="actividades-container">
                    <div class="actividad-item flex items-center space-x-2 mb-2">
                        <input type="text" name="actividades[]" 
                               class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Código de actividad económica" required>
                        <button type="button" onclick="eliminarActividad(this)" 
                                class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600">
                            Eliminar
                        </button>
                    </div>
                </div>
                <button type="button" onclick="agregarActividad()" 
                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mt-2">
                    Agregar Actividad
                </button>
            </div>
        </div>

        <!-- Accionistas (opcional) -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Accionistas</h2>
                <p class="text-sm text-gray-600">Opcional - Solo para personas morales</p>
            </div>
            <div class="px-6 py-4">
                <div id="accionistas-container">
                    <!-- Los accionistas se agregan dinámicamente -->
                </div>
                <button type="button" onclick="agregarAccionista()" 
                        class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                    Agregar Accionista
                </button>
            </div>
        </div>

        <!-- Datos Constitutivos -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Datos Constitutivos</h2>
            </div>
            <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="fecha_constitucion" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha de Constitución *
                    </label>
                    <input type="date" id="fecha_constitucion" name="fecha_constitucion" value="{{ old('fecha_constitucion') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="capital_social" class="block text-sm font-medium text-gray-700 mb-2">
                        Capital Social *
                    </label>
                    <input type="number" id="capital_social" name="capital_social" value="{{ old('capital_social') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="50000" step="0.01" min="0" required>
                </div>
                <div>
                    <label for="notario_numero" class="block text-sm font-medium text-gray-700 mb-2">
                        Número de Notario *
                    </label>
                    <input type="text" id="notario_numero" name="notario_numero" value="{{ old('notario_numero') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="123" required>
                </div>
                <div>
                    <label for="notario_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre del Notario *
                    </label>
                    <input type="text" id="notario_nombre" name="notario_nombre" value="{{ old('notario_nombre') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Lic. María González" required>
                </div>
            </div>
        </div>

        <!-- Apoderado Legal -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Apoderado Legal</h2>
            </div>
            <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="apoderado_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre Completo *
                    </label>
                    <input type="text" id="apoderado_nombre" name="apoderado_nombre" value="{{ old('apoderado_nombre') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Carlos Rodríguez López" required>
                </div>
                <div>
                    <label for="apoderado_cargo" class="block text-sm font-medium text-gray-700 mb-2">
                        Cargo *
                    </label>
                    <input type="text" id="apoderado_cargo" name="apoderado_cargo" value="{{ old('apoderado_cargo') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Administrador Único" required>
                </div>
                <div>
                    <label for="apoderado_rfc" class="block text-sm font-medium text-gray-700 mb-2">
                        RFC *
                    </label>
                    <input type="text" id="apoderado_rfc" name="apoderado_rfc" value="{{ old('apoderado_rfc') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="13 caracteres" maxlength="13" required>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex justify-end space-x-4">
            <button type="button" onclick="window.history.back()" 
                    class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition-colors">
                Cancelar
            </button>
            <button type="submit" 
                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                Inscribir Proveedor
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
// Funciones para manejar actividades dinámicamente
function agregarActividad() {
    const container = document.getElementById('actividades-container');
    const div = document.createElement('div');
    div.className = 'actividad-item flex items-center space-x-2 mb-2';
    div.innerHTML = `
        <input type="text" name="actividades[]" 
               class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
               placeholder="Código de actividad económica" required>
        <button type="button" onclick="eliminarActividad(this)" 
                class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600">
            Eliminar
        </button>
    `;
    container.appendChild(div);
}

function eliminarActividad(button) {
    const container = document.getElementById('actividades-container');
    if (container.children.length > 1) {
        button.parentElement.remove();
    } else {
        alert('Debe mantener al menos una actividad económica');
    }
}

// Funciones para manejar accionistas dinámicamente
function agregarAccionista() {
    const container = document.getElementById('accionistas-container');
    const div = document.createElement('div');
    div.className = 'accionista-item grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-4 border border-gray-200 rounded-md';
    div.innerHTML = `
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
            <input type="text" name="accionistas[${Date.now()}][nombre]" 
                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="Nombre del accionista">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Porcentaje</label>
            <input type="number" name="accionistas[${Date.now()}][porcentaje]" 
                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="25" min="0" max="100" step="0.01">
        </div>
        <div class="flex items-end">
            <button type="button" onclick="eliminarAccionista(this)" 
                    class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600 w-full">
                Eliminar
            </button>
        </div>
    `;
    container.appendChild(div);
}

function eliminarAccionista(button) {
    button.closest('.accionista-item').remove();
}

// Validación de RFC
document.getElementById('rfc').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});

document.getElementById('apoderado_rfc').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});

// Validación de código postal
document.getElementById('direccion_cp').addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '');
});
</script>
@endsection