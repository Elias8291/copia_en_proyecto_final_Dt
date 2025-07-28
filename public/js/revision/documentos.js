function toggleDocumentComment(documentoId) {
    const form = document.getElementById(`comment-form-${documentoId}`);
    if (form.classList.contains('hidden')) {
        form.classList.remove('hidden');
        const textarea = form.querySelector('textarea');
        if (!textarea.value || textarea.value.trim() === '') {
            textarea.value = 'Observación de revisión digital: ';
        }
        textarea.focus();
        textarea.setSelectionRange(textarea.value.length, textarea.value.length);
        protegerTextoPreestablecidoDocumento(textarea);
    } else {
        form.classList.add('hidden');
        form.querySelector('textarea').value = '';
    }
}

function protegerTextoPreestablecidoDocumento(textarea) {
    const textoPreestablecido = 'Observación de revisión digital: ';
    
    if (!textarea.value.includes(textoPreestablecido)) {
        textarea.value = textoPreestablecido + textarea.value;
    }
    
    textarea.addEventListener('input', function() {
        if (!this.value.startsWith(textoPreestablecido)) {
            this.value = textoPreestablecido + this.value.substring(textoPreestablecido.length);
        }
    });
    
    textarea.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' || e.key === 'Delete') {
            const cursorPos = this.selectionStart;
            if (cursorPos <= textoPreestablecido.length) {
                e.preventDefault();
            }
        }
    });
}

document.addEventListener('click', function(e) {
    if (e.target.name === 'comentario' && e.target.tagName === 'TEXTAREA') {
        const textarea = e.target;
        if (!textarea.value || textarea.value.trim() === '') {
            textarea.value = 'Observación de revisión digital: ';
            setTimeout(() => {
                textarea.setSelectionRange(textarea.value.length, textarea.value.length);
            }, 10);
        }
        protegerTextoPreestablecidoDocumento(textarea);
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
                if (aprobado !== null) {
                    fetch(`/revision/documento/${documentoId}/estado`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': window.csrfToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ aprobado })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
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