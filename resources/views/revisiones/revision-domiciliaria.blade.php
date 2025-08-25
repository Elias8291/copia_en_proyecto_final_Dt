@extends('layouts.app')

@section('title', 'Revisión Domiciliaria')

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-7xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">
                            Revisión Domiciliaria - Trámite #{{ $tramite->id }}
                        </h1>
                        <p class="text-base text-gray-500 mt-1">Revisión domiciliaria de documentos y verificación en sitio</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('revisiones.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-6 lg:p-8">
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 sm:p-6 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-200 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-bold text-gray-800">Revisión Domiciliaria</h3>
                        <p class="text-sm text-gray-600">
                            @if($tramite->proveedor && $tramite->proveedor->tipo_persona === 'Moral')
                                Persona Moral • Verificación en sitio
                            @else
                                Persona Física • Verificación en sitio
                            @endif
                        </p>
                    </div>
                </div>
            </div>
      <div class="mb-8" data-section="domicilio">
                <div class="mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Domicilio Fiscal</h2>
                </div>
                
                <div class="bg-white rounded-lg shadow-md border border-gray-200">
                    <button type="button" onclick="toggleSection('domicilio')" 
                            class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                            </div>
                            <span class="font-medium text-gray-900">Domicilio</span>
                        </div>
                        <svg id="domicilio-icon" class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="domicilio-content" class="border-t border-gray-200 p-6 bg-gray-50">
                        @if($tramite->direcciones && $tramite->direcciones->count() > 0)
                            @php
                                $datosDomicilio = [];
                                $direccion = $tramite->direcciones->first();
                                if ($direccion) {
                                    $datosDomicilio = [
                                        'calle' => $direccion->calle ?? '',
                                        'numero_exterior' => $direccion->numero_exterior ?? '',
                                        'numero_interior' => $direccion->numero_interior ?? '',
                                        'asentamiento' => $direccion->asentamiento ?? $direccion->colonia ?? '', // Usar asentamiento o colonia como fallback
                                        'codigo_postal' => $direccion->codigo_postal ?? '',
                                        'municipio' => $direccion->municipio ?? '',
                                        'estado_id' => $direccion->estado_id ?? '',
                                        'estado' => $direccion->estado->nombre ?? '',
                                        // Coordenadas en el nivel superior, no anidadas
                                        'latitud' => $direccion->coordenada ? $direccion->coordenada->latitud : '',
                                        'longitud' => $direccion->coordenada ? $direccion->coordenada->longitud : '',
                                        // Campos adicionales que el componente espera
                                        'entre_calle' => $direccion->entre_calle ?? '',
                                        'y_calle' => $direccion->y_calle ?? ''
                                    ];
                                }
                            @endphp
                            <x-forms.domicilio :datos="$datosDomicilio" :editable="false" />
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500">No se encontraron datos de domicilio registrados para este trámite.</p>
                            </div>
                        @endif
                    </div>
                </div>
                
            
                <div class="bg-white border border-gray-200 rounded-lg p-6 mt-6">
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">Evaluación Domiciliaria</h4>
                        <p class="text-sm text-gray-600">Registro de la verificación física del domicilio fiscal</p>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Observaciones de la visita domiciliaria:</label>
                        <textarea 
                            placeholder="Descripción detallada de la visita: condiciones del inmueble, operaciones observadas, personal presente, infraestructura, cumplimiento de actividades declaradas, etc."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200"
                            rows="5"></textarea>
                    </div>
                    
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                        <h5 class="text-sm font-semibold text-emerald-800 mb-4">Lista de Verificación:</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <label class="flex items-center p-2 hover:bg-white rounded transition-colors">
                                <input type="checkbox" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="ml-3 text-sm text-gray-700">El domicilio existe físicamente</span>
                            </label>
                            <label class="flex items-center p-2 hover:bg-white rounded transition-colors">
                                <input type="checkbox" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="ml-3 text-sm text-gray-700">Coincide con la dirección registrada</span>
                            </label>
                            <label class="flex items-center p-2 hover:bg-white rounded transition-colors">
                                <input type="checkbox" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="ml-3 text-sm text-gray-700">Se observan operaciones comerciales</span>
                            </label>
                            <label class="flex items-center p-2 hover:bg-white rounded transition-colors">
                                <input type="checkbox" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="ml-3 text-sm text-gray-700">Hay personal trabajando</span>
                            </label>
                            <label class="flex items-center p-2 hover:bg-white rounded transition-colors">
                                <input type="checkbox" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="ml-3 text-sm text-gray-700">Infraestructura adecuada</span>
                            </label>
                            <label class="flex items-center p-2 hover:bg-white rounded transition-colors">
                                <input type="checkbox" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="ml-3 text-sm text-gray-700">Actividades conforme a lo declarado</span>
                            </label>
                        </div>
                    </div>
                    
                    <form id="form-revision-domiciliaria" action="{{ route('revisiones.procesar-domiciliaria', $tramite->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="observaciones" id="observaciones-hidden">
                        <input type="hidden" name="decision" id="decision-hidden">
                        <input type="hidden" name="checkboxes" id="checkboxes-hidden">
                        
                        <div class="flex items-center gap-4 pt-4 border-t border-gray-200">
                            <button type="button" 
                                    onclick="procesarDecision('aprobar')"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Domicilio Verificado
                            </button>
                            <button type="button" 
                                    onclick="procesarDecision('rechazar')"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                No Conforme
                            </button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
</div>

<div class="fixed bottom-6 right-6 space-y-2 z-40">
    <button type="button" onclick="scrollToTop()" 
            class="w-12 h-12 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full shadow-lg flex items-center justify-center transition-colors">
        <i class="fas fa-arrow-up"></i>
    </button>
</div>

<script>
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function toggleSection(sectionName) {
    const content = document.getElementById(sectionName + '-content');
    const icon = document.getElementById(sectionName + '-icon');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

function procesarDecision(decision) {
    console.log('Procesando decisión:', decision);
    
    const observaciones = document.querySelector('textarea').value.trim();
    const checkboxes = Array.from(document.querySelectorAll('input[type="checkbox"]:checked')).map(cb => cb.nextElementSibling.textContent.trim());
    
    console.log('Observaciones:', observaciones);
    console.log('Checkboxes seleccionados:', checkboxes);   
    if (decision === 'aprobar' && checkboxes.length === 0) {
        showConfirmModal(
            'Validación Requerida',
            'Debe seleccionar al menos una característica verificada para aprobar el domicilio.',
            null,
            null
        );
        return;
    }
    
    if (observaciones === '') {
        showConfirmModal(
            'Validación Requerida',
            'Debe agregar observaciones sobre la visita domiciliaria.',
            null,
            null
        );
        return;
    }
    
    document.getElementById('observaciones-hidden').value = observaciones;
    document.getElementById('decision-hidden').value = decision;
    document.getElementById('checkboxes-hidden').value = JSON.stringify(checkboxes);
    
    console.log('Campos ocultos llenados');
    
    const titulo = decision === 'aprobar' ? 'Confirmar Aprobación' : 'Confirmar Rechazo';
    const mensaje = decision === 'aprobar' 
        ? '¿Está seguro de aprobar la revisión domiciliaria? El trámite será marcado como Aprobado.'
        : '¿Está seguro de rechazar la revisión domiciliaria? El trámite será marcado como Rechazado.';
    
    showConfirmModal(titulo, mensaje, 'form-revision-domiciliaria');
}

window.scrollToTop = scrollToTop;
window.toggleSection = toggleSection;
window.procesarDecision = procesarDecision;
</script>

    
<x-ui.modals.modal-confirmacion 
    id="modal-confirmacion"
    title="Confirmar Decisión"
    message="¿Está seguro que desea realizar esta acción?"
    confirmText="Confirmar"
    cancelText="Cancelar"
    confirmClass="bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500"
    cancelClass="bg-white border-gray-300 text-gray-700 hover:text-gray-500 focus:ring-emerald-500"
/>
@endsection 