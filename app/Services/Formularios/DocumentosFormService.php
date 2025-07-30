<?php

declare(strict_types=1);

namespace App\Services\Formularios;

use App\Models\Tramite;
use App\Models\Archivo;
use App\Models\CatalogoArchivo;

class DocumentosFormService
{
    /**
     * Procesa y guarda los documentos del formulario
     */
    public function procesar(Tramite $tramite, array $datos): void
    {
        if (!empty($datos['documentos'])) {
            $this->guardarDocumentos($tramite, $datos['documentos']);
        }
    }

    /**
     * Guarda los documentos
     */
    private function guardarDocumentos(Tramite $tramite, array $documentos): void
    {
        foreach ($documentos as $catalogoId => $archivo) {
            if ($archivo && $archivo->isValid()) {
                $catalogoArchivo = CatalogoArchivo::find($catalogoId);
                if ($catalogoArchivo) {
                    $this->guardarDocumento($tramite, $archivo, $catalogoArchivo);
                }
            }
        }
    }

    /**
     * Guarda un documento individual
     */
    private function guardarDocumento(Tramite $tramite, $archivo, CatalogoArchivo $catalogoArchivo): void
    {
        $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
        $ruta = $archivo->storeAs('documentos/' . $tramite->id, $nombreArchivo, 'public');

        Archivo::create([
            'id_tramite' => $tramite->id,
            'id_catalogo_archivo' => $catalogoArchivo->id,
            'nombre_original' => $archivo->getClientOriginalName(),
            'nombre_archivo' => $nombreArchivo,
            'ruta' => $ruta,
            'tipo_archivo' => $archivo->getClientMimeType(),
            'tamano' => $archivo->getSize(),
            'activo' => true,
        ]);
    }

    /**
     * Obtiene las reglas de validación para documentos
     */
    public function getValidationRules(): array
    {
        return [
            'documentos' => 'sometimes|array',
            'documentos.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', // 10MB max
        ];
    }

    /**
     * Obtiene los mensajes de error personalizados para documentos
     */
    public function getValidationMessages(): array
    {
        return [
            'documentos.array' => 'Los documentos deben ser enviados correctamente.',
            'documentos.*.file' => 'El archivo debe ser válido.',
            'documentos.*.mimes' => 'El archivo debe ser de tipo: pdf, jpg, jpeg, png, doc, docx.',
            'documentos.*.max' => 'El archivo no puede exceder 10MB.',
        ];
    }

    /**
     * Obtiene los nombres de atributos para documentos
     */
    public function getValidationAttributes(): array
    {
        return [
            'documentos' => 'documentos',
            'documentos.*' => 'documento',
        ];
    }

    /**
     * Valida los documentos (método legacy)
     */
    public function validar(array $datos): array
    {
        $errores = [];

        if (empty($datos['documentos'])) {
            $errores[] = 'Debe subir al menos un documento requerido';
        }

        if (!empty($datos['documentos'])) {
            foreach ($datos['documentos'] as $catalogoId => $archivo) {
                if ($archivo && !$archivo->isValid()) {
                    $errores[] = "Error al subir el documento con ID: {$catalogoId}";
                }
            }
        }

        return $errores;
    }

    /**
     * Obtener documentos de un trámite
     */
    public function obtenerDatos(Tramite $tramite): ?array
    {
        $archivos = $tramite->archivos;
        if ($archivos->isEmpty()) {
            return null;
        }

        return $archivos->map(function ($archivo) {
            return [
                'id' => $archivo->id,
                'nombre_original' => $archivo->nombre_original,
                'nombre_archivo' => $archivo->nombre_archivo,
                'ruta' => $archivo->ruta,
                'tipo_archivo' => $archivo->tipo_archivo,
                'tamano' => $archivo->tamano,
                'aprobado' => $archivo->aprobado,
                'observaciones' => $archivo->observaciones,
                'fecha_cotejo' => $archivo->fecha_cotejo,
                'catalogo_archivo' => $archivo->catalogoArchivo?->nombre ?? 'Documento',
            ];
        })->toArray();
    }

    /**
     * Verificar si tiene documentos completos
     */
    public function tienesDatosCompletos(Tramite $tramite): bool
    {
        return !$tramite->archivos->isEmpty();
    }
}