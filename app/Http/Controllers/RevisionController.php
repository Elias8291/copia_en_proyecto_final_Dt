<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use App\Models\RevisionTramite;
use App\Services\Tramites\DataRetrievalService;
use App\ViewModels\FormDataViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RevisionController extends Controller
{
    private DataRetrievalService $dataRetrievalService;

    public function __construct(DataRetrievalService $dataRetrievalService)
    {
        $this->dataRetrievalService = $dataRetrievalService;
    }

    /**
     * Muestra lista de trámites para revisar
     */
    public function index(Request $request)
    {
        $query = Tramite::with(['proveedor', 'datosGenerales' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(1);
        }])
        ->where('status', 'Pendiente')
        ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('proveedor', function($subQ) use ($search) {
                    $subQ->where('rfc', 'like', "%{$search}%");
                })
                ->orWhereHas('datosGenerales', function($subQ) use ($search) {
                    $subQ->where('razon_social', 'like', "%{$search}%")
                         ->orWhere('curp', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('tipo_tramite')) {
            $query->where('tipo_tramite', $request->tipo_tramite);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $tramites = $query->paginate($request->get('per_page', 15));

        return view('revisiones.index', compact('tramites'));
    }

    /**
     * Muestra vista de selección de tipo de revisión
     */
    public function seleccionarTipoRevision(int $tramiteId)
    {
        $tramite = Tramite::with(['proveedor', 'datosGenerales' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(1);
        }])->findOrFail($tramiteId);

        // Verificar si ya existe una revisión en proceso para este trámite
        $revisionExistente = RevisionTramite::where('tramite_id', $tramiteId)
            ->where('estado', '!=', 'Finalizada')
            ->first();

        // Siempre mostrar la página de selección de tipo
        // Si hay una revisión existente, se pasará como información adicional
        return view('revisiones.seleccionar-tipo', [
            'tramite' => $tramite,
            'revisionExistente' => $revisionExistente
        ]);
    }

    /**
     * Redirige a la revisión con el tipo seleccionado (sin crear revisión en BD)
     */
    public function iniciarRevision(Request $request, int $tramiteId)
    {
        $request->validate([
            'tipo_revision' => 'required|in:Digital,Presencial,Domiciliaria'
        ]);

        // Solo redirigir con el tipo de revisión, sin crear registro en BD
        return redirect()->route('revisiones.revisar', [
            'tramite' => $tramiteId,
            'tipo_revision' => $request->tipo_revision
        ])->with('success', 'Iniciando ' . $request->tipo_revision . '...');
    }

    /**
     * Muestra formulario de revisión con datos del trámite (solo lectura)
     */
    public function revisarTramite(Request $request, int $tramiteId)
    {
        // Obtener datos del trámite
        $datosTramite = $this->dataRetrievalService->obtenerDatosTramiteHistorico($tramiteId);
        
        // Crear ViewModel con los datos
        $viewModel = new FormDataViewModel($datosTramite);
        
        // Obtener el trámite para información adicional
        $tramite = Tramite::findOrFail($tramiteId);
        
        // Obtener la revisión actual si existe
        $revision = RevisionTramite::where('tramite_id', $tramiteId)
            ->where('estado', '!=', 'Finalizada')
            ->first();
        
        // Obtener tipo de revisión del parámetro URL si no hay revisión guardada
        $tipoRevision = $request->get('tipo_revision', $revision?->tipo_revision ?? 'Digital');
        
        // Obtener archivos subidos para este trámite específico
        $archivosSubidos = \App\Models\Archivo::where('tramite_id', $tramiteId)
            ->with('catalogoArchivo:id,nombre')
            ->get()
            ->map(function($archivo) {
                return [
                    'id' => $archivo->id,
                    'nombre' => $archivo->catalogoArchivo->nombre ?? $archivo->nombre_original,
                    'nombre_original' => $archivo->nombre_original
                ];
            });
        
        return view('revisiones.revision-digital', compact('viewModel', 'tramite', 'revision', 'tipoRevision', 'archivosSubidos'));
    }

    /**
     * Finaliza la revisión con observaciones (crea la revisión si no existe)
     */
    public function finalizarRevision(Request $request, int $tramiteId)
    {
        $request->validate([
            'observaciones' => 'nullable|string|max:1000',
            'decision' => 'required|in:aprobado,rechazado',
            'tipo_revision' => 'nullable|string|in:Digital,Presencial,Domiciliaria'
        ]);

        // Buscar revisión existente o crear una nueva
        $revision = RevisionTramite::where('tramite_id', $tramiteId)
            ->where('estado', '!=', 'Finalizada')
            ->first();

        if (!$revision) {
            // Crear la revisión al finalizar si no existe
            $tipoRevision = $request->get('tipo_revision', 'Digital');
            
            $revision = RevisionTramite::create([
                'tramite_id' => $tramiteId,
                'tipo_revision' => $tipoRevision,
                'revisor_id' => Auth::id(),
                'estado' => 'Pendiente',
                'fecha_inicio' => now(),
                'intento' => 1
            ]);
        }

        $revision->finalizarRevision($request->observaciones);

        // Actualizar estado del trámite según la decisión
        $tramite = Tramite::findOrFail($tramiteId);
        $tramite->update([
            'status' => $request->decision === 'aprobado' ? 'Aprobado' : 'Rechazado'
        ]);

        return redirect()->route('revisiones.index')
            ->with('success', 'Revisión finalizada correctamente.');
    }

    /**
     * Servir archivo de forma segura
     */
    public function mostrarArchivo(int $id)
    {
        $archivo = \App\Models\Archivo::findOrFail($id);
        
        // Verificar que el archivo existe físicamente
        $rutaCompleta = storage_path('app/public/' . $archivo->ruta);
        
        if (!file_exists($rutaCompleta)) {
            abort(404, 'Archivo no encontrado');
        }

        // Obtener el tipo MIME del archivo
        $mimeType = mime_content_type($rutaCompleta);
        
        return response()->file($rutaCompleta, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $archivo->nombre_original . '"'
        ]);
    }
} 