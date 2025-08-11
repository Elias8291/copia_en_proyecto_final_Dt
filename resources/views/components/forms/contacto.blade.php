@props(['datos' => [], 'editable' => false])

@php
    // Asegurar que tenemos todos los campos necesarios
    $datos = array_merge([
        'nombre_contacto' => '',
        'cargo' => '',
        'correo_electronico' => '',
        'telefono' => ''
    ], $datos ?? []);
@endphp

<div class="space-y-6" {{ $attributes }}>
    <!-- Título de la sección -->
    <div class="flex items-center space-x-3 mb-6">
        <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Información de Contacto</h3>
            <p class="text-sm text-gray-500">Datos de contacto principal</p>
        </div>
    </div>

    @if($editable)
        <!-- Formulario editable -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="nombre_contacto" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre del Contacto <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="nombre_contacto" 
                       name="nombre_contacto" 
                       value="{{ old('nombre_contacto', $datos['nombre_contacto']) }}"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                       required>
                @error('nombre_contacto')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="cargo" class="block text-sm font-medium text-gray-700 mb-2">
                    Cargo <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="cargo" 
                       name="cargo" 
                       value="{{ old('cargo', $datos['cargo']) }}"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                       required>
                @error('cargo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="correo_electronico" class="block text-sm font-medium text-gray-700 mb-2">
                    Correo Electrónico <span class="text-red-500">*</span>
                </label>
                <input type="email" 
                       id="correo_electronico" 
                       name="correo_electronico" 
                       value="{{ old('correo_electronico', $datos['correo_electronico']) }}"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                       required>
                @error('correo_electronico')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                    Teléfono <span class="text-red-500">*</span>
                </label>
                <input type="tel" 
                       id="telefono" 
                       name="telefono" 
                       value="{{ old('telefono', $datos['telefono']) }}"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                       required>
                @error('telefono')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    @else
        <!-- Vista de solo lectura -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre del Contacto</label>
                <p class="mt-1 text-sm text-gray-900">{{ $datos['nombre_contacto'] ?: 'No especificado' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Cargo</label>
                <p class="mt-1 text-sm text-gray-900">{{ $datos['cargo'] ?: 'No especificado' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                <p class="mt-1 text-sm text-gray-900">{{ $datos['correo_electronico'] ?: 'No especificado' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                <p class="mt-1 text-sm text-gray-900">{{ $datos['telefono'] ?: 'No especificado' }}</p>
            </div>
        </div>
    @endif
</div>
