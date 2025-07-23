/**
 * TramiteFormManager - Sistema profesional para manejo de formularios de trámites
 * Incluye: AJAX, validación en tiempo real, feedback elegante, estados de carga
 */

// Prevenir múltiples declaraciones
if (typeof window.TramiteFormManager === 'undefined') {
    
    class TramiteFormManager {
        constructor(formElement) {
            // Aceptar tanto ID como elemento del DOM
            if (typeof formElement === 'string') {
                this.form = document.getElementById(formElement);
            } else {
                this.form = formElement;
            }
            
            this.submitBtn = document.getElementById('btn-enviar');
            this.draftBtn = document.getElementById('btn-guardar-borrador');
            this.isSubmitting = false;
            this.validationErrors = {};
            
            this.init();
        }
        
        init() {
            if (!this.form) {
                console.error('❌ Formulario no encontrado');
                return;
            }
            
            console.log('🚀 Iniciando TramiteFormManager');
            
            // Configurar eventos
            this.setupEventListeners();
            
            // Configurar validación en tiempo real
            this.setupRealTimeValidation();
            
            // Mostrar estado inicial
            this.showReadyState();
        }
        
        setupEventListeners() {
            // Envío principal
            if (this.submitBtn) {
                this.submitBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.handleSubmit();
                });
            }
            
            // Borrador
            if (this.draftBtn) {
                this.draftBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.handleDraft();
                });
            }
            
            // Prevenir envío tradicional del form
            this.form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleSubmit();
            });
        }
        
        setupRealTimeValidation() {
            // Validar campos importantes en tiempo real
            const importantFields = ['rfc', 'razon_social', 'email_contacto'];
            
            importantFields.forEach(fieldName => {
                const field = this.form.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.addEventListener('blur', () => this.validateField(fieldName, field.value));
                    field.addEventListener('input', () => this.clearFieldError(fieldName));
                }
            });
        }
        
        validateField(fieldName, value) {
            const errors = {};
            
            switch(fieldName) {
                case 'rfc':
                    if (value && !/^[A-Z&Ñ]{3,4}[0-9]{6}[A-V1-9][A-Z1-9][0-9A]$/.test(value)) {
                        errors[fieldName] = 'RFC no tiene formato válido';
                    }
                    break;
                case 'email_contacto':
                    if (value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                        errors[fieldName] = 'Email no tiene formato válido';
                    }
                    break;
                case 'razon_social':
                    if (value && value.length < 3) {
                        errors[fieldName] = 'Razón social muy corta';
                    }
                    break;
            }
            
            if (Object.keys(errors).length > 0) {
                this.showFieldError(fieldName, errors[fieldName]);
            } else {
                this.clearFieldError(fieldName);
            }
        }
        
        showFieldError(fieldName, message) {
            const field = this.form.querySelector(`[name="${fieldName}"]`);
            if (!field) return;
            
            // Remover error anterior
            this.clearFieldError(fieldName);
            
            // Agregar clases de error
            field.classList.add('border-red-500', 'bg-red-50');
            
            // Crear mensaje de error
            const errorDiv = document.createElement('div');
            errorDiv.className = 'text-red-500 text-sm mt-1 field-error';
            errorDiv.textContent = message;
            field.parentNode.appendChild(errorDiv);
            
            this.validationErrors[fieldName] = message;
        }
        
        clearFieldError(fieldName) {
            const field = this.form.querySelector(`[name="${fieldName}"]`);
            if (!field) return;
            
            // Remover clases de error
            field.classList.remove('border-red-500', 'bg-red-50');
            
            // Remover mensaje de error
            const errorDiv = field.parentNode.querySelector('.field-error');
            if (errorDiv) {
                errorDiv.remove();
            }
            
            delete this.validationErrors[fieldName];
        }
        
        async handleSubmit() {
            if (this.isSubmitting) {
                console.log('⏳ Ya se está enviando, ignorando clic adicional');
                return;
            }
            
            console.log('🚀 Iniciando envío del trámite');
            
            // Limpiar errores anteriores
            this.clearAllFieldErrors();
            
            // Verificar si hay errores de validación locales
            if (Object.keys(this.validationErrors).length > 0) {
                this.showErrorAlert('Por favor corrija los errores antes de enviar');
                return;
            }
            
            this.isSubmitting = true;
            this.showLoadingState();
            
            try {
                // Crear FormData del formulario
                const formData = new FormData(this.form);
                
                // Debug opcional de actividades
                this.debugActividades(formData);
                
                // Envío AJAX
                const response = await fetch(this.form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    }
                });
                
                console.log('📡 Respuesta recibida:', response.status);
                
                if (response.ok) {
                    const result = await response.json();
                    console.log('✅ Respuesta exitosa:', result);
                    
                    if (result.success) {
                        await this.showSuccessAndRedirect(result);
                    } else {
                        throw new Error(result.message || 'Error desconocido');
                    }
                } else if (response.status === 422) {
                    // Manejar errores de validación específicamente
                    const errorData = await response.json();
                    console.log('🔍 Errores de validación recibidos:', errorData);
                    
                    // 🔍 DEBUG DETALLADO DE ERRORES
                    if (errorData.errors) {
                        console.log('📋 ERRORES ESPECÍFICOS POR CAMPO:');
                        let totalErrors = 0;
                        Object.keys(errorData.errors).forEach(field => {
                            const errors = errorData.errors[field];
                            console.log(`   ❌ ${field}:`, errors);
                            totalErrors += errors.length;
                            
                            // Mostrar el valor actual del campo
                            const fieldElement = this.form.querySelector(`[name="${field}"]`);
                            if (fieldElement) {
                                console.log(`      📝 Valor actual: "${fieldElement.value}"`);
                                console.log(`      📏 Tipo: ${fieldElement.type}, Required: ${fieldElement.required}`);
                            } else {
                                console.log(`      ⚠️ Campo no encontrado en el DOM`);
                            }
                        });
                        console.log(`📊 Total errores: ${totalErrors}`);
                        
                        // DEBUG ESPECÍFICO PARA CAMPOS PROBLEMÁTICOS
                        this.debugCamposProblematicos();
                        
                        // EJECUTAR DEBUG AUTOMÁTICO DEL FORMULARIO
                        if (typeof window.debugFormulario === 'function') {
                            console.log('🔄 Ejecutando debug automático...');
                            window.debugFormulario();
                        }
                    }
                    
                    if (errorData.validation_failed && errorData.errors) {
                        this.handleValidationErrors(errorData.errors);
                        
                        // Mostrar un mensaje más informativo
                        const errorCount = Object.keys(errorData.errors).length;
                        const errorFields = Object.keys(errorData.errors).join(', ');
                        this.showErrorAlert(`Se encontraron ${errorCount} errores en: ${errorFields}`);
                    } else {
                        throw new Error(errorData.message || 'Error de validación');
                    }
                } else {
                    // Manejar otros errores HTTP
                    let errorMessage;
                    try {
                        const errorData = await response.json();
                        errorMessage = errorData.message || `Error del servidor (${response.status})`;
                    } catch {
                        errorMessage = `Error del servidor (${response.status})`;
                    }
                    console.error('❌ Error HTTP:', response.status, errorMessage);
                    throw new Error(errorMessage);
                }
                
            } catch (error) {
                console.error('❌ Error al enviar trámite:', error);
                this.showErrorAlert(error.message);
            } finally {
                this.isSubmitting = false;
                this.showReadyState();
            }
        }

        /**
         * Debug de actividades para desarrollo
         */
        debugActividades(formData) {
            try {
                console.log('🔍 DEBUG COMPLETO DEL FORMULARIO:');
                
                // Debug de campos importantes
                console.log('📋 DATOS GENERALES:');
                const rfcField = this.form.querySelector('[name="rfc"]');
                const razonSocialField = this.form.querySelector('[name="razon_social"]');
                const tipoPersonaField = this.form.querySelector('[name="tipo_persona"]');
                
                console.log('   📝 RFC campo:', rfcField ? `"${rfcField.value}" (${rfcField.value.length} chars)` : 'CAMPO NO ENCONTRADO');
                console.log('   📝 RFC FormData:', formData.get('rfc') ? `"${formData.get('rfc')}" (${formData.get('rfc').length} chars)` : 'NO EN FORMDATA');
                console.log('   📝 Razón Social:', formData.get('razon_social') || 'NO ENVIADO');
                console.log('   📝 Tipo Persona:', formData.get('tipo_persona') || 'NO ENVIADO');
                
                // Debug de actividades
                console.log('📋 ACTIVIDADES:');
                const actividadesInputs = document.querySelectorAll('input[name="actividades[]"]');
                console.log('   🔢 Total inputs encontrados:', actividadesInputs.length);
                
                actividadesInputs.forEach((input, index) => {
                    console.log(`   Input ${index + 1}: valor="${input.value}"`);
                });
                
                const actividadesEnFormData = formData.getAll('actividades[]');
                console.log('   📝 Actividades en FormData:', actividadesEnFormData);
                
                // Debug de domicilio
                console.log('📋 DOMICILIO:');
                console.log('   📝 Calle:', formData.get('calle') || 'NO ENVIADO');
                console.log('   📝 Código Postal:', formData.get('codigo_postal') || 'NO ENVIADO');
                console.log('   📝 Estado ID:', formData.get('estado_id') || 'NO ENVIADO');
                
                // Debug de confirmación
                console.log('📋 CONFIRMACIÓN:');
                console.log('   📝 Confirma datos:', formData.get('confirma_datos') || 'NO ENVIADO');
                
            } catch (error) {
                console.warn('⚠️ Error en debug completo:', error);
            }
        }

        /**
         * Debug específico para campos que suelen causar problemas
         */
        debugCamposProblematicos() {
            console.log('🔍 DEBUG CAMPOS PROBLEMÁTICOS:');
            
            // Debug específico del SELECT de estado
            const estadoSelect = document.querySelector('select[name="estado_id"]');
            if (estadoSelect) {
                console.log('🏛️ SELECT ESTADO DETALLADO:');
                console.log(`   📝 Valor: "${estadoSelect.value}" (tipo: ${typeof estadoSelect.value})`);
                console.log(`   📝 SelectedIndex: ${estadoSelect.selectedIndex}`);
                console.log(`   📝 Options total: ${estadoSelect.options.length}`);
                console.log(`   📝 Required: ${estadoSelect.required}`);
                
                if (estadoSelect.selectedIndex >= 0) {
                    const selectedOption = estadoSelect.options[estadoSelect.selectedIndex];
                    console.log(`   📝 Opción seleccionada: value="${selectedOption.value}", text="${selectedOption.text}"`);
                }
                
                // Mostrar las primeras opciones disponibles
                console.log('   📋 Primeras opciones disponibles:');
                for (let i = 0; i < Math.min(5, estadoSelect.options.length); i++) {
                    const option = estadoSelect.options[i];
                    console.log(`      ${i}: value="${option.value}", text="${option.text}"`);
                }
                
                // Verificar si el valor se envía en FormData
                const formData = new FormData(this.form);
                const estadoEnFormData = formData.get('estado_id');
                console.log(`   📤 En FormData: "${estadoEnFormData}" (tipo: ${typeof estadoEnFormData})`);
            } else {
                console.log('❌ SELECT ESTADO NO ENCONTRADO');
            }
            
            // Debug de checkbox de confirmación
            const confirmaCheckbox = document.querySelector('input[name="confirma_datos"]');
            if (confirmaCheckbox) {
                console.log('☑️ CHECKBOX CONFIRMACIÓN:');
                console.log(`   📝 Checked: ${confirmaCheckbox.checked}`);
                console.log(`   📝 Value: "${confirmaCheckbox.value}"`);
                console.log(`   📝 Required: ${confirmaCheckbox.required}`);
                console.log(`   📝 Type: ${confirmaCheckbox.type}`);
                
                const formData = new FormData(this.form);
                const confirmaEnFormData = formData.get('confirma_datos');
                console.log(`   📤 En FormData: "${confirmaEnFormData}"`);
            } else {
                console.log('❌ CHECKBOX CONFIRMACIÓN NO ENCONTRADO');
            }
            
            // Debug de actividades
            const actividadesInputs = document.querySelectorAll('input[name="actividades[]"]');
            console.log('🎯 ACTIVIDADES DETALLADO:');
            console.log(`   📝 Total inputs: ${actividadesInputs.length}`);
            
            if (actividadesInputs.length > 0) {
                console.log('   📋 Actividades seleccionadas:');
                actividadesInputs.forEach((input, index) => {
                    console.log(`      ${index + 1}: value="${input.value}", checked=${input.checked}`);
                });
                
                const formData = new FormData(this.form);
                const actividadesEnFormData = formData.getAll('actividades[]');
                console.log(`   📤 En FormData: [${actividadesEnFormData.join(', ')}] (total: ${actividadesEnFormData.length})`);
            } else {
                console.log('   ❌ NO SE ENCONTRARON INPUTS DE ACTIVIDADES');
                
                // Buscar contenedores relacionados
                const contenedorActividades = document.getElementById('actividades-hidden-inputs');
                const buscadorActividades = document.getElementById('buscador-actividad');
                console.log(`   🔍 Contenedor actividades: ${contenedorActividades ? 'ENCONTRADO' : 'NO ENCONTRADO'}`);
                console.log(`   🔍 Buscador actividades: ${buscadorActividades ? 'ENCONTRADO' : 'NO ENCONTRADO'}`);
            }
            
            // Debug de otros campos problemáticos
            const camposProblematicos = ['municipio', 'asentamiento', 'numero_exterior', 'calle', 'codigo_postal', 'email_contacto', 'telefono', 'razon_social'];
            
            console.log('📋 OTROS CAMPOS IMPORTANTES:');
            camposProblematicos.forEach(campo => {
                const field = document.querySelector(`[name="${campo}"]`);
                if (field) {
                    const valor = field.value || 'VACÍO';
                    const requerido = field.required ? 'REQUERIDO' : 'OPCIONAL';
                    console.log(`   🔍 ${campo}: "${valor}" (${requerido})`);
                    
                    if (field.required && !field.value.trim()) {
                        console.log(`      ❌ CAMPO REQUERIDO VACÍO: ${campo}`);
                    }
                } else {
                    console.log(`   🔍 ${campo}: CAMPO NO ENCONTRADO EN DOM`);
                }
            });
            
            // 🎯 DEBUG ESPECÍFICO DEL APODERADO LEGAL
            console.log('🤵 APODERADO LEGAL DETALLADO:');
            const camposApoderado = [
                'apoderado_nombre', 'apoderado_rfc', 
                'poder_numero_escritura', 'poder_fecha_constitucion',
                'poder_notario_nombre', 'poder_entidad_federativa',
                'poder_notario_numero', 'poder_numero_registro'
            ];
            
            let apoderadoDatosEncontrados = 0;
            camposApoderado.forEach(campo => {
                const field = document.querySelector(`[name="${campo}"]`);
                if (field) {
                    const valor = field.value || 'VACÍO';
                    const requerido = field.required ? 'REQUERIDO' : 'OPCIONAL';
                    console.log(`   👨‍💼 ${campo}: "${valor}" (${requerido})`);
                    
                    if (field.value && field.value.trim()) {
                        apoderadoDatosEncontrados++;
                    }
                    
                    if (field.required && !field.value.trim()) {
                        console.log(`      ❌ CAMPO APODERADO REQUERIDO VACÍO: ${campo}`);
                    }
                } else {
                    console.log(`   👨‍💼 ${campo}: CAMPO NO ENCONTRADO EN DOM`);
                }
            });
            
            // Verificar si hay datos de apoderado en FormData
            const formData = new FormData(this.form);
            console.log('📤 APODERADO EN FORMDATA:');
            camposApoderado.forEach(campo => {
                const valor = formData.get(campo);
                if (valor) {
                    console.log(`   📤 ${campo}: "${valor}"`);
                }
            });
            
            console.log(`📊 RESUMEN APODERADO: ${apoderadoDatosEncontrados} campos con datos de ${camposApoderado.length} totales`);
            
            if (apoderadoDatosEncontrados === 0) {
                console.log('⚠️ NO SE ENCONTRARON DATOS DEL APODERADO LEGAL - Puede que no se esté enviando');
            } else if (apoderadoDatosEncontrados < 4) {
                console.log(`🟡 DATOS PARCIALES DEL APODERADO - Solo ${apoderadoDatosEncontrados} campos completados`);
            } else {
                console.log(`✅ DATOS COMPLETOS DEL APODERADO - ${apoderadoDatosEncontrados} campos completados`);
            }
        }
        
        async handleDraft() {
            console.log('💾 Guardando borrador');
            this.showInfoAlert('Funcionalidad de borrador en desarrollo', 'info');
        }
        
        showLoadingState() {
            if (this.submitBtn) {
                this.submitBtn.disabled = true;
                this.submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Procesando trámite...</span>
                `;
            }
            
            if (this.draftBtn) {
                this.draftBtn.disabled = true;
            }
        }
        
        showReadyState() {
            if (this.submitBtn) {
                this.submitBtn.disabled = false;
                this.submitBtn.innerHTML = `
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    <span class="text-sm sm:text-base">Enviar Trámite</span>
                `;
            }
            
            if (this.draftBtn) {
                this.draftBtn.disabled = false;
            }
        }
        
        async showSuccessAndRedirect(result) {
            await Swal.fire({
                title: '¡Éxito!',
                text: result.message || 'Trámite enviado correctamente',
                icon: 'success',
                confirmButtonText: 'Continuar',
                confirmButtonColor: '#10b981',
                timer: 3000,
                timerProgressBar: true,
                backdrop: true,
                allowOutsideClick: false
            });
            
            // Redirigir
            if (result.redirect) {
                window.location.href = result.redirect;
            }
        }
        
        showErrorAlert(message) {
            Swal.fire({
                title: 'Error',
                text: message,
                icon: 'error',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#ef4444'
            });
        }
        
        showInfoAlert(message, type = 'info') {
            Swal.fire({
                title: 'Información',
                text: message,
                icon: type,
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#3b82f6'
            });
        }

        /**
         * Maneja los errores de validación del servidor y los muestra en los campos
         */
        handleValidationErrors(errors) {
            console.log('🔍 Procesando errores de validación:', errors);
            
            Object.keys(errors).forEach(fieldName => {
                const errorMessages = Array.isArray(errors[fieldName]) ? errors[fieldName] : [errors[fieldName]];
                const firstError = errorMessages[0];
                
                console.log(`❌ Error en campo "${fieldName}": ${firstError}`);
                
                // Manejar casos especiales de nombres de campos
                this.showServerFieldError(fieldName, firstError);
            });
        }

        /**
         * Muestra errores de validación del servidor en campos específicos
         */
        showServerFieldError(fieldName, message) {
            // Mapear nombres de campos del servidor a nombres en el DOM
            const fieldMappings = {
                'accionistas.0.nombre': 'accionistas[0][nombre]',
                'accionistas.0.rfc': 'accionistas[0][rfc]',
                'accionistas.0.porcentaje': 'accionistas[0][porcentaje]',
                'accionistas.1.nombre': 'accionistas[1][nombre]',
                'accionistas.1.rfc': 'accionistas[1][rfc]',
                'accionistas.1.porcentaje': 'accionistas[1][porcentaje]',
                // Agregar más mapeos según sea necesario
            };
            
            const actualFieldName = fieldMappings[fieldName] || fieldName;
            let field = this.form.querySelector(`[name="${actualFieldName}"]`);
            
            // Si no se encuentra el campo exacto, intentar con selectores alternativos
            if (!field) {
                // Para campos de accionistas dinámicos
                if (fieldName.startsWith('accionistas.')) {
                    const match = fieldName.match(/accionistas\.(\d+)\.(\w+)/);
                    if (match) {
                        const index = match[1];
                        const subField = match[2];
                        field = this.form.querySelector(`[name="accionistas[${index}][${subField}]"]`);
                    }
                }
                
                // Para campos con nombres alternativos
                const alternativeSelectors = [
                    `[name="${fieldName}[]"]`,
                    `[id="${fieldName}"]`,
                    `[data-field="${fieldName}"]`
                ];
                
                for (const selector of alternativeSelectors) {
                    field = this.form.querySelector(selector);
                    if (field) break;
                }
            }
            
            if (field) {
                this.showFieldError(actualFieldName, message);
                console.log(`✅ Error mostrado en campo: ${actualFieldName}`);
                
                // Scroll al primer error si es visible
                if (Object.keys(this.validationErrors).length === 1) {
                    field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    field.focus();
                }
            } else {
                console.warn(`⚠️ Campo no encontrado para error: ${fieldName}`);
                // Mostrar error genérico si no se puede mapear al campo
                this.showGenericError(fieldName, message);
            }
        }

        /**
         * Muestra errores genéricos que no se pueden mapear a campos específicos
         */
        showGenericError(fieldName, message) {
            // Crear un contenedor de errores genéricos si no existe
            let errorContainer = document.getElementById('generic-errors-container');
            if (!errorContainer) {
                errorContainer = document.createElement('div');
                errorContainer.id = 'generic-errors-container';
                errorContainer.className = 'bg-red-50 border border-red-200 rounded-lg p-4 mb-6';
                
                const title = document.createElement('h3');
                title.className = 'text-red-800 font-medium mb-2';
                title.textContent = 'Errores de validación:';
                errorContainer.appendChild(title);
                
                const errorList = document.createElement('ul');
                errorList.className = 'text-red-700 text-sm space-y-1';
                errorList.id = 'generic-errors-list';
                errorContainer.appendChild(errorList);
                
                // Insertar al inicio del formulario
                this.form.insertBefore(errorContainer, this.form.firstChild);
            }
            
            const errorList = document.getElementById('generic-errors-list');
            const errorItem = document.createElement('li');
            errorItem.className = 'flex items-start space-x-2';
            errorItem.innerHTML = `
                <span class="text-red-500">•</span>
                <span><strong>${fieldName}:</strong> ${message}</span>
            `;
            errorList.appendChild(errorItem);
        }

        /**
         * Limpia todos los errores de campos
         */
        clearAllFieldErrors() {
            // Limpiar errores de campos específicos
            Object.keys(this.validationErrors).forEach(fieldName => {
                this.clearFieldError(fieldName);
            });
            
            // Limpiar errores genéricos
            const genericContainer = document.getElementById('generic-errors-container');
            if (genericContainer) {
                genericContainer.remove();
            }
            
            this.validationErrors = {};
        }
    }

    // Hacer disponible globalmente
    window.TramiteFormManager = TramiteFormManager;

} // Fin de la protección contra múltiples declaraciones

// Auto-inicializar cuando el DOM esté listo (solo una vez)
if (!window.tramiteFormManagerInitialized) {
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🎯 Inicializando sistema profesional de trámites');
        
        // Verificar que SweetAlert2 esté disponible
        if (typeof Swal === 'undefined') {
            console.error('❌ SweetAlert2 no está cargado');
            return;
        }
        
        // Verificar que no esté ya inicializado
        if (window.tramiteFormManager) {
            console.log('⚠️ TramiteFormManager ya está inicializado');
            return;
        }
        
        // Inicializar el manager
        try {
            window.tramiteFormManager = new TramiteFormManager('formulario-tramite');
            window.tramiteFormManagerInitialized = true;
            console.log('✅ Sistema profesional de trámites listo');
        } catch (error) {
            console.error('❌ Error al inicializar TramiteFormManager:', error);
        }
    });
} 