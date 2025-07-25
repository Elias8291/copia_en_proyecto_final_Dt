<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RevisionController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $allowedPerPage = [5, 10, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $tramites = Tramite::with(['proveedor', 'revisadoPor', 'datosGenerales'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $tramites->appends($request->query());

        return view('revision.index', compact('tramites', 'perPage'));
    }

    public function show(Tramite $tramite)
    {
        $tramite->load([
            'proveedor',
            'revisadoPor',
            'datosGenerales',
            'datosConstitutivos',
            'apoderadoLegal',
            'contactos',
            'accionistas',
            'actividades.actividad',
            'archivos.catalogoArchivo'
        ]);

        return view('revision.show', compact('tramite'));
    }


    public function seleccionTipo(Tramite $tramite)
    {
        $tramite->load([
            'proveedor',
            'datosGenerales',
            'archivos' => function ($query) {
                $query->where('idCatalogoArchivo', 2)->with('catalogoArchivo');
            }
        ]);

        return view('revision.seleccion-tipo', compact('tramite'));
    }

    public function revisarDatos(Tramite $tramite)
    {
        try {
            $tramite->load([
                'proveedor',
                'revisadoPor',
                'datosGenerales',
                'datosConstitutivos',
                'apoderadoLegal',
                'contactos',
                'accionistas',
                'direcciones.estado',
                'actividades',
                'archivos.catalogoArchivo'
            ]);

            return view('revision.revisar-datos', compact('tramite'));
        } catch (\Exception $e) {
            Log::error('Error al cargar datos del trámite para revisión', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Error al cargar los datos del trámite: ' . $e->getMessage());
        }
    }



    public function verDocumento($tramiteId, $archivoId, $filename)
    {
        $archivo = Tramite::findOrFail($tramiteId)
            ->archivos()
            ->where('id', $archivoId)
            ->firstOrFail();

        $rutas = [
            storage_path('app/' . $archivo->ruta_archivo),
            storage_path('app/public/' . $archivo->ruta_archivo)
        ];

        foreach ($rutas as $ruta) {
            if (file_exists($ruta)) {
                return response()->file($ruta, [
                    'Content-Type' => mime_content_type($ruta) ?: 'application/octet-stream',
                    'Content-Disposition' => 'inline; filename="' . ($archivo->nombre_original ?: $filename) . '"'
                ]);
            }
        }

        abort(404, 'Archivo no encontrado');
    }

    /**
     * Actualiza el comentario (observaciones) de un documento (archivo).
     */
    public function actualizarComentarioDocumento(Request $request, $archivoId)
    {
        $request->validate([
            'comentario' => 'nullable|string|max:1000',
        ]);

        $archivo = \App\Models\Archivo::findOrFail($archivoId);
        $archivo->observaciones = $request->input('comentario');
        $archivo->save();

        return response()->json([
            'success' => true,
            'message' => 'Comentario actualizado correctamente.',
            'comentario' => $archivo->observaciones,
        ]);
    }

    /**
     * Actualiza el estado (aprobado) de un documento (archivo).
     */
    public function actualizarEstadoDocumento(Request $request, $archivoId)
    {
        $request->validate([
            'aprobado' => 'required|boolean',
        ]);

        $archivo = \App\Models\Archivo::findOrFail($archivoId);
        $archivo->aprobado = $request->input('aprobado');
        $archivo->save();

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente.',
            'aprobado' => $archivo->aprobado,
        ]);
    }
}
