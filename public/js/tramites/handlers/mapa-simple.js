// Mapa simple para mostrar coordenadas
class MapaSimple {
    constructor() {
        this.map = null;
        this.marker = null;
        this.init();
    }
    
    init() {
        this.createMapContainer();
        this.setupCoordinateInputs();
        setTimeout(() => this.initMap(), 200);
    }
    
    createMapContainer() {
        const domicilioContainer = document.querySelector('[data-seccion="domicilio"]');
        if (!domicilioContainer) return;
        
        const mapContainer = document.createElement('div');
        mapContainer.id = 'mapa-simple';
        mapContainer.className = 'w-full h-64 rounded-lg border border-gray-200 mb-4';
        mapContainer.style.cssText = 'position: relative; z-index: 1; min-height: 256px; height: 256px;';
        
        domicilioContainer.appendChild(mapContainer);
    }
    
    setupCoordinateInputs() {
        // Obtener campos de coordenadas
        this.latInput = document.querySelector('input[name="latitud"]');
        this.lngInput = document.querySelector('input[name="longitud"]');
        
        // Actualizar mapa cuando cambien las coordenadas
        if (this.latInput && this.lngInput) {
            this.latInput.addEventListener('change', () => this.updateMap());
            this.lngInput.addEventListener('change', () => this.updateMap());
        }
    }
    
    updateMap() {
        if (!this.latInput || !this.lngInput) return;
        
        const lat = parseFloat(this.latInput.value) || 19.4326;
        const lng = parseFloat(this.lngInput.value) || -99.1332;
        
        // Actualizar mapa si existe
        if (this.map && this.marker) {
            this.marker.setLatLng([lat, lng]);
            this.map.setView([lat, lng], this.map.getZoom());
            this.marker.bindPopup(`<b>Coordenadas:</b><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`);
        }
    }
    
    initMap() {
        // Cargar Leaflet CSS
        if (!document.querySelector('link[href*="leaflet"]')) {
            const leafletCSS = document.createElement('link');
            leafletCSS.rel = 'stylesheet';
            leafletCSS.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
            document.head.appendChild(leafletCSS);
        }
        
        // Cargar Leaflet JS
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
        
        // Obtener coordenadas
        let lat, lng;
        
        if (this.latInput && this.lngInput) {
            lat = parseFloat(this.latInput.value) || 19.4326;
            lng = parseFloat(this.lngInput.value) || -99.1332;
        } else {
            const domicilioContainer = document.querySelector('[data-seccion="domicilio"]');
            lat = parseFloat(domicilioContainer.getAttribute('data-lat')) || 19.4326;
            lng = parseFloat(domicilioContainer.getAttribute('data-lng')) || -99.1332;
        }
        
        // No mostrar mapa si son coordenadas por defecto
        if (lat === 19.4326 && lng === -99.1332) {
            return;
        }
        
        // Crear mapa
        this.map = L.map('mapa-simple').setView([lat, lng], 15);
        
        // Agregar capa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(this.map);
        
        // Agregar marcador
        this.marker = L.marker([lat, lng]).addTo(this.map);
        this.marker.bindPopup(`<b>Coordenadas:</b><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`);
        
        // Permitir clic en el mapa para actualizar coordenadas
        this.map.on('click', (e) => {
            const newLat = e.latlng.lat;
            const newLng = e.latlng.lng;
            
            // Actualizar campos
            if (this.latInput) this.latInput.value = newLat.toFixed(6);
            if (this.lngInput) this.lngInput.value = newLng.toFixed(6);
            
            // Actualizar marcador
            this.marker.setLatLng([newLat, newLng]);
            this.marker.bindPopup(`<b>Coordenadas:</b><br>Lat: ${newLat.toFixed(6)}<br>Lng: ${newLng.toFixed(6)}`);
        });
        
        // Redibujar mapa
        setTimeout(() => {
            if (this.map) {
                this.map.invalidateSize();
            }
        }, 100);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    const domicilioSection = document.querySelector('[data-seccion="domicilio"]');
    if (domicilioSection) {
        new MapaSimple();
    }
}); 