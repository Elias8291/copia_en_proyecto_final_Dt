<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\Proveedor;
use App\Models\Archivo;
use App\Models\CatalogoArchivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class ArchivosService extends BaseService
{
    public function guardar(Tramite $tramite, Proveedor $proveedor, Request $request): void
    {
        if (!$request->hasFile('documentos')) {
            return;
        }
        
        $documentos = $request->file('documentos');
        $archivosParaGuardar = [];
        $errores = [];
        
        Log::info('ArchivosService: Iniciando procesamiento ultra optimizado de archivos', [
            'tramite_id' => $tramite->id,
            'total_archivos' => count($documentos)
        ]);
        
        // Procesar archivos en lote ultra optimizado
        foreach ($documentos as $nombreArchivo => $archivo) {
            try {
                if ($this->validarArchivo($archivo)) {
                    $resultado = $this->prepararArchivoUltraOptimizado($tramite, $proveedor, $nombreArchivo, $archivo);
                    if ($resultado) {
                        $archivosParaGuardar[] = $resultado;
                    }
                }
            } catch (\Exception $e) {
                $errores[] = [
                    'archivo' => $nombreArchivo,
                    'error' => $e->getMessage()
                ];
                Log::warning('ArchivosService: Error al procesar archivo', [
                    'archivo' => $nombreArchivo,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        // Guardar archivos en lote si hay archivos válidos
        if (!empty($archivosParaGuardar)) {
            $this->guardarArchivosEnLoteUltraOptimizado($archivosParaGuardar);
        }
        
        // Log de resultados
        Log::info('ArchivosService: Procesamiento ultra optimizado completado', [
            'tramite_id' => $tramite->id,
            'archivos_procesados' => count($archivosParaGuardar),
            'errores' => count($errores)
        ]);
        
        if (!empty($errores)) {
            Log::warning('ArchivosService: Errores durante el procesamiento', [
                'tramite_id' => $tramite->id,
                'errores' => $errores
            ]);
        }
    }

    /**
     * Obtiene los archivos de un trámite
     */
    public function obtener(Tramite $tramite): Collection
    {
        return $tramite->archivos()->with('catalogoArchivo')->get();
    }

    private function prepararArchivoUltraOptimizado(Tramite $tramite, Proveedor $proveedor, string $nombreArchivo, $archivo): ?array
    {
        $catalogoArchivo = $this->buscarCatalogoArchivo($nombreArchivo);
        
        if (!$catalogoArchivo) {
            return null;
        }

        // Validar tipo de archivo según el catálogo
        if (!$this->validarTipoArchivo($archivo, $catalogoArchivo->tipo_archivo)) {
            return null;
        }

        $nombreUnico = $this->generarNombreUnico('doc', $tramite->id, $archivo->getClientOriginalName());
        $ruta = $archivo->storeAs('tramites/' . $tramite->id, $nombreUnico, 'public');

        return [
            'tramite_id' => $tramite->id,
            'proveedor_id' => $proveedor->id,
            'catalogo_archivo_id' => $catalogoArchivo->id,
            'nombre_original' => $archivo->getClientOriginalName(),
            'nombre_archivo' => $nombreUnico,
            'ruta' => $ruta,
            'extension' => $archivo->getClientOriginalExtension(),
            'tamaño' => $archivo->getSize(),
            'status' => 'Pendiente',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function guardarArchivosEnLoteUltraOptimizado(array $archivos): void
    {
        try {
            // Usar inserción en lote ultra optimizada
            Archivo::insert($archivos);
            
            Log::info('ArchivosService: Archivos guardados en lote ultra optimizado', [
                'total_archivos' => count($archivos)
            ]);
        } catch (\Exception $e) {
            Log::error('ArchivosService: Error al guardar archivos en lote', [
                'error' => $e->getMessage(),
                'archivos' => count($archivos)
            ]);
            throw $e;
        }
    }

    private function buscarCatalogoArchivo(string $nombre): ?CatalogoArchivo
    {
        return CatalogoArchivo::where('nombre', 'LIKE', '%' . $this->normalizarNombre($nombre) . '%')->first();
    }

    private function normalizarNombre(string $nombre): string
    {
        return ucwords(str_replace(['-', '_'], ' ', $nombre));
    }

    private function validarTipoArchivo($archivo, string $tipoEsperado): bool
    {
        $extension = strtolower($archivo->getClientOriginalExtension());
        
        $tiposPermitidos = $this->obtenerExtensionesPorTipo($tipoEsperado);
        
        return in_array($extension, $tiposPermitidos);
    }

    private function obtenerExtensionesPorTipo(string $tipoArchivo): array
    {
        return match($tipoArchivo) {
            'pdf' => ['pdf'],
            'png' => ['png', 'jpg', 'jpeg', 'gif', 'webp'],
            'mp3' => ['mp3', 'wav', 'ogg'],
            'mp4' => ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'],
            default => ['pdf', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'mp3', 'wav', 'ogg', 'mp4', 'avi', 'mov', 'wmv', 'flv', 'webm']
        };
    }

    /**
     * Actualizar archivos de un trámite existente
     */
    public function actualizar(Tramite $tramite, Request $request): void
    {
        $archivos = $request->file('archivos');
        
        if (!$archivos) {
            Log::info('ArchivosService: No hay archivos para actualizar', [
                'tramite_id' => $tramite->id
            ]);
            return;
        }
        
        Log::info('ArchivosService: Iniciando actualización de archivos', [
            'tramite_id' => $tramite->id,
            'archivos_recibidos' => array_keys($archivos)
        ]);
        
        foreach ($archivos as $catalogoId => $archivo) {
            if ($archivo && $archivo->isValid()) {
                Log::info('ArchivosService: Procesando archivo', [
                    'tramite_id' => $tramite->id,
                    'catalogo_id' => $catalogoId,
                    'nombre_archivo' => $archivo->getClientOriginalName()
                ]);
                
                // Buscar archivo anterior
                $archivoExistente = $tramite->archivos()
                    ->where('catalogo_archivo_id', $catalogoId)
                    ->first();
                
                if ($archivoExistente) {
                    Log::info('ArchivosService: Eliminando archivo anterior', [
                        'tramite_id' => $tramite->id,
                        'archivo_id' => $archivoExistente->id,
                        'status_anterior' => $archivoExistente->status
                    ]);
                    
                    // Eliminar archivo físico
                    if (file_exists(storage_path('app/public/' . $archivoExistente->ruta))) {
                        unlink(storage_path('app/public/' . $archivoExistente->ruta));
                    }
                    $archivoExistente->delete();
                }

                // Guardar nuevo archivo con status Pendiente
                $this->guardarArchivoCorreccion($tramite, $archivo, $catalogoId);
            } else {
                Log::warning('ArchivosService: Archivo inválido', [
                    'tramite_id' => $tramite->id,
                    'catalogo_id' => $catalogoId
                ]);
            }
        }
        
        Log::info('ArchivosService: Actualización completada', [
            'tramite_id' => $tramite->id
        ]);
    }

    /**
     * Guardar un archivo específico para correcciones
     */
    public function guardarArchivoCorreccion(Tramite $tramite, $archivo, $catalogoId): void
    {
        $catalogoArchivo = CatalogoArchivo::find($catalogoId);
        
        if (!$catalogoArchivo) {
            Log::error('ArchivosService: Catálogo de archivo no encontrado', [
                'tramite_id' => $tramite->id,
                'catalogo_id' => $catalogoId
            ]);
            return;
        }

        $nombreUnico = $this->generarNombreUnico('doc', $tramite->id, $archivo->getClientOriginalName());
        $ruta = $archivo->storeAs('tramites/' . $tramite->id, $nombreUnico, 'public');

        $nuevoArchivo = Archivo::create([
            'tramite_id' => $tramite->id,
            'proveedor_id' => $tramite->proveedor_id,
            'catalogo_archivo_id' => $catalogoId,
            'nombre_original' => $archivo->getClientOriginalName(),
            'nombre_archivo' => $nombreUnico,
            'ruta' => $ruta,
            'extension' => $archivo->getClientOriginalExtension(),
            'tamaño' => $archivo->getSize(),
            'status' => 'Pendiente',
            'comentario_revision' => null,
            'revisado_por' => null,
            'fecha_revision' => null
        ]);

        Log::info('ArchivosService: Archivo de corrección creado exitosamente', [
            'tramite_id' => $tramite->id,
            'archivo_id' => $nuevoArchivo->id,
            'catalogo_id' => $catalogoId,
            'status' => $nuevoArchivo->status,
            'nombre_original' => $nuevoArchivo->nombre_original
        ]);
    }

    /**
     * Guardar un archivo específico
     */
    public function guardarArchivo(Tramite $tramite, $archivo, $catalogoId): void
    {
        $catalogoArchivo = CatalogoArchivo::find($catalogoId);
        
        if (!$catalogoArchivo) {
            return;
        }

        $nombreUnico = $this->generarNombreUnico('doc', $tramite->id, $archivo->getClientOriginalName());
        $ruta = $archivo->storeAs('tramites/' . $tramite->id, $nombreUnico, 'public');

        Archivo::create([
            'tramite_id' => $tramite->id,
            'proveedor_id' => $tramite->proveedor_id,
            'catalogo_archivo_id' => $catalogoId,
            'nombre_original' => $archivo->getClientOriginalName(),
            'nombre_archivo' => $nombreUnico,
            'ruta' => $ruta,
            'extension' => $archivo->getClientOriginalExtension(),
            'tamaño' => $archivo->getSize(),
            'status' => 'Pendiente',
        ]);
    }
} 