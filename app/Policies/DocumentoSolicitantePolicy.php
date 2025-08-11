<?php

namespace App\Policies;

use App\Models\DocumentoSolicitante;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentoSolicitantePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the documento.
     */
    public function view(User $user, DocumentoSolicitante $documento)
    {
        // Permitir que todos los usuarios autenticados vean documentos
        return true;
    }

    /**
     * Determina si el usuario puede ver el documento.
     *
     * @return bool
     */
    public function ver(User $user, DocumentoSolicitante $documentoSolicitante)
    {
        // Permitir que todos los usuarios autenticados vean documentos
        return true;
    }
}
