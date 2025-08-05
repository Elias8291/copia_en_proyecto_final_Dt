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
            return; // Saltar archivos no reconocidos
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
    }

    private function buscarCatalogoArchivo(string $nombre): ?CatalogoArchivo
    {
        return CatalogoArchivo::where('nombre', 'LIKE', '%' . $this->normalizarNombre($nombre) . '%')->first();
    }

    private function normalizarNombre(string $nombre): string
    {
        return ucwords(str_replace(['-', '_'], ' ', $nombre));
    }
} 