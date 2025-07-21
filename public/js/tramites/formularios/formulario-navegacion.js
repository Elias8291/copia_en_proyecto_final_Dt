/**
 * Navegación y control de pasos del formulario de trámites
 */

// Evitar redeclaración
if (typeof FormularioNavegacion === 'undefined') {
    class FormularioNavegacion {
    constructor(totalSteps, stepNames) {
        this.currentStep = 1;
        this.totalSteps = totalSteps;
        this.stepNames = stepNames;
        
        this.btnAnterior = document.getElementById('btn-anterior');
        this.btnSiguiente = document.getElementById('btn-siguiente');
        this.btnEnviar = document.getElementById('btn-enviar');
        this.progressBar = document.getElementById('progress-bar');
        this.mobileCurrentStep = document.getElementById('mobile-current-step');
        this.mobileStepText = document.getElementById('mobile-step-text');
        this.mobileStepName = document.getElementById('mobile-step-name');
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.updateStep();
    }
    
    setupEventListeners() {
        if (this.btnSiguiente) {
            this.btnSiguiente.addEventListener('click', () => this.nextStep());
        }
        
        if (this.btnAnterior) {
            this.btnAnterior.addEventListener('click', () => this.prevStep());
        }
    }
    
    nextStep() {
        if (this.currentStep < this.totalSteps) {
            this.currentStep++;
            this.updateStep();
        }
    }
    
    prevStep() {
        if (this.currentStep > 1) {
            this.currentStep--;
            this.updateStep();
        }
    }
    
    updateStep() {
        this.hideAllSteps();
        this.showCurrentStep();
        this.updateProgressIndicators();
        this.updateButtons();
        this.updateProgressBar();
        this.updateMobileIndicators();
    }
    
    hideAllSteps() {
        document.querySelectorAll('.step-section').forEach(section => {
            section.classList.add('hidden');
        });
    }
    
    showCurrentStep() {
        const currentSection = document.querySelector(`[data-step="${this.currentStep}"]`);
        if (currentSection) {
            currentSection.classList.remove('hidden');
            currentSection.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        }
    }
    
    updateProgressIndicators() {
        document.querySelectorAll('[data-step-indicator]').forEach((indicator, index) => {
            const stepNum = index + 1;
            const circle = indicator.querySelector('div');
            const text = indicator.querySelector('span');
            
            if (stepNum <= this.currentStep) {
                circle.className = 'flex items-center justify-center w-8 h-8 border-2 border-[#9D2449] rounded-full bg-[#9D2449] text-white text-xs font-bold shadow-sm transition-all duration-300';
                if (text) text.className = 'ml-2 font-medium text-[#9D2449] whitespace-nowrap';
            } else {
                circle.className = 'flex items-center justify-center w-8 h-8 border-2 border-slate-300 rounded-full text-xs transition-all duration-300';
                if (text) text.className = 'ml-2 font-medium text-slate-400 whitespace-nowrap';
            }
        });
    }
    
    updateButtons() {
        // Botón anterior
        if (this.btnAnterior) {
            this.btnAnterior.disabled = this.currentStep === 1;
        }
        
        // Botones siguiente/enviar
        if (this.currentStep === this.totalSteps) {
            if (this.btnSiguiente) this.btnSiguiente.classList.add('hidden');
            if (this.btnEnviar) this.btnEnviar.classList.remove('hidden');
        } else {
            if (this.btnSiguiente) this.btnSiguiente.classList.remove('hidden');
            if (this.btnEnviar) this.btnEnviar.classList.add('hidden');
        }
    }
    
    updateProgressBar() {
        if (this.progressBar) {
            const progress = (this.currentStep / this.totalSteps) * 100;
            this.progressBar.style.width = progress + '%';
        }
    }
    
    updateMobileIndicators() {
        if (this.mobileCurrentStep) {
            this.mobileCurrentStep.textContent = this.currentStep;
        }
        
        if (this.mobileStepText) {
            this.mobileStepText.textContent = `de ${this.totalSteps}`;
        }
        
        if (this.mobileStepName && this.stepNames[this.currentStep]) {
            this.mobileStepName.textContent = this.stepNames[this.currentStep];
        }
    }
    
    // Método público para ir a un paso específico
    goToStep(stepNumber) {
        if (stepNumber >= 1 && stepNumber <= this.totalSteps) {
            this.currentStep = stepNumber;
            this.updateStep();
        }
    }
    
    // Método público para obtener el paso actual
    getCurrentStep() {
        return this.currentStep;
    }
    
    // Validar paso actual antes de continuar
    validateCurrentStep() {
        const currentSection = document.querySelector(`[data-step="${this.currentStep}"]`);
        if (!currentSection) return true;
        
        const requiredFields = currentSection.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                isValid = false;
            } else {
                field.classList.remove('border-red-500');
            }
        });
        
        return isValid;
    }
}

    // Hacer disponible globalmente
    window.FormularioNavegacion = FormularioNavegacion;
}