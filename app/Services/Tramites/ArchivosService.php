<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\Proveedor;
use App\Models\Archivo;
use App\Models\CatalogoArchivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ArchivosService extends BaseService
{
    public function guardar(Tramite $tramite, Proveedor $proveedor, Request $request): void
    {
        if (!$request->hasFile('documentos')) {
            return;
        }
        foreach ($request->file('documentos') as $claveCatalogo => $archivo) {
            if (!$archivo || !$archivo->isValid()) {
                continue;
            }
            $catalogoId = $this->resolverCatalogoIdDesdeClave($claveCatalogo);
            if (!$catalogoId) {
                continue;
            }
            $this->guardarArchivoCorreccion($tramite, $archivo, $catalogoId, 'Pendiente');
        }
    }

    /**
     * Obtiene los archivos de un trámite
     */
    public function obtener(Tramite $tramite): Collection
    {
        return $tramite->archivos()->with('catalogoArchivo')->get();
    }

    // Simplificado: sin preparación por lote; se delega al método guardarArchivoCorreccion

    // Eliminado flujo de inserción en lote para claridad.

    private function buscarCatalogoArchivo(string $nombre): ?CatalogoArchivo
    {
        return CatalogoArchivo::where('nombre', 'LIKE', '%' . $this->normalizarNombre($nombre) . '%')->first();
    }

    private function normalizarNombre(string $nombre): string
    {
        return ucwords(str_replace(['-', '_'], ' ', $nombre));
    }

    // Validación de tipo/tamaño ya se hace en los FormRequest; no se duplica aquí.

    /**
     * Actualizar archivos de un trámite existente
     */
    public function actualizar(Tramite $tramite, Request $request): void
    {
        $archivos = $request->file('documentos_correccion')
            ?: $request->file('archivos')
            ?: $request->file('documentos');
        if (!$archivos) return;
        foreach ($archivos as $claveCatalogo => $archivo) {
            if ($archivo && $archivo->isValid()) {
                $catalogoId = $this->resolverCatalogoIdDesdeClave($claveCatalogo);
                if (!$catalogoId) continue;
                $archivoExistente = $tramite->archivos()
                    ->where('catalogo_archivo_id', $catalogoId)
                    ->first();
                if ($archivoExistente) {
                    $oldPath = ltrim($archivoExistente->ruta, '/');
                    Storage::disk('local')->delete($oldPath);
                    Storage::disk('public')->delete($oldPath);
                    $archivoExistente->delete();
                }
                $this->guardarArchivoCorreccion($tramite, $archivo, $catalogoId);
            }
        }
    }

    /**
     * Guardar un archivo específico para correcciones
     */
    public function guardarArchivoCorreccion(Tramite $tramite, $archivo, $catalogoId, string $status = 'Pendiente')
    {
        $catalogoArchivo = CatalogoArchivo::find($catalogoId);
        
        if (!$catalogoArchivo) return null;

        $nombreUnico = $this->generarNombreUnico('doc', $tramite->id, $archivo->getClientOriginalName());
        $rutaRelativa = 'tramites/' . $tramite->id . '/' . $nombreUnico;
        // Cifrar antes de guardar en almacenamiento privado
        $ciphertext = encrypt($archivo->get());
        Storage::disk('local')->put($rutaRelativa, $ciphertext);

        $nuevoArchivo = Archivo::create([
            'tramite_id' => $tramite->id,
            'proveedor_id' => $tramite->proveedor_id,
            'catalogo_archivo_id' => $catalogoId,
            'nombre_original' => $archivo->getClientOriginalName(),
            'nombre_archivo' => $nombreUnico,
            'ruta' => $rutaRelativa,
            'extension' => strtolower($archivo->getClientOriginalExtension()),
            'tamaño' => $archivo->getSize(),
            'status' => $status,
            'comentario_revision' => null,
            'revisado_por' => null,
            'fecha_revision' => null
        ]);
        return $nuevoArchivo;
    }

    /**
     * Resolver el ID del catálogo a partir de una clave (ID numérico o slug de nombre)
     */
    private function resolverCatalogoIdDesdeClave($clave): ?int
    {
        if (is_numeric($clave)) {
            return (int) $clave;
        }
        $claveStr = (string) $clave;
        $slug = Str::slug($claveStr);
        $catalogos = CatalogoArchivo::select('id', 'nombre')->get();
        $match = $catalogos->first(function ($c) use ($slug) { return Str::slug($c->nombre) === $slug; });
        if ($match) return (int) $match->id;
        $like = CatalogoArchivo::where('nombre', 'LIKE', '%' . $claveStr . '%')->first();
        return $like?->id;
    }
    
} 