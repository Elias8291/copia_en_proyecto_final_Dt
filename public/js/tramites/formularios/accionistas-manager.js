/**
 * Gestor de accionistas para el formulario de inscripción
 * Permite agregar, eliminar y gestionar accionistas con efectos visuales mejorados
 */

class AccionistasManager {
    /**
     * Constructor del gestor de accionistas
     */
    constructor() {
        this.elementos = {
            container: document.getElementById("accionistas-container"),
            btnAgregar: document.getElementById("agregar-accionista"),
            contador: document.getElementById("contador-accionistas"),
            porcentajeTotal: document.getElementById("porcentaje-total"),
            warningElement: document.getElementById("participacion-warning")
        };

        this.contadorAccionistas = 1;
        this.accionistas = [];
        this.animacionDuracion = 400;

        this.init();
    }

    /**
     * Inicializa el módulo
     */
    init() {
        if (!this.elementos.container || !this.elementos.btnAgregar) {
            console.warn("AccionistasManager: Elementos no encontrados");
            return;
        }

        // Registrar el primer accionista (que ya existe en el HTML)
        const primerAccionista = this.elementos.container.querySelector(".accionista-item");
        if (primerAccionista) {
            this.accionistas.push({
                id: 0,
                elemento: primerAccionista,
            });

            // Configurar el acordeón para el primer accionista
            this.configurarAcordeon(primerAccionista);
        }

        // Configurar event listeners
        this.elementos.btnAgregar.addEventListener("click", () => this.agregarAccionista());

        // Configurar event listeners para los inputs de porcentaje
        this.configurarInputsPorcentaje();

        // Calcular porcentaje inicial
        this.calcularPorcentajeTotal();

        // Agregar efectos de hover mejorados
        this.agregarEfectosHover();
    }

    /**
     * Agrega efectos de hover mejorados
     */
    agregarEfectosHover() {
        // Efecto hover para el botón agregar
        this.elementos.btnAgregar.addEventListener('mouseenter', () => {
            this.elementos.btnAgregar.style.transform = 'translateY(-2px) scale(1.02)';
        });

        this.elementos.btnAgregar.addEventListener('mouseleave', () => {
            this.elementos.btnAgregar.style.transform = 'translateY(0) scale(1)';
        });
    }

    /**
     * Configura el comportamiento de acordeón para un elemento
     * @param {HTMLElement} elemento - Elemento del acordeón
     */
    configurarAcordeon(elemento) {
        const header = elemento.querySelector(".accordion-header");
        const content = elemento.querySelector(".accordion-content");
        const icon = elemento.querySelector(".accordion-icon");

        if (header && content) {
            header.addEventListener("click", () => {
                const isOpen = !content.classList.contains("hidden");

                // Agregar efecto de ripple
                this.crearEfectoRipple(header, event);

                if (isOpen) {
                    // Cerrar con animación
                    content.style.maxHeight = content.scrollHeight + "px";
                    content.style.opacity = "1";
                    
                    requestAnimationFrame(() => {
                        content.style.maxHeight = "0px";
                        content.style.opacity = "0";
                        content.style.paddingTop = "0px";
                        content.style.paddingBottom = "0px";
                    });

                    setTimeout(() => {
                        content.classList.add("hidden");
                        content.style.maxHeight = "";
                        content.style.opacity = "";
                        content.style.paddingTop = "";
                        content.style.paddingBottom = "";
                    }, this.animacionDuracion);

                    if (icon) {
                        icon.style.transform = "rotate(0deg)";
                    }
                } else {
                    // Abrir con animación
                    content.classList.remove("hidden");
                    content.style.maxHeight = "0px";
                    content.style.opacity = "0";
                    content.style.paddingTop = "0px";
                    content.style.paddingBottom = "0px";

                    requestAnimationFrame(() => {
                        content.style.maxHeight = content.scrollHeight + "px";
                        content.style.opacity = "1";
                        content.style.paddingTop = "1.5rem";
                        content.style.paddingBottom = "1.5rem";
                    });

                    setTimeout(() => {
                        content.style.maxHeight = "";
                        content.style.opacity = "";
                        content.style.paddingTop = "";
                        content.style.paddingBottom = "";
                    }, this.animacionDuracion);

                    if (icon) {
                        icon.style.transform = "rotate(180deg)";
                    }
                }
            });

            // Agregar efecto hover al header
            header.addEventListener('mouseenter', () => {
                header.style.transform = 'translateX(4px)';
            });

            header.addEventListener('mouseleave', () => {
                header.style.transform = 'translateX(0)';
            });
        }
    }

    /**
     * Crea un efecto ripple en el elemento
     * @param {HTMLElement} elemento - Elemento donde crear el ripple
     * @param {Event} event - Evento del click
     */
    crearEfectoRipple(elemento, event) {
        const ripple = document.createElement('span');
        const rect = elemento.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;

        ripple.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            background: rgba(157, 36, 73, 0.3);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s ease-out;
            pointer-events: none;
            z-index: 1;
        `;

        // Agregar keyframes si no existen
        if (!document.querySelector('#ripple-keyframes')) {
            const style = document.createElement('style');
            style.id = 'ripple-keyframes';
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(2);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }

        elemento.style.position = 'relative';
        elemento.style.overflow = 'hidden';
        elemento.appendChild(ripple);

        setTimeout(() => {
            ripple.remove();
        }, 600);
    }

    /**
     * Oculta el contenido de todos los accionistas
     */
    ocultarTodosLosAccionistas() {
        this.accionistas.forEach((accionista) => {
            const content = accionista.elemento.querySelector(".accordion-content");
            const icon = accionista.elemento.querySelector(".accordion-icon");

            if (content && !content.classList.contains("hidden")) {
                content.style.maxHeight = content.scrollHeight + "px";
                
                requestAnimationFrame(() => {
                    content.style.maxHeight = "0px";
                    content.style.opacity = "0";
                });

                setTimeout(() => {
                    content.classList.add("hidden");
                    content.style.maxHeight = "";
                    content.style.opacity = "";
                }, this.animacionDuracion);

                if (icon) {
                    icon.style.transform = "rotate(0deg)";
                }
            }
        });
    }

    /**
     * Agrega un nuevo accionista al formulario
     */
    agregarAccionista() {
        // Ocultar todos los accionistas existentes
        this.ocultarTodosLosAccionistas();

        this.contadorAccionistas++;
        const nuevoId = this.accionistas.length;

        // Crear elemento HTML para el nuevo accionista
        const nuevoAccionista = document.createElement("div");
        nuevoAccionista.className = "accionista-item bg-white border-2 border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden opacity-0 transform translate-y-4 scale-95";
        nuevoAccionista.dataset.id = nuevoId;

        // Generar HTML para el nuevo accionista
        nuevoAccionista.innerHTML = `
            <!-- Cabecera del acordeón -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 flex justify-between items-center cursor-pointer accordion-header hover:from-indigo-50 hover:to-indigo-100 transition-all duration-200">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#9D2449] to-[#B91C1C] rounded-full flex items-center justify-center text-white font-bold text-sm mr-4 shadow-md transform transition-transform duration-200 hover:scale-110">
                        ${this.contadorAccionistas}
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 text-lg">Accionista #${this.contadorAccionistas}</h3>
                        <p class="text-sm text-gray-500">Complete la información requerida</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <span class="porcentaje-display text-lg font-bold text-[#9D2449]">0.00%</span>
                        <p class="text-xs text-gray-500">Participación</p>
                    </div>
                    <button type="button" class="eliminar-accionista w-8 h-8 bg-red-100 hover:bg-red-200 rounded-full flex items-center justify-center text-red-600 hover:text-red-700 transition-all duration-200 transform hover:scale-110">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200 accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Contenido del acordeón -->
            <div class="px-6 py-6 accordion-content bg-white">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="accionistas[${nuevoId}][rfc]" class="block text-sm font-medium text-gray-700 mb-2">
                            RFC <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                <i class="fas fa-id-card text-gray-400 group-focus-within:text-[#9D2449] transition-colors duration-200"></i>
                            </div>
                            <input type="text" 
                                name="accionistas[${nuevoId}][rfc]" 
                                id="accionistas[${nuevoId}][rfc]"
                                placeholder="Ej: XAXX010101000" 
                                required 
                                data-required="true"
                                class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9D2449] focus:border-[#9D2449] transition-all duration-200 shadow-sm hover:shadow-md focus:shadow-lg">
                        </div>
                    </div>
                    <div>
                        <label for="accionistas[${nuevoId}][porcentaje]" class="block text-sm font-medium text-gray-700 mb-2">
                            Porcentaje de Participación <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                <i class="fas fa-percentage text-gray-400 group-focus-within:text-[#9D2449] transition-colors duration-200"></i>
                            </div>
                            <input type="number" 
                                name="accionistas[${nuevoId}][porcentaje]" 
                                id="accionistas[${nuevoId}][porcentaje]"
                                placeholder="0.00" 
                                min="0" 
                                max="100" 
                                step="0.01" 
                                required 
                                data-required="true"
                                class="porcentaje-input w-full pl-10 pr-12 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9D2449] focus:border-[#9D2449] transition-all duration-200 shadow-sm hover:shadow-md focus:shadow-lg">
                            <span class="absolute right-3 top-3 text-gray-500 font-medium">%</span>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="accionistas[${nuevoId}][nombre]" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                <i class="fas fa-user text-gray-400 group-focus-within:text-[#9D2449] transition-colors duration-200"></i>
                            </div>
                            <input type="text" 
                                name="accionistas[${nuevoId}][nombre]" 
                                id="accionistas[${nuevoId}][nombre]"
                                placeholder="Nombre" 
                                required 
                                data-required="true"
                                class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9D2449] focus:border-[#9D2449] transition-all duration-200 shadow-sm hover:shadow-md focus:shadow-lg">
                        </div>
                    </div>
                    <div>
                        <label for="accionistas[${nuevoId}][apellido_paterno]" class="block text-sm font-medium text-gray-700 mb-2">
                            Apellido Paterno <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                <i class="fas fa-user-tag text-gray-400 group-focus-within:text-[#9D2449] transition-colors duration-200"></i>
                            </div>
                            <input type="text" 
                                name="accionistas[${nuevoId}][apellido_paterno]" 
                                id="accionistas[${nuevoId}][apellido_paterno]"
                                placeholder="Apellido paterno" 
                                required 
                                data-required="true"
                                class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9D2449] focus:border-[#9D2449] transition-all duration-200 shadow-sm hover:shadow-md focus:shadow-lg">
                        </div>
                    </div>
                    <div>
                        <label for="accionistas[${nuevoId}][apellido_materno]" class="block text-sm font-medium text-gray-700 mb-2">
                            Apellido Materno
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                <i class="fas fa-user-friends text-gray-400 group-focus-within:text-[#9D2449] transition-colors duration-200"></i>
                            </div>
                            <input type="text" 
                                name="accionistas[${nuevoId}][apellido_materno]" 
                                id="accionistas[${nuevoId}][apellido_materno]"
                                placeholder="Apellido materno"
                                class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9D2449] focus:border-[#9D2449] transition-all duration-200 shadow-sm hover:shadow-md focus:shadow-lg">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="accionistas[${nuevoId}][nacionalidad]" class="block text-sm font-medium text-gray-700 mb-2">
                            Nacionalidad <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                <i class="fas fa-globe text-gray-400 group-focus-within:text-[#9D2449] transition-colors duration-200"></i>
                            </div>
                            <select name="accionistas[${nuevoId}][nacionalidad]" 
                                    id="accionistas[${nuevoId}][nacionalidad]"
                                    class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9D2449] focus:border-[#9D2449] transition-all duration-200 shadow-sm hover:shadow-md focus:shadow-lg appearance-none" 
                                    required>
                                <option value="">Seleccione nacionalidad</option>
                                <option value="MEXICANA">Mexicana</option>
                                <option value="EXTRANJERA">Extranjera</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="accionistas[${nuevoId}][curp]" class="block text-sm font-medium text-gray-700 mb-2">
                            CURP
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                <i class="fas fa-address-card text-gray-400 group-focus-within:text-[#9D2449] transition-colors duration-200"></i>
                            </div>
                            <input type="text" 
                                name="accionistas[${nuevoId}][curp]" 
                                id="accionistas[${nuevoId}][curp]"
                                placeholder="CURP (opcional)"
                                maxlength="18"
                                class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9D2449] focus:border-[#9D2449] transition-all duration-200 shadow-sm hover:shadow-md focus:shadow-lg">
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Agregar el nuevo accionista al contenedor
        this.elementos.container.appendChild(nuevoAccionista);

        // Animar la entrada
        requestAnimationFrame(() => {
            nuevoAccionista.style.opacity = "1";
            nuevoAccionista.style.transform = "translate(0) scale(1)";
        });

        // Registrar el nuevo accionista
        this.accionistas.push({
            id: nuevoId,
            elemento: nuevoAccionista,
        });

        // Actualizar contador visual con animación
        this.actualizarContadorConAnimacion();

        // Configurar el acordeón para el nuevo accionista
        this.configurarAcordeon(nuevoAccionista);

        // Configurar event listener para eliminar
        const btnEliminar = nuevoAccionista.querySelector(".eliminar-accionista");
        if (btnEliminar) {
            btnEliminar.addEventListener("click", (e) => {
                e.stopPropagation();
                this.eliminarAccionista(nuevoId);
            });
        }

        // Configurar event listener para el input de porcentaje
        const inputPorcentaje = nuevoAccionista.querySelector(".porcentaje-input");
        if (inputPorcentaje) {
            inputPorcentaje.addEventListener("input", () => {
                this.calcularPorcentajeTotal();
                this.actualizarPorcentajeDisplay(nuevoAccionista, inputPorcentaje.value);
            });
        }

        // Hacer scroll al nuevo accionista con animación suave
        setTimeout(() => {
            nuevoAccionista.scrollIntoView({ 
                behavior: "smooth", 
                block: "center",
                inline: "nearest"
            });
        }, 100);

        // Abrir automáticamente el nuevo accionista
        setTimeout(() => {
            const header = nuevoAccionista.querySelector(".accordion-header");
            if (header) {
                header.click();
            }
        }, 300);

        // Enfocar el primer campo después de abrir
        setTimeout(() => {
            const primerInput = nuevoAccionista.querySelector("input");
            if (primerInput) {
                primerInput.focus();
            }
        }, 700);
    }

    /**
     * Actualiza el contador con animación
     */
    actualizarContadorConAnimacion() {
        if (this.elementos.contador) {
            this.elementos.contador.style.transform = "scale(1.2)";
            this.elementos.contador.style.color = "#9D2449";
            
            setTimeout(() => {
                this.elementos.contador.textContent = this.contadorAccionistas;
                this.elementos.contador.style.transform = "scale(1)";
            }, 150);
        }
    }

    /**
     * Actualiza el porcentaje mostrado en la cabecera del acordeón
     * @param {HTMLElement} accionistaElement - Elemento del accionista
     * @param {String|Number} valor - Valor del porcentaje
     */
    actualizarPorcentajeDisplay(accionistaElement, valor) {
        const porcentajeDisplay = accionistaElement.querySelector(".porcentaje-display");
        if (porcentajeDisplay) {
            const porcentaje = parseFloat(valor) || 0;
            porcentajeDisplay.style.transform = "scale(1.1)";
            
            setTimeout(() => {
                porcentajeDisplay.textContent = `${porcentaje.toFixed(2)}%`;
                porcentajeDisplay.style.transform = "scale(1)";
            }, 100);
        }
    }

    /**
     * Elimina un accionista del formulario
     * @param {Number} id - ID del accionista a eliminar
     */
    eliminarAccionista(id) {
        const index = this.accionistas.findIndex((a) => a.id === id);
        if (index === -1) return;

        const accionista = this.accionistas[index];

        // Animar salida
        accionista.elemento.style.transform = "translateX(-100%) scale(0.8)";
        accionista.elemento.style.opacity = "0";

        // Eliminar después de la animación
        setTimeout(() => {
            accionista.elemento.remove();
            this.accionistas.splice(index, 1);
            this.contadorAccionistas--;

            // Actualizar contador visual con animación
            this.actualizarContadorConAnimacion();

            // Actualizar numeración de los accionistas restantes
            this.actualizarNumeracion();

            // Recalcular porcentaje total
            this.calcularPorcentajeTotal();
        }, this.animacionDuracion);
    }

    /**
     * Actualiza la numeración visual de los accionistas
     */
    actualizarNumeracion() {
        let contador = 1;
        this.accionistas.forEach((accionista) => {
            const numeroCirculo = accionista.elemento.querySelector(".w-10.h-10");
            const titulo = accionista.elemento.querySelector("h3");

            if (numeroCirculo) {
                numeroCirculo.style.transform = "scale(1.1)";
                setTimeout(() => {
                    numeroCirculo.textContent = contador;
                    numeroCirculo.style.transform = "scale(1)";
                }, 100);
            }

            if (titulo) {
                titulo.textContent = `Accionista #${contador}`;
            }

            contador++;
        });
    }

    /**
     * Configura los event listeners para los inputs de porcentaje
     */
    configurarInputsPorcentaje() {
        // Configurar el input de porcentaje del primer accionista
        const primerInputPorcentaje = this.elementos.container.querySelector('input[name="accionistas[0][porcentaje]"]');
        if (primerInputPorcentaje) {
            primerInputPorcentaje.addEventListener("input", () => {
                this.calcularPorcentajeTotal();

                // Actualizar el porcentaje mostrado en la cabecera
                const accionistaElement = primerInputPorcentaje.closest(".accionista-item");
                if (accionistaElement) {
                    this.actualizarPorcentajeDisplay(accionistaElement, primerInputPorcentaje.value);
                }
            });
        }
    }

    /**
     * Calcula el porcentaje total de participación
     */
    calcularPorcentajeTotal() {
        if (!this.elementos.porcentajeTotal) return;

        let total = 0;

        // Sumar todos los porcentajes
        const inputsPorcentaje = document.querySelectorAll('input[name$="[porcentaje]"]');
        inputsPorcentaje.forEach((input) => {
            const valor = parseFloat(input.value) || 0;
            total += valor;
        });

        // Actualizar el elemento visual con animación
        const spanTotal = this.elementos.porcentajeTotal.querySelector("span:first-child");
        const spanFaltante = this.elementos.porcentajeTotal.querySelector("span:last-child");

        if (spanTotal) {
            spanTotal.style.transform = "scale(1.05)";
            
            setTimeout(() => {
                spanTotal.textContent = `${total.toFixed(2)}%`;
                spanTotal.style.transform = "scale(1)";
            }, 100);
        }

        if (spanFaltante) {
            const faltante = 100 - total;

            if (faltante === 0) {
                spanFaltante.textContent = "(¡Perfecto!)";
                spanFaltante.className = "text-sm text-white/90 block";
                this.elementos.porcentajeTotal.parentElement.className = "bg-gradient-to-r from-green-600 to-green-700 rounded-xl p-6 text-white shadow-lg";
                this.ocultarWarning();
            } else if (faltante < 0) {
                spanFaltante.textContent = `(Excedente: ${Math.abs(faltante).toFixed(2)}%)`;
                spanFaltante.className = "text-sm text-white/90 block";
                this.elementos.porcentajeTotal.parentElement.className = "bg-gradient-to-r from-red-600 to-red-700 rounded-xl p-6 text-white shadow-lg";
                this.mostrarWarning();
            } else {
                spanFaltante.textContent = `(Faltan: ${faltante.toFixed(2)}%)`;
                spanFaltante.className = "text-sm text-white/80 block";
                this.elementos.porcentajeTotal.parentElement.className = "bg-gradient-to-r from-[#9D2449] to-[#B91C1C] rounded-xl p-6 text-white shadow-lg";
                this.mostrarWarning();
            }
        }
    }

    /**
     * Muestra el warning de participación
     */
    mostrarWarning() {
        if (this.elementos.warningElement && this.elementos.warningElement.classList.contains('hidden')) {
            this.elementos.warningElement.classList.remove('hidden');
            this.elementos.warningElement.style.opacity = '0';
            this.elementos.warningElement.style.transform = 'translateY(-10px)';
            
            requestAnimationFrame(() => {
                this.elementos.warningElement.style.opacity = '1';
                this.elementos.warningElement.style.transform = 'translateY(0)';
            });
        }
    }

    /**
     * Oculta el warning de participación
     */
    ocultarWarning() {
        if (this.elementos.warningElement && !this.elementos.warningElement.classList.contains('hidden')) {
            this.elementos.warningElement.style.opacity = '0';
            this.elementos.warningElement.style.transform = 'translateY(-10px)';
            
            setTimeout(() => {
                this.elementos.warningElement.classList.add('hidden');
            }, 200);
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", () => {
    // Solo inicializar si estamos en la sección de accionistas
    if (document.getElementById("accionistas-container")) {
        window.accionistasManager = new AccionistasManager();
    }
});