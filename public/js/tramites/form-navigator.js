/**
 * Form Navigator - Sistema de navegación por secciones
 * Maneja la navegación entre secciones del formulario con botones "Siguiente" y "Anterior"
 */
class FormNavigator {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 0;
        this.sections = [];

        this.init();
    }

    init() {
        // ELIMINAR TODOS LOS REQUIRED DINÁMICAMENTE
        this.removeAllRequired();

        // Obtener todas las secciones del formulario
        this.sections = document.querySelectorAll('.form-section');
        this.totalSteps = this.sections.length;

        // Configurar los botones de navegación
        this.setupNavigationButtons();

        // Mostrar la primera sección
        this.showStep(1);

        // Actualizar progreso inicial
        this.updateProgress();

        console.log(`Form Navigator inicializado: ${this.totalSteps} secciones`);
    }

    // Eliminar todos los atributos required
    removeAllRequired() {
        const allRequiredFields = document.querySelectorAll('input[required], select[required], textarea[required]');
        allRequiredFields.forEach(field => {
            field.removeAttribute('required');
            console.log('Removido required de:', field.name);
        });
    }

    setupNavigationButtons() {
        // Los botones serán manejados por el form-validator
        // NO agregar event listeners aquí para evitar conflictos
    }

    showStep(step) {
        // Ocultar todas las secciones
        this.sections.forEach(section => {
            section.classList.add('hidden');
        });

        // Mostrar la sección actual
        const currentSection = document.querySelector(`[data-step="${step}"]`);
        if (currentSection) {
            currentSection.classList.remove('hidden');

            // Scroll suave a la sección
            setTimeout(() => {
                currentSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }, 100);
        }

        this.currentStep = step;
        this.updateButtons();
        this.updateProgress();
    }

    nextStep() {
        // Solo navegar sin validar (la validación se hace en form-validator)
        if (this.currentStep < this.totalSteps) {
            this.showStep(this.currentStep + 1);
        }
    }

    previousStep() {
        // Solo navegar sin validar (la validación se hace en form-validator)
        if (this.currentStep > 1) {
            this.showStep(this.currentStep - 1);
        }
    }

    updateButtons() {
        const btnSiguiente = document.getElementById('btn-siguiente');
        const btnAnterior = document.getElementById('btn-anterior');
        const btnEnviar = document.getElementById('btn-enviar');

        // Mostrar/ocultar botón anterior
        if (btnAnterior) {
            btnAnterior.classList.toggle('hidden', this.currentStep <= 1);
        }

        // Mostrar/ocultar botón siguiente y enviar
        if (btnSiguiente && btnEnviar) {
            if (this.currentStep < this.totalSteps) {
                btnSiguiente.classList.remove('hidden');
                btnEnviar.classList.add('hidden');
            } else {
                btnSiguiente.classList.add('hidden');
                btnEnviar.classList.remove('hidden');
            }
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

    // Método para ir a una sección específica (útil para errores)
    goToStep(step) {
        if (step >= 1 && step <= this.totalSteps) {
            this.showStep(step);
        }
    }

    // Método para obtener el paso actual
    getCurrentStep() {
        return this.currentStep;
    }

    // Método para obtener el total de pasos
    getTotalSteps() {
        return this.totalSteps;
    }

    // Método para manejar errores de Laravel
    handleLaravelErrors() {
        const firstErrorField = document.querySelector('.field-error');
        if (firstErrorField) {
            const section = firstErrorField.closest('.form-section');
            if (section) {
                const step = section.getAttribute('data-step');
                if (step) {
                    this.goToStep(parseInt(step));
                }
            }
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    // Inicializar el navegador del formulario
    window.formNavigator = new FormNavigator();

    // Manejar errores de Laravel si existen
    setTimeout(() => {
        if (window.formNavigator) {
            window.formNavigator.handleLaravelErrors();
        }
    }, 500);

    console.log('Form Navigator cargado - NO interfiere con el envío del formulario');
}); 