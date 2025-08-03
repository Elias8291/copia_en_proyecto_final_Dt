<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\Proveedor;
use App\Models\Archivo;
use App\Models\CatalogoArchivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArchivosService
{
    public function guardar(Tramite $tramite, Proveedor $proveedor, Request $request): void
    {
        if ($request->hasFile('documentos') && $request->file('documentos')) {
            $documentos = $request->file('documentos');
            
            foreach ($documentos as $nombreArchivo => $archivo) {
                if ($archivo && $archivo->isValid()) {
                    $this->procesarArchivo($tramite, $proveedor, $nombreArchivo, $archivo);
                }
            }
        }
    }

    private function procesarArchivo(Tramite $tramite, Proveedor $proveedor, string $nombreArchivo, $archivo): void
    {
        // Buscar el catálogo de archivo correspondiente
        $catalogoArchivo = CatalogoArchivo::where('nombre', 'LIKE', '%' . $this->normalizarNombre($nombreArchivo) . '%')->first();
        
        if (!$catalogoArchivo) {
            return; // Saltar archivos no reconocidos
        }

        // Generar nombre único para el archivo
        $nombreUnico = time() . '_' . $tramite->id . '_' . $archivo->getClientOriginalName();
        $ruta = $archivo->storeAs('tramites/' . $tramite->id, $nombreUnico, 'public');

        // Guardar en la base de datos
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

    private function normalizarNombre(string $nombre): string
    {
        // Convertir nombres como "acta-constitutiva" a "Acta Constitutiva"
        return ucwords(str_replace(['-', '_'], ' ', $nombre));
    }
} 