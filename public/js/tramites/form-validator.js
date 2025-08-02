class FormValidator {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = this.getTotalSteps();
        this.campos = {};
        this.expresiones = this.getExpresiones();
        this.init();
    }

    init() {
        this.bindEvents();
        this.updateProgress();
        this.inicializarCampos();
    }

    getTotalSteps() {
        const sections = document.querySelectorAll('.form-section');
        return sections.length;
    }

    getExpresiones() {
        return {
            rfc: /^[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{3}$/,
            razon_social: /^[a-zA-ZÀ-ÿ\s]{3,100}$/,
            cargo: /^[a-zA-ZÀ-ÿ\s]{3,50}$/,
            email_contacto: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/,
            telefono: /^\d{10}$/,
            calle: /^[a-zA-ZÀ-ÿ\s\d]{3,100}$/,
            entre_calles: /^[a-zA-ZÀ-ÿ\s\d]{3,100}$/,
            numero_exterior: /^[a-zA-Z0-9\s]{1,10}$/,
            asentamiento: /^[a-zA-ZÀ-ÿ\s]{3,100}$/,
            municipio: /^[a-zA-ZÀ-ÿ\s]{3,100}$/,
            estado_id: /^\d+$/,
            codigo_postal: /^\d{5}$/,
            fecha_constitucion: /^\d{4}-\d{2}-\d{2}$/,
            numero_escritura: /^[a-zA-Z0-9\s]{1,20}$/,
            notario_nombre: /^[a-zA-ZÀ-ÿ\s]{3,100}$/,
            notario_numero: /^\d{1,10}$/,
            apoderado_nombre: /^[a-zA-ZÀ-ÿ\s]{3,100}$/,
            apoderado_rfc: /^[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{3}$/,
            poder_numero_escritura: /^[a-zA-Z0-9\s]{1,20}$/,
            poder_fecha_constitucion: /^\d{4}-\d{2}-\d{2}$/,
            poder_notario_nombre: /^[a-zA-ZÀ-ÿ\s]{3,100}$/,
            poder_notario_numero: /^\d{1,10}$/
        };
    }

    inicializarCampos() {
        const camposRequeridos = [
            'rfc', 'razon_social', 'cargo', 'email_contacto', 'telefono',
            'calle', 'entre_calles', 'numero_exterior', 'asentamiento', 'municipio', 'estado_id', 'codigo_postal',
            'fecha_constitucion', 'numero_escritura', 'notario_nombre', 'notario_numero',
            'apoderado_nombre', 'apoderado_rfc', 'poder_numero_escritura', 
            'poder_fecha_constitucion', 'poder_notario_nombre', 'poder_notario_numero'
        ];

        camposRequeridos.forEach(campo => {
            this.campos[campo] = false;
        });
    }

    bindEvents() {
        const btnSiguiente = document.getElementById('btn-siguiente');
        const btnAnterior = document.getElementById('btn-anterior');
        const btnEnviar = document.getElementById('btn-enviar');

        if (btnSiguiente) {
            btnSiguiente.addEventListener('click', () => this.nextStep());
        }

        if (btnAnterior) {
            btnAnterior.addEventListener('click', () => this.previousStep());
        }

        if (btnEnviar) {
            btnEnviar.addEventListener('click', (e) => this.validateFinalStep(e));
        }

        this.setupRealTimeValidation();
    }

    setupRealTimeValidation() {
        const inputs = document.querySelectorAll('.form-section input, .form-section select, .form-section textarea');
        inputs.forEach(input => {
            input.addEventListener('input', () => this.validarFormulario(input));
            input.addEventListener('blur', () => this.validarFormulario(input));
        });
    }

    validarFormulario(input) {
        const campo = input.name;
        if (!campo || !this.expresiones[campo]) return;

        const expresion = this.expresiones[campo];
        this.validarCampo(expresion, input, campo);
        this.validateCurrentSection();
    }

    validarCampo(expresion, input, campo) {
        const valor = input.value.trim();
        const esValido = valor !== '' && expresion.test(valor);
        
        this.campos[campo] = esValido;
        this.showFieldMessage(input, esValido, this.getErrorMessage(campo, valor));
    }

    getErrorMessage(campo, valor) {
        if (valor === '') {
            return `El campo ${this.getFieldLabel(campo)} es obligatorio`;
        }

        const mensajes = {
            rfc: 'RFC inválido. Formato: XXXX000000XXX',
            razon_social: 'Razón social debe tener entre 3 y 100 caracteres',
            cargo: 'Cargo debe tener entre 3 y 50 caracteres',
            email_contacto: 'Correo electrónico inválido',
            telefono: 'Teléfono debe tener 10 dígitos',
            calle: 'Calle debe tener entre 3 y 100 caracteres',
            entre_calles: 'Entre calles debe tener entre 3 y 100 caracteres',
            numero_exterior: 'Número exterior inválido',
            asentamiento: 'Asentamiento debe tener entre 3 y 100 caracteres',
            municipio: 'Municipio debe tener entre 3 y 100 caracteres',
            estado_id: 'Debe seleccionar un estado',
            codigo_postal: 'Código postal debe tener 5 dígitos',
            fecha_constitucion: 'Fecha de constitución inválida',
            numero_escritura: 'Número de escritura inválido',
            notario_nombre: 'Nombre del notario debe tener entre 3 y 100 caracteres',
            notario_numero: 'Número del notario inválido',
            apoderado_nombre: 'Nombre del apoderado debe tener entre 3 y 100 caracteres',
            apoderado_rfc: 'RFC del apoderado inválido',
            poder_numero_escritura: 'Número de escritura del poder inválido',
            poder_fecha_constitucion: 'Fecha de constitución del poder inválida',
            poder_notario_nombre: 'Nombre del notario del poder debe tener entre 3 y 100 caracteres',
            poder_notario_numero: 'Número del notario del poder inválido'
        };

        return mensajes[campo] || `Formato inválido para ${this.getFieldLabel(campo)}`;
    }

    validateCurrentSection() {
        const currentSection = document.querySelector(`[data-step="${this.currentStep}"]`);
        if (!currentSection) return true;

        const sectionId = currentSection.id;
        let isValid = true;
        let errorMessage = '';

        switch (sectionId) {
            case 'datos-generales':
                isValid = this.validateDatosGenerales();
                if (!isValid) errorMessage = 'Complete todos los campos obligatorios en Datos Generales';
                break;
            case 'actividades':
                isValid = this.validateActividades();
                if (!isValid) errorMessage = 'Debe agregar al menos una actividad económica';
                break;
            case 'domicilio':
                isValid = this.validateDomicilio();
                if (!isValid) errorMessage = 'Complete todos los campos obligatorios en Domicilio';
                break;
            case 'constitucion':
                isValid = this.validateConstitucion();
                if (!isValid) errorMessage = 'Complete todos los campos obligatorios en Constitución';
                break;
            case 'apoderado':
                isValid = this.validateApoderado();
                if (!isValid) errorMessage = 'Complete todos los campos obligatorios en Apoderado Legal';
                break;
            case 'accionistas':
                isValid = this.validateAccionistas();
                if (!isValid) errorMessage = 'Debe agregar al menos un accionista y la suma de porcentajes debe ser 100%';
                break;
            case 'documentos':
                isValid = this.validateDocumentos();
                if (!isValid) errorMessage = 'Todos los documentos deben estar aprobados';
                break;
        }

        this.showSectionMessage(currentSection, isValid, errorMessage);
        return isValid;
    }

    validateDatosGenerales() {
        const requiredFields = ['rfc', 'razon_social', 'cargo', 'email_contacto', 'telefono'];
        return requiredFields.every(field => this.campos[field] === true);
    }

    validateActividades() {
        if (window.actividadesValidator) {
            return window.actividadesValidator.validateActividades();
        }

        const actividadesContainer = document.querySelector('#actividades-seleccionadas');
        if (!actividadesContainer) return false;
        
        let actividades = actividadesContainer.querySelectorAll('.flex.items-center.justify-between.bg-slate-50.border.border-slate-200.rounded-lg.px-4.py-3');
        
        if (actividades.length === 0) {
            actividades = actividadesContainer.querySelectorAll('[class*="bg-slate-50"][class*="border"]');
        }
        
        if (actividades.length === 0) {
            const todosLosDivs = actividadesContainer.querySelectorAll('div');
            actividades = Array.from(todosLosDivs).filter(div => {
                const clases = div.className;
                return clases.includes('bg-slate-50') || clases.includes('border') || clases.includes('rounded');
            });
        }
        
        return actividades.length > 0;
    }

    validateDomicilio() {
        const requiredFields = ['calle', 'entre_calles', 'numero_exterior', 'asentamiento', 'municipio', 'estado_id', 'codigo_postal'];
        return requiredFields.every(field => this.campos[field] === true);
    }

    validateConstitucion() {
        const requiredFields = ['fecha_constitucion', 'numero_escritura', 'notario_nombre', 'notario_numero'];
        return requiredFields.every(field => this.campos[field] === true);
    }

    validateApoderado() {
        const requiredFields = ['apoderado_nombre', 'apoderado_rfc', 'poder_numero_escritura', 'poder_fecha_constitucion', 'poder_notario_nombre', 'poder_notario_numero'];
        return requiredFields.every(field => this.campos[field] === true);
    }

    validateAccionistas() {
        if (window.accionistasValidator) {
            return window.accionistasValidator.validateAccionistas();
        }

        const accionistasContainer = document.querySelector('#accionistas-container');
        if (!accionistasContainer) return false;
        
        const accionistas = accionistasContainer.querySelectorAll('.accionista-item');
        if (accionistas.length === 0) return false;

        let totalPorcentaje = 0;
        accionistas.forEach(accionista => {
            const porcentajeInput = accionista.querySelector('[name*="porcentaje"]');
            if (porcentajeInput) {
                totalPorcentaje += parseFloat(porcentajeInput.value) || 0;
            }
        });

        return totalPorcentaje === 100;
    }

    validateDocumentos() {
        const documentos = document.querySelectorAll('input[type="file"][name*="documentos"]');
        const documentosSubidos = Array.from(documentos).filter(doc => doc.files.length > 0);
        
        if (documentos.length === 0) {
            return true; // No hay documentos para validar
        }
        
        // Validar que todos los documentos subidos sean del tipo correcto
        let documentosValidos = 0;
        documentosSubidos.forEach(doc => {
            const file = doc.files[0];
            const documentoId = doc.id.replace('file_', '');
            
            // Usar la función de validación de tipo de archivo si está disponible
            if (window.documentosValidator && window.documentosValidator.getTipoArchivo) {
                const expectedType = window.documentosValidator.getTipoArchivo(documentoId);
                if (expectedType && window.documentosValidator.isValidFileType(file, expectedType)) {
                    documentosValidos++;
                }
            } else {
                // Fallback: solo contar como válido si tiene archivo
                documentosValidos++;
            }
        });
        
        const todosValidos = documentosValidos === documentosSubidos.length;
        
        if (!todosValidos) {
            // Mostrar mensaje de error específico
            const documentosSection = document.querySelector('#documentos');
            if (documentosSection) {
                let messageContainer = documentosSection.querySelector('.documentos-message');
                
                if (!messageContainer) {
                    messageContainer = document.createElement('div');
                    messageContainer.className = 'documentos-message mt-4 p-3 rounded-lg';
                    documentosSection.appendChild(messageContainer);
                }
                
                messageContainer.className = 'documentos-message mt-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700';
                messageContainer.textContent = `Debe subir todos los archivos con el tipo correcto (${documentosValidos}/${documentosSubidos.length} válidos)`;
            }
        } else {
            // Limpiar mensaje de error si todos están válidos
            const documentosSection = document.querySelector('#documentos');
            if (documentosSection) {
                const messageContainer = documentosSection.querySelector('.documentos-message');
                if (messageContainer) {
                    messageContainer.remove();
                }
            }
        }
        
        return todosValidos;
    }

    showSectionMessage(section, isValid, errorMessage) {
        let messageContainer = section.querySelector('.section-message');
        
        if (!messageContainer) {
            messageContainer = document.createElement('div');
            messageContainer.className = 'section-message mt-4 p-3 rounded-lg';
            section.appendChild(messageContainer);
        }

        if (!isValid) {
            messageContainer.className = 'section-message mt-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700';
            messageContainer.textContent = errorMessage;
        } else {
            messageContainer.className = 'section-message mt-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700';
            messageContainer.textContent = 'Sección completada correctamente';
        }
    }

    showFieldMessage(input, isValid, errorMessage) {
        let fieldContainer = input.closest('.form-group') || input.closest('.field-container');
        if (!fieldContainer) {
            fieldContainer = input.parentElement;
        }

        let messageContainer = fieldContainer.querySelector('.field-message');
        
        if (!messageContainer) {
            messageContainer = document.createElement('div');
            messageContainer.className = 'field-message mt-1';
            fieldContainer.appendChild(messageContainer);
        }

        messageContainer.innerHTML = '';

        if (!isValid) {
            messageContainer.className = 'field-message mt-1 flex items-center text-red-600';
            messageContainer.innerHTML = `
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm font-medium">${errorMessage}</span>
            `;
            
            input.classList.add('border-red-500');
            input.classList.remove('border-gray-200', 'border-gray-300');
        } else {
            messageContainer.className = 'field-message mt-1 flex items-center text-green-600';
            messageContainer.innerHTML = `
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm font-medium">Campo válido</span>
            `;
            
            input.classList.remove('border-red-500');
            input.classList.add('border-gray-200', 'border-gray-300');
        }
    }

    getFieldLabel(fieldName) {
        const labels = {
            'rfc': 'RFC',
            'razon_social': 'Razón Social',
            'cargo': 'Cargo',
            'email_contacto': 'Correo Electrónico',
            'telefono': 'Teléfono',
            'calle': 'Calle',
            'entre_calles': 'Entre Calles',
            'numero_exterior': 'Número Exterior',
            'asentamiento': 'Asentamiento/Colonia',
            'municipio': 'Municipio',
            'estado_id': 'Estado',
            'codigo_postal': 'Código Postal',
            'fecha_constitucion': 'Fecha de Constitución',
            'numero_escritura': 'Número de Escritura',
            'notario_nombre': 'Nombre del Notario',
            'notario_numero': 'Número del Notario',
            'apoderado_nombre': 'Nombre del Apoderado',
            'apoderado_rfc': 'RFC del Apoderado',
            'poder_numero_escritura': 'Número de Escritura del Poder',
            'poder_fecha_constitucion': 'Fecha de Constitución del Poder',
            'poder_notario_nombre': 'Nombre del Notario del Poder',
            'poder_notario_numero': 'Número del Notario del Poder'
        };

        return labels[fieldName] || fieldName;
    }

    nextStep() {
        const currentSection = document.querySelector(`[data-step="${this.currentStep}"]`);
        if (!currentSection) return;

        // Validar todos los campos de la sección actual
        const inputs = currentSection.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (input.name && this.expresiones[input.name]) {
                this.validarFormulario(input);
            }
        });

        if (!this.validateCurrentSection()) {
            return;
        }

        const nextSection = document.querySelector(`[data-step="${this.currentStep + 1}"]`);
        if (currentSection && nextSection) {
            currentSection.classList.add('hidden');
            nextSection.classList.remove('hidden');
            this.currentStep++;
            this.updateProgress();
            this.updateButtons();
        }
    }

    previousStep() {
        const currentSection = document.querySelector(`[data-step="${this.currentStep}"]`);
        const previousSection = document.querySelector(`[data-step="${this.currentStep - 1}"]`);

        if (currentSection && previousSection) {
            currentSection.classList.add('hidden');
            previousSection.classList.remove('hidden');
            this.currentStep--;
            this.updateProgress();
            this.updateButtons();
        }
    }

    updateProgress() {
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');
        
        if (progressBar && progressText) {
            const percentage = (this.currentStep / this.totalSteps) * 100;
            progressBar.style.width = `${percentage}%`;
            progressText.textContent = `Paso ${this.currentStep} de ${this.totalSteps}`;
        }
    }

    updateButtons() {
        const btnAnterior = document.getElementById('btn-anterior');
        const btnSiguiente = document.getElementById('btn-siguiente');
        const btnEnviar = document.getElementById('btn-enviar');

        if (btnAnterior) {
            btnAnterior.classList.toggle('hidden', this.currentStep === 1);
        }

        if (btnSiguiente) {
            btnSiguiente.classList.toggle('hidden', this.currentStep === this.totalSteps);
        }

        if (btnEnviar) {
            btnEnviar.classList.toggle('hidden', this.currentStep !== this.totalSteps);
        }
    }

    validateFinalStep(e) {
        let allValid = true;

        for (let step = 1; step <= this.totalSteps; step++) {
            const section = document.querySelector(`[data-step="${step}"]`);
            if (!section) continue;

            const inputs = section.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.name && this.expresiones[input.name]) {
                    this.validarFormulario(input);
                }
            });

            this.currentStep = step;
            if (!this.validateCurrentSection()) {
                allValid = false;
            }
        }

        if (!allValid) {
            e.preventDefault();
            return false;
        }

        return true;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    window.formValidator = new FormValidator();
}); 