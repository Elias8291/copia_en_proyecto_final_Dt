<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\Proveedor;
use App\Models\Archivo;
use App\Models\CatalogoArchivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class ArchivosService extends BaseService
{
    public function guardar(Tramite $tramite, Proveedor $proveedor, Request $request): void
    {
        if (!$request->hasFile('documentos')) {
            return;
        }
        
        $documentos = $request->file('documentos');
        
        foreach ($documentos as $nombreArchivo => $archivo) {
            if ($this->validarArchivo($archivo)) {
                $this->procesarArchivo($tramite, $proveedor, $nombreArchivo, $archivo);
            }
        }
    }

    /**
     * Obtiene los archivos de un trámite
     */
    public function obtener(Tramite $tramite): Collection
    {
        return $tramite->archivos()->with('catalogoArchivo')->get();
    }

    private function procesarArchivo(Tramite $tramite, Proveedor $proveedor, string $nombreArchivo, $archivo): void
    {
        $catalogoArchivo = $this->buscarCatalogoArchivo($nombreArchivo);
        
        if (!$catalogoArchivo) {
            \Log::warning('ArchivoService: Archivo no reconocido en catálogo', [
                'nombre_archivo' => $nombreArchivo,
                'nombre_original' => $archivo->getClientOriginalName()
            ]);
            return; // Saltar archivos no reconocidos
        }

        // Validar tipo de archivo según el catálogo
        if (!$this->validarTipoArchivo($archivo, $catalogoArchivo->tipo_archivo)) {
            \Log::warning('ArchivoService: Tipo de archivo no válido', [
                'nombre_archivo' => $nombreArchivo,
                'tipo_esperado' => $catalogoArchivo->tipo_archivo,
                'extension_real' => $archivo->getClientOriginalExtension()
            ]);
            return;
        }

        $nombreUnico = $this->generarNombreUnico('doc', $tramite->id, $archivo->getClientOriginalName());
        $ruta = $archivo->storeAs('tramites/' . $tramite->id, $nombreUnico, 'public');

        Archivo::create([
            'tramite_id' => $tramite->id,
            'proveedor_id' => $proveedor->id,
            'catalogo_archivo_id' => $catalogoArchivo->id,
            'nombre_original' => $archivo->getClientOriginalName(),
            'nombre_archivo' => $nombreUnico,
            'ruta' => $ruta,
            'extension' => $archivo->getClientOriginalExtension(),
            'tamaño' => $archivo->getSize(),
            'status' => 'Pendiente',
        ]);

        \Log::info('ArchivoService: Archivo procesado exitosamente', [
            'tramite_id' => $tramite->id,
            'catalogo_archivo_id' => $catalogoArchivo->id,
            'nombre_original' => $archivo->getClientOriginalName(),
            'tipo_archivo' => $catalogoArchivo->tipo_archivo
        ]);
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
            return;
        }
        
        foreach ($archivos as $catalogoId => $archivo) {
            if ($archivo && $archivo->isValid()) {
                // Eliminar archivo anterior si existe
                $archivoExistente = $tramite->archivos()
                    ->where('catalogo_archivo_id', $catalogoId)
                    ->first();
                
                if ($archivoExistente) {
                    // Eliminar archivo físico
                    if (file_exists(storage_path('app/public/' . $archivoExistente->ruta))) {
                        unlink(storage_path('app/public/' . $archivoExistente->ruta));
                    }
                    $archivoExistente->delete();
                }

                // Guardar nuevo archivo
                $this->guardarArchivo($tramite, $archivo, $catalogoId);
            }
        }
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