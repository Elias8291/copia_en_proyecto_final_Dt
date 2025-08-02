class AccionistasValidator {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.validateAccionistas();
    }

    bindEvents() {
        // Observar cambios en el contenedor de accionistas
        const accionistasContainer = document.getElementById('accionistas-container');
        if (accionistasContainer) {
            const observer = new MutationObserver(() => {
                this.validateAccionistas();
            });
            
            observer.observe(accionistasContainer, {
                childList: true,
                subtree: true,
                attributes: true
            });
        }

        // Validar cuando se cambien los porcentajes
        document.addEventListener('input', (e) => {
            if (e.target.name && e.target.name.includes('porcentaje')) {
                this.validateAccionistas();
            }
        });
    }

    validateAccionistas() {
        const accionistasContainer = document.getElementById('accionistas-container');
        if (!accionistasContainer) return false;

        const accionistas = accionistasContainer.querySelectorAll('.accionista-item');
        
        // Verificar que haya al menos un accionista
        if (accionistas.length === 0) {
            this.showAccionistasMessage('Debe agregar al menos un accionista', false);
            return false;
        }

        // Calcular suma de porcentajes
        let totalPorcentaje = 0;
        accionistas.forEach(accionista => {
            const porcentajeInput = accionista.querySelector('[name*="porcentaje"]');
            if (porcentajeInput) {
                totalPorcentaje += parseFloat(porcentajeInput.value) || 0;
            }
        });

        // Verificar que la suma sea 100%
        if (totalPorcentaje !== 100) {
            this.showAccionistasMessage(`La suma de porcentajes debe ser 100%. Actual: ${totalPorcentaje}%`, false);
            return false;
        }

        this.showAccionistasMessage('Accionistas validados correctamente', true);
        return true;
    }

    showAccionistasMessage(message, isValid) {
        const accionistasSection = document.getElementById('accionistas');
        if (!accionistasSection) return;

        let messageContainer = accionistasSection.querySelector('.accionistas-message');
        
        if (!messageContainer) {
            messageContainer = document.createElement('div');
            messageContainer.className = 'accionistas-message mt-4 p-3 rounded-lg';
            accionistasSection.appendChild(messageContainer);
        }

        if (!isValid) {
            messageContainer.className = 'accionistas-message mt-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700';
            messageContainer.textContent = message;
        } else {
            messageContainer.className = 'accionistas-message mt-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700';
            messageContainer.textContent = message;
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.accionistasValidator = new AccionistasValidator();
}); 