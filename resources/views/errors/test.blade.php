<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de Errores - Sistema</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#9d2449',
                        'primary-dark': '#7a1d37',
                        'primary-light': '#b83055',
                        'primary-50': '#fdf2f5',
                        'primary-100': '#fce7ec',
                        'primary-200': '#f9d0db'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">
                    🧪 Prueba de Manejo de Errores
                </h1>
                
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Web Errors -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Errores Web</h2>
                        
                        <div class="space-y-3">
                            <a href="{{ route('error.test.404') }}" 
                               class="block w-full px-4 py-2 text-center text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                                404 - No Encontrado
                            </a>
                            
                            <a href="{{ route('error.test.403') }}" 
                               class="block w-full px-4 py-2 text-center text-white bg-orange-600 hover:bg-orange-700 rounded-lg transition-colors">
                                403 - Acceso Prohibido
                            </a>
                            
                            <a href="{{ route('error.test.419') }}" 
                               class="block w-full px-4 py-2 text-center text-white bg-yellow-600 hover:bg-yellow-700 rounded-lg transition-colors">
                                419 - Token Expirado
                            </a>
                            
                            <a href="{{ route('error.test.429') }}" 
                               class="block w-full px-4 py-2 text-center text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                                429 - Demasiadas Solicitudes
                            </a>
                            
                            <a href="{{ route('error.test.500') }}" 
                               class="block w-full px-4 py-2 text-center text-white bg-purple-600 hover:bg-purple-700 rounded-lg transition-colors">
                                500 - Error del Servidor
                            </a>
                            
                            <a href="{{ route('error.test.503') }}" 
                               class="block w-full px-4 py-2 text-center text-white bg-gray-600 hover:bg-gray-700 rounded-lg transition-colors">
                                503 - Servicio No Disponible
                            </a>
                        </div>
                    </div>
                    
                    <!-- API Errors -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Errores API</h2>
                        
                        <div class="space-y-3">
                            <button onclick="testApiError('404')" 
                                    class="block w-full px-4 py-2 text-center text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                                API 404 - No Encontrado
                            </button>
                            
                            <button onclick="testApiError('403')" 
                                    class="block w-full px-4 py-2 text-center text-white bg-orange-600 hover:bg-orange-700 rounded-lg transition-colors">
                                API 403 - Acceso Prohibido
                            </button>
                            
                            <button onclick="testApiError('422')" 
                                    class="block w-full px-4 py-2 text-center text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors">
                                API 422 - Validación
                            </button>
                            
                            <button onclick="testApiError('500')" 
                                    class="block w-full px-4 py-2 text-center text-white bg-purple-600 hover:bg-purple-700 rounded-lg transition-colors">
                                API 500 - Error del Servidor
                            </button>
                        </div>
                        
                        <!-- API Response Display -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Respuesta API:</h3>
                            <pre id="apiResponse" class="bg-gray-100 p-4 rounded-lg text-sm overflow-auto max-h-64 hidden"></pre>
                        </div>
                    </div>
                </div>
                
                <!-- Validation Test Form -->
                <div class="mt-8 border-t pt-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Prueba de Validación (422)</h2>
                    
                    <form id="validationForm" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Campo Requerido (min 5 chars):</label>
                            <input type="text" name="required_field" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email:</label>
                            <input type="email" name="email_field" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Número (1-100):</label>
                            <input type="number" name="numeric_field" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                            Probar Validación
                        </button>
                    </form>
                </div>
                
                <div class="mt-8 text-center">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function testApiError(type) {
            const responseDiv = document.getElementById('apiResponse');
            
            try {
                const response = await fetch(`/api/test-error?type=${type}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await response.json();
                responseDiv.textContent = JSON.stringify(data, null, 2);
                responseDiv.classList.remove('hidden');
                
            } catch (error) {
                responseDiv.textContent = `Error: ${error.message}`;
                responseDiv.classList.remove('hidden');
            }
        }
        
        document.getElementById('validationForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const responseDiv = document.getElementById('apiResponse');
            
            try {
                const response = await fetch('/api/test-error?type=422', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });
                
                const data = await response.json();
                responseDiv.textContent = JSON.stringify(data, null, 2);
                responseDiv.classList.remove('hidden');
                
            } catch (error) {
                responseDiv.textContent = `Error: ${error.message}`;
                responseDiv.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>