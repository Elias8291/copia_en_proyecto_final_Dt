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
        \Log::info('ArchivosService: Iniciando guardado de archivos', [
            'tramite_id' => $tramite->id,
            'proveedor_id' => $proveedor->id,
            'has_files' => $request->hasFile('documentos'),
            'all_files' => $request->allFiles()
        ]);
        
        if ($request->hasFile('documentos') && $request->file('documentos')) {
            $documentos = $request->file('documentos');
            
            \Log::info('ArchivosService: Archivos encontrados', [
                'count' => count($documentos),
                'files' => array_keys($documentos)
            ]);
            
            foreach ($documentos as $nombreArchivo => $archivo) {
                if ($archivo && $archivo->isValid()) {
                    \Log::info('ArchivosService: Procesando archivo', [
                        'nombre' => $nombreArchivo,
                        'original_name' => $archivo->getClientOriginalName(),
                        'size' => $archivo->getSize()
                    ]);
                    
                    $this->procesarArchivo($tramite, $proveedor, $nombreArchivo, $archivo);
                } else {
                    \Log::warning('ArchivosService: Archivo inválido', [
                        'nombre' => $nombreArchivo,
                        'is_valid' => $archivo ? $archivo->isValid() : false
                    ]);
                }
            }
        } else {
            \Log::warning('ArchivosService: No se encontraron archivos para procesar');
        }
    }

    private function procesarArchivo(Tramite $tramite, Proveedor $proveedor, string $nombreArchivo, $archivo): void
    {
        \Log::info('ArchivosService: Procesando archivo individual', [
            'nombre_archivo' => $nombreArchivo,
            'original_name' => $archivo->getClientOriginalName()
        ]);
        
        // Buscar el catálogo de archivo correspondiente
        $catalogoArchivo = CatalogoArchivo::where('nombre', 'LIKE', '%' . $this->normalizarNombre($nombreArchivo) . '%')->first();
        
        if (!$catalogoArchivo) {
            \Log::warning('ArchivosService: No se encontró catálogo para archivo', [
                'nombre_archivo' => $nombreArchivo,
                'nombre_normalizado' => $this->normalizarNombre($nombreArchivo)
            ]);
            return; // Saltar archivos no reconocidos
        }
        
        \Log::info('ArchivosService: Catálogo encontrado', [
            'catalogo_id' => $catalogoArchivo->id,
            'catalogo_nombre' => $catalogoArchivo->nombre
        ]);

        // Generar nombre único para el archivo
        $nombreUnico = time() . '_' . $tramite->id . '_' . $archivo->getClientOriginalName();
        $ruta = $archivo->storeAs('tramites/' . $tramite->id, $nombreUnico, 'public');
        
        \Log::info('ArchivosService: Archivo almacenado', [
            'nombre_unico' => $nombreUnico,
            'ruta' => $ruta
        ]);

        // Guardar en la base de datos
        $archivoGuardado = Archivo::create([
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
        
        \Log::info('ArchivosService: Archivo guardado en BD', [
            'archivo_id' => $archivoGuardado->id,
            'tramite_id' => $tramite->id
        ]);
    }

    private function normalizarNombre(string $nombre): string
    {
        // Convertir nombres como "acta-constitutiva" a "Acta Constitutiva"
        return ucwords(str_replace(['-', '_'], ' ', $nombre));
    }
} 