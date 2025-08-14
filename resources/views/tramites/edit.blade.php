@extends('layouts.app')

@section('title', 'Corregir Trámite')

<!-- Meta tag para el ID del trámite -->
<meta name="tramite-id" content="{{ $tramite->id }}">

@section('content')
<style>
    .border-red-500 {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }
    
    .btn-loading {
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    .field-error {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }
    
    .field-error:focus {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2) !important;
    }
</style>

<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-full mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-red-600 to-red-800 rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Corregir Trámite #{{ $tramite->id }}</h1>
                        <p class="text-base text-gray-500 mt-1">Realice las correcciones necesarias y vuelva a enviar</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('tramites.estado') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver al Estado
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <h3 class="text-red-800 font-semibold mb-2">Errores de validación:</h3>
                    <ul class="text-red-700 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Información Básica -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4 text-sm">
                        <span><strong>Tipo:</strong> {{ ucfirst($tramite->tipo_tramite) }}</span>
                        <span><strong>RFC:</strong> {{ $tramite->proveedor->rfc }}</span>
                        <span><strong>Persona:</strong> {{ $tramite->proveedor->tipo_persona === 'Moral' ? 'Moral' : 'Física' }}</span>
                    </div>
                    <div class="text-xs text-gray-500">
                        Solo secciones rechazadas ✏️ son editables
                    </div>
                </div>
            </div>

            <!-- Observaciones del Revisor -->
            @if($tramite->observaciones)
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 mb-6">
                <div class="flex items-start">
                    <svg class="w-4 h-4 text-orange-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-orange-800 mb-1">Observaciones del Revisor:</p>
                        <p class="text-sm text-orange-700">{{ $tramite->observaciones }}</p>
                    </div>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('tramites.update', $tramite->id) }}" enctype="multipart/form-data" class="space-y-8" id="tramite-form">
                @csrf
                @method('PUT')
                
                <!-- Campo oculto para tipo de trámite -->
                <input type="hidden" name="tipo_tramite" value="{{ $tramite->tipo_tramite }}">
                
                <!-- Campos ocultos para comentarios y estados -->
                <input type="hidden" name="secciones[datos_generales][decision]" id="decision_datos_generales" value="Pendiente">
                <input type="hidden" name="secciones[datos_generales][comentario]" id="comentario_datos_generales" value="">
                
                <input type="hidden" name="secciones[actividades][decision]" id="decision_actividades" value="Pendiente">
                <input type="hidden" name="secciones[actividades][comentario]" id="comentario_actividades" value="">
                
                <input type="hidden" name="secciones[domicilio][decision]" id="decision_domicilio" value="Pendiente">
                <input type="hidden" name="secciones[domicilio][comentario]" id="comentario_domicilio" value="">
                
                <input type="hidden" name="secciones[contacto][decision]" id="decision_contacto" value="Pendiente">
                <input type="hidden" name="secciones[contacto][comentario]" id="comentario_contacto" value="">
                
                <input type="hidden" name="secciones[archivos_correccion][decision]" id="decision_archivos_correccion" value="Pendiente">
                <input type="hidden" name="secciones[archivos_correccion][comentario]" id="comentario_archivos_correccion" value="">
                
                @if($tramite->proveedor->tipo_persona === 'Moral')
                    <input type="hidden" name="secciones[constitucion][decision]" id="decision_constitucion" value="Pendiente">
                    <input type="hidden" name="secciones[constitucion][comentario]" id="comentario_constitucion" value="">
                    
                    <input type="hidden" name="secciones[accionistas][decision]" id="decision_accionistas" value="Pendiente">
                    <input type="hidden" name="secciones[accionistas][comentario]" id="comentario_accionistas" value="">
                    
                    <input type="hidden" name="secciones[apoderado][decision]" id="decision_apoderado" value="Pendiente">
                    <input type="hidden" name="secciones[apoderado][comentario]" id="comentario_apoderado" value="">
                @endif

                <!-- Datos Generales -->
                @php
                    $estadoSeccion = $estadosSecciones['datos_generales'] ?? 'Pendiente';
                    $esEditable = $estadoSeccion === 'Rechazado';
                @endphp
                @if($estadoSeccion === 'Rechazado')
                <div class="mb-8 border-2 border-red-500 rounded-lg" data-section="datos_generales">
                    <div class="flex items-center justify-center mb-4 p-4 bg-red-50 border-b border-red-200">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-600 text-white">
                            Corregir
                        </span>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        @include('components.forms.datos-generales', [
                            'editable' => $esEditable, 
                            'datosConstancia' => $viewModel
                        ])
                    </div>
                    
                    <!-- Comentarios del Revisor -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mt-3" data-seccion="datos_generales">
                        <x-revision.textarea-comentarios 
                            seccion="datos_generales"
                            placeholder="Observaciones del revisor..."
                            label-text="Observaciones:"
                            :rows="2"
                            :soloLectura="true"
                        />
                    </div>
                </div>
                @endif

                <div class="border-t border-gray-200 my-8"></div>

                <!-- Actividades Económicas -->
                @php
                    $estadoSeccion = $estadosSecciones['actividades'] ?? 'Pendiente';
                    $esEditable = $estadoSeccion === 'Rechazado';
                @endphp
                @if($estadoSeccion === 'Rechazado')
                <div class="mb-8 border-2 border-red-500 rounded-lg" data-section="actividades">
                    <div class="flex items-center justify-center mb-4 p-4 bg-red-50 border-b border-red-200">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-600 text-white">
                            Corregir
                        </span>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        @include('components.forms.actividades-economicas', [
                            'editable' => $esEditable,
                            'actividadesSeleccionadas' => $viewModel
                        ])
                    </div>
                    
                    <!-- Comentarios del Revisor -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mt-3" data-seccion="actividades">
                        <x-revision.textarea-comentarios 
                            seccion="actividades"
                            placeholder="Observaciones del revisor..."
                            label-text="Observaciones:"
                            :rows="2"
                            :soloLectura="true"
                        />
                    </div>
                </div>
                @endif

                <div class="border-t border-gray-200 my-8"></div>

                <!-- Domicilio -->
                @php
                    $estadoSeccion = $estadosSecciones['domicilio'] ?? 'Pendiente';
                    $esEditable = $estadoSeccion === 'Rechazado';
                @endphp
                @if($estadoSeccion === 'Rechazado')
                <div class="mb-8 border-2 border-red-500 rounded-lg" data-section="domicilio">
                    <div class="flex items-center justify-center mb-4 p-4 bg-red-50 border-b border-red-200">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-600 text-white">
                            Corregir
                        </span>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        @include('components.forms.domicilio', [
                            'editable' => $esEditable, 
                            'datosConstancia' => $viewModel
                        ])
                    </div>
                    
                    <!-- Comentarios del Revisor -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mt-3" data-seccion="domicilio">
                        <x-revision.textarea-comentarios 
                            seccion="domicilio"
                            placeholder="Observaciones del revisor..."
                            label-text="Observaciones:"
                            :rows="2"
                            :soloLectura="true"
                        />
                    </div>
                </div>
                @endif

                @if($tramite->proveedor->tipo_persona === 'Moral')
                    <div class="border-t border-gray-200 my-8"></div>

                    <!-- Constitución -->
                    @php
                        $estadoSeccion = $estadosSecciones['constitucion'] ?? 'Pendiente';
                        $esEditable = $estadoSeccion === 'Rechazado';
                    @endphp
                    @if($estadoSeccion === 'Rechazado')
                    <div class="mb-8 border-2 border-red-500 rounded-lg" data-section="constitucion">
                        <div class="flex items-center justify-center mb-4 p-4 bg-red-50 border-b border-red-200">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-600 text-white">
                                Corregir
                            </span>
                        </div>
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            @include('components.forms.constitucion', [
                                'editable' => $esEditable,
                                'datosConstitucion' => $viewModel
                            ])
                        </div>
                        
                        <!-- Comentarios del Revisor -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mt-3" data-seccion="constitucion">
                            <x-revision.textarea-comentarios 
                                seccion="constitucion"
                                placeholder="Observaciones del revisor..."
                                label-text="Observaciones:"
                                :rows="2"
                                :soloLectura="true"
                            />
                        </div>
                    </div>
                    @endif

                    <div class="border-t border-gray-200 my-8"></div>

                    <!-- Accionistas -->
                    @php
                        $estadoSeccion = $estadosSecciones['accionistas'] ?? 'Pendiente';
                        $esEditable = $estadoSeccion === 'Rechazado';
                    @endphp
                    @if($estadoSeccion === 'Rechazado')
                    <div class="mb-8 border-2 border-red-500 rounded-lg" data-section="accionistas">
                        <div class="flex items-center justify-center mb-4 p-4 bg-red-50 border-b border-red-200">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-600 text-white">
                                Corregir
                            </span>
                        </div>
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            @include('components.forms.accionistas', [
                                'editable' => $esEditable,
                                'accionistas' => $viewModel
                            ])
                        </div>
                        
                        <!-- Comentarios del Revisor -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mt-3" data-seccion="accionistas">
                            <x-revision.textarea-comentarios 
                                seccion="accionistas"
                                placeholder="Observaciones del revisor..."
                                label-text="Observaciones:"
                                :rows="2"
                                :soloLectura="true"
                            />
                        </div>
                    </div>
                    @endif

                    <div class="border-t border-gray-200 my-8"></div>

                    <!-- Apoderado Legal -->
                    @php
                        $estadoSeccion = $estadosSecciones['apoderado'] ?? 'Pendiente';
                        $esEditable = $estadoSeccion === 'Rechazado';
                    @endphp
                    @if($estadoSeccion === 'Rechazado')
                    <div class="mb-8 border-2 border-red-500 rounded-lg" data-section="apoderado">
                        <div class="flex items-center justify-center mb-4 p-4 bg-red-50 border-b border-red-200">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-600 text-white">
                                Corregir
                            </span>
                        </div>
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            @include('components.forms.apoderado', [
                                'editable' => $esEditable,
                                'datosApoderado' => $viewModel
                            ])
                        </div>
                        
                        <!-- Comentarios del Revisor -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mt-3" data-seccion="apoderado">
                            <x-revision.textarea-comentarios 
                                seccion="apoderado"
                                placeholder="Observaciones del revisor..."
                                label-text="Observaciones:"
                                :rows="2"
                                :soloLectura="true"
                            />
                        </div>
                    </div>
                    @endif
                @endif

                <div class="border-t border-gray-200 my-8"></div>

                <!-- Archivos -->
                @if(count($archivosRechazados) > 0)
                <div class="mb-8 border-2 border-red-500 rounded-lg" data-section="archivos-correccion">
                    <div class="flex items-center justify-center mb-4 p-4 bg-red-50 border-b border-red-200">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-600 text-white">
                            Corregir
                        </span>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <x-revision.evaluacion-archivos 
                            :archivosSubidos="$archivosRechazados"
                            seccion="archivos_correccion"
                            :modoCorreccion="true"
                        />
                    </div>
                </div>
                @endif

                <!-- Botón de envío -->
                <div class="flex justify-center bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex space-x-4">
                        <a href="{{ route('tramites.estado') }}" 
                           class="px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition-colors">
                            Cancelar
                        </a>
                        
                        <button type="submit" 
                                class="px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                            Guardar Correcciones
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Botones de navegación ocultos para el formulario de edición -->
<div class="fixed bottom-6 right-6 space-y-2 z-40" style="display: none;">
    <button type="button" id="btn-prev" onclick="navigateSection('prev')" 
            class="w-12 h-12 bg-gray-600 hover:bg-gray-700 text-white rounded-full shadow-lg flex items-center justify-center transition-colors">
        <i class="fas fa-chevron-up"></i>
    </button>
    <button type="button" id="btn-next" onclick="navigateSection('next')" 
            class="w-12 h-12 bg-red-600 hover:bg-red-700 text-white rounded-full shadow-lg flex items-center justify-center transition-colors">
        <i class="fas fa-chevron-down"></i>
    </button>
</div>

<script>
// Para el formulario de edición, mostrar todas las secciones
document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('[data-section]');
    
    // Mostrar todas las secciones
    sections.forEach((section) => {
        section.style.display = 'block';
    });
    
    // Ocultar los botones de navegación ya que no son necesarios
    const navButtons = document.querySelector('.fixed.bottom-6.right-6');
    if (navButtons) {
        navButtons.style.display = 'none';
    }
});

// Función para actualizar el nombre del archivo seleccionado
function updateFileName(input, nameElementId) {
    const nameElement = document.getElementById(nameElementId);
    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        nameElement.textContent = `Archivo seleccionado: ${fileName}`;
        nameElement.classList.remove('hidden');
        
        // Cambiar el color del borde del contenedor para indicar que se seleccionó un archivo
        const container = input.closest('.border-dashed');
        if (container) {
            container.classList.remove('border-red-300', 'border-gray-300');
            container.classList.add('border-blue-300');
        }
    } else {
        nameElement.classList.add('hidden');
    }
}

// Función para validar que solo se suban archivos en secciones editables
function validateFileUpload(input) {
    const container = input.closest('.border-dashed');
    if (container) {
        // Verificar si el contenedor tiene opacidad reducida (no editable)
        const computedStyle = window.getComputedStyle(container);
        const opacity = parseFloat(computedStyle.opacity);
        
        if (opacity < 1) {
            // Si la sección no es editable, limpiar el input y mostrar mensaje
            input.value = '';
            alert('Este archivo no se puede modificar en modo de corrección. Solo los archivos rechazados permiten subir nuevos documentos.');
            return false;
        }
    }
    return true;
}

// Función para validar archivos en la sección de corrección
function validateFileUploadCorreccion(input) {
    const container = input.closest('.border-dashed');
    if (container) {
        // En la sección de corrección, solo permitir archivos rechazados
        const archivoId = input.getAttribute('data-archivo-id');
        const estadoArchivo = getEstadoArchivo(archivoId);
        
        if (estadoArchivo !== 'Rechazado') {
            input.value = '';
            alert('Solo los archivos marcados como "Rechazado" permiten subir un nuevo documento en la sección de corrección.');
            return false;
        }
    }
    return true;
}

// Función para obtener el estado de un archivo (simulada)
function getEstadoArchivo(archivoId) {
    // Esta función debería obtener el estado real del archivo desde el servidor
    // Por ahora, retornamos 'Rechazado' para archivos que tienen el atributo data-archivo-id
    return 'Rechazado';
}
</script>

<!-- Scripts de revisión digital -->
<script src="{{ asset('js/revision-digital.js') }}"></script>
<script src="{{ asset('js/revision/cargar-estados.js') }}"></script>

<!-- Scripts de validación -->
<script type="module" src="{{ asset('js/validations/edit-form-validator.js') }}"></script>
<script src="{{ asset('js/validations/edit-form-conditional.js') }}"></script>

<script>
// Inicializar sistema de revisión para el formulario de edición
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el sistema de carga de estados si existe
    if (typeof RevisionDigitalEstados !== 'undefined') {
        const tramiteId = {{ $tramite->id }};
        window.revisionEstados = new RevisionDigitalEstados(tramiteId);
    }
    
    // Agregar event listeners para sincronización de comentarios
    const textareas = document.querySelectorAll('[id^="textarea_"]');
    textareas.forEach(textarea => {
        const seccion = textarea.id.replace('textarea_', '');
        
        // Sincronizar en tiempo real mientras el usuario escribe
        textarea.addEventListener('input', function() {
            if (typeof sincronizarComentario === 'function') {
                sincronizarComentario(seccion);
            }
        });
        
        // Sincronizar cuando pierde el foco
        textarea.addEventListener('blur', function() {
            if (typeof sincronizarComentario === 'function') {
                sincronizarComentario(seccion);
            }
        });
    });
    
    // Para el formulario de edición, solo sincronizar comentarios si es necesario
    const form = document.getElementById('tramite-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Sincronizar todos los comentarios antes de enviar si es necesario
            textareas.forEach(textarea => {
                const seccion = textarea.id.replace('textarea_', '');
                if (typeof sincronizarComentario === 'function') {
                    sincronizarComentario(seccion);
                }
            });
        });
    }
    
    // Agregar event listeners para archivos de corrección
    const archivosCorreccion = document.querySelectorAll('input[name^="documentos_correccion"]');
    archivosCorreccion.forEach(input => {
        input.addEventListener('change', function() {
            validateFileUploadCorreccion(this);
        });
    });
});
</script>
@endsection 