@props(['datos' => [], 'editable' => false])

@php
    // Obtener actividades del old() si hay errores de validación
    $actividadesOld = old('actividades_seleccionadas');
    $actividadesArray = [];
    
    if ($actividadesOld) {
        // Si viene como JSON string, decodificarlo
        if (is_string($actividadesOld)) {
            $actividadesArray = json_decode($actividadesOld, true) ?: [];
        } elseif (is_array($actividadesOld)) {
            $actividadesArray = $actividadesOld;
        }
    } elseif (!empty($datos)) {
        // Si no hay old() pero hay datos, usar los datos
        $actividadesArray = $datos;
    }
@endphp

@if(empty($datos))
    <div class="space-y-6" {{ $attributes }}>
        <!-- Título de la sección -->
        <div class="flex items-center space-x-3 mb-6">
            <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Actividades Económicas</h3>
                <p class="text-sm text-gray-500">Seleccione las actividades que realiza</p>
            </div>
        </div>

        @if($editable)
        <div class="space-y-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-800 mb-3">Seleccionar Actividades</h4>
                
                <!-- Búsqueda en tiempo real -->
                <div class="mb-4">
                    <label for="buscar-actividad" class="block text-sm font-medium text-gray-700 mb-2">
                        Buscar actividad económica
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="buscar-actividad" 
                               class="w-full px-3 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary"
                               placeholder="Escriba para buscar actividades...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                    
                    <!-- Resultados de búsqueda -->
                    <div id="resultados-busqueda" class="mt-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg hidden">
                        <!-- Los resultados se cargarán aquí dinámicamente -->
                    </div>
                </div>
                
                <!-- Actividades seleccionadas -->
                <div id="actividades-seleccionadas" class="space-y-2">
                    <!-- Las actividades seleccionadas se mostrarán aquí -->
                </div>
                
                <!-- Input oculto para enviar datos -->
                <input type="hidden" name="actividades_seleccionadas" id="actividades-json" value="{{ old('actividades_seleccionadas', '[]') }}">
                @error('actividades')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                
                <!-- Mensaje de error para actividades vacías -->
                <div id="error-actividades-vacias" class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg hidden">
                    <p class="text-sm text-red-600">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Debe seleccionar al menos una actividad económica
                    </p>
                </div>
            </div>
        </div>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buscarInput = document.getElementById('buscar-actividad');
            const resultadosDiv = document.getElementById('resultados-busqueda');
            const actividadesSeleccionadas = document.getElementById('actividades-seleccionadas');
            const actividadesJson = document.getElementById('actividades-json');
            const errorActividadesVacias = document.getElementById('error-actividades-vacias');
            
            // Check if required elements exist
            if (!buscarInput || !resultadosDiv || !actividadesSeleccionadas || !actividadesJson) {
                console.warn('Required elements for actividades-economicas not found');
                return;
            }
            
            let timeoutId;
            let actividadesSeleccionadasArray = [];
            
            // Cargar actividades existentes desde old() o datos
            @if(!empty($actividadesArray))
                actividadesSeleccionadasArray = @json($actividadesArray);
                actualizarActividadesSeleccionadas();
            @endif
            
            // Búsqueda en tiempo real
            buscarInput.addEventListener('input', function() {
                clearTimeout(timeoutId);
                const query = this.value.trim();
                
                if (query.length < 2) {
                    resultadosDiv.classList.add('hidden');
                    return;
                }
                
                timeoutId = setTimeout(() => {
                    fetch(`/api/catalogo/actividades?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            mostrarResultados(data);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }, 300);
            });
            
            function mostrarResultados(actividades) {
                if (actividades.length === 0) {
                    resultadosDiv.innerHTML = '<div class="p-3 text-sm text-gray-500">No se encontraron actividades</div>';
                } else {
                    resultadosDiv.innerHTML = actividades.map(actividad => `
                        <div class="p-2 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 actividad-item" 
                             data-id="${actividad.id}" 
                             data-nombre="${actividad.nombre}">
                            <div class="text-sm text-gray-900">${actividad.nombre}</div>
                        </div>
                    `).join('');
                }
                resultadosDiv.classList.remove('hidden');
            }
            
            // Seleccionar actividad
            resultadosDiv.addEventListener('click', function(e) {
                if (e.target.closest('.actividad-item')) {
                    const item = e.target.closest('.actividad-item');
                    const id = item.dataset.id;
                    const nombre = item.dataset.nombre;
                    
                    if (!actividadesSeleccionadasArray.find(a => a.id == id)) {
                        actividadesSeleccionadasArray.push({id, nombre});
                        actualizarActividadesSeleccionadas();
                    }
                    
                    buscarInput.value = '';
                    resultadosDiv.classList.add('hidden');
                }
            });
            
            function actualizarActividadesSeleccionadas() {
                actividadesSeleccionadas.innerHTML = actividadesSeleccionadasArray.map(actividad => `
                    <div class="flex items-center justify-between p-2 bg-blue-50 border border-blue-200 rounded-lg">
                        <span class="text-sm text-blue-900">${actividad.nombre}</span>
                        <button type="button" 
                                class="text-red-500 hover:text-red-700 text-sm"
                                onclick="removerActividad(${actividad.id})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `).join('');
                
                actividadesJson.value = JSON.stringify(actividadesSeleccionadasArray);
                verificarActividadesSeleccionadas();
            }
            
            // Función global para remover actividad
            window.removerActividad = function(id) {
                actividadesSeleccionadasArray = actividadesSeleccionadasArray.filter(a => a.id != id);
                actualizarActividadesSeleccionadas();
            };
            
            // Ocultar resultados al hacer clic fuera
            document.addEventListener('click', function(e) {
                if (buscarInput && resultadosDiv && !buscarInput.contains(e.target) && !resultadosDiv.contains(e.target)) {
                    resultadosDiv.classList.add('hidden');
                }
            });

            function verificarActividadesSeleccionadas() {
                if (actividadesSeleccionadasArray.length === 0) {
                    errorActividadesVacias.classList.remove('hidden');
                } else {
                    errorActividadesVacias.classList.add('hidden');
                }
            }
        });
        </script>
        @endif
    </div>
@else
    <div class="space-y-6" {{ $attributes }}>
        <!-- Título de la sección -->
        <div class="flex items-center space-x-3 mb-6">
            <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Actividades Económicas</h3>
                <p class="text-sm text-gray-500">Actividades registradas</p>
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">
                        Actividades registradas
                    </h3>
                    <div class="mt-2 text-sm text-green-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($datos as $actividad)
                                <li>{{ $actividad }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif 