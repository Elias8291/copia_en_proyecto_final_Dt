@props([
    'lat' => null,
    'lng' => null,
    'editable' => false,
    'height' => '300px',
])

<div class="relative space-y-2">
    <div id="mapa" class="rounded border" style="height: {{ $height }};"></div>

    @if($editable)
        <button type="button" id="ubicacion-btn" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
            Obtener ubicación actual
        </button>
        <div id="coordenadas-display" class="text-sm text-gray-700"></div>
    @endif
</div>

@once
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
@endonce

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editable = @json($editable);
    let lat = {{ $lat ?? 19.4326 }};
    let lng = {{ $lng ?? -99.1332 }};
    const hasCoords = {{ $lat ? 'true' : 'false' }} && {{ $lng ? 'true' : 'false' }};

    const map = L.map('mapa').setView([lat, lng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker = hasCoords ? L.marker([lat, lng], { draggable: editable }).addTo(map) : null;

    function updateCoords(lat, lng) {
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { draggable: editable }).addTo(map);
        }
        
        const latInput = document.getElementById('latitud-manual');
        const lngInput = document.getElementById('longitud-manual');
        const display = document.getElementById('coordenadas-display');
        
        if (latInput) latInput.value = lat.toFixed(6);
        if (lngInput) lngInput.value = lng.toFixed(6);
        if (display) display.textContent = `Coordenadas: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    }

    if (editable) {
        map.on('click', e => updateCoords(e.latlng.lat, e.latlng.lng));
        
        if (marker) marker.on('dragend', e => updateCoords(e.target.getLatLng().lat, e.target.getLatLng().lng));
        
        ['latitud-manual', 'longitud-manual'].forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                input.addEventListener('change', () => {
                    const latVal = document.getElementById('latitud-manual')?.value;
                    const lngVal = document.getElementById('longitud-manual')?.value;
                    if (latVal && lngVal) {
                        const newLat = parseFloat(latVal);
                        const newLng = parseFloat(lngVal);
                        if (!isNaN(newLat) && !isNaN(newLng)) {
                            updateCoords(newLat, newLng);
                            map.setView([newLat, newLng], 13);
                        }
                    }
                });
            }
        });
        
        document.getElementById('ubicacion-btn')?.addEventListener('click', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(pos => {
                    updateCoords(pos.coords.latitude, pos.coords.longitude);
                    map.setView([pos.coords.latitude, pos.coords.longitude], 13);
                }, () => alert('No se pudo obtener la ubicación'));
            } else {
                alert('Geolocalización no soportada');
            }
        });
    }
});
</script>
