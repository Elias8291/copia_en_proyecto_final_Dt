@props([
    'steps' => [],
    'currentStep' => 0,
    'totalSteps' => 0
])

<div class="mb-6" data-steps-container>
    <!-- Progress Bar - Simple y elegante -->
    <div class="w-full bg-gray-200 rounded-full h-2 mb-6">
        <div class="bg-[#9d2449] h-2 rounded-full transition-all duration-500" 
             id="progress-bar-fill"
             style="width: {{ round((($currentStep + 1) / $totalSteps) * 100) }}%"></div>
    </div>

    <!-- Versión Móvil - Simple -->
    <div class="block sm:hidden mb-4">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-10 h-10 bg-[#9d2449] text-white rounded-full font-bold text-sm" id="mobile-step-number">
                {{ $currentStep + 1 }}/{{ $totalSteps }}
            </div>
        </div>
    </div>

    <!-- Steps Desktop - Simple y ordenado -->
    <div class="hidden sm:flex justify-between items-start mb-4">
        @foreach($steps as $index => $step)
            <div class="flex flex-col items-center flex-1 relative">
                <!-- Step Circle -->
                <div class="relative z-10">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300 border-2
                                {{ $index <= $currentStep 
                                    ? 'bg-[#9d2449] text-white border-[#9d2449]' 
                                    : 'bg-white text-gray-400 border-gray-300' }}"
                         data-step-circle>
                        @if($index < $currentStep)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @else
                            {{ $index + 1 }}
                        @endif
                    </div>
                </div>
                
                <!-- Connecting Line -->
                @if($index < count($steps) - 1)
                    <div class="absolute top-5 left-5 w-full h-0.5 bg-gray-300 transition-all duration-300
                                {{ $index < $currentStep ? 'bg-[#9d2449]' : '' }}"></div>
                @endif
                
                <!-- Step Label -->
                <div class="mt-2 text-center px-1">
                    <p class="text-xs font-medium {{ $index <= $currentStep ? 'text-[#9d2449]' : 'text-gray-500' }} transition-colors duration-300">
                        {{ $step['title'] }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
/**
 * Sistema de Navegación por Pasos
 */

// Variables globales
let currentStep = {{ $currentStep }};
const totalSteps = {{ $totalSteps }};
const steps = @json($steps);

/**
 * Navega entre pasos
 */
function navigateStep(direction) {
    console.log('navigateStep called with direction:', direction, 'currentStep:', currentStep, 'totalSteps:', totalSteps);
    
    // Verificar si hay una función interceptada y llamarla primero
    if (window.navigateStep !== navigateStep) {
        const result = window.navigateStep(direction);
        if (result === false) {
            console.log('Navigation blocked by validation');
            return false;
        }
    }
    
    // Si es retroceso, siempre permitir
    if (direction === 'prev' && currentStep > 0) {
        currentStep--;
    } else if (direction === 'next' && currentStep < totalSteps - 1) {
        currentStep++;
    } else {
        console.log('Navigation limits reached');
        return false;
    }
    
    console.log('New currentStep:', currentStep);
    
    updateStepDisplay();
    updateStepsComponent();
    updateNavigation();
    showFinalSubmitButton();
    
    return true;
}

/**
 * Actualiza la visualización del paso actual
 */
function updateStepDisplay() {
    console.log('updateStepDisplay - currentStep:', currentStep);
    
    document.querySelectorAll('.step-content').forEach(step => {
        step.classList.remove('active');
    });
    
    const currentStepElement = document.querySelector(`[data-step="${currentStep}"]`);
    if (currentStepElement) {
        currentStepElement.classList.add('active');
        currentStepElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
        
        // Disparar evento personalizado para notificar cambio de step
        window.dispatchEvent(new CustomEvent('stepChanged', { 
            detail: { currentStep, totalSteps } 
        }));
    }
}

/**
 * Actualiza el componente visual
 */
function updateStepsComponent() {
    console.log('updateStepsComponent - currentStep:', currentStep, 'totalSteps:', totalSteps);
    
    const stepsContainer = document.querySelector('[data-steps-container]');
    if (!stepsContainer) {
        console.warn('Steps container not found');
        return;
    }
    
    // Actualizar barra de progreso
    const progressBar = document.getElementById('progress-bar-fill');
    if (progressBar) {
        const progress = Math.round(((currentStep + 1) / totalSteps) * 100);
        console.log('Updating progress bar to:', progress + '%');
        progressBar.style.width = `${progress}%`;
    } else {
        console.warn('Progress bar element not found');
    }
    
    // Actualizar versión móvil
    const mobileStepNumber = document.getElementById('mobile-step-number');
    if (mobileStepNumber) {
        mobileStepNumber.textContent = `${currentStep + 1}/${totalSteps}`;
    }
    
    // Actualizar círculos
    const stepCircles = stepsContainer.querySelectorAll('[data-step-circle]');
    stepCircles.forEach((circle, index) => {
        if (index <= currentStep) {
            circle.classList.add('bg-[#9d2449]', 'text-white', 'border-[#9d2449]');
            circle.classList.remove('bg-white', 'text-gray-400', 'border-gray-300');
        } else {
            circle.classList.remove('bg-[#9d2449]', 'text-white', 'border-[#9d2449]');
            circle.classList.add('bg-white', 'text-gray-400', 'border-gray-300');
        }
    });
    
    // Actualizar líneas
    const connectingLines = stepsContainer.querySelectorAll('.absolute.top-5');
    connectingLines.forEach((line, index) => {
        if (index < currentStep) {
            line.classList.add('bg-[#9d2449]');
            line.classList.remove('bg-gray-300');
        } else {
            line.classList.remove('bg-[#9d2449]');
            line.classList.add('bg-gray-300');
        }
    });
}

/**
 * Actualiza la navegación
 */
function updateNavigation() {
    console.log('updateNavigation - currentStep:', currentStep);
    
    const prevBtn = document.querySelector('[onclick="navigateStep(\'prev\')"]');
    const nextBtn = document.querySelector('[onclick="navigateStep(\'next\')"]');
    
    if (prevBtn) {
        prevBtn.disabled = currentStep === 0;
        console.log('Previous button disabled:', currentStep === 0);
    }
    
    if (nextBtn) {
        nextBtn.disabled = currentStep === totalSteps - 1;
        console.log('Next button disabled:', currentStep === totalSteps - 1);
    }
}

/**
 * Muestra el botón de envío final
 */
function showFinalSubmitButton() {
    console.log('showFinalSubmitButton - currentStep:', currentStep, 'totalSteps:', totalSteps);
    
    const navigation = document.querySelector('[data-step-navigation]');
    if (!navigation) {
        console.warn('Navigation container not found');
        return;
    }
    
    if (currentStep === totalSteps - 1) {
        navigation.innerHTML = `
            <div class="bg-white border-t border-gray-200 mt-6 pt-4">
                <div class="flex justify-between items-center">
                    <button type="button" 
                            onclick="navigateStep('prev')" 
                            class="flex items-center px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Anterior
                    </button>

                    <button type="button" id="btn-enviar-tramite-final" 
                            class="flex items-center px-6 py-3 text-white bg-gray-400 rounded-lg transition-all duration-300 font-medium cursor-not-allowed"
                            disabled>
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Enviar Trámite
                    </button>
                </div>
            </div>
        `;
        
        const btnEnviar = document.getElementById('btn-enviar-tramite-final');
        if (btnEnviar) {
            btnEnviar.addEventListener('click', handleFormSubmit);
            // Validar términos y condiciones al mostrar el botón
            validarTerminosYCondicionesFinal();
        }
    } else {
        navigation.innerHTML = `
            <div class="bg-white border-t border-gray-200 mt-6 pt-4">
                <div class="flex justify-between items-center">
                    <button type="button" 
                            onclick="navigateStep('prev')" 
                            class="flex items-center px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-300 disabled:opacity-40 disabled:cursor-not-allowed"
                            ${currentStep === 0 ? 'disabled' : ''}>
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Anterior
                    </button>

                    <div class="text-center">
                        <div class="text-sm font-medium text-gray-700">
                            Paso ${currentStep + 1} de ${totalSteps}
                        </div>
                    </div>

                    <button type="button" 
                            onclick="navigateStep('next')" 
                            class="flex items-center px-4 py-2 text-white bg-[#9d2449] rounded-lg hover:bg-[#8a1f40] transition-all duration-300">
                        Siguiente
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;
    }
}

/**
 * Valida términos y condiciones para el botón final
 */
function validarTerminosYCondicionesFinal() {
    const checkboxTerminos = document.getElementById('acepto_terminos');
    const btnEnviar = document.getElementById('btn-enviar-tramite-final');
    
    if (checkboxTerminos && btnEnviar) {
        if (checkboxTerminos.checked) {
            // Habilitar botón
            btnEnviar.disabled = false;
            btnEnviar.classList.remove('bg-gray-400', 'cursor-not-allowed');
            btnEnviar.classList.add('bg-[#9d2449]', 'hover:bg-[#8a1f40]');
        } else {
            // Deshabilitar botón
            btnEnviar.disabled = true;
            btnEnviar.classList.remove('bg-[#9d2449]', 'hover:bg-[#8a1f40]');
            btnEnviar.classList.add('bg-gray-400', 'cursor-not-allowed');
        }
    }
}

/**
 * Maneja el envío del formulario
 */
function handleFormSubmit(e) {
    e.preventDefault();
    
    if (typeof showConfirmModal === 'function') {
        showConfirmModal(
            'Confirmar envío',
            '¿Está seguro que desea enviar el trámite? Esta acción no se puede deshacer.',
            'tramite-form',
            function() {
                const form = document.getElementById('tramite-form');
                if (form) {
                    form.submit();
                }
            }
        );
    } else {
        if (confirm('¿Está seguro que desea enviar el trámite? Esta acción no se puede deshacer.')) {
            const form = document.getElementById('tramite-form');
            if (form) {
                form.submit();
            }
        }
    }
}

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    console.log('Steps component initialized - currentStep:', currentStep, 'totalSteps:', totalSteps);
    
    // Verificar que el contenedor de navegación existe
    const navigationContainer = document.querySelector('[data-step-navigation]');
    if (navigationContainer) {
        console.log('Navigation container found, generating buttons...');
    } else {
        console.error('Navigation container not found!');
    }
    
    updateStepDisplay();
    updateStepsComponent();
    updateNavigation();
    showFinalSubmitButton();
});

// Exportar funciones
window.navigateStep = navigateStep;
window.updateStepDisplay = updateStepDisplay;
window.updateStepsComponent = updateStepsComponent;
window.updateNavigation = updateNavigation;
window.showFinalSubmitButton = showFinalSubmitButton;
window.handleFormSubmit = handleFormSubmit;
</script> 