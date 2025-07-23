@extends('layouts.app')

@section('title', $titulo ?? 'Formulario de Trámite')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900 flex items-center justify-center p-4">
    <!-- Elementos decorativos de fondo -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl animate-float"></div>
        <div class="absolute top-3/4 right-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-3/4 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl animate-float" style="animation-delay: 4s;"></div>
    </div>

    <div class="w-full max-w-2xl mx-auto relative">
        <!-- Contenedor principal del formulario -->
        <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8 shadow-2xl border border-white/20">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white mb-2 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                    Formulario Elegante
                </h1>
                <p class="text-white/70 text-lg">Complete la información paso a paso</p>
            </div>

            <!-- Indicador de progreso -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex space-x-4">
                        <div id="step-1" class="flex items-center space-x-2 step-indicator active">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold transition-all duration-300">1</div>
                            <span class="text-white font-medium hidden sm:block">Personal</span>
                        </div>
                        <div class="flex-1 h-1 bg-white/20 rounded-full mx-4 mt-5">
                            <div id="progress-1" class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full transition-all duration-500 w-0"></div>
                        </div>
                        <div id="step-2" class="flex items-center space-x-2 step-indicator">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white font-bold transition-all duration-300">2</div>
                            <span class="text-white/50 font-medium hidden sm:block">Contacto</span>
                        </div>
                        <div class="flex-1 h-1 bg-white/20 rounded-full mx-4 mt-5">
                            <div id="progress-2" class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full transition-all duration-500 w-0"></div>
                        </div>
                        <div id="step-3" class="flex items-center space-x-2 step-indicator">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white font-bold transition-all duration-300">3</div>
                            <span class="text-white/50 font-medium hidden sm:block">Preferencias</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <form id="multiStepForm" class="space-y-6">
                <!-- Paso 1: Información Personal -->
                <div id="form-step-1" class="form-step animate-fade-in">
                    <h2 class="text-2xl font-semibold text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Información Personal
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="block text-white/80 text-sm font-medium mb-2">Nombre</label>
                            <input type="text" name="firstName" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300" placeholder="Tu nombre">
                        </div>
                        <div class="form-group">
                            <label class="block text-white/80 text-sm font-medium mb-2">Apellido</label>
                            <input type="text" name="lastName" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300" placeholder="Tu apellido">
                        </div>
                        <div class="form-group md:col-span-2">
                            <label class="block text-white/80 text-sm font-medium mb-2">Fecha de Nacimiento</label>
                            <input type="date" name="birthDate" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                        </div>
                        <div class="form-group md:col-span-2">
                            <label class="block text-white/80 text-sm font-medium mb-2">Género</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="gender" value="masculino" class="text-purple-500 focus:ring-purple-500">
                                    <span class="text-white/80">Masculino</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="gender" value="femenino" class="text-purple-500 focus:ring-purple-500">
                                    <span class="text-white/80">Femenino</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="gender" value="otro" class="text-purple-500 focus:ring-purple-500">
                                    <span class="text-white/80">Otro</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paso 2: Información de Contacto -->
                <div id="form-step-2" class="form-step hidden">
                    <h2 class="text-2xl font-semibold text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Información de Contacto
                    </h2>
                    <div class="space-y-6">
                        <div class="form-group">
                            <label class="block text-white/80 text-sm font-medium mb-2">Email</label>
                            <input type="email" name="email" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300" placeholder="tu@email.com">
                        </div>
                        <div class="form-group">
                            <label class="block text-white/80 text-sm font-medium mb-2">Teléfono</label>
                            <input type="tel" name="phone" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300" placeholder="+1 (555) 123-4567">
                        </div>
                        <div class="form-group">
                            <label class="block text-white/80 text-sm font-medium mb-2">Dirección</label>
                            <input type="text" name="address" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300" placeholder="Tu dirección completa">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label class="block text-white/80 text-sm font-medium mb-2">Ciudad</label>
                                <input type="text" name="city" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300" placeholder="Tu ciudad">
                            </div>
                            <div class="form-group">
                                <label class="block text-white/80 text-sm font-medium mb-2">Código Postal</label>
                                <input type="text" name="zipCode" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300" placeholder="12345">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paso 3: Preferencias -->
                <div id="form-step-3" class="form-step hidden">
                    <h2 class="text-2xl font-semibold text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Preferencias
                    </h2>
                    <div class="space-y-6">
                        <div class="form-group">
                            <label class="block text-white/80 text-sm font-medium mb-2">Intereses</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                <label class="flex items-center space-x-2 cursor-pointer p-3 bg-white/5 rounded-lg hover:bg-white/10 transition-all duration-300">
                                    <input type="checkbox" name="interests" value="tecnologia" class="text-green-500 focus:ring-green-500">
                                    <span class="text-white/80">Tecnología</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer p-3 bg-white/5 rounded-lg hover:bg-white/10 transition-all duration-300">
                                    <input type="checkbox" name="interests" value="deportes" class="text-green-500 focus:ring-green-500">
                                    <span class="text-white/80">Deportes</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer p-3 bg-white/5 rounded-lg hover:bg-white/10 transition-all duration-300">
                                    <input type="checkbox" name="interests" value="musica" class="text-green-500 focus:ring-green-500">
                                    <span class="text-white/80">Música</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer p-3 bg-white/5 rounded-lg hover:bg-white/10 transition-all duration-300">
                                    <input type="checkbox" name="interests" value="arte" class="text-green-500 focus:ring-green-500">
                                    <span class="text-white/80">Arte</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer p-3 bg-white/5 rounded-lg hover:bg-white/10 transition-all duration-300">
                                    <input type="checkbox" name="interests" value="viajes" class="text-green-500 focus:ring-green-500">
                                    <span class="text-white/80">Viajes</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer p-3 bg-white/5 rounded-lg hover:bg-white/10 transition-all duration-300">
                                    <input type="checkbox" name="interests" value="cocina" class="text-green-500 focus:ring-green-500">
                                    <span class="text-white/80">Cocina</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-white/80 text-sm font-medium mb-2">Comentarios adicionales</label>
                            <textarea name="comments" rows="4" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300 resize-none" placeholder="Cuéntanos algo más sobre ti..."></textarea>
                        </div>
                        <div class="form-group">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="notifications" class="text-green-500 focus:ring-green-500">
                                <span class="text-white/80">Acepto recibir notificaciones por email</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="terms" class="text-green-500 focus:ring-green-500">
                                <span class="text-white/80">Acepto los términos y condiciones</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Botones de navegación -->
                <div class="flex justify-between pt-6 border-t border-white/20">
                    <button type="button" id="prevBtn" class="px-6 py-3 bg-white/10 text-white rounded-xl border border-white/20 hover:bg-white/20 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2" disabled>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span>Anterior</span>
                    </button>
                    <button type="button" id="nextBtn" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl hover:from-purple-600 hover:to-pink-600 transition-all duration-300 flex items-center space-x-2 shadow-lg hover:shadow-xl">
                        <span>Siguiente</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Variables globales
        let currentStep = 1;
        const totalSteps = 3;

        // Elementos del DOM
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const formSteps = document.querySelectorAll('.form-step');
        const stepIndicators = document.querySelectorAll('.step-indicator');

        // Función para mostrar el paso actual
        function showStep(step) {
            // Ocultar todos los pasos
            formSteps.forEach(formStep => {
                formStep.classList.add('hidden');
                formStep.classList.remove('animate-fade-in');
            });

            // Mostrar el paso actual con animación
            const currentFormStep = document.getElementById(`form-step-${step}`);
            currentFormStep.classList.remove('hidden');
            setTimeout(() => {
                currentFormStep.classList.add('animate-fade-in');
            }, 50);

            // Actualizar indicadores de paso
            updateStepIndicators(step);
            
            // Actualizar barras de progreso
            updateProgressBars(step);

            // Actualizar botones
            updateButtons(step);
        }

        // Función para actualizar los indicadores de paso
        function updateStepIndicators(step) {
            stepIndicators.forEach((indicator, index) => {
                const stepNumber = index + 1;
                const circle = indicator.querySelector('div');
                const label = indicator.querySelector('span');

                if (stepNumber < step) {
                    // Paso completado
                    circle.className = 'w-10 h-10 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center text-white font-bold transition-all duration-300';
                    circle.innerHTML = '✓';
                    label.className = 'text-green-400 font-medium hidden sm:block';
                } else if (stepNumber === step) {
                    // Paso actual
                    circle.className = 'w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold transition-all duration-300';
                    circle.innerHTML = stepNumber;
                    label.className = 'text-white font-medium hidden sm:block';
                } else {
                    // Paso pendiente
                    circle.className = 'w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white font-bold transition-all duration-300';
                    circle.innerHTML = stepNumber;
                    label.className = 'text-white/50 font-medium hidden sm:block';
                }
            });
        }

        // Función para actualizar las barras de progreso
        function updateProgressBars(step) {
            const progress1 = document.getElementById('progress-1');
            const progress2 = document.getElementById('progress-2');

            if (step > 1) {
                progress1.style.width = '100%';
            } else {
                progress1.style.width = '0%';
            }

            if (step > 2) {
                progress2.style.width = '100%';
            } else {
                progress2.style.width = '0%';
            }
        }

        // Función para actualizar los botones
        function updateButtons(step) {
            // Botón anterior
            if (step === 1) {
                prevBtn.disabled = true;
                prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                prevBtn.disabled = false;
                prevBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }

            // Botón siguiente/enviar
            if (step === totalSteps) {
                nextBtn.innerHTML = `
                    <span>Enviar</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;
                nextBtn.className = 'px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all duration-300 flex items-center space-x-2 shadow-lg hover:shadow-xl';
            } else {
                nextBtn.innerHTML = `
                    <span>Siguiente</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                `;
                nextBtn.className = 'px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl hover:from-purple-600 hover:to-pink-600 transition-all duration-300 flex items-center space-x-2 shadow-lg hover:shadow-xl';
            }
        }

        // Event listeners para los botones
        nextBtn.addEventListener('click', () => {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            } else {
                // Simular envío del formulario
                alert('¡Formulario enviado exitosamente!');
            }
        });

        prevBtn.addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });

        // Inicializar el formulario
        showStep(currentStep);

        // Efectos de hover en los inputs
        const inputs = document.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('transform', 'scale-105');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('transform', 'scale-105');
            });
        });
    </script>
</div>
@endsection 