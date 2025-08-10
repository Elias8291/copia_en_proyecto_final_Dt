<?php

namespace App\Http\Controllers;

use App\Models\CatalogoArchivo;
use App\Models\Archivo;
use App\Models\Tramite;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Middleware\PermissionMiddleware;

class ArchivoController extends Controller
{
    public function __construct()
    {
        $this->middleware(PermissionMiddleware::class . ':archivos.ver')->only(['index', 'show']);
        $this->middleware(PermissionMiddleware::class . ':archivos.crear')->only(['create', 'store']);
        $this->middleware(PermissionMiddleware::class . ':archivos.editar')->only(['edit', 'update', 'updateStatus']);
        $this->middleware(PermissionMiddleware::class . ':archivos.eliminar')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CatalogoArchivo::query();
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'like', "%{$request->search}%")
                  ->orWhere('descripcion', 'like', "%{$request->search}%");
            });
        }
        
        if ($request->filled('tipo_persona')) {
            $query->where('tipo_persona', $request->tipo_persona);
        }
        
        if ($request->filled('tipo_archivo')) {
            $query->where('tipo_archivo', $request->tipo_archivo);
        }
        
        if ($request->filled('es_visible')) {
            $query->where('es_visible', $request->es_visible);
        }
        
        $catalogoArchivos = $query->orderBy('nombre', 'asc')->paginate(15)->withQueryString();
        
        return view('archivos.index', compact('catalogoArchivos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('archivos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:catalogo_archivos,nombre',
            'descripcion' => 'required|string',
            'tipo_persona' => 'required|in:Física,Moral,Ambas',
            'tipo_archivo' => 'required|in:png,pdf,mp3,mp4',
            'es_visible' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            CatalogoArchivo::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'tipo_persona' => $request->tipo_persona,
                'tipo_archivo' => $request->tipo_archivo,
                'es_visible' => $request->has('es_visible')
            ]);
            
            DB::commit();
            
            return redirect()->route('archivos.index')
                ->with('success', 'Catálogo de archivo creado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al crear el catálogo de archivo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CatalogoArchivo $archivo)
    {
        return view('archivos.show', compact('archivo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CatalogoArchivo $archivo)
    {
        return view('archivos.edit', compact('archivo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CatalogoArchivo $archivo)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:catalogo_archivos,nombre,' . $archivo->id,
            'descripcion' => 'required|string',
            'tipo_persona' => 'required|in:Física,Moral,Ambas',
            'tipo_archivo' => 'required|in:png,pdf,mp3,mp4',
            'es_visible' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            $archivo->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'tipo_persona' => $request->tipo_persona,
                'tipo_archivo' => $request->tipo_archivo,
                'es_visible' => $request->has('es_visible')
            ]);
            
            DB::commit();
            
            return redirect()->route('archivos.index')
                ->with('success', 'Catálogo de archivo actualizado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar el catálogo de archivo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CatalogoArchivo $archivo)
    {
        try {
            DB::beginTransaction();
            
            // Verificar si hay archivos asociados
            if ($archivo->archivos()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar el catálogo de archivo porque tiene archivos asociados.');
            }
            
            $archivo->delete();
            
            DB::commit();
            
            return redirect()->route('archivos.index')
                ->with('success', 'Catálogo de archivo eliminado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al eliminar el catálogo de archivo: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $archivoId)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Pendiente,Aprobado,Rechazado',
            'comentario_revision' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $archivo = Archivo::findOrFail($archivoId);
            
            $archivo->update([
                'status' => $request->status,
                'comentario_revision' => $request->comentario_revision,
                'revisado_por' => auth()->id(),
                'fecha_revision' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status del archivo actualizado correctamente',
                'data' => [
                    'status' => $archivo->status,
                    'comentario_revision' => $archivo->comentario_revision,
                    'fecha_revision' => $archivo->fecha_revision
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el status del archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getArchivosFromTramite($tramiteId)
    {
        try {
            $archivos = Archivo::where('tramite_id', $tramiteId)
                ->with(['catalogoArchivo', 'revisor'])
                ->get();

            return response()->json([
                'success' => true,
                'data' => $archivos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener archivos del trámite: ' . $e->getMessage()
            ], 500);
        }
    }

    public function guardarIndividual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'archivo' => 'required|file|max:10240', // 10MB max
            'catalogo_archivo_id' => 'required|exists:catalogo_archivos,id',
            'tramite_id' => 'required|exists:tramites,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $file = $request->file('archivo');
            $catalogoId = $request->catalogo_archivo_id;
            $tramiteId = $request->tramite_id;

            // Verificar si ya existe un archivo para este catálogo y trámite
            $archivoExistente = Archivo::where('tramite_id', $tramiteId)
                ->where('catalogo_archivo_id', $catalogoId)
                ->first();

            if ($archivoExistente) {
                // Eliminar archivo físico anterior
                $rutaAnterior = storage_path('app/public/' . $archivoExistente->ruta);
                if (file_exists($rutaAnterior)) {
                    unlink($rutaAnterior);
                }
                $archivoExistente->delete();
            }

            // Generar nombre único
            $extension = $file->getClientOriginalExtension();
            $nombreUnico = 'doc_' . $tramiteId . '_' . $catalogoId . '_' . time() . '.' . $extension;
            
            // Guardar archivo
            $ruta = $file->storeAs('tramites/' . $tramiteId, $nombreUnico, 'public');

            // Crear registro en BD
            $archivo = Archivo::create([
                'tramite_id' => $tramiteId,
                'proveedor_id' => auth()->user()->proveedor_id ?? 1, // Temporal
                'catalogo_archivo_id' => $catalogoId,
                'nombre_original' => $file->getClientOriginalName(),
                'nombre_archivo' => $nombreUnico,
                'ruta' => $ruta,
                'extension' => $extension,
                'tamaño' => $file->getSize(),
                'status' => 'Aprobado', // Aprobado automáticamente al cargar
                'comentario_revision' => 'Archivo cargado y validado automáticamente',
                'revisado_por' => auth()->id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Archivo guardado exitosamente',
                'data' => [
                    'id' => $archivo->id,
                    'status' => $archivo->status,
                    'nombre_original' => $archivo->nombre_original,
                    'ruta' => $archivo->ruta
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }
} 