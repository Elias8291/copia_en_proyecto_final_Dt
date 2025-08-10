class CodigoPostalAutocomplete {
    constructor(options = {}) {
        this.cpInput = options.codigoPostalInput;
        this.estadoSelect = options.estadoSelect;
        this.municipioInput = options.municipioInput;
        this.asentamientoInput = options.asentamientoInput;
        this.loadingElement = options.loadingElement;
        this.timeoutId = null;
        this.init();
    }

    init() {
        if (!this.cpInput) return;
        this.cpInput.addEventListener('input', () => this.handleInput());
    }

    handleInput() {
        const cp = this.cpInput.value.trim();
        clearTimeout(this.timeoutId);
        
        if (cp.length !== 5) {
            this.hideLoading();
            this.limpiarCampos();
            return;
        }

        this.timeoutId = setTimeout(() => this.buscarCP(cp), 500);
    }

    async buscarCP(cp) {
        this.showLoading();

        try {
            const response = await fetch('/api/ubicacion/buscar-codigo-postal', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ codigo_postal: cp })
            });

            const data = await response.json();
            this.hideLoading();

            if (data.success && data.data.length > 0) {
                this.llenarCampos(data.data[0]);
            } else {
                this.limpiarCampos();
            }
        } catch (error) {
            this.hideLoading();
            this.limpiarCampos();
        }
    }

    llenarCampos(datos) {
        if (this.municipioInput) this.municipioInput.value = datos.municipio;
        if (this.asentamientoInput) this.asentamientoInput.value = datos.asentamiento;
        
        if (this.estadoSelect) {
            const estadoOption = [...this.estadoSelect.options]
                .find(o => o.text.toLowerCase() === datos.estado.toLowerCase());
            if (estadoOption) this.estadoSelect.value = estadoOption.value;
        }
    }

    showLoading() {
        if (this.loadingElement) this.loadingElement.classList.remove('hidden');
    }

    hideLoading() {
        if (this.loadingElement) this.loadingElement.classList.add('hidden');
    }

    limpiarCampos() {
        if (this.municipioInput) this.municipioInput.value = '';
        if (this.asentamientoInput) this.asentamientoInput.value = '';
    }
}

function crearAutocompletadoCP(config) {
    return new CodigoPostalAutocomplete(config);
}
