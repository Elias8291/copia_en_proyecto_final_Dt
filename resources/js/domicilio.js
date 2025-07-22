
let map;
let marker;

function initMap() {
    const initialPosition = { lat: -34.397, lng: 150.644 };
    map = new google.maps.Map(document.getElementById("map"), {
        center: initialPosition,
        zoom: 8,
    });

    marker = new google.maps.Marker({
        position: initialPosition,
        map: map,
        draggable: true,
    });

    google.maps.event.addListener(marker, 'dragend', function(event) {
        document.getElementById("latitud").value = this.getPosition().lat();
        document.getElementById("longitud").value = this.getPosition().lng();
    });
}
