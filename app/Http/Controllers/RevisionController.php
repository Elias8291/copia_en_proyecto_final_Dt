<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function asignar(Request $request, Tramite $tramite)
    {
        $request->validate([
            'revisor_id' => 'required|exists:users,id'
        ]);

        try {
            $tramite->update([
                'revisado_por' => $request->revisor_id,
                'estado' => 'En_Revision'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Revisor asignado exitosamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar revisor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cambiarEstado(Request $request, Tramite $tramite)
    {
        $request->validate([
            'estado' => 'required|in:Pendiente,En_Revision,Aprobado,Rechazado,Por_Cotejar,Para_Correccion,Cancelado',
            'observaciones' => 'nullable|string|max:1000'
        ]);

        try {
            $updateData = [
                'estado' => $request->estado,
                'revisado_por' => Auth::id()
            ];

            if ($request->filled('observaciones')) {
                $updateData['observaciones'] = $request->observaciones;
            }

            // Si se aprueba o rechaza, marcar fecha de finalización
            if (in_array($request->estado, ['Aprobado', 'Rechazado', 'Cancelado'])) {
                $updateData['fecha_finalizacion'] = now();
            }

            $tramite->update($updateData);
            
            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado exitosamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado: ' . $e->getMessage()
            ], 500);
        }
    }

    public function pendientes()
    {
        $tramites = Tramite::with(['proveedor', 'datosGenerales'])
            ->where('estado', 'Pendiente')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('revision.pendientes', compact('tramites'));
    }

    public function enRevision()
    {
        $tramites = Tramite::with(['proveedor', 'revisadoPor', 'datosGenerales'])
            ->where('estado', 'En_Revision')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('revision.en-revision', compact('tramites'));
    }

    public function misRevisiones()
    {
        $tramites = Tramite::with(['proveedor', 'datosGenerales'])
            ->where('revisado_por', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('revision.mis-revisiones', compact('tramites'));
    }

    public function estadisticas()
    {
        $estadisticas = [
            'total' => Tramite::count(),
            'por_estado' => Tramite::select('estado', DB::raw('count(*) as total'))
                ->groupBy('estado')
                ->pluck('total', 'estado'),
            'por_tipo' => Tramite::select('tipo_tramite', DB::raw('count(*) as total'))
                ->groupBy('tipo_tramite')
                ->pluck('total', 'tipo_tramite'),
            'pendientes' => Tramite::where('estado', 'Pendiente')->count(),
            'en_revision' => Tramite::where('estado', 'En_Revision')->count(),
            'aprobados_hoy' => Tramite::where('estado', 'Aprobado')
                ->whereDate('fecha_finalizacion', today())->count(),
            'mis_asignados' => Tramite::where('revisado_por', Auth::id())
                ->whereIn('estado', ['En_Revision', 'Por_Cotejar'])->count()
        ];

        return response()->json($estadisticas);
    }

    public function historial(Tramite $tramite)
    {
        // Aquí podrías implementar un sistema de historial de cambios
        // Por ahora, solo mostramos la información básica del trámite
        $tramite->load(['proveedor', 'revisadoPor', 'datosGenerales']);
        
        return view('revision.historial', compact('tramite'));
    }

    public function exportar(Request $request)
    {
        $estado = $request->get('estado');
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        $query = Tramite::with(['proveedor', 'revisadoPor', 'datosGenerales']);

        if ($estado) {
            $query->where('estado', $estado);
        }

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        }

        $tramites = $query->get();

        // Aquí implementarías la lógica de exportación (Excel, PDF, etc.)
        // Por ahora, devolvemos JSON
        return response()->json([
            'success' => true,
            'data' => $tramites,
            'total' => $tramites->count()
        ]);
    }
}