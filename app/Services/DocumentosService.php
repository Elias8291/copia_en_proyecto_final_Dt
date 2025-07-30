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
    private const MAX_FILE_SIZE = 51200; // 50MB en KB
    private const DEFAULT_MIMES = 'pdf,jpg,jpeg,png,doc,docx';

    /**
     * Obtiene los archivos del catálogo con ID 2 asociados a un trámite
     */
    public function obtenerArchivosCatalogo2(Tramite $tramite)
    {
        return $tramite->archivos()
            ->where('idCatalogoArchivo', 2)
            ->with('catalogoArchivo')
            ->get();
    }

    /**
     * Obtiene el catálogo con ID 2
     */
    public function obtenerCatalogo2()
    {
        return CatalogoArchivo::find(2);
    }

    /**
     * Obtiene información completa del catálogo 2 y sus archivos asociados a un trámite
     */
    public function obtenerInformacionCatalogo2(Tramite $tramite)
    {
        $catalogo = $this->obtenerCatalogo2();
        $archivos = $this->obtenerArchivosCatalogo2($tramite);

        return [
            'catalogo' => $catalogo,
            'archivos' => $archivos,
            'total_archivos' => $archivos->count(),
            'tramite_id' => $tramite->id
        ];
    }

    /**
     * Método estático para obtener archivos del catálogo 2
     * Uso: DocumentosService::getCatalogo2Archivos($tramite)
     */
    public static function getCatalogo2Archivos(Tramite $tramite)
    {
        $service = app(self::class);
        return $service->obtenerArchivosCatalogo2($tramite);
    }

    /**
     * Método estático para obtener información del catálogo 2
     * Uso: DocumentosService::getCatalogo2Info($tramite)
     */
    public static function getCatalogo2Info(Tramite $tramite)
    {
        $service = app(self::class);
        return $service->obtenerInformacionCatalogo2($tramite);
    }

    /**
     * Método estático para obtener solo el catálogo 2
     * Uso: DocumentosService::getCatalogo2()
     */
    public static function getCatalogo2()
    {
        $service = app(self::class);
        return $service->obtenerCatalogo2();
    }

    /**
     * Método estático para verificar si tiene archivos del catálogo 2
     * Uso: DocumentosService::hasCatalogo2($tramite)
     */
    public static function hasCatalogo2(Tramite $tramite): bool
    {
        $archivos = self::getCatalogo2Archivos($tramite);
        return $archivos->count() > 0;
    }

    /**
     * Método estático para obtener el conteo del catálogo 2
     * Uso: DocumentosService::getCatalogo2Count($tramite)
     */
    public static function getCatalogo2Count(Tramite $tramite): int
    {
        $archivos = self::getCatalogo2Archivos($tramite);
        return $archivos->count();
    }

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
            'total_documentos' => count($documentos),
            'documentos_keys' => array_keys($documentos)
        ]);

        foreach ($documentos as $catalogoId => $archivos) {
            try {
                $this->procesarDocumentosPorCatalogo($tramite, (int) $catalogoId, $archivos);
            } catch (\Exception $e) {
                Log::error('Error procesando documento', [
                    'tramite_id' => $tramite->id,
                    'catalogo_id' => $catalogoId,
                    'error' => $e->getMessage()
                ]);
                // Continuar con el siguiente documento en lugar de fallar todo
            }
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
        if (!$archivo) {
            return false;
        }

        // Si es un array (múltiples archivos)
        if (is_array($archivo)) {
            return !empty($archivo) && $this->esArchivoValido($archivo[0]);
        }

        // Si es un archivo individual
        if (method_exists($archivo, 'isValid')) {
            return $archivo->isValid();
        }

        // Si es un string (ruta de archivo)
        if (is_string($archivo)) {
            return !empty($archivo);
        }

        return false;
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
        Log::info('DocumentosService::getValidationRules called', [
            'has_files' => $request->hasFile('documentos'),
            'files_count' => count($request->allFiles())
        ]);

        if (!$request->hasFile('documentos')) {
            Log::info('No hay archivos de documentos en la request');
            return [];
        }

        $rules = [];
        $documentos = $request->file('documentos', []);

        Log::info('Procesando documentos para validación', [
            'documentos_keys' => array_keys($documentos),
            'total_documentos' => count($documentos)
        ]);

        foreach (array_keys($documentos) as $catalogoId) {
            $mimes = $this->obtenerMimesPermitidos($catalogoId);
            $rules["documentos.$catalogoId"] = "nullable|file|mimes:$mimes|max:" . self::MAX_FILE_SIZE;
            
            Log::info("Regla generada para documento $catalogoId", [
                'catalogo_id' => $catalogoId,
                'mimes' => $mimes,
                'max_size' => self::MAX_FILE_SIZE,
                'rule' => $rules["documentos.$catalogoId"]
            ]);
        }

        Log::info('Reglas de validación generadas', ['rules' => $rules]);
        return $rules;
    }

    /**
     * Obtiene los tipos MIME permitidos para un catálogo
     */
    public function obtenerMimesPermitidos(int $catalogoId): string
    {
        Log::info("Obteniendo mimes para catálogo $catalogoId");
        
        $catalogo = CatalogoArchivo::find($catalogoId);
        
        if (!$catalogo) {
            Log::warning('Catálogo no encontrado, usando mimes por defecto', [
                'catalogo_id' => $catalogoId
            ]);
            return self::DEFAULT_MIMES;
        }

        $mimes = $this->convertirTipoAMimes($catalogo->tipo_archivo);
        
        Log::info("Mimes obtenidos para catálogo $catalogoId", [
            'catalogo_id' => $catalogoId,
            'tipo_archivo' => $catalogo->tipo_archivo,
            'mimes' => $mimes
        ]);

        return $mimes;
    }

    /**
     * Convierte el tipo de archivo del catálogo a mimes de Laravel
     */
    public function convertirTipoAMimes(?string $tipo): string
    {
        return match (strtolower($tipo ?? '')) {
            'pdf' => 'pdf',
            'imagen', 'jpg', 'jpeg', 'png' => 'jpg,jpeg,png',
            'audio', 'mp3' => 'mp3',
            'video', 'mp4' => 'mp4,avi,mov,wmv',
            'documento', 'doc', 'docx' => 'doc,docx',
            default => self::DEFAULT_MIMES
        };
    }
} 