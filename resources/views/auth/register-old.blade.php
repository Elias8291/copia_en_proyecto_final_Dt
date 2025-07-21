@extends('layouts.auth')

@section('title', 'Registro - Padrón de Proveedores de Oaxaca')

@push('styles')
    <style>
        .border-3 {
            border-width: 3px;
        }

        .form-disabled {
            transition: all 0.3s ease-out;
            filter: grayscale(0.3);
        }
    </style>
@endpush

<script>
    // Funciones para manejo de constancia fiscal
    window.uploadFile = function(input) {
        if (input.files && input.files.length > 0) {
            const file = input.files[0];
            
            // Actualizar nombre en UI
            const fileNameEl = document.getElementById('fileName');
            if (fileNameEl) {
                fileNameEl.textContent = file.name;
            }
            
            // Procesar archivo
            processFileHead(file);
        }
    };
    
    window.processFileHead = async function(file) {
        try {
            // Mostrar indicador
            const indicator = document.getElementById('processingStatus');
            if (indicator) {
                indicator.classList.remove('hidden');
            }
            
            // Preparar datos
            const formData = new FormData();
            formData.append('pdf', file);
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            // Intentar ruta API primero, luego alternativa
            let response;
            try {
                response = await fetch('/api/extract-qr-url', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                });
                
                if (response.status === 419) {
                    response = await fetch('/extract-qr-url-web', {
                        method: 'POST',
                        body: formData,
                        headers: { 'Accept': 'application/json' }
                    });
                }
            } catch (error) {
                throw new Error('Error de conexión: ' + error.message);
            }
            
            const data = await response.json();
            
            // Ocultar indicador
            if (indicator) {
                indicator.classList.add('hidden');
            }
            
            if (data.success && data.sat_data) {
                // Llenar campos ocultos
                fillHiddenInputs(data.sat_data);
                
                // Mostrar modal con datos
                mostrarModalSAT(data.sat_data);
                
            } else {
                alert('Error: No se pudieron extraer los datos fiscales de la constancia.\n\n' + 
                      (data.error || 'Verifique que el archivo tenga un código QR válido.'));
            }
            
        } catch (error) {
            const indicator = document.getElementById('processingStatus');
            if (indicator) {
                indicator.classList.add('hidden');
            }
            alert('Error al procesar la constancia: ' + error.message);
        }
    };
    
    // Llenar campos ocultos con datos del SAT
    function fillHiddenInputs(satData) {
        const fields = {
            'satRfc': satData.rfc,
            'satNombre': satData.nombre,
            'satCurp': satData.curp,
            'satRegimenFiscal': satData.regimen_fiscal,
            'satEstatus': satData.estatus,
            'satEntidadFederativa': satData.entidad_federativa,
            'satMunicipio': satData.municipio,
            'satEmail': satData.email
        };
        
        Object.keys(fields).forEach(fieldId => {
            const element = document.getElementById(fieldId);
            if (element && fields[fieldId]) {
                element.value = fields[fieldId];
            }
        });
    }
    
    window.handleActionButton = function() {
        const input = document.getElementById('document');
        if (input) {
            if (input.files && input.files.length > 0) {
                processFileHead(input.files[0]);
            } else {
                input.click();
            }
        }
    };
    
    // Función para toggle de contraseña
    window.togglePassword = function(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '-toggle-icon');
        
        if (field && icon) {
            if (field.type === 'password') {
                field.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L12 12m6.121-6.121A9.97 9.97 0 0721 12c0 .906-.117 1.785-.337 2.625m-3.846 6.321L9.878 9.878"></path>';
            } else {
                field.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.723 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }
    };
</script>

@section('content')
    <!-- Modal para mostrar datos del SAT -->
    @include('components.modal-sat-datos')

    <!-- Modal de Éxito Reutilizable -->
    @include('components.modal-success')

    <!-- Modal de Loading -->
    @include('components.loading-modal')

    <form method="POST" action="{{ route('register') }}" class="space-y-6" enctype="multipart/form-data">
        @csrf
        <!-- Header con Logo -->
        <div class="text-center mb-3">
            <div class="flex flex-col items-center justify-center mb-2">
                <div
                    class="w-14 h-14 flex items-center justify-center mb-2 bg-gradient-to-br from-primary/10 to-primary-dark/10 rounded-full p-2">
                    <img src="{{ asset('images/logoprin.jpg') }}" alt="Logo Estado de Oaxaca"
                        class="w-full h-full object-contain rounded-full">
                </div>
                <div class="text-center space-y-1">
                    <span class="text-primary font-bold text-sm block tracking-wide">ADMINISTRACIÓN</span>
                    <span class="text-gray-500 text-xs font-medium uppercase tracking-wider">Gobierno del Estado de
                        Oaxaca</span>
                </div>
            </div>

            <div class="space-y-1 mb-2">
                <h1 class="text-lg font-bold text-gray-800 leading-tight">Registro de Proveedor</h1>
                <p class="text-gray-600 text-xs leading-tight max-w-xs mx-auto">
                    Suba su Constancia de Situación Fiscal con QR
                </p>
            </div>
        </div>

        <!-- Mensajes de error del servidor -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-2 py-1.5 rounded-lg mb-2">
                <ul class="list-disc list-inside text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Área de subida de PDF -->
        <div id="uploadArea" class="transition-all duration-300 ease-in-out min-h-[80px]">
            <div class="mt-1">
                <label for="document" class="block text-xs font-medium text-gray-700 mb-0.5">
                    <span class="block md:inline">Constancia de Situación Fiscal</span>
                    <span class="text-xs text-gray-500 block md:inline md:ml-1">(PDF o Imagen)</span>
                </label>
                <div class="relative">
                    <input type="file" id="document" name="document" accept=".pdf,.png,.jpg,.jpeg" required
                        class="hidden" onchange="uploadFile(this)">
                    <label for="document"
                        class="group flex flex-col items-center justify-center w-full h-16 border-2 border-dashed border-primary/20 hover:border-primary rounded-lg transition-all duration-300 cursor-pointer bg-primary-50/30 hover:bg-primary-50"
                        onclick="console.log('🔘 LABEL: Click en label'); document.getElementById('document').click();">
                        <div class="flex flex-col md:flex-row items-center space-y-0.5 md:space-y-0 md:space-x-2 px-3">
                            <div class="transform group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-4 h-4 text-primary/70 group-hover:text-primary" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="text-center md:text-left">
                                <p class="text-primary/70 group-hover:text-primary font-medium text-xs mb-0">
                                    Haga clic para seleccionar archivo
                                </p>
                                <p class="text-xs text-gray-500" id="fileName">
                                    PDF o Imagen con QR (Máximo 5MB)
                                </p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Estado de procesamiento -->
            <div id="processingStatus" class="hidden mt-3">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <div class="flex items-center justify-center space-x-2">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-primary"></div>
                        <span class="text-xs text-primary font-medium">Extrayendo datos fiscales automáticamente...</span>
                    </div>
                </div>
            </div>

            <!-- Área de previsualización (oculta) -->
            <div id="previewArea" class="hidden">
                <div class="hidden">
                    <div id="qrResult"></div>
                    <canvas id="pdfCanvas"></canvas>
                </div>
            </div>
        </div>

        <!-- Formulario de registro (inicialmente oculto) -->
        <div id="registrationForm" class="hidden space-y-2 transition-all duration-300 ease-in-out">
            <input type="hidden" id="qrUrl" name="qr_url">

            <!-- Campos ocultos para datos del SAT -->
            <input type="hidden" id="satRfc" name="sat_rfc">
            <input type="hidden" id="satNombre" name="sat_nombre">
            <input type="hidden" id="satTipoPersona" name="sat_tipo_persona">
            <input type="hidden" id="satCurp" name="sat_curp">
            <input type="hidden" id="satCp" name="sat_cp">
            <input type="hidden" id="satColonia" name="sat_colonia">
            <input type="hidden" id="satNombreVialidad" name="sat_nombre_vialidad">
            <input type="hidden" id="satNumeroExterior" name="sat_numero_exterior">
            <input type="hidden" id="satNumeroInterior" name="sat_numero_interior">
            <input type="hidden" id="satRegimenFiscal" name="sat_regimen_fiscal">
            <input type="hidden" id="satEstatus" name="sat_estatus">
            <input type="hidden" id="satEntidadFederativa" name="sat_entidad_federativa">
            <input type="hidden" id="satMunicipio" name="sat_municipio">
            <input type="hidden" id="satEmail" name="sat_email">

            <button type="button" id="verDatosBtn" onclick="showSatModal()"
                class="hidden inline-flex items-center text-xs bg-white hover:bg-primary-50 text-primary font-medium py-1 px-2 rounded-lg transition-all duration-300 shadow-sm hover:shadow border border-primary/20 hover:border-primary/40">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Ver Datos del SAT
            </button>

            <div>
                <label for="email" class="block text-xs font-medium text-gray-700 mb-0.5">Correo Electrónico</label>
                <div class="relative">
                    <input type="email" id="email" name="email" required
                        class="w-full px-2.5 py-1.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors duration-300 text-sm"
                        placeholder="ejemplo@correo.com">
                    <div id="emailValidationIcon" class="absolute right-2 top-1/2 -translate-y-1/2 hidden"></div>
                </div>
                <div class="h-6 relative">
                    <div id="emailValidation"
                        class="absolute top-0 left-0 w-full opacity-0 transform translate-y-1 transition-all duration-200">
                    </div>
                </div>
            </div>

            <!-- Contraseñas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-3">
                <div>
                    <label for="password" class="block text-xs font-medium text-gray-700 mb-0.5">Contraseña</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="w-full px-2.5 py-1.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors duration-300 text-sm"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword('password')"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                id="password-toggle-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-medium text-gray-700 mb-0.5">Confirmar
                        Contraseña</label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full px-2.5 py-1.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors duration-300 text-sm"
                            placeholder="••••••••">
                        <div class="absolute right-8 top-1/2 -translate-y-1/2">
                            <div id="passwordMatchIcon" class="hidden"></div>
                        </div>
                        <button type="button" onclick="togglePassword('password_confirmation')"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                id="password_confirmation-toggle-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Área para validación de contraseñas -->
            <div class="h-6 relative">
                <div id="passwordMatchValidation"
                    class="absolute top-0 left-0 w-full opacity-0 transform translate-y-1 transition-all duration-200">
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="space-y-2 pt-3">
            <button type="button" id="actionButton" onclick="console.log('🔘 BTN: Click directo'); handleActionButton()"
                class="group w-full bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white font-semibold py-2.5 px-4 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 relative overflow-hidden text-sm">
                <div
                    class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
                <div class="relative flex items-center justify-center space-x-2">
                    <svg id="actionIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span id="actionText">Siguiente</span>
                </div>
            </button>

            <a href="{{ url('/') }}"
                class="group w-full bg-white hover:bg-gray-50 text-primary hover:text-primary-dark font-semibold py-2.5 px-4 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 border-2 border-primary/20 hover:border-primary/40 relative overflow-hidden inline-flex items-center justify-center text-sm">
                <div class="relative flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span>Volver al Inicio</span>
                </div>
            </a>
        </div>

        <!-- Enlace de recuperación de contraseña -->
        <div class="text-center mt-3 relative z-10">
            <a href="{{ route('password.request') }}"
                class="text-gray-500 hover:text-primary text-xs font-medium transition-all duration-200 flex items-center justify-center space-x-2 hover:bg-gray-50 py-1.5 px-3 rounded-lg">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 0121 9z" />
                </svg>
                <span>¿Olvidaste tu contraseña?</span>
            </a>
        </div>
    </form>


@endsection
