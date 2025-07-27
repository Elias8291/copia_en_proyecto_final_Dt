<?php

namespace App\Http\Controllers;

use App\Models\CatalogoArchivo;
use App\Http\Requests\CatalogoArchivoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogoArchivoController extends Controller
{
    public function index(Request $request)
    {
        $query = CatalogoArchivo::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tipo_persona')) {
            $query->where('tipo_persona', $request->tipo_persona);
        }

        if ($request->filled('tipo_archivo')) {
            $query->where('tipo_archivo', $request->tipo_archivo);
        }

        if ($request->filled('es_visible')) {
            $query->where('es_visible', $request->es_visible === 'true');
        }

        $archivos = $query->orderBy('nombre', 'asc')
            ->paginate(10)
            ->withQueryString()
            ->through(function ($archivo) {
                $archivo->estado = $archivo->es_visible ? 'activo' : 'inactivo';
                return $archivo;
            });

        return view('archivos.index', compact('archivos'));
    }

    public function create()
    {
        return view('archivos.create');
    }

    public function store(CatalogoArchivoRequest $request)
    {
        try {
            DB::beginTransaction();

            $archivo = CatalogoArchivo::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'tipo_persona' => $request->tipo_persona,
                'tipo_archivo' => $request->tipo_archivo,
                'es_visible' => $request->boolean('es_visible', true),
            ]);

            DB::commit();

            return redirect()->route('archivos.index')
                ->with('success', 'Archivo creado exitosamente')
                ->with('success_title', '¡Archivo Creado!')
                ->with('success_message', 'El archivo ha sido creado correctamente.')
                ->with('success_accept_text', 'Aceptar')
                ->with('success_redirect', route('archivos.index'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el archivo: ' . $e->getMessage());
        }
    }

    public function show(CatalogoArchivo $archivo)
    {
        $archivo->estado = $archivo->es_visible ? 'activo' : 'inactivo';
        return view('archivos.show', compact('archivo'));
    }

    public function edit(CatalogoArchivo $archivo)
    {
        return view('archivos.edit', compact('archivo'));
    }

    public function update(CatalogoArchivoRequest $request, CatalogoArchivo $archivo)
    {
        try {
            DB::beginTransaction();

            $archivo->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'tipo_persona' => $request->tipo_persona,
                'tipo_archivo' => $request->tipo_archivo,
                'es_visible' => $request->boolean('es_visible', true),
            ]);

            DB::commit();

            return redirect()->route('archivos.index')
                ->with('success', 'Archivo actualizado exitosamente')
                ->with('success_title', '¡Archivo Actualizado!')
                ->with('success_message', 'El archivo ha sido actualizado correctamente.')
                ->with('success_accept_text', 'Aceptar')
                ->with('success_redirect', route('archivos.index'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el archivo: ' . $e->getMessage());
        }
    }

    public function destroy(CatalogoArchivo $archivo)
    {
        try {
            $archivo->delete();

            return redirect()->route('archivos.index')
                ->with('success', 'Archivo eliminado exitosamente')
                ->with('success_title', '¡Archivo Eliminado!')
                ->with('success_message', 'El archivo ha sido eliminado correctamente.')
                ->with('success_accept_text', 'Aceptar')
                ->with('success_redirect', route('archivos.index'));

        } catch (\Exception $e) {
            return redirect()->route('archivos.index')
                ->with('error', 'Error al eliminar el archivo: ' . $e->getMessage());
        }
    }

    public function porTipoPersona($tipoPersona = 'Física')
    {
        try {
            $documentos = CatalogoArchivo::where('es_visible', true)
                ->where(function ($query) use ($tipoPersona) {
                    $query->where('tipo_persona', $tipoPersona)
                          ->orWhere('tipo_persona', 'Ambas');
                })
                ->orderBy('nombre', 'asc')
                ->get()
                ->map(function ($documento) {
                    return [
                        'id' => $documento->id,
                        'nombre' => $documento->nombre,
                        'descripcion' => $documento->descripcion,
                        'tipo_persona' => $documento->tipo_persona,
                        'tipo_archivo' => $documento->tipo_archivo,
                        'tipo_persona_label' => $documento->tipo_persona === 'Física' ? 'Persona Física' : 
                                               ($documento->tipo_persona === 'Moral' ? 'Persona Moral' : 'Ambas'),
                        'tipo_archivo_label' => strtoupper($documento->tipo_archivo),
                    ];
                });

            return response()->json([
                'success' => true,
                'documentos' => $documentos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener documentos: ' . $e->getMessage()
            ], 500);
        }
    }
}
