// Handler para autocompletar domicilio por código postal

document.addEventListener("DOMContentLoaded", function () {
    const cpInput = document.getElementById("codigo_postal");
    const estadoSelect = document.getElementById("estado");
    const municipioSelect = document.getElementById("municipio");
    const asentamientoSelect = document.getElementById("asentamiento");
    const municipioIdInput = document.getElementById("municipio_id");

    if (!cpInput) return;

    let lastSearchedCP = "";
    let isLoading = false;

    function buscarPorCP(cp) {
        if (isLoading) return;
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
            });
    }

    cpInput.addEventListener("input", function () {
        const cp = cpInput.value.trim();
        if (/^\d{5}$/.test(cp) && cp !== lastSearchedCP) {
            lastSearchedCP = cp;
            buscarPorCP(cp);
        } else if (cp.length < 5) {
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
