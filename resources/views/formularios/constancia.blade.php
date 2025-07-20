@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Sección de carga de PDF -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                    Extractor de Datos desde PDF con QR
                </h2>

                <form id="qrPdfForm" enctype="multipart/form-data" class="space-y-4">
                    <div
                        class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary transition-colors">
                        <div class="mb-4">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                        </div>
                        <input type="file" name="pdf" accept="application/pdf" required
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark">
                        <p class="mt-2 text-sm text-gray-600">
                            Selecciona un archivo PDF que contenga código QR del SAT
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Formatos soportados: PDF (máximo 5MB)
                        </p>
                    </div>

                    <button type="submit"
                        class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-qrcode mr-2"></i>
                        Extraer Datos del QR
                    </button>
                </form>

                <div id="loadingIndicator" class="mt-6 text-center hidden">
                    <div
                        class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-primary">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Procesando PDF y extrayendo datos...
                    </div>
                </div>
            </div>

            <!-- Sección de resultados -->
            <div id="qrResult" class="bg-white rounded-lg shadow-md p-6 hidden">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    Datos Extraídos
                </h3>
                <div id="qrContent" class="space-y-4"></div>
            </div>

            <!-- Formulario de datos extraídos -->
            <div id="dataForm" class="bg-white rounded-lg shadow-md p-6 hidden">
                <h3 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-edit text-blue-500 mr-2"></i>
                    Datos del Contribuyente
                </h3>

                <form id="contributorForm" class="space-y-6">
                    <!-- Datos básicos -->
                    <div class="border-b pb-4">
                        <h4 class="text-lg font-semibold text-gray-700 mb-4">Información Básica</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">RFC</label>
                                <input type="text" id="rfc" name="rfc" readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Persona</label>
                                <input type="text" id="tipoPersona" name="tipo_persona" readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Razón Social / Nombre
                                    Completo</label>
                                <input type="text" id="razonSocial" name="razon_social" readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700">
                            </div>

                            <div id="curpField" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">CURP</label>
                                <input type="text" id="curp" name="curp" readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Estatus</label>
                                <input type="text" id="estatus" name="estatus" readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700">
                            </div>
                        </div>
                    </div>

                    <!-- Información fiscal -->
                    <div class="border-b pb-4">
                        <h4 class="text-lg font-semibold text-gray-700 mb-4">Información Fiscal</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Régimen Fiscal</label>
                                <input type="text" id="regimenFiscal" name="regimen_fiscal" readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Inicio de
                                    Operaciones</label>
                                <input type="text" id="fechaInicio" name="fecha_inicio" readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Última
                                    Actualización</label>
                                <input type="text" id="fechaActualizacion" name="fecha_actualizacion" readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700">
                            </div>
                        </div>
                    </div>

                    <!-- Información de ubicación -->
                    <div class="border-b pb-4">
                        <h4 class="text-lg font-semibold text-gray-700 mb-4">Domicilio Fiscal</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Código Postal</label>
                                <input type="text" id="codigoPostal" name="codigo_postal" readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Entidad Federativa</label>
                                <input type="text" id="entidadFederativa" name="entidad_federativa" readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Municipio</label>
                                <input type="text" id="municipio" name="municipio" readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Colonia</label>
                                <input type="text" id="colonia" name="colonia" readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Calle</label>
                                <input type="text" id="calle" name="calle" readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Número Exterior</label>
                                <input type="text" id="numeroExterior" name="numero_exterior" readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Número Interior</label>
                                <input type="text" id="numeroInterior" name="numero_interior" readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                                <input type="email" id="email" name="email" readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="button" id="processDataBtn"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Procesar y Guardar Datos
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sección para probar con URL directa -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-link text-purple-500 mr-2"></i>
                    Probar con URL del SAT
                </h3>

                <form id="urlTestForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">URL del QR del SAT</label>
                        <input type="url" id="satUrl" name="sat_url"
                            placeholder="https://siat.sat.gob.mx/app/qr/faces/pages/mobile/validadorqr.jsf?D1=10&D2=1&D3=..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">
                            Pega aquí la URL completa del código QR del SAT para extraer los datos directamente
                        </p>
                    </div>

                    <button type="submit"
                        class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200">
                        <i class="fas fa-search mr-2"></i>
                        Extraer Datos de URL
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/modules/sat-scraper.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Debug: Verificar que el módulo SATScraper se cargó correctamente
            if (typeof SATScraper === 'undefined') {
                console.error('❌ SATScraper module not loaded');
                return;
            } else {
                console.log('✅ SATScraper module loaded successfully');
            }
            const form = document.getElementById('qrPdfForm');
            const resultDiv = document.getElementById('qrResult');
            const contentDiv = document.getElementById('qrContent');
            const loadingDiv = document.getElementById('loadingIndicator');
            const dataFormDiv = document.getElementById('dataForm');
            const processDataBtn = document.getElementById('processDataBtn');

            // Función para extraer datos del QR del SAT
            function extractSATData(url) {
                try {
                    const urlObj = new URL(url);
                    const params = new URLSearchParams(urlObj.search);

                    // Extraer parámetros comunes del QR del SAT
                    const data = {
                        rfc: params.get('rfc') || params.get('RFC') || '',
                        razonSocial: params.get('nombre') || params.get('NOMBRE') || params.get(
                            'razonSocial') || '',
                        regimenFiscal: params.get('regimen') || params.get('REGIMEN') || '',
                        fechaInicio: params.get('fechaInicio') || params.get('FECHA_INICIO') || '',
                        estatus: params.get('estatus') || params.get('ESTATUS') || 'Activo',
                        fechaActualizacion: new Date().toLocaleDateString('es-MX')
                    };

                    return data;
                } catch (error) {
                    console.error('Error extrayendo datos del QR:', error);
                    return null;
                }
            }

            // Función para consultar datos del SAT por RFC
            async function consultarDatosSAT(rfc) {
                try {
                    // Aquí puedes implementar una consulta a la API del SAT o tu base de datos
                    // Por ahora simulamos una respuesta
                    const response = await fetch(`/api/consultar-rfc/${rfc}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    if (response.ok) {
                        return await response.json();
                    }

                    return null;
                } catch (error) {
                    console.error('Error consultando datos del SAT:', error);
                    return null;
                }
            }

            // Función para llenar el formulario con los datos extraídos del SAT
            function fillForm(data) {
                // Datos básicos
                document.getElementById('rfc').value = data.rfc || '';
                document.getElementById('razonSocial').value = data.razon_social || '';
                document.getElementById('tipoPersona').value = data.tipo_persona === 'fisica' ? 'Persona Física' :
                    'Persona Moral';
                document.getElementById('estatus').value = data.estatus || '';

                // Mostrar CURP solo para personas físicas
                const curpField = document.getElementById('curpField');
                if (data.tipo_persona === 'fisica' && data.curp) {
                    document.getElementById('curp').value = data.curp;
                    curpField.classList.remove('hidden');
                } else {
                    curpField.classList.add('hidden');
                }

                // Información fiscal
                document.getElementById('regimenFiscal').value = data.regimen_fiscal || '';
                document.getElementById('fechaInicio').value = data.fecha_inicio || '';
                document.getElementById('fechaActualizacion').value = data.fecha_actualizacion || '';

                // Información de ubicación
                document.getElementById('codigoPostal').value = data.codigo_postal || '';
                document.getElementById('entidadFederativa').value = data.entidad_federativa || '';
                document.getElementById('municipio').value = data.municipio || '';
                document.getElementById('colonia').value = data.colonia || '';
                document.getElementById('calle').value = data.calle || '';
                document.getElementById('numeroExterior').value = data.numero_exterior || '';
                document.getElementById('numeroInterior').value = data.numero_interior || '';
                document.getElementById('email').value = data.email || '';
            }

            // Función para mostrar los datos extraídos
            function displayExtractedData(url, satData, rawSatData) {
                let dataPreview = '';

                if (satData) {
                    dataPreview = `
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h4 class="font-semibold text-green-800 mb-3">Datos Extraídos del SAT:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        ${satData.rfc ? `<div><span class="font-medium">RFC:</span> ${satData.rfc}</div>` : ''}
                        ${satData.razon_social ? `<div><span class="font-medium">Razón Social:</span> ${satData.razon_social}</div>` : ''}
                        ${satData.tipo_persona ? `<div><span class="font-medium">Tipo:</span> ${satData.tipo_persona === 'fisica' ? 'Persona Física' : 'Persona Moral'}</div>` : ''}
                        ${satData.regimen_fiscal ? `<div><span class="font-medium">Régimen:</span> ${satData.regimen_fiscal}</div>` : ''}
                        ${satData.estatus ? `<div><span class="font-medium">Estatus:</span> ${satData.estatus}</div>` : ''}
                        ${satData.entidad_federativa ? `<div><span class="font-medium">Estado:</span> ${satData.entidad_federativa}</div>` : ''}
                        ${satData.codigo_postal ? `<div><span class="font-medium">CP:</span> ${satData.codigo_postal}</div>` : ''}
                        ${satData.email ? `<div><span class="font-medium">Email:</span> ${satData.email}</div>` : ''}
                    </div>
                    
                    ${rawSatData && rawSatData.success ? `
                                        <div class="mt-4 pt-3 border-t border-green-300">
                                            <details class="cursor-pointer">
                                                <summary class="text-sm font-medium text-green-700 hover:text-green-800">
                                                    Ver datos completos extraídos
                                                </summary>
                                                <div class="mt-2 text-xs bg-white rounded p-3 border">
                                                    <div class="space-y-2">
                                                        ${rawSatData.identificacion ? `
                                            <div>
                                                <strong>Identificación:</strong>
                                                <pre class="text-xs mt-1 whitespace-pre-wrap">${JSON.stringify(rawSatData.identificacion, null, 2)}</pre>
                                            </div>
                                        ` : ''}
                                                        ${rawSatData.ubicacion ? `
                                            <div>
                                                <strong>Ubicación:</strong>
                                                <pre class="text-xs mt-1 whitespace-pre-wrap">${JSON.stringify(rawSatData.ubicacion, null, 2)}</pre>
                                            </div>
                                        ` : ''}
                                                        ${rawSatData.caracteristicas_fiscales ? `
                                            <div>
                                                <strong>Características Fiscales:</strong>
                                                <pre class="text-xs mt-1 whitespace-pre-wrap">${JSON.stringify(rawSatData.caracteristicas_fiscales, null, 2)}</pre>
                                            </div>
                                        ` : ''}
                                                    </div>
                                                </div>
                                            </details>
                                        </div>
                                    ` : ''}
                </div>
            `;
                } else {
                    dataPreview = `
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h4 class="font-semibold text-yellow-800 mb-2">Información:</h4>
                    <p class="text-yellow-700 text-sm">No se pudieron extraer datos estructurados del QR. Puedes consultar la URL manualmente o intentar con otro archivo.</p>
                    ${rawSatData && rawSatData.error ? `
                                        <p class="text-red-600 text-xs mt-2">Error: ${rawSatData.error}</p>
                                    ` : ''}
                </div>
            `;
                }

                contentDiv.innerHTML = `
            <div class="space-y-4">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <span class="text-green-600 font-semibold">Código QR procesado exitosamente</span>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-800 mb-2">URL Original del QR:</h4>
                    <a href="${url}" target="_blank" class="text-blue-600 hover:text-blue-800 underline break-all text-sm">
                        ${url}
                    </a>
                </div>
                
                ${dataPreview}
                
                <div class="pt-4">
                    <button type="button" id="showFormBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>
                        ${satData ? 'Ver Formulario con Datos' : 'Llenar Formulario Manualmente'}
                    </button>
                </div>
            </div>
        `;

                // Agregar evento al botón para mostrar el formulario
                document.getElementById('showFormBtn').addEventListener('click', function() {
                    if (satData) {
                        fillForm(satData);
                    }
                    dataFormDiv.classList.remove('hidden');
                    dataFormDiv.scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            }

            // Evento principal del formulario
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const fileInput = this.querySelector('input[type="file"]');

                if (!fileInput.files[0]) {
                    alert('Por favor selecciona un archivo PDF');
                    return;
                }

                // Mostrar loading
                loadingDiv.classList.remove('hidden');
                resultDiv.classList.add('hidden');
                dataFormDiv.classList.add('hidden');

                try {
                    const response = await fetch('/api/extract-qr-url', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();

                    loadingDiv.classList.add('hidden');
                    resultDiv.classList.remove('hidden');

                    if (data.success && data.url) {
                        // Usar los datos scrapeados del SAT que vienen del backend
                        let satData = null;

                        if (data.sat_data && data.sat_data.success) {
                            satData = data.sat_data.form_data;
                            console.log('✅ Datos del SAT obtenidos del backend:', satData);
                        } else {
                            console.log('⚠️ No se obtuvieron datos del backend, intentando scraping adicional...');
                            // Si no vienen datos del backend, hacer scraping desde el frontend
                            try {
                                const frontendScrapeResult = await satScraper.scrapeFromUrl(data.url);
                                if (frontendScrapeResult.success && frontendScrapeResult.sat_data) {
                                    satData = satScraper.formatDataForDisplay(frontendScrapeResult.sat_data);
                                    console.log('✅ Datos obtenidos del scraping frontend:', satData);
                                } else {
                                    console.log('⚠️ Scraping frontend falló, usando extracción básica de URL');
                                    satData = extractSATData(data.url);
                                }
                            } catch (error) {
                                console.warn('❌ Error en scraping frontend:', error);
                                // Fallback: intentar extraer datos básicos de la URL
                                satData = extractSATData(data.url);
                            }
                        }

                        displayExtractedData(data.url, satData, data.sat_data);
                    } else {
                        contentDiv.innerHTML = `
                    <div class="text-red-600">
                        <div class="flex items-center space-x-2 mb-2">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span class="font-semibold">No se encontró código QR en el PDF</span>
                        </div>
                        <p class="text-sm">${data.error || 'Verifica que el PDF contenga un código QR válido del SAT'}</p>
                    </div>
                `;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    loadingDiv.classList.add('hidden');
                    resultDiv.classList.remove('hidden');
                    contentDiv.innerHTML = `
                <div class="text-red-600">
                    <div class="flex items-center space-x-2 mb-2">
                        <i class="fas fa-times-circle"></i>
                        <span class="font-semibold">Error al procesar el PDF</span>
                    </div>
                    <p class="text-sm">${error.message}</p>
                </div>
            `;
                }
            });

            // Evento para procesar y guardar datos
            processDataBtn.addEventListener('click', async function() {
                const formData = new FormData(document.getElementById('contributorForm'));
                const data = Object.fromEntries(formData);

                // Validar datos mínimos
                if (!data.rfc) {
                    alert('El RFC es requerido');
                    return;
                }

                try {
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Procesando...';

                    const response = await fetch('/api/procesar-datos-contribuyente', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert('Datos procesados y guardados exitosamente');
                        // Opcional: redirigir o limpiar formulario
                        window.location.reload();
                    } else {
                        alert('Error al procesar los datos: ' + (result.error || 'Error desconocido'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error al procesar los datos');
                } finally {
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-save mr-2"></i>Procesar y Guardar Datos';
                }
            });

            // Inicializar el scraper del SAT
            const satScraper = new SATScraper();

            // Evento para el formulario de URL directa
            const urlTestForm = document.getElementById('urlTestForm');
            urlTestForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const urlInput = document.getElementById('satUrl');
                const url = urlInput.value.trim();

                if (!url) {
                    alert('Por favor ingresa una URL válida');
                    return;
                }

                if (!satScraper.isValidSATUrl(url)) {
                    alert('La URL debe ser del sitio oficial del SAT (siat.sat.gob.mx)');
                    return;
                }

                // Mostrar loading
                loadingDiv.classList.remove('hidden');
                resultDiv.classList.add('hidden');
                dataFormDiv.classList.add('hidden');

                try {
                    const data = await satScraper.scrapeFromUrl(url);

                    loadingDiv.classList.add('hidden');
                    resultDiv.classList.remove('hidden');

                    if (data.success && data.sat_data) {
                        const satData = satScraper.formatDataForDisplay(data.sat_data);
                        displayExtractedData(url, satData, data.sat_data);
                    } else {
                        contentDiv.innerHTML = `
                    <div class="text-red-600">
                        <div class="flex items-center space-x-2 mb-2">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span class="font-semibold">No se pudieron extraer datos de la URL</span>
                        </div>
                        <p class="text-sm">${data.error || 'Verifica que la URL sea válida y esté accesible'}</p>
                    </div>
                `;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    loadingDiv.classList.add('hidden');
                    resultDiv.classList.remove('hidden');
                    contentDiv.innerHTML = `
                <div class="text-red-600">
                    <div class="flex items-center space-x-2 mb-2">
                        <i class="fas fa-times-circle"></i>
                        <span class="font-semibold">Error al procesar la URL</span>
                    </div>
                    <p class="text-sm">${error.message}</p>
                </div>
            `;
                }
            });

            // Función auxiliar para extraer RFC de URL y prellenar
            document.getElementById('satUrl').addEventListener('input', function() {
                const url = this.value.trim();
                if (url && satScraper.isValidSATUrl(url)) {
                    const rfc = satScraper.extractRFCFromUrl(url);
                    if (rfc) {
                        // Mostrar RFC extraído como hint
                        const hint = document.createElement('div');
                        hint.className = 'mt-1 text-xs text-green-600';
                        hint.textContent = `RFC detectado: ${rfc}`;

                        // Remover hint anterior si existe
                        const existingHint = this.parentNode.querySelector('.rfc-hint');
                        if (existingHint) {
                            existingHint.remove();
                        }

                        hint.classList.add('rfc-hint');
                        this.parentNode.appendChild(hint);
                    }
                } else {
                    // Remover hint si URL no es válida
                    const existingHint = this.parentNode.querySelector('.rfc-hint');
                    if (existingHint) {
                        existingHint.remove();
                    }
                }
            });
        });
    </script>
@endsection
