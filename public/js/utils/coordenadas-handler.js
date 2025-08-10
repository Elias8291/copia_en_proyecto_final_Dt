/**
 * Manejador básico de coordenadas
 */
class CoordenadasHandler {
    constructor(latInput, lngInput, displayElement, linkContainer) {
        this.latInput = latInput;
        this.lngInput = lngInput;
        this.displayElement = displayElement;
        this.linkContainer = linkContainer;
        
        this.init();
    }

    init() {
        // Escuchar cambios en los inputs
        if (this.latInput) {
            this.latInput.addEventListener('input', () => this.actualizarLink());
        }
        
        if (this.lngInput) {
            this.lngInput.addEventListener('input', () => this.actualizarLink());
        }

        this.actualizarLink();
    }

    actualizarLink() {
        const lat = this.latInput?.value;
        const lng = this.lngInput?.value;
        
        if (lat && lng && !isNaN(lat) && !isNaN(lng)) {
            this.mostrarLink(lat, lng);
            this.mostrarCoordenadas(lat, lng);
        } else {
            this.ocultarLink();
        }
    }

    mostrarLink(lat, lng) {
        if (!this.linkContainer) return;
        
        const url = `https://www.google.com/maps?q=${lat},${lng}`;
        
        this.linkContainer.innerHTML = `
            <a href="${url}" target="_blank" class="btn btn-primary">
                Ver en Google Maps
            </a>
        `;
        this.linkContainer.style.display = 'block';
    }

    ocultarLink() {
        if (this.linkContainer) {
            this.linkContainer.style.display = 'none';
        }
        if (this.displayElement) {
            this.displayElement.textContent = 'Ingresa las coordenadas';
        }
    }

    mostrarCoordenadas(lat, lng) {
        if (this.displayElement) {
            this.displayElement.textContent = `Coordenadas: ${lat}, ${lng}`;
        }
    }

    actualizarCoordenadas(lat, lng) {
        if (this.latInput) this.latInput.value = lat;
        if (this.lngInput) this.lngInput.value = lng;
        this.actualizarLink();
    }
}

// Función para crear el handler
function crearCoordenadasHandler(config) {
    return new CoordenadasHandler(
        config.latInput,
        config.lngInput,
        config.displayElement,
        config.linkContainer
    );
}
