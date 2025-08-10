<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Tailwind CSS</title>
    
    {{-- Forzar uso de archivos compilados --}}
    @if(file_exists(public_path('build/manifest.json')))
        @php
            $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        @endphp
        @if(isset($manifest['resources/css/app.css']['file']))
            <link rel="stylesheet" href="/build/{{ $manifest['resources/css/app.css']['file'] }}">
        @endif
    @endif
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-center text-primary mb-8">
                🎨 Test de Tailwind CSS
            </h1>
            
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                    Verificación de Estilos
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Colores -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-medium text-gray-700">Colores</h3>
                        <div class="flex space-x-2">
                            <div class="w-8 h-8 bg-primary rounded"></div>
                            <div class="w-8 h-8 bg-blue-500 rounded"></div>
                            <div class="w-8 h-8 bg-green-500 rounded"></div>
                            <div class="w-8 h-8 bg-red-500 rounded"></div>
                            <div class="w-8 h-8 bg-yellow-500 rounded"></div>
                        </div>
                    </div>
                    
                    <!-- Tipografía -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-medium text-gray-700">Tipografía</h3>
                        <p class="text-sm text-gray-600">Texto pequeño</p>
                        <p class="text-base text-gray-800">Texto normal</p>
                        <p class="text-lg font-semibold text-primary">Texto destacado</p>
                    </div>
                </div>
            </div>
            
            <!-- Botones -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-700 mb-4">Botones</h3>
                <div class="flex flex-wrap gap-3">
                    <button class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg transition-colors">
                        Botón Primario
                    </button>
                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                        Botón Azul
                    </button>
                    <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                        Botón Verde
                    </button>
                    <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition-colors">
                        Botón Gris
                    </button>
                </div>
            </div>
            
            <!-- Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-primary">
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Card 1</h4>
                    <p class="text-gray-600">Este es un ejemplo de card con borde izquierdo.</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Card 2</h4>
                    <p class="text-gray-600">Este es un ejemplo de card con borde azul.</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Card 3</h4>
                    <p class="text-gray-600">Este es un ejemplo de card con borde verde.</p>
                </div>
            </div>
            
            <!-- Estado de Verificación -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-green-800">¡Tailwind CSS Funciona!</h4>
                        <p class="text-green-700">Si puedes ver esta página con estilos, significa que Tailwind CSS se está cargando correctamente.</p>
                    </div>
                </div>
            </div>
            
            <!-- Información Técnica -->
            <div class="mt-8 bg-gray-50 rounded-lg p-4">
                <h4 class="text-lg font-medium text-gray-800 mb-2">Información Técnica</h4>
                <div class="text-sm text-gray-600 space-y-1">
                    <p><strong>Archivo CSS:</strong> {{ $manifest['resources/css/app.css']['file'] ?? 'No encontrado' }}</p>
                    <p><strong>Tamaño CSS:</strong> {{ file_exists(public_path('build/' . ($manifest['resources/css/app.css']['file'] ?? ''))) ? number_format(filesize(public_path('build/' . $manifest['resources/css/app.css']['file']))) . ' bytes' : 'N/A' }}</p>
                    <p><strong>URL CSS:</strong> /build/{{ $manifest['resources/css/app.css']['file'] ?? '' }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
