function toggleDocumentComment(documentoId) {
    const form = document.getElementById(`comment-form-${documentoId}`);
    if (form.classList.contains('hidden')) {
        form.classList.remove('hidden');
    } else {
        form.classList.add('hidden');
        form.querySelector('textarea').value = '';
        // No hay opción comentar, así que no es necesario marcar checked
    }
}

// AJAX para guardar comentario y estado

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