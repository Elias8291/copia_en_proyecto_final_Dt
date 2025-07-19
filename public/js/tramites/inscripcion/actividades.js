/**
 * Módulo para búsqueda y selección de actividades económicas
 * Permite buscar actividades por nombre o código SCIAN y agregarlas a una lista
 */

class ActividadesBuscar {
    constructor() {
        this.elementos = {
            buscarInput: document.getElementById("buscar-actividades"),
            resultadosContainer: document.getElementById(
                "resultados-actividades"
            ),
            listaActividades: document.getElementById(
                "lista-actividades-seleccionadas"
            ),
            contenedorActividades: document.getElementById(
                "contenedor-actividades"
            ),
            estadoVacio: document.getElementById("estado-vacio"),
            contadorActividades: document.getElementById(
                "contador-actividades"
            ),
            limpiarBtn: document.getElementById("limpiar-actividades"),
            actividadesIdsInput: document.getElementById(
                "actividades-economicas-ids"
            ),
        };

        this.actividadesSeleccionadas = [];
        this.timeoutId = null;

        this.init();
    }

    /**
     * Inicializa los event listeners
     */
    init() {
        if (!this.elementos.buscarInput) {
            console.warn(
                "ActividadesBuscar: Elemento de búsqueda no encontrado"
            );
            return;
        }

        this.bindEvents();
        this.cargarActividadesGuardadas();
    }

    /**
     * Vincula los event listeners
     */
    bindEvents() {
        // Event listener para búsqueda
        this.elementos.buscarInput.addEventListener("input", (e) => {
            this.handleBusquedaInput(e.target.value);
        });

        // Event listener para cerrar resultados al hacer clic fuera
        document.addEventListener("click", (e) => {
            if (
                !this.elementos.buscarInput.contains(e.target) &&
                !this.elementos.resultadosContainer.contains(e.target)
            ) {
                this.ocultarResultados();
            }
        });

        // Event listener para botón de limpiar
        if (this.elementos.limpiarBtn) {
            this.elementos.limpiarBtn.addEventListener("click", () => {
                this.limpiarActividades();
            });
        }
    }

    /**
     * Maneja la entrada de búsqueda
     */
    handleBusquedaInput(texto) {
        // Limpiar timeout anterior
        if (this.timeoutId) {
            clearTimeout(this.timeoutId);
        }

        // Si el texto está vacío, ocultar resultados
        if (!texto || texto.length < 2) {
            this.ocultarResultados();
            return;
        }

        // Buscar con delay para evitar muchas peticiones
        this.timeoutId = setTimeout(() => {
            this.buscarActividades(texto);
        }, 300);
    }

    /**
     * Busca actividades por texto
     */
    async buscarActividades(texto) {
        try {
            this.mostrarCargando();

            const response = await fetch(
                `/api/actividades/buscar?q=${encodeURIComponent(texto)}`,
                {
                    method: "GET",
                    headers: {
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN":
                            document
                                .querySelector('meta[name="csrf-token"]')
                                ?.getAttribute("content") || "",
                    },
                }
            );

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success && data.data.length > 0) {
                this.mostrarResultados(data.data);
            } else {
                this.mostrarNoResultados();
            }
        } catch (error) {
            console.error("Error al buscar actividades:", error);
            this.mostrarError(
                "Error al buscar actividades. Intente nuevamente."
            );
        } finally {
            this.ocultarCargando();
        }
    }

    /**
     * Muestra los resultados de la búsqueda
     */
    mostrarResultados(actividades) {
        if (!this.elementos.resultadosContainer) return;

        // Filtrar actividades ya seleccionadas
        const actividadesFiltradas = actividades.filter(
            (actividad) =>
                !this.actividadesSeleccionadas.some(
                    (act) => act.id === actividad.id
                )
        );

        if (actividadesFiltradas.length === 0) {
            this.mostrarNoResultados();
            return;
        }

        const html = actividadesFiltradas
            .map(
                (actividad) => `
            <div class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0" 
                 data-id="${actividad.id}" 
                 data-nombre="${actividad.nombre}"
                 data-codigo="${actividad.codigo_scian || ""}">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-gray-900">${
                            actividad.nombre
                        }</div>
                        ${
                            actividad.codigo_scian
                                ? `<div class="text-xs text-gray-500">Código: ${actividad.codigo_scian}</div>`
                                : ""
                        }
                    </div>
                    <button type="button" class="text-xs bg-[#9d2449] text-white px-2 py-1 rounded hover:bg-[#8a1f3e] transition-colors">
                        Agregar
                    </button>
                </div>
            </div>
        `
            )
            .join("");

        this.elementos.resultadosContainer.innerHTML = html;
        this.elementos.resultadosContainer.classList.remove("hidden");

        // Agregar event listeners a los resultados
        this.elementos.resultadosContainer
            .querySelectorAll("[data-id]")
            .forEach((item) => {
                item.addEventListener("click", () => {
                    const id = parseInt(item.dataset.id);
                    const nombre = item.dataset.nombre;
                    const codigo = item.dataset.codigo;
                    this.agregarActividad(id, nombre, codigo);
                });
            });
    }

    /**
     * Muestra mensaje de no resultados
     */
    mostrarNoResultados() {
        if (!this.elementos.resultadosContainer) return;

        this.elementos.resultadosContainer.innerHTML = `
            <div class="px-4 py-6 text-center">
                <p class="text-sm text-gray-500">No se encontraron actividades</p>
                <p class="text-xs text-gray-400 mt-1">Intente con otro término de búsqueda</p>
            </div>
        `;
        this.elementos.resultadosContainer.classList.remove("hidden");
    }

    /**
     * Oculta los resultados de búsqueda
     */
    ocultarResultados() {
        if (this.elementos.resultadosContainer) {
            this.elementos.resultadosContainer.classList.add("hidden");
        }
    }

    /**
     * Agrega una actividad a la lista de seleccionadas
     */
    agregarActividad(id, nombre, codigo) {
        // Verificar si ya existe
        if (this.actividadesSeleccionadas.some((act) => act.id === id)) {
            return;
        }

        // Agregar a la lista
        this.actividadesSeleccionadas.push({
            id: id,
            nombre: nombre,
            codigo: codigo,
        });

        // Actualizar UI
        this.actualizarListaActividades();
        this.ocultarResultados();
        this.elementos.buscarInput.value = "";
    }

    /**
     * Elimina una actividad de la lista
     */
    eliminarActividad(id) {
        this.actividadesSeleccionadas = this.actividadesSeleccionadas.filter(
            (act) => act.id !== id
        );
        this.actualizarListaActividades();
    }

    /**
     * Actualiza la lista visual de actividades
     */
    actualizarListaActividades() {
        if (
            !this.elementos.contenedorActividades ||
            !this.elementos.estadoVacio
        )
            return;

        if (this.actividadesSeleccionadas.length === 0) {
            this.elementos.contenedorActividades.classList.add("hidden");
            this.elementos.estadoVacio.classList.remove("hidden");

            if (this.elementos.limpiarBtn) {
                this.elementos.limpiarBtn.classList.add("hidden");
            }

            if (this.elementos.contadorActividades) {
                this.elementos.contadorActividades.classList.add("hidden");
            }
        } else {
            this.elementos.contenedorActividades.classList.remove("hidden");
            this.elementos.estadoVacio.classList.add("hidden");

            if (this.elementos.limpiarBtn) {
                this.elementos.limpiarBtn.classList.remove("hidden");
            }

            if (this.elementos.contadorActividades) {
                this.elementos.contadorActividades.textContent =
                    this.actividadesSeleccionadas.length;
                this.elementos.contadorActividades.classList.remove("hidden");
            }

            // Generar HTML para las actividades
            const html = this.actividadesSeleccionadas
                .map(
                    (actividad) => `
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-3 relative actividad-item" data-id="${
                    actividad.id
                }">
                    <button type="button" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition-colors eliminar-actividad">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <div class="pr-6">
                        <div class="text-sm font-medium text-gray-900">${
                            actividad.nombre
                        }</div>
                        ${
                            actividad.codigo
                                ? `<div class="text-xs text-gray-500 mt-1">Código: ${actividad.codigo}</div>`
                                : ""
                        }
                    </div>
                </div>
            `
                )
                .join("");

            this.elementos.contenedorActividades.innerHTML = html;

            // Agregar event listeners para eliminar
            this.elementos.contenedorActividades
                .querySelectorAll(".eliminar-actividad")
                .forEach((btn) => {
                    btn.addEventListener("click", (e) => {
                        const item = e.target.closest(".actividad-item");
                        if (item) {
                            const id = parseInt(item.dataset.id);
                            this.eliminarActividad(id);
                        }
                    });
                });
        }

        // Actualizar campo oculto con IDs
        this.actualizarCampoIds();
    }

    /**
     * Actualiza el campo oculto con los IDs de actividades
     */
    actualizarCampoIds() {
        if (this.elementos.actividadesIdsInput) {
            const ids = this.actividadesSeleccionadas.map((act) => act.id);
            this.elementos.actividadesIdsInput.value = ids.join(",");
        }
    }

    /**
     * Limpia todas las actividades seleccionadas
     */
    limpiarActividades() {
        this.actividadesSeleccionadas = [];
        this.actualizarListaActividades();
    }

    /**
     * Carga actividades guardadas previamente
     */
    cargarActividadesGuardadas() {
        // Si hay un valor en el campo oculto, intentar cargar
        if (
            this.elementos.actividadesIdsInput &&
            this.elementos.actividadesIdsInput.value
        ) {
            const ids = this.elementos.actividadesIdsInput.value
                .split(",")
                .map((id) => parseInt(id.trim()))
                .filter((id) => !isNaN(id));

            if (ids.length > 0) {
                this.cargarActividadesPorIds(ids);
            }
        }
    }

    /**
     * Carga actividades por IDs desde el servidor
     */
    async cargarActividadesPorIds(ids) {
        try {
            const response = await fetch(
                `/api/actividades/por-ids?ids=${ids.join(",")}`,
                {
                    method: "GET",
                    headers: {
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN":
                            document
                                .querySelector('meta[name="csrf-token"]')
                                ?.getAttribute("content") || "",
                    },
                }
            );

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success && data.data.length > 0) {
                // Agregar cada actividad a la lista
                data.data.forEach((actividad) => {
                    this.actividadesSeleccionadas.push({
                        id: actividad.id,
                        nombre: actividad.nombre,
                        codigo: actividad.codigo_scian || "",
                    });
                });

                this.actualizarListaActividades();
            }
        } catch (error) {
            console.error("Error al cargar actividades guardadas:", error);
        }
    }

    /**
     * Muestra indicador de carga
     */
    mostrarCargando() {
        if (this.elementos.buscarInput) {
            this.elementos.buscarInput.classList.add("animate-pulse");
        }
    }

    /**
     * Oculta indicador de carga
     */
    ocultarCargando() {
        if (this.elementos.buscarInput) {
            this.elementos.buscarInput.classList.remove("animate-pulse");
        }
    }

    /**
     * Muestra mensaje de error
     */
    mostrarError(mensaje) {
        console.error("ActividadesBuscar:", mensaje);
        // Implementar UI para mostrar error si es necesario
    }
}

// Exportar para uso global
window.ActividadesBuscar = ActividadesBuscar;
