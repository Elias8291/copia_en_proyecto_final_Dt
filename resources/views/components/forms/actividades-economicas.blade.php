@props(['datos' => [], 'editable' => false])

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

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">
                        No hay actividades registradas
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Para continuar, debe seleccionar al menos una actividad económica.</p>
                    </div>
                </div>
            </div>
        </div>

        @if($editable)
        <div class="space-y-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-800 mb-3">Seleccionar Actividades</h4>
                
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input type="checkbox" name="actividades[]" value="comercio" id="act_comercio" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="act_comercio" class="ml-2 block text-sm text-gray-900">
                            Comercio al por mayor y menor
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="actividades[]" value="servicios" id="act_servicios" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="act_servicios" class="ml-2 block text-sm text-gray-900">
                            Servicios profesionales
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="actividades[]" value="manufactura" id="act_manufactura" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="act_manufactura" class="ml-2 block text-sm text-gray-900">
                            Manufactura e industria
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="actividades[]" value="construccion" id="act_construccion" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="act_construccion" class="ml-2 block text-sm text-gray-900">
                            Construcción
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="actividades[]" value="transporte" id="act_transporte" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="act_transporte" class="ml-2 block text-sm text-gray-900">
                            Transporte y logística
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="actividades[]" value="tecnologia" id="act_tecnologia" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="act_tecnologia" class="ml-2 block text-sm text-gray-900">
                            Tecnología e informática
                        </label>
                    </div>
                </div>
                
                <div class="mt-4">
                    <label for="actividad_otra" class="block text-sm font-medium text-gray-700 mb-2">
                        Otra actividad (especifique)
                    </label>
                    <input type="text" name="actividad_otra" id="actividad_otra" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary"
                           placeholder="Describa otra actividad económica">
                </div>
            </div>
        </div>
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