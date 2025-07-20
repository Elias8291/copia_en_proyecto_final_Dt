@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header del formulario -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-[#9D2449]">{{ $titulo }}</h1>
                    <p class="text-gray-600 mt-1">{{ $descripcion }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                        {{ ucfirst($tipo_tramite) }}
                    </span>
                    @if($proveedor)
                        <p class="text-sm text-gray-500 mt-1">{{ $proveedor->razon_social }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Formulario principal -->
        <form id="formulario-tramite" method="POST" action="{{ route('formularios.store', $tipo_tramite) }}" enctype="multipart/form-data">
            @csrf
            
            <!-- Progress indicator -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center space-x-8">
                        <div class="flex items-center text-blue-600">
                            <div class="flex items-center justify-center w-8 h-8 border-2 border-blue-600 rounded-full bg-blue-600 text-white text-xs font-bold">1</div>
                            <span class="ml-2 font-medium">Datos Generales</span>
                        </div>
                        <div class="flex items-center text-gray-400">
                            <div class="flex items-center justify-center w-8 h-8 border-2 border-gray-300 rounded-full text-xs">2</div>
                            <span class="ml-2">Domicilio</span>
                        </div>
                        <div class="flex items-center text-gray-400">
                            <div class="flex items-center justify-center w-8 h-8 border-2 border-gray-300 rounded-full text-xs">3</div>
                            <span class="ml-2">Documentos</span>
                        </div>
                        <div class="flex items-center text-gray-400">
                            <div class="flex items-center justify-center w-8 h-8 border-2 border-gray-300 rounded-full text-xs">4</div>
                            <span class="ml-2">Confirmación</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 25%"></div>
                    </div>
                </div>
            </div>

            <!-- Secciones del formulario -->
            <div class="space-y-6">
                
                <!-- Sección 1: Datos Generales -->
                <div class="step-section" data-step="1">
                    <div class="bg-white rounded-lg shadow-md">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-user mr-2 text-[#9D2449]"></i>
                                Datos Generales
                            </h3>
                        </div>
                        <div class="p-6">
                            <x-formularios.datos-generales :tipo="$tipo_tramite" :proveedor="$proveedor" />
                        </div>
                    </div>
                </div>

                <!-- Sección 2: Domicilio -->
                <div class="step-section hidden" data-step="2">
                    <div class="bg-white rounded-lg shadow-md">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 text-[#9D2449]"></i>
                                Domicilio
                            </h3>
                        </div>
                        <div class="p-6">
                            <x-formularios.domicilio :tipo="$tipo_tramite" :proveedor="$proveedor" />
                        </div>
                    </div>
                </div>

                <!-- Sección 3: Datos Constitutivos (solo para personas morales) -->
                <div class="step-section hidden" data-step="3" id="seccion-constitucion">
                    <div class="bg-white rounded-lg shadow-md">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-building mr-2 text-[#9D2449]"></i>
                                Datos Constitutivos
                            </h3>
                        </div>
                        <div class="p-6">
                            <x-formularios.constitucion :tipo="$tipo_tramite" :proveedor="$proveedor" />
                        </div>
                    </div>
                </div>

                <!-- Sección 4: Apoderado Legal (solo para personas morales) -->
                <div class="step-section hidden" data-step="4" id="seccion-apoderado">
                    <div class="bg-white rounded-lg shadow-md">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-user-tie mr-2 text-[#9D2449]"></i>
                                Apoderado Legal
                            </h3>
                        </div>
                        <div class="p-6">
                            <x-formularios.apoderado :tipo="$tipo_tramite" :proveedor="$proveedor" />
                        </div>
                    </div>
                </div>

                <!-- Sección 5: Accionistas (solo para personas morales) -->
                <div class="step-section hidden" data-step="5" id="seccion-accionistas">
                    <div class="bg-white rounded-lg shadow-md">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-users mr-2 text-[#9D2449]"></i>
                                Accionistas
                            </h3>
                        </div>
                        <div class="p-6">
                            <x-formularios.accionistas :tipo="$tipo_tramite" :proveedor="$proveedor" />
                        </div>
                    </div>
                </div>

                <!-- Sección 6: Documentos -->
                <div class="step-section hidden" data-step="6">
                    <div class="bg-white rounded-lg shadow-md">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-file-upload mr-2 text-[#9D2449]"></i>
                                Documentos
                            </h3>
                        </div>
                        <div class="p-6">
                            <x-formularios.documentos :tipo="$tipo_tramite" :proveedor="$proveedor" />
                        </div>
                    </div>
                </div>

            </div>

            <!-- Botones de navegación -->
            <div class="mt-8 bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between">
                    <button type="button" id="btn-anterior" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <i class="fas fa-arrow-left mr-2"></i>
                        Anterior
                    </button>
                    
                    <div class="flex space-x-4">
                        <button type="button" id="btn-guardar-borrador" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-save mr-2"></i>
                            Guardar Borrador
                        </button>
                        
                        <button type="button" id="btn-siguiente" class="px-6 py-3 bg-[#9D2449] text-white rounded-lg hover:bg-[#7a1c38]">
                            Siguiente
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                        
                        <button type="submit" id="btn-enviar" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 hidden">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Enviar Trámite
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>
// Script para manejar la navegación entre pasos del formulario
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = document.querySelectorAll('.step-section').length;
    
    const btnAnterior = document.getElementById('btn-anterior');
    const btnSiguiente = document.getElementById('btn-siguiente');
    const btnEnviar = document.getElementById('btn-enviar');
    
    function updateStep() {
        // Ocultar todas las secciones
        document.querySelectorAll('.step-section').forEach(section => {
            section.classList.add('hidden');
        });
        
        // Mostrar la sección actual
        const currentSection = document.querySelector(`[data-step="${currentStep}"]`);
        if (currentSection) {
            currentSection.classList.remove('hidden');
        }
        
        // Actualizar botones
        btnAnterior.disabled = currentStep === 1;
        
        if (currentStep === totalSteps) {
            btnSiguiente.classList.add('hidden');
            btnEnviar.classList.remove('hidden');
        } else {
            btnSiguiente.classList.remove('hidden');
            btnEnviar.classList.add('hidden');
        }
        
        // Actualizar barra de progreso
        const progress = (currentStep / totalSteps) * 100;
        document.querySelector('.bg-blue-600').style.width = progress + '%';
    }
    
    btnSiguiente.addEventListener('click', function() {
        if (currentStep < totalSteps) {
            currentStep++;
            updateStep();
        }
    });
    
    btnAnterior.addEventListener('click', function() {
        if (currentStep > 1) {
            currentStep--;
            updateStep();
        }
    });
    
    // Inicializar
    updateStep();
});
</script>
@endpush
@endsection 