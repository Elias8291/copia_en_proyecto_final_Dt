<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use App\Models\CatalogoArchivo;
use App\Models\Tramite;
use App\Services\Tramites\ArchivosService;
use App\Support\ArchivosValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

    public function index(Request $request)
    {
        $catalogoArchivos = CatalogoArchivo::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($qq) use ($request) {
                    $qq->where('nombre', 'like', "%{$request->search}%")
                       ->orWhere('descripcion', 'like', "%{$request->search}%");
                });
            })
            ->when($request->filled('tipo_persona'), fn ($q) => $q->where('tipo_persona', $request->tipo_persona))
            ->when($request->filled('tipo_archivo'), fn ($q) => $q->where('tipo_archivo', $request->tipo_archivo))
            ->when($request->filled('es_visible'), fn ($q) => $q->where('es_visible', $request->boolean('es_visible')))
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view('archivos.index', compact('catalogoArchivos'));
    }

    public function create()
    {
        return view('archivos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'       => 'required|string|max:100|unique:catalogo_archivos,nombre',
            'descripcion'  => 'required|string',
            'tipo_persona' => 'required|in:Física,Moral,Ambas',
            'tipo_archivo' => 'required|in:png,pdf,mp3,mp4',
            'es_visible'   => 'nullable|boolean',
        ]);

        try {
            DB::transaction(function () use ($data, $request) {
                CatalogoArchivo::create([
                    'nombre'       => $data['nombre'],
                    'descripcion'  => $data['descripcion'],
                    'tipo_persona' => $data['tipo_persona'],
                    'tipo_archivo' => $data['tipo_archivo'],
                    'es_visible'   => $request->boolean('es_visible'),
                ]);
            });

            return redirect()->route('archivos.index')->with('success', 'Catálogo de archivo creado exitosamente.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Error al crear el catálogo de archivo: ' . $e->getMessage())->withInput();
        }
    }

    public function show(CatalogoArchivo $archivo)
    {
        return view('archivos.show', compact('archivo'));
    }

    public function edit(CatalogoArchivo $archivo)
    {
        return view('archivos.edit', compact('archivo'));
    }

    public function update(Request $request, CatalogoArchivo $archivo)
    {
        $data = $request->validate([
            'nombre'       => 'required|string|max:100|unique:catalogo_archivos,nombre,' . $archivo->id,
            'descripcion'  => 'required|string',
            'tipo_persona' => 'required|in:Física,Moral,Ambas',
            'tipo_archivo' => 'required|in:png,pdf,mp3,mp4',
            'es_visible'   => 'nullable|boolean',
        ]);

        try {
            DB::transaction(function () use ($archivo, $data, $request) {
                $archivo->update([
                    'nombre'       => $data['nombre'],
                    'descripcion'  => $data['descripcion'],
                    'tipo_persona' => $data['tipo_persona'],
                    'tipo_archivo' => $data['tipo_archivo'],
                    'es_visible'   => $request->boolean('es_visible'),
                ]);
            });

            return redirect()->route('archivos.index')->with('success', 'Catálogo de archivo actualizado exitosamente.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Error al actualizar el catálogo de archivo: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(CatalogoArchivo $archivo)
    {
        try {
            DB::transaction(function () use ($archivo) {
                if ($archivo->archivos()->exists()) {
                    abort(400, 'No se puede eliminar el catálogo de archivo porque tiene archivos asociados.');
                }
                $archivo->delete();
            });

            return redirect()->route('archivos.index')->with('success', 'Catálogo de archivo eliminado exitosamente.');
        } catch (\Throwable $e) {
            $message = $e->getCode() === 400 ? $e->getMessage() : 'Error al eliminar el catálogo de archivo: ' . $e->getMessage();
            return back()->with('error', $message);
        }
    }

    public function updateStatus(Request $request, int $archivoId)
    {
        $data = $request->validate([
            'status'              => 'required|in:Pendiente,Aprobado,Rechazado',
            'comentario_revision' => 'nullable|string|max:500',
        ]);

        try {
            $archivo = Archivo::findOrFail($archivoId);

            $archivo->update([
                'status'              => $data['status'],
                'comentario_revision' => $data['comentario_revision'] ?? null,
                'revisado_por'        => auth()->id(),
                'fecha_revision'      => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status del archivo actualizado correctamente',
                'data'    => [
                    'status'              => $archivo->status,
                    'comentario_revision' => $archivo->comentario_revision,
                    'fecha_revision'      => $archivo->fecha_revision,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el status del archivo: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getArchivosFromTramite(int $tramiteId)
    {
        try {
            $archivos = Archivo::where('tramite_id', $tramiteId)
                ->with(['catalogoArchivo', 'revisor'])
                ->get();

            return response()->json([
                'success' => true,
                'data'    => $archivos,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener archivos del trámite: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function guardarIndividual(Request $request)
    {
        $catalogoIdInput = (int) $request->input('catalogo_archivo_id');
        $catalogo        = CatalogoArchivo::find($catalogoIdInput);
        $tipoCatalogo    = $catalogo ? (string) $catalogo->tipo_archivo : 'pdf';
        $mimes           = ArchivosValidation::extensionesPermitidasPorTipoCatalogo($tipoCatalogo);
        $maxKb           = ArchivosValidation::maximoKbPorTipo($tipoCatalogo);

        $data = $request->validate([
            'archivo'             => 'required|file|mimes:' . $mimes . '|max:' . $maxKb,
            'catalogo_archivo_id' => 'required|exists:catalogo_archivos,id',
            'tramite_id'          => 'required|exists:tramites,id',
        ]);

        try {
            $archivo = DB::transaction(function () use ($request, $data) {
                $file       = $request->file('archivo');
                $catalogoId = (int) $data['catalogo_archivo_id'];
                $tramiteId  = (int) $data['tramite_id'];

                $archivoExistente = Archivo::where('tramite_id', $tramiteId)
                    ->where('catalogo_archivo_id', $catalogoId)
                    ->first();

                if ($archivoExistente) {
                    $oldPath = ltrim($archivoExistente->ruta, '/');
                    Storage::disk('local')->delete($oldPath);
                    Storage::disk('public')->delete($oldPath);
                    $archivoExistente->delete();
                }

                $archivosService = app(ArchivosService::class);
                $registro = $archivosService->guardarArchivoCorreccion(
                    Tramite::findOrFail($tramiteId),
                    $file,
                    $catalogoId,
                    'Aprobado'
                );

                $registro->update([
                    'comentario_revision' => 'Archivo cargado y validado automáticamente',
                    'revisado_por'        => auth()->id(),
                ]);

                return $registro;
            });

            return response()->json([
                'success' => true,
                'message' => 'Archivo guardado exitosamente',
                'data'    => [
                    'id'              => $archivo->id,
                    'status'          => $archivo->status,
                    'nombre_original' => $archivo->nombre_original,
                    'ruta'            => $archivo->ruta,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el archivo: ' . $e->getMessage(),
            ], 500);
        }
    }
}
