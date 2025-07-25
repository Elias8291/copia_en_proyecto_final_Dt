// Script extraído de resources/views/revision/revisar-datos.blade.php
// NOTA: tramiteId, csrfToken y revisionSeccionComentarioRoute deben ser definidos globalmente en la vista o como data-attributes

function setEstadoVisual(seccion, aprobado) {
    const estadoSpan = document.getElementById(`estado_visual_${seccion}`);
    if (!estadoSpan) return;
    if (aprobado === true) {
        estadoSpan.className =
            'inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200';
        estadoSpan.innerHTML = `<div class="w-2 h-2 bg-emerald-500 rounded-full mr-1.5"></div>Aprobado`;
    } else if (aprobado === false) {
        estadoSpan.className =
            'inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-red-100 text-red-800 border border-red-200';
        estadoSpan.innerHTML = `<div class=\"w-2 h-2 bg-red-500 rounded-full mr-1.5\"></div>Rechazado`;
    } else {
        estadoSpan.className =
            'inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200';
        estadoSpan.innerHTML = `<div class=\"w-2 h-2 bg-amber-400 rounded-full mr-1.5\"></div>Pendiente`;
    }
}

function setComentarioBox(seccion, comentario, aprobado = null) {
    const box = document.getElementById(`comentario_box_${seccion}`);
    const icono = document.getElementById(`icono_comentario_${seccion}`);
    const texto = document.getElementById(`comentario_texto_${seccion}`);
    if (!box || !icono || !texto) return;
    if (comentario && comentario.trim() !== '') {
        texto.textContent = comentario;
        box.style.display = 'block';
        // Color y icono según aprobado
        if (aprobado === true) {
            box.className = 'px-4 py-3 bg-emerald-50 border-l-3 border-emerald-400';
            icono.innerHTML =
                `<svg class=\"w-4 h-4 text-emerald-500\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M5 13l4 4L19 7\"/></svg>`;
        } else if (aprobado === false) {
            box.className = 'px-4 py-3 bg-red-50 border-l-3 border-red-400';
            icono.innerHTML =
                `<svg class=\"w-4 h-4 text-red-500\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M6 18L18 6M6 6l12 12\"/></svg>`;
        } else {
            box.className = 'px-4 py-3 bg-blue-50 border-l-3 border-blue-400';
            icono.innerHTML =
                `<svg class=\"w-4 h-4 text-blue-500\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M8 10h.01M12 10h.01M16 10h.01\"/></svg>`;
        }
    } else {
        texto.textContent = '';
        icono.innerHTML = '';
        box.style.display = 'none';
    }
}

function setAprobado(seccion, valor) {
    const hidden = document.getElementById(`aprobado_${seccion}`);
    const btnAprobar = document.getElementById(`btn_aprobar_${seccion}`);
    const btnRechazar = document.getElementById(`btn_rechazar_${seccion}`);
    if (valor === true) {
        hidden.value = '1';
        // Botón aprobar activo
        btnAprobar.classList.remove('bg-white', 'border-emerald-300', 'text-emerald-700', 'hover:bg-emerald-50');
        btnAprobar.classList.add('bg-emerald-600', 'border-emerald-600', 'text-white', 'hover:bg-emerald-700');
        // Botón rechazar inactivo
        btnRechazar.classList.add('bg-white', 'border-red-300', 'text-red-700', 'hover:bg-red-50');
        btnRechazar.classList.remove('bg-red-600', 'border-red-600', 'text-white', 'hover:bg-red-700');
    } else if (valor === false) {
        hidden.value = '0';
        // Botón rechazar activo
        btnRechazar.classList.remove('bg-white', 'border-red-300', 'text-red-700', 'hover:bg-red-50');
        btnRechazar.classList.add('bg-red-600', 'border-red-600', 'text-white', 'hover:bg-red-700');
        // Botón aprobar inactivo
        btnAprobar.classList.add('bg-white', 'border-emerald-300', 'text-emerald-700', 'hover:bg-emerald-50');
        btnAprobar.classList.remove('bg-emerald-600', 'border-emerald-600', 'text-white', 'hover:bg-emerald-700');
    } else {
        hidden.value = '';
        // Ambos botones inactivos
        btnAprobar.classList.add('bg-white', 'border-emerald-300', 'text-emerald-700', 'hover:bg-emerald-50');
        btnAprobar.classList.remove('bg-emerald-600', 'border-emerald-600', 'text-white', 'hover:bg-emerald-700');
        btnRechazar.classList.add('bg-white', 'border-red-300', 'text-red-700', 'hover:bg-red-50');
        btnRechazar.classList.remove('bg-red-600', 'border-red-600', 'text-white', 'hover:bg-red-700');
    }
}

function guardarComentarioSeccion(seccion) {
    // tramiteId, csrfToken y revisionSeccionComentarioRoute deben estar definidos globalmente
    const textarea = document.querySelector(`textarea[data-seccion="${seccion}"]`);
    const comentario = textarea.value;
    const estadoDiv = document.getElementById(`estado_comentario_${seccion}`);
    const aprobadoHidden = document.getElementById(`aprobado_${seccion}`);
    const aprobado = aprobadoHidden.value === '' ? null : (aprobadoHidden.value === '1');
    fetch(window.revisionSeccionComentarioRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: JSON.stringify({
                tramite_id: window.tramiteId,
                seccion: seccion,
                comentario: comentario,
                aprobado: aprobado
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                estadoDiv.className = 'text-sm text-center font-medium text-emerald-600';
                estadoDiv.innerHTML = `
            <div class="inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Comentario guardado correctamente
            </div>
        `;
                setTimeout(() => {
                    estadoDiv.innerHTML = '';
                }, 3000);
                setEstadoVisual(seccion, aprobado);
                setComentarioBox(seccion, comentario, aprobado);
            } else {
                estadoDiv.className = 'text-sm text-center font-medium text-red-600';
                estadoDiv.innerHTML = `
            <div class="inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Error al guardar el comentario
            </div>
        `;
            }
        })
        .catch(() => {
            estadoDiv.className = 'text-sm text-center font-medium text-red-600';
            estadoDiv.innerHTML = `
        <div class="inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            Error de conexión
        </div>
    `;
        });
}
// Cargar comentarios y estado existentes al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // tramiteId debe estar definido globalmente
    const secciones = ['datos_generales', 'domicilio', 'actividades', 'documentos'];
    secciones.forEach(seccion => {
        fetch(`/revision/seccion/${window.tramiteId}/${seccion}`)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.data) {
                    const textarea = document.querySelector(
                        `textarea[data-seccion="${seccion}"]`);
                    if (textarea && data.data.comentario) textarea.value = data.data.comentario;
                    setComentarioBox(seccion, data.data.comentario, data.data.aprobado);
                    const aprobadoHidden = document.getElementById(`aprobado_${seccion}`);
                    if (aprobadoHidden) {
                        if (data.data.aprobado === true) {
                            aprobadoHidden.value = '1';
                            setAprobado(seccion, true);
                        } else if (data.data.aprobado === false) {
                            aprobadoHidden.value = '0';
                            setAprobado(seccion, false);
                        } else {
                            aprobadoHidden.value = '';
                            setAprobado(seccion, null);
                        }
                    }
                    setEstadoVisual(seccion, data.data.aprobado);
                }
            });
    });
}); 