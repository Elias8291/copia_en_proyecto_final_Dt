<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ArchivosFormatter
{
    /**
     * Normaliza una colección de archivos para cotejo en vistas o APIs
     */
    public function prepararParaCotejo(Collection $archivos): array
    {
        return $archivos->map(function ($archivo) {
            return [
                'id' => $archivo->id,
                'nombre_original' => $archivo->nombre_original,
                'nombre_catalogo' => $archivo->catalogoArchivo ? $archivo->catalogoArchivo->nombre : null,
                'extension' => $archivo->extension,
                'tamaño' => $archivo->tamaño,
                'status' => $archivo->status,
                'comentario_revision' => $archivo->comentario_revision,
                'fecha_revision' => $archivo->fecha_revision,
                'revisor' => $archivo->revisor ? $archivo->revisor->name : null,
                'catalogo_archivo_id' => $archivo->catalogo_archivo_id,
                'existe_en_storage' => Storage::exists($archivo->ruta),
                'ruta' => $archivo->ruta
            ];
        })->values()->toArray();
    }
}


