class ArchivosController {
    constructor() {
        this.archivos = new Map();
        this.init();
    }

    init() {
        this.configurarEventos();
        this.observarCambios();
        this.cargarArchivosExistentes();
    }

    configurarEventos() {
        document.addEventListener('change', (e) => {
            if (e.target.type === 'file') {
                this.procesarArchivo(e.target);
            }
        });

        document.addEventListener('dragover', (e) => {
            e.preventDefault();
        });

        document.addEventListener('drop', (e) => {
            e.preventDefault();
            this.procesarDrop(e);
        });
    }

    async procesarArchivo(input) {
        const file = input.files[0];
        if (!file) return;

        const catalogoId = input.getAttribute('data-catalogo-id');
        const tramiteId = this.obtenerTramiteId();
        
        if (!catalogoId) {
            console.error('Falta catalogoId');
            return;
        }

        const validacion = this.validarArchivo(file, input);
        const archivoKey = tramiteId ? `${tramiteId}_${catalogoId}` : catalogoId;

        this.archivos.set(archivoKey, {
            file: file,
            valido: validacion.valido,
            errores: validacion.errores,
            input: input,
            catalogoId: catalogoId,
            tramiteId: tramiteId,
            temporal: !tramiteId // Marcar como temporal si no hay tramite_id
        });

        this.actualizarUI(archivoKey, validacion);
        
        // Solo guardar en BD si hay tramite_id
        if (validacion.valido && tramiteId) {
            await this.guardarArchivoBD(file, catalogoId, tramiteId);
        } else if (validacion.valido) {
            this.mostrarNotificacion('Archivo validado. Se guardará al enviar el trámite.', 'success');
        }
        
        this.actualizarEstadoGeneral();
    }

    procesarDrop(e) {
        const files = Array.from(e.dataTransfer.files);
        const target = e.target.closest('input[type="file"]');
        
        if (target && files.length > 0) {
            target.files = e.dataTransfer.files;
            this.procesarArchivo(target);
        }
    }

    validarArchivo(file, input) {
        const errores = [];
        const accept = input.getAttribute('accept');
        const maxSize = parseInt(input.getAttribute('data-max-size')) || 5242880; // 5MB default

        if (accept && !this.validarTipo(file, accept)) {
            errores.push(`Tipo de archivo no válido. Se espera: ${accept}`);
        }

        if (file.size > maxSize) {
            errores.push(`Archivo demasiado grande. Máximo: ${this.formatearTamaño(maxSize)}`);
        }

        return {
            valido: errores.length === 0,
            errores: errores
        };
    }

    validarTipo(file, accept) {
        const tipos = accept.split(',').map(t => t.trim());
        return tipos.some(tipo => {
            if (tipo.startsWith('.')) {
                return file.name.toLowerCase().endsWith(tipo.toLowerCase());
            }
            return file.type === tipo;
        });
    }

    actualizarUI(archivoId, validacion) {
        const container = document.querySelector(`[data-archivo-id="${archivoId}"]`).closest('.archivo-container');
        if (!container) return;

        this.limpiarErrores(container);

        if (validacion.valido) {
            this.mostrarExito(container);
        } else {
            this.mostrarErrores(container, validacion.errores);
        }
    }

    mostrarExito(container) {
        container.classList.remove('archivo-error');
        container.classList.add('archivo-valido');
        
        const mensaje = container.querySelector('.archivo-mensaje') || this.crearMensaje(container);
        mensaje.className = 'archivo-mensaje text-green-600 text-sm mt-1';
        mensaje.textContent = '✓ Archivo cargado correctamente';
    }

    mostrarErrores(container, errores) {
        container.classList.remove('archivo-valido');
        container.classList.add('archivo-error');
        
        const mensaje = container.querySelector('.archivo-mensaje') || this.crearMensaje(container);
        mensaje.className = 'archivo-mensaje text-red-600 text-sm mt-1';
        mensaje.textContent = '✗ ' + errores.join(', ');
    }

    crearMensaje(container) {
        const mensaje = document.createElement('div');
        container.appendChild(mensaje);
        return mensaje;
    }

    limpiarErrores(container) {
        container.classList.remove('archivo-error', 'archivo-valido');
        const mensaje = container.querySelector('.archivo-mensaje');
        if (mensaje) mensaje.remove();
    }

    actualizarEstadoGeneral() {
        const estadisticas = this.obtenerEstadisticas();
        this.actualizarContador(estadisticas);
        this.actualizarProgreso(estadisticas);
        
        window.dispatchEvent(new CustomEvent('archivosActualizados', {
            detail: estadisticas
        }));
    }

    obtenerEstadisticas() {
        const total = this.archivos.size;
        const validos = Array.from(this.archivos.values()).filter(a => a.valido).length;
        const requeridos = document.querySelectorAll('input[type="file"][required]').length;
        const cargados = Array.from(this.archivos.values()).filter(a => a.file).length;

        return {
            total: total,
            validos: validos,
            cargados: cargados,
            requeridos: requeridos,
            completado: cargados >= requeridos && validos === cargados
        };
    }

    actualizarContador(stats) {
        const contador = document.getElementById('archivos-contador');
        if (contador) {
            contador.textContent = `${stats.cargados}/${stats.requeridos}`;
        }
    }

    actualizarProgreso(stats) {
        const progreso = document.getElementById('archivos-progreso');
        if (progreso) {
            const porcentaje = stats.requeridos > 0 ? (stats.cargados / stats.requeridos) * 100 : 0;
            progreso.style.width = `${porcentaje}%`;
        }

        const estado = document.getElementById('archivos-estado');
        if (estado) {
            if (stats.completado) {
                estado.className = 'text-green-600 font-medium';
                estado.textContent = '✓ Todos los archivos cargados';
            } else {
                estado.className = 'text-orange-600 font-medium';
                estado.textContent = `Faltan ${stats.requeridos - stats.cargados} archivos`;
            }
        }
    }

    observarCambios() {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList') {
                    const nuevosInputs = mutation.target.querySelectorAll('input[type="file"]');
                    nuevosInputs.forEach(input => {
                        if (!input.hasAttribute('data-observado')) {
                            input.setAttribute('data-observado', 'true');
                            this.configurarInput(input);
                        }
                    });
                }
            });
        });

        const container = document.querySelector('.archivos-container') || document.body;
        observer.observe(container, { childList: true, subtree: true });
    }

    configurarInput(input) {
        input.addEventListener('change', () => {
            this.procesarArchivo(input);
        });
    }

    formatearTamaño(bytes) {
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        if (bytes === 0) return '0 Bytes';
        const i = Math.floor(Math.log(bytes) / Math.log(1024));
        return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i];
    }

    obtenerArchivo(archivoId) {
        return this.archivos.get(archivoId);
    }

    async guardarArchivoBD(file, catalogoId, tramiteId) {
        const formData = new FormData();
        formData.append('archivo', file);
        formData.append('catalogo_archivo_id', catalogoId);
        formData.append('tramite_id', tramiteId);
        
        try {
            const response = await fetch('/archivos/guardar-individual', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: formData
            });

            const result = await response.json();
            
            if (response.ok && result.success) {
                this.mostrarNotificacion('Archivo guardado correctamente', 'success');
                
                // Actualizar el ID real del archivo
                const archivoKey = `${tramiteId}_${catalogoId}`;
                if (this.archivos.has(archivoKey)) {
                    this.archivos.get(archivoKey).archivoId = result.data.id;
                }
                
                return result.data;
            } else {
                console.error('Error al guardar archivo:', result.message || response.statusText);
                this.mostrarNotificacion('Error al guardar el archivo', 'error');
                return null;
            }
        } catch (error) {
            console.error('Error al conectar con el servidor:', error);
            this.mostrarNotificacion('Error de conexión con el servidor', 'error');
            return null;
        }
    }

    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    async cargarArchivosExistentes() {
        const tramiteId = this.obtenerTramiteId();
        if (!tramiteId) return;

        try {
            const response = await fetch(`/archivos/tramite/${tramiteId}`);
            const data = await response.json();

            if (data.success && data.data) {
                data.data.forEach(archivo => {
                    this.archivos.set(archivo.id.toString(), {
                        file: null,
                        valido: archivo.status === 'Aprobado',
                        errores: archivo.status === 'Rechazado' ? [archivo.comentario_revision || 'Archivo rechazado'] : [],
                        input: document.querySelector(`[data-archivo-id="${archivo.id}"]`),
                        existeEnBD: true,
                        status: archivo.status
                    });

                    this.mostrarEstadoExistente(archivo.id.toString(), archivo.status, archivo.comentario_revision);
                });

                this.actualizarEstadoGeneral();
            }
        } catch (error) {
            console.error('Error al cargar archivos existentes:', error);
        }
    }

    mostrarEstadoExistente(archivoId, status, comentario) {
        const input = document.querySelector(`[data-archivo-id="${archivoId}"]`);
        if (!input) return;

        const container = input.closest('.archivo-container');
        if (!container) return;

        this.limpiarErrores(container);

        const mensaje = container.querySelector('.archivo-mensaje') || this.crearMensaje(container);

        if (status === 'Aprobado') {
            container.classList.add('archivo-valido');
            mensaje.className = 'archivo-mensaje text-green-600 text-sm mt-1';
            mensaje.textContent = '✓ Archivo aprobado previamente';
        } else if (status === 'Rechazado') {
            container.classList.add('archivo-error');
            mensaje.className = 'archivo-mensaje text-red-600 text-sm mt-1';
            mensaje.textContent = '✗ ' + (comentario || 'Archivo rechazado');
        } else {
            mensaje.className = 'archivo-mensaje text-yellow-600 text-sm mt-1';
            mensaje.textContent = '⏳ Pendiente de revisión';
        }
    }

    obtenerTramiteId() {
        return document.querySelector('[data-tramite-id]')?.getAttribute('data-tramite-id') ||
               document.querySelector('meta[name="tramite-id"]')?.getAttribute('content') ||
               window.tramiteId;
    }

    mostrarNotificacion(mensaje, tipo) {
        const notif = document.createElement('div');
        notif.className = `fixed top-4 right-4 px-4 py-2 rounded-lg text-white z-50 ${tipo === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
        notif.textContent = mensaje;
        document.body.appendChild(notif);
        
        setTimeout(() => {
            notif.remove();
        }, 3000);
    }

    async procesarArchivosPendientes(tramiteId) {
        window.tramiteId = tramiteId;
        
        const archivosPendientes = Array.from(this.archivos.entries())
            .filter(([key, archivo]) => archivo.temporal && archivo.valido);
        
        for (const [key, archivo] of archivosPendientes) {
            if (archivo.file && archivo.catalogoId) {
                await this.guardarArchivoBD(archivo.file, archivo.catalogoId, tramiteId);
                
                // Actualizar el archivo en el Map
                archivo.temporal = false;
                archivo.tramiteId = tramiteId;
                
                // Actualizar la key
                this.archivos.delete(key);
                this.archivos.set(`${tramiteId}_${archivo.catalogoId}`, archivo);
            }
        }
    }

    esValidoTodo() {
        const stats = this.obtenerEstadisticas();
        return stats.completado;
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.archivosController = new ArchivosController();
});

// Funciones globales
window.validarArchivos = () => {
    return window.archivosController ? window.archivosController.esValidoTodo() : false;
};

window.obtenerEstadisticasArchivos = () => {
    return window.archivosController ? window.archivosController.obtenerEstadisticas() : null;
};

window.procesarArchivosPendientes = (tramiteId) => {
    return window.archivosController ? window.archivosController.procesarArchivosPendientes(tramiteId) : null;
}; 