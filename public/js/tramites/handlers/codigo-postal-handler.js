document.addEventListener("DOMContentLoaded", function () {
    console.log('Código Postal Handler: Inicializando...');
    
    const cpInput = document.getElementById("codigo_postal");
    const estadoSelect = document.getElementById("estado");
    const municipioSelect = document.getElementById("municipio");
    const asentamientoSelect = document.getElementById("asentamiento");
    const municipioIdInput = document.getElementById("municipio_id");

    if (!cpInput) {
        console.error('Código Postal Handler: Input de código postal no encontrado');
        return;
    }
    
    console.log('Código Postal Handler: Elementos encontrados correctamente');

    let lastSearchedCP = "";
    let isLoading = false;

    function buscarPorCP(cp) {
        console.log('Código Postal Handler: Buscando por CP:', cp);
        
        if (isLoading || !/^\d{5}$/.test(cp) || cp === lastSearchedCP) {
            console.log('Código Postal Handler: Búsqueda omitida (ya cargando, formato inválido o ya buscado)');
            return;
        }
        
        isLoading = true;
        estadoSelect.disabled = true;
        municipioSelect.disabled = true;
        asentamientoSelect.disabled = true;

        fetch("/api/ubicacion/codigo-postal", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN":
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") || "",
            },
            body: JSON.stringify({ codigo_postal: cp }),
        })
            .then((res) => res.json())
            .then((data) => {
                console.log('Código Postal Handler: Respuesta de la API:', data);
                if (data.success && data.data.length > 0) {
                    const estado = data.data[0];
                    estadoSelect.innerHTML = `<option value="${estado.estado_id}">${estado.estado}</option>`;
                    municipioSelect.innerHTML = `<option value="${estado.municipio}">${estado.municipio}</option>`;
                    municipioIdInput.value = estado.municipio_id;

                    asentamientoSelect.innerHTML =
                        '<option value="">Seleccione un asentamiento</option>';
                    data.data.forEach(function (item) {
                        asentamientoSelect.innerHTML += `<option value="${item.asentamiento}">${item.asentamiento}</option>`;
                    });

                    if (data.data.length === 1) {
                        asentamientoSelect.value = data.data[0].asentamiento;
                    } else {
                        asentamientoSelect.value = "";
                    }
                } else {
                    estadoSelect.innerHTML =
                        '<option value="">Seleccione un estado</option>';
                    municipioSelect.innerHTML =
                        '<option value="">Seleccione un municipio</option>';
                    municipioIdInput.value = "";
                    asentamientoSelect.innerHTML =
                        '<option value="">Seleccione un asentamiento</option>';
                }
            })
            .catch(() => {
                estadoSelect.innerHTML =
                    '<option value="">Seleccione un estado</option>';
                municipioSelect.innerHTML =
                    '<option value="">Seleccione un municipio</option>';
                municipioIdInput.value = "";
                asentamientoSelect.innerHTML =
                    '<option value="">Seleccione un asentamiento</option>';
            })
            .finally(() => {
                estadoSelect.disabled = false;
                municipioSelect.disabled = false;
                asentamientoSelect.disabled = false;
                isLoading = false;
                lastSearchedCP = cp;
            });
    }

    // Check for pre-filled value on page load
    const initialCP = cpInput.value.trim();
    if (/^\d{5}$/.test(initialCP)) {
        buscarPorCP(initialCP);
    }

    // Event listener for input changes
    cpInput.addEventListener("input", function () {
        const cp = cpInput.value.trim();
        if (/^\d{5}$/.test(cp)) {
            buscarPorCP(cp);
        } else {
            lastSearchedCP = "";
            estadoSelect.innerHTML =
                '<option value="">Seleccione un estado</option>';
            municipioSelect.innerHTML =
                '<option value="">Seleccione un municipio</option>';
            municipioIdInput.value = "";
            asentamientoSelect.innerHTML =
                '<option value="">Seleccione un asentamiento</option>';
        }
    });
});