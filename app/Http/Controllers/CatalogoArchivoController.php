<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CatalogoArchivo;
use App\Services\CatalogoArchivoService;
use App\Http\Requests\CatalogoArchivoRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\Response;

class CatalogoArchivoController extends Controller
{
    public function __construct(
        private readonly CatalogoArchivoService $catalogoArchivoService
    ) {}

    /**
     * Mostrar lista de archivos del catálogo
     */
    public function index(Request $request): View|JsonResponse
    {
        $archivos = $this->catalogoArchivoService->getArchivosConFiltros($request);

        // Si es una petición AJAX, retornar JSON para filtros dinámicos
        if ($request->ajax()) {
            return $this->handleAjaxRequest($archivos);
        }

        return view('archivos.index', compact('archivos'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create(): View
    {
        return view('archivos.create');
    }

    /**
     * Almacenar nuevo archivo en el catálogo
     */
    public function store(CatalogoArchivoRequest $request): RedirectResponse
    {
        try {
            // Crear archivo usando el servicio
            $this->catalogoArchivoService->crearArchivo($request->getValidatedData());

            return redirect()
                ->route('archivos.index')
                ->with('success', 'Archivo creado exitosamente.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al crear el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(CatalogoArchivo $archivo): View
    {
        return view('archivos.edit', compact('archivo'));
    }

    /**
     * Actualizar archivo existente
     */
    public function update(CatalogoArchivoRequest $request, CatalogoArchivo $archivo): RedirectResponse
    {
        try {
            // Actualizar archivo usando el servicio
            $this->catalogoArchivoService->actualizarArchivo($archivo, $request->getValidatedData());

            return redirect()
                ->route('archivos.index')
                ->with('success', 'Archivo actualizado exitosamente.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar archivo del catálogo
     */
    public function destroy(CatalogoArchivo $archivo): RedirectResponse
    {
        try {
            $this->catalogoArchivoService->eliminarArchivo($archivo);
            
            return redirect()
                ->route('archivos.index')
                ->with('success', 'Archivo eliminado exitosamente.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al eliminar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar estado de visibilidad via AJAX
     */
    public function toggleVisibility(CatalogoArchivo $archivo): JsonResponse
    {
        try {
            $this->catalogoArchivoService->toggleVisibilidad($archivo);
            
            return response()->json([
                'success' => true,
                'message' => 'Estado cambiado exitosamente.',
                'nuevo_estado' => $archivo->fresh()->es_visible
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * API para búsqueda de archivos (autocomplete)
     */
    public function buscar(Request $request): JsonResponse
    {
        if (!$request->filled('termino')) {
            return response()->json([]);
        }

        $archivos = $this->catalogoArchivoService->buscarPorNombre(
            $request->termino,
            (int) $request->get('limite', 10)
        );

        return response()->json($archivos);
    }

    /**
     * Obtener estadísticas del catálogo
     */
    public function estadisticas(): JsonResponse
    {
        $estadisticas = $this->catalogoArchivoService->getEstadisticas();
        
        return response()->json($estadisticas);
    }

    /**
     * Manejar peticiones AJAX para filtros dinámicos
     */
    private function handleAjaxRequest($archivos): JsonResponse
    {
        $html = view('archivos.partials.table', compact('archivos'))->render();
        $paginationHtml = view('archivos.partials.pagination', compact('archivos'))->render();
        
        return response()->json([
            'html' => $html,
            'pagination' => $paginationHtml,
            'total' => $archivos->total()
        ]);
    }
} 