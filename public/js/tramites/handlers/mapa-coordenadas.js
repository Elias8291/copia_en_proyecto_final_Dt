// Handler simplificado para mapa con coordenadas

class MapaSimple {
    constructor() {
        this.map = null;
        this.marker = null;
        this.init();
    }
    
    init() {
        this.createMapContainer();
        setTimeout(() => this.initMap(), 200);
        this.setupVisibilityObserver();
        this.setupBotonUbicacion();
        this.setupBuscadorCoordenadas();
    }
    
    createMapContainer() {
        const domicilioContainer = document.querySelector('[data-seccion="domicilio"]');
        if (!domicilioContainer) return;
        
        // Verificar si está en modo de solo lectura
        const isReadOnly = domicilioContainer.querySelector('input[disabled]') !== null;
        
        // Verificar si hay coordenadas disponibles
        const latInput = document.getElementById('latitud');
        const lngInput = document.getElementById('longitud');
        const hasCoordinates = latInput && lngInput && latInput.value && lngInput.value;
        
        const mapContainer = document.createElement('div');
        mapContainer.id = 'mapa-simple';
        mapContainer.className = 'w-full h-64 rounded-lg border border-gray-200 mb-4';
        mapContainer.style.cssText = 'position: relative; z-index: 1; min-height: 256px; height: 256px;';
        
        const mapTitle = document.createElement('div');
        mapTitle.className = 'flex items-center space-x-2 mb-3';
        
        // Título diferente según el contexto
        if (isReadOnly && !hasCoordinates) {
            mapTitle.innerHTML = '<i class="fas fa-map-marker-alt text-gray-400"></i><span class="text-sm font-medium text-gray-500">Ubicación no disponible</span>';
        } else {
            mapTitle.innerHTML = '<i class="fas fa-map-marker-alt text-[#9d2449]"></i><span class="text-sm font-medium text-gray-700">Vista del Mapa</span>';
        }
        
        // Solo crear controles de coordenadas si no está en modo de solo lectura
        let coordenadasContainer = null;
        if (!isReadOnly) {
            coordenadasContainer = document.createElement('div');
            coordenadasContainer.id = 'coordenadas-container';
            coordenadasContainer.className = 'bg-gray-50 border border-gray-200 rounded-lg p-3 mb-3';
            coordenadasContainer.innerHTML = `
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-crosshairs text-[#9d2449]"></i>
                        <span class="text-sm font-medium text-gray-700">Coordenadas</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div id="indicador-domicilio-fiscal" class="hidden">
                            <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>En domicilio fiscal
                            </span>
                        </div>
                        <button id="btn-ubicacion-actual" class="flex items-center space-x-1 px-2 py-1 bg-[#9d2449] text-white text-xs rounded hover:bg-[#7a1c37] transition-colors">
                            <i class="fas fa-location-arrow"></i><span>Mi Ubicación</span>
                        </button>
                    </div>
                </div>
                
                <div class="mb-3 p-2 bg-white border border-gray-200 rounded">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-search text-[#9d2449] text-xs"></i>
                            <span class="text-xs font-medium text-gray-700">Ingresar coordenadas</span>
                        </div>
                        <button id="btn-limpiar-coordenadas" class="text-xs text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="text-xs text-gray-600 mb-2">Ingresa las coordenadas del lugar</div>
                    <div class="flex space-x-2">
                        <div class="flex-1">
                            <label class="block text-xs text-gray-600 mb-1">Latitud</label>
                            <input type="text" id="input-latitud" placeholder="Ej: 19.4326" class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:border-[#9d2449]">
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs text-gray-600 mb-1">Longitud</label>
                            <input type="text" id="input-longitud" placeholder="Ej: -99.1332" class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:border-[#9d2449]">
                        </div>
                        <div class="flex flex-col justify-end">
                            <button id="btn-buscar-coordenadas" class="px-3 py-1 bg-[#9d2449] text-white text-xs rounded hover:bg-[#7a1c37] transition-colors">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="flex space-x-4">
                    <div class="flex items-center space-x-1">
                        <span class="text-xs text-gray-500">Lat:</span>
                        <span id="latitud-display" class="text-xs font-mono text-gray-700">19.4326</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <span class="text-xs text-gray-500">Lng:</span>
                        <span id="longitud-display" class="text-xs font-mono text-gray-700">-99.1332</span>
                    </div>
                </div>
            `;
        } else if (hasCoordinates) {
            // En modo de solo lectura con coordenadas, mostrar solo las coordenadas
            coordenadasContainer = document.createElement('div');
            coordenadasContainer.id = 'coordenadas-container';
            coordenadasContainer.className = 'bg-gray-50 border border-gray-200 rounded-lg p-3 mb-3';
            coordenadasContainer.innerHTML = `
                <div class="flex items-center space-x-2 mb-3">
                    <i class="fas fa-crosshairs text-[#9d2449]"></i>
                    <span class="text-sm font-medium text-gray-700">Coordenadas del Domicilio</span>
                </div>
                <div class="flex space-x-4">
                    <div class="flex items-center space-x-1">
                        <span class="text-xs text-gray-500">Latitud:</span>
                        <span id="latitud-display" class="text-xs font-mono text-gray-700">19.4326</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <span class="text-xs text-gray-500">Longitud:</span>
                        <span id="longitud-display" class="text-xs font-mono text-gray-700">-99.1332</span>
                    </div>
                </div>
            `;
        }
        // Si está en modo de solo lectura sin coordenadas, no crear contenedor de coordenadas
        
        domicilioContainer.appendChild(mapTitle);
        if (coordenadasContainer) {
            domicilioContainer.appendChild(coordenadasContainer);
        }
        domicilioContainer.appendChild(mapContainer);
    }
    
    initMap() {
        if (!document.querySelector('link[href*="leaflet"]')) {
            const leafletCSS = document.createElement('link');
            leafletCSS.rel = 'stylesheet';
            leafletCSS.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
            document.head.appendChild(leafletCSS);
        }
        
        if (typeof L === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
            script.onload = () => this.createMap();
            document.head.appendChild(script);
        } else {
            this.createMap();
        }
    }
    
    createMap() {
        const mapContainer = document.getElementById('mapa-simple');
        if (!mapContainer) return;
        
        // Verificar si está en modo de solo lectura
        const domicilioContainer = document.querySelector('[data-seccion="domicilio"]');
        const isReadOnly = domicilioContainer && domicilioContainer.querySelector('input[disabled]') !== null;
        
        // Obtener coordenadas de diferentes fuentes
        let defaultLat = 19.4326, defaultLng = -99.1332;
        let hasCoordinates = false;
        
        // 1. Intentar obtener de atributos data del contenedor (más confiable)
        if (domicilioContainer) {
            const dataLat = domicilioContainer.getAttribute('data-lat');
            const dataLng = domicilioContainer.getAttribute('data-lng');
            if (dataLat && dataLng) {
                defaultLat = parseFloat(dataLat);
                defaultLng = parseFloat(dataLng);
                hasCoordinates = true;
                console.log('Coordenadas obtenidas de atributos data:', { lat: defaultLat, lng: defaultLng });
            }
        }
        
        // 2. Si no hay atributos data, intentar obtener de variables globales (pasadas desde el componente)
        if (!hasCoordinates && window.domicilioMapInstance && window.domicilioMapInstance.coordinates) {
            defaultLat = window.domicilioMapInstance.coordinates.lat;
            defaultLng = window.domicilioMapInstance.coordinates.lng;
            hasCoordinates = true;
            console.log('Coordenadas obtenidas de variables globales:', { lat: defaultLat, lng: defaultLng });
        }
        
        // 3. Intentar obtener de campos hidden del formulario (solo en modo editable)
        if (!hasCoordinates && !isReadOnly) {
            const latInput = document.getElementById('latitud');
            const lngInput = document.getElementById('longitud');
            
            if (latInput && lngInput && latInput.value && lngInput.value) {
                defaultLat = parseFloat(latInput.value);
                defaultLng = parseFloat(lngInput.value);
                hasCoordinates = true;
                console.log('Coordenadas obtenidas de campos hidden:', { lat: defaultLat, lng: defaultLng });
            }
        }
        
        console.log('Estado final de coordenadas:', { hasCoordinates, isReadOnly, lat: defaultLat, lng: defaultLng });
        
        // En modo de solo lectura, si no hay coordenadas, usar un centro genérico de México
        if (isReadOnly && !hasCoordinates) {
            defaultLat = 23.6345; // Centro aproximado de México
            defaultLng = -102.5528;
        }
        
        this.map = L.map('mapa-simple').setView([defaultLat, defaultLng], isReadOnly && !hasCoordinates ? 5 : 10);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(this.map);
        
        // Solo crear marcador si hay coordenadas o no está en modo de solo lectura
        if (hasCoordinates || !isReadOnly) {
            // Crear marcador con configuración según el modo
            this.marker = L.marker([defaultLat, defaultLng], { 
                draggable: !isReadOnly 
            }).addTo(this.map);
            
            // Solo agregar eventos si no está en modo de solo lectura
            if (!isReadOnly) {
                this.marker.on('dragend', (e) => {
                    const { lat, lng } = e.target.getLatLng();
                    this.updateCoordenadas(lat, lng);
                    this.verificarDomicilioFiscal(lat, lng);
                });
                
                this.map.on('click', (e) => {
                    const { lat, lng } = e.latlng;
                    this.marker.setLatLng([lat, lng]);
                    this.updateCoordenadas(lat, lng);
                    this.verificarDomicilioFiscal(lat, lng);
                });
                
                // Solo obtener ubicación si no hay coordenadas existentes
                if (!latInput?.value || !lngInput?.value) {
                    this.obtenerUbicacionDispositivo();
                }
            }
        } else {
            // En modo de solo lectura sin coordenadas, mostrar mensaje en el mapa
            const noCoordinatesDiv = document.createElement('div');
            noCoordinatesDiv.className = 'no-coordinates-message';
            noCoordinatesDiv.innerHTML = `
                <div style="text-align: center; padding: 20px; color: #666;">
                    <i class="fas fa-map-marker-alt" style="font-size: 2em; margin-bottom: 10px; color: #ccc;"></i>
                    <p>No hay coordenadas disponibles para este domicilio</p>
                </div>
            `;
            mapContainer.appendChild(noCoordinatesDiv);
        }
        
        setTimeout(() => {
            if (this.map) {
                this.map.invalidateSize();
            }
        }, 100);
        
        // Solo actualizar coordenadas si hay marcador
        if (this.marker) {
            this.updateCoordenadas(defaultLat, defaultLng);
        }
    }
    
    obtenerUbicacionDispositivo() {
        if (!navigator.geolocation) return;
        
        navigator.geolocation.getCurrentPosition(
            (posicion) => {
                const { latitude: lat, longitude: lng, accuracy: precision } = posicion.coords;
                this.actualizarMapaConUbicacion(lat, lng, precision);
            },
            (error) => {
                this.mostrarMensaje('Error al obtener ubicación', 'error');
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 }
        );
    }
    
    actualizarMapaConUbicacion(lat, lng, precision) {
        if (!this.map || !this.marker) return;
        
        this.marker.setLatLng([lat, lng]);
        this.map.setView([lat, lng], 15);
        this.updateCoordenadas(lat, lng);
        this.mostrarPrecisionUbicacion(precision);
        this.verificarDomicilioFiscal(lat, lng);
    }
    
    setupBotonUbicacion() {
        setTimeout(() => {
            const btnUbicacion = document.getElementById('btn-ubicacion-actual');
            if (btnUbicacion) {
                btnUbicacion.addEventListener('click', () => {
                    this.obtenerUbicacionDispositivo();
                    btnUbicacion.disabled = true;
                    btnUbicacion.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Obteniendo...</span>';
                    setTimeout(() => {
                        btnUbicacion.disabled = false;
                        btnUbicacion.innerHTML = '<i class="fas fa-location-arrow"></i><span>Mi Ubicación</span>';
                    }, 3000);
                });
            }
        }, 500);
    }
    
    setupBuscadorCoordenadas() {
        setTimeout(() => {
            const btnBuscar = document.getElementById('btn-buscar-coordenadas');
            const inputLat = document.getElementById('input-latitud');
            const inputLng = document.getElementById('input-longitud');
            const btnLimpiar = document.getElementById('btn-limpiar-coordenadas');
            
            if (btnBuscar && inputLat && inputLng) {
                btnBuscar.addEventListener('click', () => this.buscarPorCoordenadas());
                [inputLat, inputLng].forEach(input => {
                    input.addEventListener('keypress', (e) => {
                        if (e.key === 'Enter') this.buscarPorCoordenadas();
                    });
                });
                
                if (btnLimpiar) {
                    btnLimpiar.addEventListener('click', () => this.limpiarInputsCoordenadas());
                }
            }
        }, 500);
    }
    
    buscarPorCoordenadas() {
        const inputLat = document.getElementById('input-latitud');
        const inputLng = document.getElementById('input-longitud');
        const btnBuscar = document.getElementById('btn-buscar-coordenadas');
        
        if (!inputLat || !inputLng || !btnBuscar) return;
        
        const latStr = inputLat.value.trim();
        const lngStr = inputLng.value.trim();
        
        if (!latStr || !lngStr) {
            this.mostrarMensaje('Por favor ingresa ambas coordenadas', 'error');
            return;
        }
        
        const lat = parseFloat(latStr);
        const lng = parseFloat(lngStr);
        
        if (isNaN(lat) || isNaN(lng)) {
            this.mostrarMensaje('Las coordenadas deben ser números válidos', 'error');
            return;
        }
        
        if (lat < -90 || lat > 90) {
            this.mostrarMensaje('La latitud debe estar entre -90 y 90 grados', 'error');
            return;
        }
        
        if (lng < -180 || lng > 180) {
            this.mostrarMensaje('La longitud debe estar entre -180 y 180 grados', 'error');
            return;
        }
        
        btnBuscar.disabled = true;
        btnBuscar.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        this.actualizarMapaConCoordenadas(lat, lng);
        
        setTimeout(() => {
            btnBuscar.disabled = false;
            btnBuscar.innerHTML = '<i class="fas fa-search"></i>';
        }, 1000);
    }
    
    actualizarMapaConCoordenadas(lat, lng) {
        if (!this.map || !this.marker) return;
        
        this.marker.setLatLng([lat, lng]);
        this.map.setView([lat, lng], 15);
        this.updateCoordenadas(lat, lng);
        this.verificarDomicilioFiscal(lat, lng);
        
        const inputLat = document.getElementById('input-latitud');
        const inputLng = document.getElementById('input-longitud');
        if (inputLat) inputLat.value = '';
        if (inputLng) inputLng.value = '';
        
        this.mostrarMensaje('Ubicación actualizada correctamente', 'success');
    }
    
    updateCoordenadas(lat, lng) {
        const latDisplay = document.getElementById('latitud-display');
        const lngDisplay = document.getElementById('longitud-display');
        
        if (latDisplay && lngDisplay) {
            latDisplay.textContent = lat.toFixed(6);
            lngDisplay.textContent = lng.toFixed(6);
        }
        
        // Actualizar campos hidden del formulario
        const latInput = document.getElementById('latitud');
        const lngInput = document.getElementById('longitud');
        
        if (latInput && lngInput) {
            latInput.value = lat.toFixed(8);
            lngInput.value = lng.toFixed(8);
        }
    }
    
    getCoordenadasActuales() {
        return this.marker ? this.marker.getLatLng() : null;
    }
    
    verificarDomicilioFiscal(lat, lng) {
        const domicilioFiscal = this.obtenerDatosDomicilioFiscal();
        if (!domicilioFiscal) return;
        
        const distancia = this.calcularDistancia(lat, lng, domicilioFiscal.lat, domicilioFiscal.lng);
        const estaEnDomicilio = distancia <= 100;
        
        this.mostrarIndicadorDomicilioFiscal(estaEnDomicilio, distancia);
        this.mostrarConfirmacionDomicilioFiscal(estaEnDomicilio, distancia);
    }
    
    mostrarIndicadorDomicilioFiscal(estaEnDomicilio, distancia) {
        const indicador = document.getElementById('indicador-domicilio-fiscal');
        if (!indicador) return;
        
        if (estaEnDomicilio) {
            indicador.className = 'text-xs px-2 py-1 rounded-full bg-green-100 text-green-800';
            indicador.innerHTML = '<i class="fas fa-check-circle mr-1"></i>En domicilio fiscal';
            indicador.classList.remove('hidden');
        } else {
            indicador.className = 'text-xs px-2 py-1 rounded-full bg-yellow-100 text-yellow-800';
            indicador.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i>Fuera del domicilio fiscal';
            indicador.classList.remove('hidden');
        }
    }
    
    obtenerDatosDomicilioFiscal() {
        const inputLat = document.querySelector('input[name="latitud_domicilio"]');
        const inputLng = document.querySelector('input[name="longitud_domicilio"]');
        
        if (inputLat && inputLng && inputLat.value && inputLng.value) {
            return { lat: parseFloat(inputLat.value), lng: parseFloat(inputLng.value) };
        }
        
        const codigoPostal = document.getElementById('codigo_postal');
        return codigoPostal && codigoPostal.value ? this.obtenerCoordenadasPorCP(codigoPostal.value) : null;
    }
    
    obtenerCoordenadasPorCP(cp) {
        const coordenadasCP = {
            '01000': { lat: 19.4326, lng: -99.1332 },
            '44100': { lat: 20.6597, lng: -103.3496 },
            '64000': { lat: 25.6866, lng: -100.3161 },
            '72000': { lat: 19.0413, lng: -98.2062 }
        };
        return coordenadasCP[cp] || null;
    }
    
    calcularDistancia(lat1, lng1, lat2, lng2) {
        const R = 6371000;
        const dLat = this.toRadians(lat2 - lat1);
        const dLng = this.toRadians(lng2 - lng1);
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(this.toRadians(lat1)) * Math.cos(this.toRadians(lat2)) *
                  Math.sin(dLng/2) * Math.sin(dLng/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return Math.round(R * c);
    }
    
    toRadians(grados) {
        return grados * (Math.PI / 180);
    }
    

    

    

    

    

    

    

    
    mostrarConfirmacionDomicilioFiscal(estaEnDomicilio, distancia) {
        this.limpiarMensajes();
        
        const confirmacionElement = document.createElement('div');
        confirmacionElement.className = `confirmacion-domicilio-fiscal mt-2 p-2 rounded ${estaEnDomicilio ? 'bg-green-50 border border-green-200' : 'bg-yellow-50 border border-yellow-200'}`;
        
        confirmacionElement.innerHTML = `
            <div class="flex items-center space-x-2">
                <i class="fas ${estaEnDomicilio ? 'fa-check-circle text-green-600' : 'fa-exclamation-triangle text-yellow-600'}"></i>
                <div>
                    <div class="text-xs font-medium ${estaEnDomicilio ? 'text-green-800' : 'text-yellow-800'}">
                        ${estaEnDomicilio ? '¡Estás en el domicilio fiscal!' : 'No estás en el domicilio fiscal'}
                    </div>
                    <div class="text-xs ${estaEnDomicilio ? 'text-green-600' : 'text-yellow-600'}">Distancia: ${distancia} metros</div>
                    ${!estaEnDomicilio ? '<div class="text-xs text-yellow-600 mt-1"><button onclick="window.mapaSimple.obtenerUbicacionDispositivo()" class="text-[#9d2449] hover:underline">Usar mi ubicación actual</button></div>' : ''}
                </div>
            </div>
        `;
        
        const coordenadasContainer = document.getElementById('coordenadas-container');
        const buscador = coordenadasContainer?.querySelector('.mb-3.p-2');
        if (buscador) {
            buscador.parentNode.insertBefore(confirmacionElement, buscador.nextSibling);
        }
    }
    
    mostrarPrecisionUbicacion(precision) {
        this.limpiarMensajes();
        
        const precisionElement = document.createElement('div');
        precisionElement.className = 'precision-info text-xs text-green-600 mt-2';
        precisionElement.innerHTML = `<i class="fas fa-check-circle mr-1"></i>Ubicación actual (precisión: ${Math.round(precision)}m)`;
        
        const coordenadasContainer = document.getElementById('coordenadas-container');
        if (coordenadasContainer) {
            coordenadasContainer.appendChild(precisionElement);
        }
    }
    
    mostrarMensaje(mensaje, tipo = 'info') {
        this.limpiarMensajes();
        
        const mensajeElement = document.createElement('div');
        const clases = {
            'success': 'text-green-600',
            'error': 'text-red-600',
            'warning': 'text-yellow-600',
            'info': 'text-blue-600'
        };
        
        mensajeElement.className = `mensaje-coordenadas text-xs ${clases[tipo]} mt-2`;
        mensajeElement.innerHTML = `<i class="fas fa-${tipo === 'success' ? 'check-circle' : tipo === 'error' ? 'exclamation-circle' : 'info-circle'} mr-1"></i>${mensaje}`;
        
        const coordenadasContainer = document.getElementById('coordenadas-container');
        const buscador = coordenadasContainer?.querySelector('.mb-3.p-2');
        if (buscador) {
            buscador.parentNode.insertBefore(mensajeElement, buscador.nextSibling);
        }
        
        if (tipo === 'success') {
            setTimeout(() => this.limpiarMensajes(), 3000);
        }
    }
    
    limpiarMensajes() {
        ['error-coordenadas', 'exito-coordenadas', 'confirmacion-domicilio-fiscal', 'precision-info', 'mensaje-coordenadas'].forEach(className => {
            const element = document.querySelector(`.${className}`);
            if (element) element.remove();
        });
    }
    
    limpiarInputsCoordenadas() {
        const inputLat = document.getElementById('input-latitud');
        const inputLng = document.getElementById('input-longitud');
        if (inputLat) inputLat.value = '';
        if (inputLng) inputLng.value = '';
        this.limpiarMensajes();
    }
    
    setupVisibilityObserver() {
        const domicilioSection = document.querySelector('[data-seccion="domicilio"]');
        if (!domicilioSection) return;
        
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    const isVisible = !domicilioSection.classList.contains('hidden');
                    if (isVisible && this.map) {
                        setTimeout(() => {
                            this.map.invalidateSize();
                        }, 300);
                    }
                }
            });
        });
        
        observer.observe(domicilioSection, { attributes: true });
        
        document.addEventListener('click', (e) => {
            if (e.target.id === 'btn-siguiente' || e.target.id === 'btn-anterior') {
                setTimeout(() => {
                    if (this.map && !domicilioSection.classList.contains('hidden')) {
                        this.map.invalidateSize();
                    }
                }, 500);
            }
        });
    }
}

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    const domicilioSection = document.querySelector('[data-seccion="domicilio"]');
    if (domicilioSection) {
        window.mapaSimple = new MapaSimple();
    }
});

// Funciones globales
window.MapaSimple = MapaSimple;
window.getCoordenadasMapa = () => window.mapaSimple?.getCoordenadasActuales();
window.actualizarCoordenadasDesdeFormulario = (lat, lng) => {
    if (window.mapaSimple?.map && window.mapaSimple?.marker) {
        window.mapaSimple.marker.setLatLng([lat, lng]);
        window.mapaSimple.map.setView([lat, lng], 10);
        window.mapaSimple.updateCoordenadas(lat, lng);
    }
};
window.obtenerUbicacionDispositivo = () => {
    if (window.mapaSimple) {
        window.mapaSimple.obtenerUbicacionDispositivo();
    }
};
window.buscarPorCoordenadas = (lat, lng) => {
    if (window.mapaSimple) {
        window.mapaSimple.actualizarMapaConCoordenadas(lat, lng);
    }
};
window.verificarDomicilioFiscal = () => {
    if (window.mapaSimple) {
        const coordenadas = window.mapaSimple.getCoordenadasActuales();
        if (coordenadas) {
            window.mapaSimple.verificarDomicilioFiscal(coordenadas.lat, coordenadas.lng);
        }
    }
};

 