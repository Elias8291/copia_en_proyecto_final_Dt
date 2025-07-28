/**
 * Mapa de domicilio - Componente simple
 * 
 * Nota: Usamos google.maps.Marker (tradicional) en lugar de AdvancedMarkerElement
 * porque es más estable y compatible. El warning de deprecación es solo informativo
 * y no afecta la funcionalidad. Google Maps.Marker seguirá funcionando por al menos
 * 12 meses más según Google.
 */
class DomicilioMap {
    constructor(elementId, options = {}) {
        this.elementId = elementId;
        this.options = {
            lat: 19.4326, // CDMX por defecto
            lng: -99.1332,
            zoom: 15,
            direccion: null,
            ...options
        };
        this.map = null;
        this.marker = null;
    }

    async init() {
        const mapElement = document.getElementById(this.elementId);
        if (!mapElement) {
            console.warn(`Map element with id '${this.elementId}' not found`);
            return;
        }

        // Crear mapa con diseño mejorado
        this.map = new google.maps.Map(mapElement, {
            center: { 
                lat: this.options.lat, 
                lng: this.options.lng 
            },
            zoom: this.options.zoom,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [
                // Estilo personalizado para mejor visualización
                {
                    featureType: "all",
                    elementType: "geometry",
                    stylers: [{ color: "#f8f9fa" }]
                },
                {
                    featureType: "road",
                    elementType: "geometry",
                    stylers: [{ color: "#ffffff" }]
                },
                {
                    featureType: "road",
                    elementType: "geometry.stroke",
                    stylers: [{ color: "#e0e0e0", weight: 1 }]
                },
                {
                    featureType: "road",
                    elementType: "labels.text.fill",
                    stylers: [{ color: "#666666", weight: 300 }]
                },
                {
                    featureType: "road",
                    elementType: "labels.text.stroke",
                    stylers: [{ color: "#ffffff", weight: 1 }]
                },
                {
                    featureType: "road.highway",
                    elementType: "geometry",
                    stylers: [{ color: "#fff3cd" }]
                },
                {
                    featureType: "road.highway",
                    elementType: "geometry.stroke",
                    stylers: [{ color: "#ffc107", weight: 2 }]
                },
                {
                    featureType: "water",
                    elementType: "geometry",
                    stylers: [{ color: "#e3f2fd" }]
                },
                {
                    featureType: "poi",
                    elementType: "labels",
                    stylers: [{ visibility: "on" }]
                },
                {
                    featureType: "transit",
                    elementType: "labels",
                    stylers: [{ visibility: "on" }]
                },
                {
                    featureType: "landscape",
                    elementType: "geometry",
                    stylers: [{ color: "#f5f5f5" }]
                }
            ],
            mapTypeControl: true,
            streetViewControl: true,
            fullscreenControl: true,
            zoomControl: true,
            scaleControl: true
        });

        // Si hay dirección, hacer geocoding
        if (this.options.direccion && this.options.direccion.trim() !== '') {
            try {
                console.log('Intentando geocodificar:', this.options.direccion);
                const geocoder = new google.maps.Geocoder();
                const result = await geocoder.geocode({ 
                    address: this.options.direccion,
                    region: 'mx' // Priorizar resultados de México
                });
                
                if (result.results.length > 0) {
                    const location = result.results[0].geometry.location;
                    this.options.lat = location.lat();
                    this.options.lng = location.lng();
                    
                    console.log('Ubicación encontrada:', location.lat(), location.lng());
                    
                    // Centrar mapa en la ubicación encontrada
                    this.map.setCenter(location);
                    
                    // Ajustar zoom para mostrar calles cercanas
                    this.map.setZoom(17); // Zoom más cercano para ver calles
                    
                    // Agregar información de calles cercanas
                    this.addStreetInfo(location);
                    
                    // Cargar calles cercanas
                    this.loadNearbyStreets(location);
                } else {
                    console.warn('No se encontró la dirección:', this.options.direccion);
                }
            } catch (error) {
                console.warn('Error al geocodificar la dirección:', this.options.direccion, error);
            }
        } else {
            console.log('No hay dirección para geocodificar, usando coordenadas por defecto');
        }

        // Agregar marcador tradicional (más estable)
        // Suprimir warning de deprecación temporalmente
        const originalWarn = console.warn;
        console.warn = function(...args) {
            if (args[0] && typeof args[0] === 'string' && args[0].includes('google.maps.Marker is deprecated')) {
                return; // Suprimir este warning específico
            }
            originalWarn.apply(console, args);
        };
        
        // Crear marcador personalizado más elegante
        this.marker = new google.maps.Marker({
            position: { 
                lat: this.options.lat, 
                lng: this.options.lng 
            },
            map: this.map,
            title: this.options.direccion || "Ubicación del domicilio registrada",
            animation: google.maps.Animation.DROP,
            icon: {
                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                    <svg width="32" height="40" viewBox="0 0 32 40" xmlns="http://www.w3.org/2000/svg">
                        <!-- Sombra -->
                        <ellipse cx="16" cy="38" rx="6" ry="2" fill="rgba(0,0,0,0.3)"/>
                        
                        <!-- Pin principal con gradiente -->
                        <defs>
                            <linearGradient id="pinGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" style="stop-color:#9d2449;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#7a1c37;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        
                        <!-- Pin principal -->
                        <path d="M16 2 C24 2 30 8 30 16 C30 24 16 38 16 38 C16 38 2 24 2 16 C2 8 8 2 16 2 Z" 
                              fill="url(#pinGradient)" 
                              stroke="#ffffff" 
                              stroke-width="2" 
                              filter="drop-shadow(0 2px 4px rgba(0,0,0,0.3))"/>
                        
                        <!-- Círculo interno -->
                        <circle cx="16" cy="14" r="6" fill="#ffffff" opacity="0.9"/>
                        
                        <!-- Punto central -->
                        <circle cx="16" cy="14" r="3" fill="#9d2449"/>
                        
                        <!-- Brillo en la parte superior -->
                        <circle cx="14" cy="12" r="2" fill="#ffffff" opacity="0.7"/>
                    </svg>
                `),
                scaledSize: new google.maps.Size(32, 40),
                anchor: new google.maps.Point(16, 40)
            }
        });

        // Crear info window nativo de Google Maps
        this.infoWindow = new google.maps.InfoWindow({
            content: this.options.direccion || "Ubicación del domicilio registrada"
        });
        
        // Agregar evento de clic al marcador para mostrar/ocultar info window
        this.marker.addListener('click', () => {
            if (this.infoWindow.getMap()) {
                // Si ya está abierto, cerrarlo
                this.infoWindow.close();
            } else {
                // Si está cerrado, abrirlo
                this.infoWindow.open(this.map, this.marker);
            }
        });
        
        // Cerrar info window al hacer clic en el mapa
        this.map.addListener('click', () => {
            this.infoWindow.close();
        });
        
        // Restaurar console.warn original
        console.warn = originalWarn;

        return this;
    }
    
    async loadNearbyStreets(location) {
        const callesContainer = document.getElementById('calles-cercanas');
        if (!callesContainer) return;
        
        try {
            // Mostrar loading
            callesContainer.innerHTML = `
                <div class="text-center text-gray-500 py-8">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 animate-spin">
                        <i class="fas fa-spinner text-blue-500"></i>
                    </div>
                    <p class="text-sm">Buscando calles cercanas...</p>
                </div>
            `;
            
            // Generar puntos cercanos para buscar calles
            const nearbyPoints = this.generateNearbyPoints(location, 500); // 500 metros
            const streetNames = new Set();
            
            // Buscar calles en puntos cercanos usando Geocoding
            for (let i = 0; i < nearbyPoints.length && streetNames.size < 10; i++) {
                try {
                    const point = nearbyPoints[i];
                    const geocoder = new google.maps.Geocoder();
                    const result = await geocoder.geocode({
                        location: point,
                        region: 'mx'
                    });
                    
                    if (result.results.length > 0) {
                        const addressComponents = result.results[0].address_components;
                        const routeComponent = addressComponents.find(component => 
                            component.types.includes('route')
                        );
                        
                        if (routeComponent && routeComponent.long_name) {
                            streetNames.add(routeComponent.long_name);
                        }
                    }
                    
                    // Pequeña pausa para evitar límites de API
                    await new Promise(resolve => setTimeout(resolve, 100));
                    
                } catch (error) {
                    console.warn('Error al buscar calle en punto:', point, error);
                }
            }
            
            // Convertir Set a Array y mostrar
            const streets = Array.from(streetNames).map((name, index) => ({
                name: name,
                index: index
            }));
            
            this.displayNearbyStreets(streets, callesContainer);
            
        } catch (error) {
            console.warn('Error al cargar calles cercanas:', error);
            this.displayNearbyStreets([], callesContainer);
        }
    }
    
    generateNearbyPoints(center, radius) {
        const points = [];
        const lat = center.lat();
        const lng = center.lng();
        
        // Generar puntos en círculo alrededor del centro
        for (let angle = 0; angle < 360; angle += 45) {
            for (let distance = 100; distance <= radius; distance += 100) {
                const rad = angle * Math.PI / 180;
                const latOffset = (distance / 111320) * Math.cos(rad);
                const lngOffset = (distance / (111320 * Math.cos(lat * Math.PI / 180))) * Math.sin(rad);
                
                points.push({
                    lat: lat + latOffset,
                    lng: lng + lngOffset
                });
            }
        }
        
        return points;
    }
    
    displayNearbyStreets(streets, container) {
        if (streets.length === 0) {
            container.innerHTML = `
                <div class="text-center text-gray-500 py-8">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                    </div>
                    <p class="text-sm">No se encontraron calles cercanas</p>
                    <p class="text-xs text-gray-400 mt-1">Intenta hacer zoom en el mapa</p>
                </div>
            `;
            return;
        }
        
        // Mostrar las calles encontradas
        const streetsHTML = streets.map((street, index) => {
            return `
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg mb-2 hover:bg-gray-100 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-xs font-bold text-blue-600">${index + 1}</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            ${street.name}
                        </p>
                        <p class="text-xs text-gray-500">
                            Calle cercana
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-road text-blue-400 text-xs"></i>
                    </div>
                </div>
            `;
        }).join('');
        
        container.innerHTML = `
            <div class="space-y-2">
                <div class="flex items-center justify-between mb-3">
                    <h5 class="text-sm font-semibold text-gray-700">Calles encontradas</h5>
                    <span class="text-xs text-gray-500">${streets.length} resultados</span>
                </div>
                ${streetsHTML}
            </div>
        `;
    }

    setLocation(lat, lng) {
        if (!this.map || !this.marker) return;

        const position = { lat: parseFloat(lat), lng: parseFloat(lng) };
        
        this.map.setCenter(position);
        this.marker.setPosition(position);
        
        this.options.lat = lat;
        this.options.lng = lng;
    }

    setZoom(zoom) {
        if (!this.map) return;
        this.map.setZoom(zoom);
    }
    
    addStreetInfo(location) {
        // Agregar información de calles cercanas
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div style="padding: 10px; max-width: 250px;">
                    <h3 style="margin: 0 0 8px 0; color: #9d2449; font-size: 14px; font-weight: bold;">
                        📍 Ubicación del Domicilio
                    </h3>
                    <p style="margin: 0 0 8px 0; font-size: 12px; color: #666;">
                        ${this.options.direccion || 'Ubicación registrada'}
                    </p>
                    <div style="font-size: 11px; color: #888;">
                        <p style="margin: 2px 0;">🗺️ Calles cercanas visibles</p>
                        <p style="margin: 2px 0;">📍 Marcador de ubicación</p>
                        <p style="margin: 2px 0;">🔍 Usa el zoom para más detalle</p>
                    </div>
                </div>
            `
        });
        
        // Mostrar info window después de un breve delay
        setTimeout(() => {
            infoWindow.open(this.map, this.marker);
        }, 1000);
        
        // Cerrar info window al hacer clic en el mapa
        this.map.addListener('click', () => {
            infoWindow.close();
        });
    }


}

// Función global para inicializar el mapa
window.initDomicilioMap = async function(elementId, lat, lng, direccion = null) {
    const map = new DomicilioMap(elementId, {
        lat: parseFloat(lat) || 19.4326,
        lng: parseFloat(lng) || -99.1332,
        direccion: direccion
    });
    
    return await map.init();
}; 