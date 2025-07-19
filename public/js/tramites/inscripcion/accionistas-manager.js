/**
 * Gestor de accionistas para el formulario de inscripción
 * Permite agregar, eliminar y gestionar accionistas en formato de acordeón
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
        };

        this.contadorAccionistas = 1;
        this.accionistas = [];

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
        const primerAccionista =
            this.elementos.container.querySelector(".accionista-item");
        if (primerAccionista) {
            this.accionistas.push({
                id: 0,
                elemento: primerAccionista,
            });

            // Configurar el acordeón para el primer accionista
            this.configurarAcordeon(primerAccionista);
        }

        // Configurar event listeners
        this.elementos.btnAgregar.addEventListener("click", () =>
            this.agregarAccionista()
        );

        // Configurar event listeners para los inputs de porcentaje
        this.configurarInputsPorcentaje();

        // Calcular porcentaje inicial
        this.calcularPorcentajeTotal();
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
                // Toggle la visibilidad del contenido
                const isOpen = !content.classList.contains("hidden");

                if (isOpen) {
                    content.classList.add("hidden");
                    if (icon) icon.classList.add("rotate-180");
                } else {
                    content.classList.remove("hidden");
                    if (icon) icon.classList.remove("rotate-180");
                }
            });
        }
    }

    /**
     * Oculta el contenido de todos los accionistas
     */
    ocultarTodosLosAccionistas() {
        this.accionistas.forEach((accionista) => {
            const content =
                accionista.elemento.querySelector(".accordion-content");
            const icon = accionista.elemento.querySelector(".accordion-icon");

            if (content && !content.classList.contains("hidden")) {
                content.classList.add("hidden");
                if (icon) icon.classList.add("rotate-180");
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
        nuevoAccionista.className =
            "accionista-item bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden animate__animated animate__fadeIn";
        nuevoAccionista.dataset.id = nuevoId;

        // Generar HTML para el nuevo accionista
        nuevoAccionista.innerHTML = `
            <!-- Cabecera del acordeón -->
            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center cursor-pointer accordion-header">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-[#9d2449] rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                        ${this.contadorAccionistas}
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Accionista #${this.contadorAccionistas}</h3>
                        <p class="text-xs text-gray-500">Información requerida</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium text-[#9d2449] mr-3 porcentaje-display">0%</span>
                    <button type="button" class="eliminar-accionista text-red-500 hover:text-red-700 transition-colors mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Contenido del acordeón -->
            <div class="px-6 py-5 accordion-content">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="accionistas[${nuevoId}][rfc]" class="block text-sm font-semibold text-gray-700 mb-2">
                            RFC <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                name="accionistas[${nuevoId}][rfc]" 
                                id="accionistas[${nuevoId}][rfc]"
                                placeholder="Ej: XAXX010101000" 
                                required 
                                data-required="true"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] focus:outline-none transition-colors duration-200 text-sm">
                        </div>
                    </div>
                    <div>
                        <label for="accionistas[${nuevoId}][porcentaje]" class="block text-sm font-semibold text-gray-700 mb-2">
                            Porcentaje de Participación <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                name="accionistas[${nuevoId}][porcentaje]" 
                                id="accionistas[${nuevoId}][porcentaje]"
                                placeholder="0.00" 
                                min="0" 
                                max="100" 
                                step="0.01" 
                                required 
                                data-required="true"
                                class="porcentaje-input w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] focus:outline-none transition-colors duration-200 text-sm pr-10">
                            <span class="absolute right-3 top-2.5 text-gray-500 text-sm">%</span>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="accionistas[${nuevoId}][nombre]" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                name="accionistas[${nuevoId}][nombre]" 
                                id="accionistas[${nuevoId}][nombre]"
                                placeholder="Nombre" 
                                required 
                                data-required="true"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] focus:outline-none transition-colors duration-200 text-sm">
                        </div>
                    </div>
                    <div>
                        <label for="accionistas[${nuevoId}][apellido_paterno]" class="block text-sm font-semibold text-gray-700 mb-2">
                            Apellido Paterno <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                name="accionistas[${nuevoId}][apellido_paterno]" 
                                id="accionistas[${nuevoId}][apellido_paterno]"
                                placeholder="Apellido paterno" 
                                required 
                                data-required="true"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] focus:outline-none transition-colors duration-200 text-sm">
                        </div>
                    </div>
                    <div>
                        <label for="accionistas[${nuevoId}][apellido_materno]" class="block text-sm font-semibold text-gray-700 mb-2">
                            Apellido Materno
                        </label>
                        <div class="relative">
                            <input type="text" 
                                name="accionistas[${nuevoId}][apellido_materno]" 
                                id="accionistas[${nuevoId}][apellido_materno]"
                                placeholder="Apellido materno"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] focus:outline-none transition-colors duration-200 text-sm">
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Agregar el nuevo accionista al contenedor
        this.elementos.container.appendChild(nuevoAccionista);

        // Registrar el nuevo accionista
        this.accionistas.push({
            id: nuevoId,
            elemento: nuevoAccionista,
        });

        // Actualizar contador visual
        if (this.elementos.contador) {
            this.elementos.contador.textContent = this.contadorAccionistas;
        }

        // Configurar el acordeón para el nuevo accionista
        this.configurarAcordeon(nuevoAccionista);

        // Configurar event listener para eliminar
        const btnEliminar = nuevoAccionista.querySelector(
            ".eliminar-accionista"
        );
        if (btnEliminar) {
            btnEliminar.addEventListener("click", (e) => {
                e.stopPropagation(); // Evitar que se active el acordeón al hacer clic en eliminar
                this.eliminarAccionista(nuevoId);
            });
        }

        // Configurar event listener para el input de porcentaje
        const inputPorcentaje =
            nuevoAccionista.querySelector(".porcentaje-input");
        if (inputPorcentaje) {
            inputPorcentaje.addEventListener("input", () => {
                this.calcularPorcentajeTotal();
                this.actualizarPorcentajeDisplay(
                    nuevoAccionista,
                    inputPorcentaje.value
                );
            });
        }

        // Hacer scroll al nuevo accionista
        nuevoAccionista.scrollIntoView({ behavior: "smooth", block: "center" });

        // Enfocar el primer campo
        const primerInput = nuevoAccionista.querySelector("input");
        if (primerInput) {
            primerInput.focus();
        }
    }

    /**
     * Actualiza el porcentaje mostrado en la cabecera del acordeón
     * @param {HTMLElement} accionistaElement - Elemento del accionista
     * @param {String|Number} valor - Valor del porcentaje
     */
    actualizarPorcentajeDisplay(accionistaElement, valor) {
        const porcentajeDisplay = accionistaElement.querySelector(
            ".porcentaje-display"
        );
        if (porcentajeDisplay) {
            const porcentaje = parseFloat(valor) || 0;
            porcentajeDisplay.textContent = `${porcentaje.toFixed(2)}%`;
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
        accionista.elemento.classList.add(
            "animate__animated",
            "animate__fadeOut"
        );

        // Eliminar después de la animación
        setTimeout(() => {
            accionista.elemento.remove();
            this.accionistas.splice(index, 1);
            this.contadorAccionistas--;

            // Actualizar contador visual
            if (this.elementos.contador) {
                this.elementos.contador.textContent = this.contadorAccionistas;
            }

            // Actualizar numeración de los accionistas restantes
            this.actualizarNumeracion();

            // Recalcular porcentaje total
            this.calcularPorcentajeTotal();
        }, 300);
    }

    /**
     * Actualiza la numeración visual de los accionistas
     */
    actualizarNumeracion() {
        let contador = 1;
        this.accionistas.forEach((accionista) => {
            const numeroCirculo = accionista.elemento.querySelector(
                ".w-8.h-8.bg-\\[\\#9d2449\\].rounded-full"
            );
            const titulo = accionista.elemento.querySelector("h3");

            if (numeroCirculo) {
                numeroCirculo.textContent = contador;
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
        const primerInputPorcentaje = this.elementos.container.querySelector(
            'input[name="accionistas[0][porcentaje]"]'
        );
        if (primerInputPorcentaje) {
            primerInputPorcentaje.addEventListener("input", () => {
                this.calcularPorcentajeTotal();

                // Actualizar el porcentaje mostrado en la cabecera
                const accionistaElement =
                    primerInputPorcentaje.closest(".accionista-item");
                if (accionistaElement) {
                    this.actualizarPorcentajeDisplay(
                        accionistaElement,
                        primerInputPorcentaje.value
                    );
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
        const inputsPorcentaje = document.querySelectorAll(
            'input[name$="[porcentaje]"]'
        );
        inputsPorcentaje.forEach((input) => {
            const valor = parseFloat(input.value) || 0;
            total += valor;
        });

        // Actualizar el elemento visual
        const spanTotal =
            this.elementos.porcentajeTotal.querySelector("span:first-child");
        const spanFaltante =
            this.elementos.porcentajeTotal.querySelector("span:last-child");

        if (spanTotal) {
            spanTotal.textContent = `${total.toFixed(2)}%`;

            // Cambiar color según el valor
            if (total === 100) {
                spanTotal.className = "text-xl font-bold text-green-600";
            } else if (total > 100) {
                spanTotal.className = "text-xl font-bold text-red-600";
            } else {
                spanTotal.className = "text-xl font-bold text-[#9d2449]";
            }
        }

        if (spanFaltante) {
            const faltante = 100 - total;

            if (faltante === 0) {
                spanFaltante.textContent = "(Correcto)";
                spanFaltante.className = "text-sm text-green-600 ml-2";
            } else if (faltante < 0) {
                spanFaltante.textContent = `(Excedente: ${Math.abs(
                    faltante
                ).toFixed(2)}%)`;
                spanFaltante.className = "text-sm text-red-600 ml-2";
            } else {
                spanFaltante.textContent = `(Faltan: ${faltante.toFixed(2)}%)`;
                spanFaltante.className = "text-sm text-gray-500 ml-2";
            }
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
