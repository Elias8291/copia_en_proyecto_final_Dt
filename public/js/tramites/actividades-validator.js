class ActividadesValidator {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.validateActividades();
    }

    bindEvents() {
        const actividadesContainer = document.getElementById('actividades-seleccionadas');
        if (actividadesContainer) {
            const observer = new MutationObserver(() => {
                this.validateActividades();
            });
            
            observer.observe(actividadesContainer, {
                childList: true,
                subtree: true,
                attributes: true
            });
        }
    }

    validateActividades() {
        const actividadesContainer = document.getElementById('actividades-seleccionadas');
        if (!actividadesContainer) {
            return false;
        }

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
        
        if (actividades.length === 0) {
            this.showActividadesMessage('Debe agregar al menos una actividad económica', false);
            return false;
        }

        this.showActividadesMessage(`${actividades.length} actividad(es) seleccionada(s)`, true);
        return true;
    }

    showActividadesMessage(message, isValid) {
        const actividadesSection = document.getElementById('actividades');
        if (!actividadesSection) return;

        let messageContainer = actividadesSection.querySelector('.actividades-message');
        
        if (!messageContainer) {
            messageContainer = document.createElement('div');
            messageContainer.className = 'actividades-message mt-4 p-3 rounded-lg';
            actividadesSection.appendChild(messageContainer);
        }

        if (!isValid) {
            messageContainer.className = 'actividades-message mt-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700';
            messageContainer.textContent = message;
        } else {
            messageContainer.className = 'actividades-message mt-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700';
            messageContainer.textContent = message;
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    window.actividadesValidator = new ActividadesValidator();
}); 