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

        <div class="p-6">
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <span class="text-sm font-medium text-emerald-800">Tipo de Trámite:</span>
                        <p class="text-emerald-900">{{ $tramite->tipo_tramite }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-emerald-800">Estado:</span>
                        <p class="text-emerald-900">{{ $tramite->status }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-emerald-800">Fecha de Creación:</span>
                        <p class="text-emerald-900">{{ $tramite->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-emerald-800">Tipo de Revisión:</span>
                        <p class="text-emerald-900">Domiciliaria</p>
                    </div>
                </div>
            </div>

            @if($revision)
        
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-green-800">Revisión Guardada</h3>
                            <p class="text-sm text-green-700">
                                Domiciliaria - Iniciada el {{ $revision->fecha_inicio->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ $revision->estado_label }}
                    </span>
                </div>
            </div>
            @else
        
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-emerald-800">Revisión Domiciliaria en Progreso</h3>
                            <p class="text-sm text-emerald-700">La revisión se guardará al finalizar el proceso</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                        En Progreso
                    </span>
                </div>
            </div>
            @endif

        
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-yellow-800">Verificación Domiciliaria en Sitio</h3>
                        <p class="text-sm text-yellow-700">Inspección física del domicilio fiscal para constatar la veracidad de la dirección y verificar las operaciones comerciales.</p>
                    </div>
                </div>
            </div>

        
            <div class="mb-6" data-section="domicilio">
                <div class="mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Verificación Domiciliaria</h2>
                    <p class="text-sm text-gray-600 mt-1">Verificación física del domicilio fiscal y operaciones</p>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 min-h-[600px]">
                    @include('components.forms.domicilio', [
                        'editable' => false, 
                        'datosConstancia' => $viewModel
                    ])
                </div>
                
            
                <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-medium text-gray-700">Decisión - Verificación Domiciliaria</h4>
                        <span class="text-xs text-gray-500">Visita en Sitio</span>
                    </div>
                    
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-600 mb-2">Observaciones de la visita domiciliaria:</label>
                        <textarea 
                            placeholder="Descripción detallada de la visita: condiciones del inmueble, operaciones observadas, personal presente, infraestructura, etc."
                            class="w-full text-sm px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500"
                            rows="4"></textarea>
                    </div>
                    
                
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Checklist de Verificación:</h5>
                        <div class="space-y-2 text-xs">
                            <label class="flex items-center">
                                <input type="checkbox" class="mr-2 text-emerald-600">
                                <span>El domicilio existe físicamente</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="mr-2 text-emerald-600">
                                <span>Coincide con la dirección registrada</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="mr-2 text-emerald-600">
                                <span>Se observan operaciones comerciales</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="mr-2 text-emerald-600">
                                <span>Hay personal trabajando</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="mr-2 text-emerald-600">
                                <span>Infraestructura adecuada para la actividad</span>
                            </label>
                        </div>
                    </div>
                    
                    <x-revision.botones-evaluacion-domicilio />
                </div>
            </div>

        
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6 border-t-4 border-emerald-500">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Decisión de Revisión Domiciliaria</h3>
                
                <form action="{{ route('revisiones.finalizar', $tramite->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="tipo_revision" value="Domiciliaria">
                    
                    <div>
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                            Observaciones (opcional)
                        </label>
                        <textarea 
                            id="observaciones" 
                            name="observaciones" 
                            rows="4" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600"
                            placeholder="Ingresa cualquier observación sobre la revisión domiciliaria..."
                        ></textarea>
                    </div>

                    <x-revision.botones-decision-final 
                        :showAprobar="true"
                        :showAgendarCita="false"
                        :showCorrecciones="false"
                        :showRechazar="true"
                        layout="flex"
                    />
                </form>
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

window.scrollToTop = scrollToTop;
</script>

    
<x-ui.modals.modal-confirmacion 
    id="modal-confirmacion-decision"
    title="Confirmar Decisión"
    message="¿Está seguro que desea realizar esta acción?"
    confirmText="Confirmar"
    cancelText="Cancelar"
    confirmClass="bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500"
    cancelClass="bg-white border-gray-300 text-gray-700 hover:text-gray-500 focus:ring-emerald-500"
/>
@endsection 