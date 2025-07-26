function toggleDocumentComment(documentoId) {
    const form = document.getElementById(`comment-form-${documentoId}`);
    if (form.classList.contains('hidden')) {
        form.classList.remove('hidden');
        const textarea = form.querySelector('textarea');
        // Si el textarea está vacío, agregar el texto inicial
        if (!textarea.value || textarea.value.trim() === '') {
            textarea.value = 'Observación de revisión presencial: ';
        }
        // Posicionar el cursor al final del texto
        textarea.focus();
        textarea.setSelectionRange(textarea.value.length, textarea.value.length);
    } else {
        form.classList.add('hidden');
        form.querySelector('textarea').value = '';
        // No hay opción comentar, así que no es necesario marcar checked
    }
}

// AJAX para guardar comentario y estado

// Evento para manejar el clic en textareas de comentarios
document.addEventListener('click', function(e) {
    if (e.target.name === 'comentario' && e.target.tagName === 'TEXTAREA') {
        const textarea = e.target;
        // Si el textarea está vacío o solo tiene espacios, agregar el texto inicial
        if (!textarea.value || textarea.value.trim() === '') {
            textarea.value = 'Observación de revisión presencial: ';
            // Posicionar el cursor al final
            setTimeout(() => {
                textarea.setSelectionRange(textarea.value.length, textarea.value.length);
            }, 10);
        }
    }
});

document.querySelectorAll('.documento-review-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const documentoId = this.getAttribute('data-documento-id');
        const comentario = this.querySelector('textarea[name="comentario"]').value;
        const decision = this.querySelector('input[name="decision_documento"]:checked').value;
        let aprobado = null;
        if (decision === 'aprobar') aprobado = true;
        else if (decision === 'rechazar') aprobado = false;
        // Enviar AJAX para comentario
        fetch(`/revision/documento/${documentoId}/comentario`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ comentario })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Cambiar estado tanto en aprobar como en rechazar
                if (aprobado !== null) {
                    fetch(`/revision/documento/${documentoId}/estado`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': window.csrfToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ aprobado })
                    })
                    .then(res2 => res2.json())
                    .then(data2 => {
                        if (data2.success) {
                            location.reload();
                        }
                    });
                } else {
                    location.reload();
                }
            }
        });
    });
}); 