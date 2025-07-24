<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tramite;
use App\Models\Archivo;
use App\Models\CatalogoArchivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DocumentosService
{
    /**
     * Guarda los documentos enviados en el formulario
     */
    public function guardar(Tramite $tramite, Request $request): void
    {
        $archivos = $request->allFiles();
        
        if (empty($archivos['documentos'])) {
            Log::info('No se enviaron documentos para el trámite', ['tramite_id' => $tramite->id]);
            return;
        }

        Log::info('Procesando documentos', [
            'tramite_id' => $tramite->id,
            'documentos_count' => count($archivos['documentos'])
        ]);

        foreach ($archivos['documentos'] as $catalogoId => $archivo) {
            if (is_array($archivo)) {
                foreach ($archivo as $index => $archivoIndividual) {
                    $this->procesarArchivoIndividual($tramite, $catalogoId, $archivoIndividual, $index);
                }
            } else {
                $this->procesarArchivoIndividual($tramite, $catalogoId, $archivo);
            }
        }
    }

    /**
     * Procesa un archivo individual
     */
    private function procesarArchivoIndividual(Tramite $tramite, string $catalogoId, $archivo, ?int $index = null): void
    {
        if (!$archivo || !method_exists($archivo, 'isValid') || !$archivo->isValid()) {
            Log::warning('Archivo inválido', ['tramite_id' => $tramite->id, 'catalogo_id' => $catalogoId]);
            return;
        }

        try {
            $nombreCarpeta = 'documentos/' . $tramite->id;
            if ($index !== null) {
                $nombreCarpeta .= '/' . $catalogoId . '_' . $index;
            }

            $rutaArchivo = $archivo->store($nombreCarpeta, 'public');

            Archivo::create([
                'tramite_id' => $tramite->id,
                'idCatalogoArchivo' => (int) $catalogoId,
                'nombre_original' => $archivo->getClientOriginalName(),
                'ruta_archivo' => $rutaArchivo,
                'aprobado' => false,
            ]);

            Log::info('Documento guardado exitosamente', [
                'tramite_id' => $tramite->id,
                'catalogo_id' => $catalogoId,
                'archivo' => $archivo->getClientOriginalName(),
                'ruta' => $rutaArchivo
            ]);

        } catch (\Exception $e) {
            Log::error('Error al guardar documento', [
                'tramite_id' => $tramite->id,
                'catalogo_id' => $catalogoId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtiene las reglas de validación dinámicas para documentos
     */
    public function getValidationRules(Request $request): array
    {
        $rules = [];

        if (!$request->hasFile('documentos')) {
            return $rules;
        }

        foreach (array_keys($request->file('documentos', [])) as $catalogoId) {
            $catalogo = CatalogoArchivo::find($catalogoId);
            
            if ($catalogo) {
                $mimes = $this->getMimesPorTipo($catalogo->tipo_archivo);
                $rules["documentos.$catalogoId"] = "nullable|file|mimes:$mimes|max:10240";
                
                Log::info('Regla de validación creada', [
                    'catalogo_id' => $catalogoId,
                    'tipo_archivo' => $catalogo->tipo_archivo,
                    'mimes' => $mimes
                ]);
            } else {
                // Validación genérica si no existe en catálogo
                $rules["documentos.$catalogoId"] = "nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240";
                
                Log::warning('Catálogo de archivo no encontrado, usando validación genérica', [
                    'catalogo_id' => $catalogoId
                ]);
            }
        }

        return $rules;
    }

    /**
     * Convierte el tipo de archivo del catálogo a mimes de Laravel
     */
    private function getMimesPorTipo(?string $tipo): string
    {
        return match (strtolower($tipo ?? '')) {
            'pdf' => 'pdf',
            'imagen', 'jpg', 'jpeg', 'png' => 'jpg,jpeg,png',
            'audio', 'mp3' => 'mp3',
            'documento', 'doc', 'docx' => 'doc,docx',
            default => 'pdf,jpg,jpeg,png,doc,docx'
        };
    }
} 