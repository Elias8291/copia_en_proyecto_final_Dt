/**
 * Reportes Trimestrales - JavaScript
 * Maneja la funcionalidad de generación de reportes trimestrales
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Iniciando módulo de Reportes Trimestrales');
    
    // Elementos del DOM
    const form = document.getElementById('formReporteTrimestral');
    const btnGenerar = document.getElementById('btnGenerar');
    const btnText = document.getElementById('btnText');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const previewSection = document.getElementById('previewSection');
    const previewContent = document.getElementById('previewContent');
    const messagesContainer = document.getElementById('messages');
    
    // Elementos de formulario
    const añoSelect = document.getElementById('año');
    const trimestreSelect = document.getElementById('trimestre');
    const formatoSelect = document.getElementById('formato');

    // Configuración de trimestres
    const trimestres = {
        1: { nombre: 'Primer Trimestre (Q1)', periodo: 'Enero - Marzo', meses: 'ENE, FEB, MAR' },
        2: { nombre: 'Segundo Trimestre (Q2)', periodo: 'Abril - Junio', meses: 'ABR, MAY, JUN' },
        3: { nombre: 'Tercer Trimestre (Q3)', periodo: 'Julio - Septiembre', meses: 'JUL, AGO, SEP' },
        4: { nombre: 'Cuarto Trimestre (Q4)', periodo: 'Octubre - Diciembre', meses: 'OCT, NOV, DIC' }
    };

    // Event Listeners
    añoSelect.addEventListener('change', actualizarPreview);
    trimestreSelect.addEventListener('change', actualizarPreview);
    formatoSelect.addEventListener('change', actualizarPreview);
    form.addEventListener('submit', manejarSubmit);

    // Inicializar
    actualizarPreview();
    seleccionarTrimestreActual();

    /**
     * Seleccionar automáticamente el trimestre actual
     */
    function seleccionarTrimestreActual() {
        const mesActual = new Date().getMonth() + 1; // getMonth() retorna 0-11
        let trimestreActual;
        
        if (mesActual >= 1 && mesActual <= 3) {
            trimestreActual = 1;
        } else if (mesActual >= 4 && mesActual <= 6) {
            trimestreActual = 2;
        } else if (mesActual >= 7 && mesActual <= 9) {
            trimestreActual = 3;
        } else {
            trimestreActual = 4;
        }
        
        trimestreSelect.value = trimestreActual;
        console.log(`📅 Trimestre actual detectado: Q${trimestreActual}`);
        actualizarPreview();
    }

    /**
     * Actualizar vista previa del reporte
     */
    function actualizarPreview() {
        const año = añoSelect.value;
        const trimestre = parseInt(trimestreSelect.value);
        const formato = formatoSelect.value;
        
        if (!año || !trimestre) {
            previewSection.style.display = 'none';
            return;
        }

        const trimestreInfo = trimestres[trimestre];
        const fechas = calcularFechasTrimestre(año, trimestre);
        
        const previewHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="text-blue-600 font-semibold">📊 Trimestre:</span>
                        <span>${trimestreInfo.nombre}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-blue-600 font-semibold">📅 Período:</span>
                        <span>${fechas.inicio} al ${fechas.fin}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-blue-600 font-semibold">📁 Formato:</span>
                        <span>${formato.toUpperCase()}</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="text-blue-600 font-semibold">🗓️ Año:</span>
                        <span>${año}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-blue-600 font-semibold">📋 Meses:</span>
                        <span>${trimestreInfo.meses}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-blue-600 font-semibold">📄 Archivo:</span>
                        <span>reporte_trimestral_${año}_T${trimestre}.xlsx</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                <p class="text-sm text-blue-800">
                    <strong>Criterio de selección:</strong> Proveedores que estuvieron activos durante el ${trimestreInfo.periodo.toLowerCase()} de ${año}, 
                    considerando fechas de creación y finalización.
                </p>
            </div>
        `;
        
        previewContent.innerHTML = previewHTML;
        previewSection.style.display = 'block';
        
        // Agregar animación de entrada
        previewSection.style.opacity = '0';
        previewSection.style.transform = 'translateY(10px)';
        setTimeout(() => {
            previewSection.style.transition = 'all 0.3s ease';
            previewSection.style.opacity = '1';
            previewSection.style.transform = 'translateY(0)';
        }, 10);
    }

    /**
     * Calcular fechas de inicio y fin del trimestre
     */
    function calcularFechasTrimestre(año, trimestre) {
        switch (trimestre) {
            case 1:
                return {
                    inicio: `01/01/${año}`,
                    fin: `31/03/${año}`
                };
            case 2:
                return {
                    inicio: `01/04/${año}`,
                    fin: `30/06/${año}`
                };
            case 3:
                return {
                    inicio: `01/07/${año}`,
                    fin: `30/09/${año}`
                };
            case 4:
                return {
                    inicio: `01/10/${año}`,
                    fin: `31/12/${año}`
                };
        }
    }

    /**
     * Manejar envío del formulario
     */
    function manejarSubmit(event) {
        event.preventDefault();
        
        console.log('📊 Iniciando generación de reporte trimestral');
        
        // Validar formulario
        if (!validarFormulario()) {
            return;
        }
        
        // Mostrar estado de carga
        mostrarCargando(true);
        
        // Obtener datos del formulario
        const formData = new FormData(form);
        const año = formData.get('año');
        const trimestre = formData.get('trimestre');
        const formato = formData.get('formato');
        
        console.log('Parámetros del reporte:', { año, trimestre, formato });
        
        // Enviar solicitud
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            
            // Verificar si es un archivo Excel
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')) {
                return response.blob();
            } else {
                return response.json();
            }
        })
        .then(result => {
            if (result instanceof Blob) {
                // Es un archivo Excel - descargar
                descargarArchivo(result, `reporte_trimestral_${año}_T${trimestre}_${obtenerTimestamp()}.xlsx`);
                mostrarMensaje('✅ Reporte generado y descargado exitosamente', 'success');
            } else {
                // Es una respuesta JSON - manejar error o éxito
                if (result.error) {
                    throw new Error(result.message || 'Error desconocido');
                } else {
                    mostrarMensaje('✅ Reporte generado exitosamente', 'success');
                }
            }
        })
        .catch(error => {
            console.error('❌ Error al generar reporte:', error);
            mostrarMensaje(`❌ Error al generar el reporte: ${error.message}`, 'error');
        })
        .finally(() => {
            mostrarCargando(false);
        });
    }

    /**
     * Validar formulario
     */
    function validarFormulario() {
        const año = añoSelect.value;
        const trimestre = trimestreSelect.value;
        const formato = formatoSelect.value;
        
        if (!año) {
            mostrarMensaje('⚠️ Por favor selecciona un año', 'warning');
            añoSelect.focus();
            return false;
        }
        
        if (!trimestre) {
            mostrarMensaje('⚠️ Por favor selecciona un trimestre', 'warning');
            trimestreSelect.focus();
            return false;
        }
        
        if (!formato) {
            mostrarMensaje('⚠️ Por favor selecciona un formato', 'warning');
            formatoSelect.focus();
            return false;
        }
        
        return true;
    }

    /**
     * Mostrar/ocultar estado de carga
     */
    function mostrarCargando(mostrar) {
        if (mostrar) {
            btnGenerar.disabled = true;
            btnText.textContent = 'Generando Reporte...';
            loadingSpinner.classList.remove('hidden');
            btnGenerar.classList.add('opacity-75', 'cursor-not-allowed');
        } else {
            btnGenerar.disabled = false;
            btnText.textContent = 'Generar Reporte Trimestral';
            loadingSpinner.classList.add('hidden');
            btnGenerar.classList.remove('opacity-75', 'cursor-not-allowed');
        }
    }

    /**
     * Descargar archivo blob
     */
    function descargarArchivo(blob, filename) {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
        
        console.log(`📄 Archivo descargado: ${filename}`);
    }

    /**
     * Mostrar mensaje al usuario
     */
    function mostrarMensaje(mensaje, tipo = 'info') {
        const colores = {
            success: 'bg-green-50 border-green-200 text-green-800',
            error: 'bg-red-50 border-red-200 text-red-800',
            warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
            info: 'bg-blue-50 border-blue-200 text-blue-800'
        };
        
        const iconos = {
            success: '✅',
            error: '❌',
            warning: '⚠️',
            info: 'ℹ️'
        };
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `${colores[tipo]} border rounded-lg p-4 mb-4 transition-all duration-300`;
        messageDiv.innerHTML = `
            <div class="flex items-center gap-2">
                <span class="text-lg">${iconos[tipo]}</span>
                <span>${mensaje}</span>
            </div>
        `;
        
        // Limpiar mensajes anteriores
        messagesContainer.innerHTML = '';
        messagesContainer.appendChild(messageDiv);
        
        // Auto-ocultar después de 5 segundos para mensajes de éxito
        if (tipo === 'success') {
            setTimeout(() => {
                messageDiv.style.opacity = '0';
                messageDiv.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    if (messageDiv.parentNode) {
                        messageDiv.parentNode.removeChild(messageDiv);
                    }
                }, 300);
            }, 5000);
        }
        
        // Scroll hacia el mensaje
        messageDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    /**
     * Obtener timestamp actual
     */
    function obtenerTimestamp() {
        const now = new Date();
        return now.getFullYear() + 
               String(now.getMonth() + 1).padStart(2, '0') + 
               String(now.getDate()).padStart(2, '0') + '_' +
               String(now.getHours()).padStart(2, '0') + 
               String(now.getMinutes()).padStart(2, '0') + 
               String(now.getSeconds()).padStart(2, '0');
    }

    console.log('✅ Módulo de Reportes Trimestrales inicializado correctamente');
});
