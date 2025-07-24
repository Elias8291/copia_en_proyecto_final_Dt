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
    private const MAX_FILE_SIZE = 10240; // 10MB en KB
    private const DEFAULT_MIMES = 'pdf,jpg,jpeg,png,doc,docx';

    /**
     * Guarda los documentos enviados en el formulario
     */
    public function guardar(Tramite $tramite, Request $request): void
    {
        $documentos = $request->file('documentos', []);
        
        if (empty($documentos)) {
            Log::info('No hay documentos para procesar', ['tramite_id' => $tramite->id]);
            return;
        }

        Log::info('Procesando documentos', [
            'tramite_id' => $tramite->id,
            'total_documentos' => count($documentos)
        ]);

        foreach ($documentos as $catalogoId => $archivos) {
            $this->procesarDocumentosPorCatalogo($tramite, (int) $catalogoId, $archivos);
        }
    }

    /**
     * Procesa los archivos de un catálogo específico
     */
    private function procesarDocumentosPorCatalogo(Tramite $tramite, int $catalogoId, mixed $archivos): void
    {
        if (is_array($archivos)) {
            foreach ($archivos as $index => $archivo) {
                $this->guardarArchivo($tramite, $catalogoId, $archivo, $index);
            }
        } else {
            $this->guardarArchivo($tramite, $catalogoId, $archivos);
        }
    }

    /**
     * Guarda un archivo individual
     */
    private function guardarArchivo(Tramite $tramite, int $catalogoId, mixed $archivo, ?int $index = null): void
    {
        if (!$this->esArchivoValido($archivo)) {
            Log::warning('Archivo inválido omitido', [
                'tramite_id' => $tramite->id,
                'catalogo_id' => $catalogoId
            ]);
            return;
        }

        try {
            $rutaArchivo = $this->almacenarArchivo($tramite, $catalogoId, $archivo, $index);
            
            $this->crearRegistroArchivo($tramite, $catalogoId, $archivo, $rutaArchivo);
            
            Log::info('Documento guardado', [
                'tramite_id' => $tramite->id,
                'catalogo_id' => $catalogoId,
                'archivo' => $archivo->getClientOriginalName()
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
     * Valida si el archivo es válido
     */
    private function esArchivoValido(mixed $archivo): bool
    {
        return $archivo && 
               method_exists($archivo, 'isValid') && 
               $archivo->isValid();
    }

    /**
     * Almacena el archivo en el storage
     */
    private function almacenarArchivo(Tramite $tramite, int $catalogoId, mixed $archivo, ?int $index): string
    {
        $carpeta = "documentos/{$tramite->id}";
        
        if ($index !== null) {
            $carpeta .= "/{$catalogoId}_{$index}";
        }

        return $archivo->store($carpeta, 'public');
    }

    /**
     * Crea el registro en la base de datos
     */
    private function crearRegistroArchivo(Tramite $tramite, int $catalogoId, mixed $archivo, string $rutaArchivo): void
    {
        Archivo::create([
            'tramite_id' => $tramite->id,
            'idCatalogoArchivo' => $catalogoId,
            'nombre_original' => $archivo->getClientOriginalName(),
            'ruta_archivo' => $rutaArchivo,
            'aprobado' => false,
        ]);
    }

    /**
     * Obtiene las reglas de validación dinámicas para documentos
     */
    public function getValidationRules(Request $request): array
    {
        if (!$request->hasFile('documentos')) {
            return [];
        }

        $rules = [];
        $documentos = $request->file('documentos', []);

        foreach (array_keys($documentos) as $catalogoId) {
            $mimes = $this->obtenerMimesPermitidos($catalogoId);
            $rules["documentos.$catalogoId"] = "nullable|file|mimes:$mimes|max:" . self::MAX_FILE_SIZE;
        }

        return $rules;
    }

    /**
     * Obtiene los tipos MIME permitidos para un catálogo
     */
    private function obtenerMimesPermitidos(int $catalogoId): string
    {
        $catalogo = CatalogoArchivo::find($catalogoId);
        
        if (!$catalogo) {
            Log::warning('Catálogo no encontrado, usando mimes por defecto', [
                'catalogo_id' => $catalogoId
            ]);
            return self::DEFAULT_MIMES;
        }

        return $this->convertirTipoAMimes($catalogo->tipo_archivo);
    }

    /**
     * Convierte el tipo de archivo del catálogo a mimes de Laravel
     */
    private function convertirTipoAMimes(?string $tipo): string
    {
        return match (strtolower($tipo ?? '')) {
            'pdf' => 'pdf',
            'imagen', 'jpg', 'jpeg', 'png' => 'jpg,jpeg,png',
            'audio', 'mp3' => 'mp3',
            'documento', 'doc', 'docx' => 'doc,docx',
            default => self::DEFAULT_MIMES
        };
    }
} 