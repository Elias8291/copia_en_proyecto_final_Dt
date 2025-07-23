<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Tramite;
use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CitasController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $allowedPerPage = [5, 10, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $citas = Cita::with(['tramite', 'proveedor', 'atendidoPor'])
            ->orderBy('fecha_cita', 'desc')
            ->paginate($perPage);
        
        $citas->appends($request->query());

        return view('citas.index', compact('citas', 'perPage'));
    }

    public function create()
    {
        $tramites = Tramite::with('datosGenerales')->get();
        $proveedores = Proveedor::all();
        $usuarios = User::all();

        return view('citas.create', compact('tramites', 'proveedores', 'usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tramite_id' => 'required|exists:tramites,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha_cita' => 'required|date|after:now',
            'tipo_cita' => 'required|in:Revision,Cotejo,Entrega',
            'estado' => 'required|in:Programada,Confirmada,Cancelada,Reagendada,Completada',
            'atendido_por' => 'nullable|exists:users,id'
        ]);

        try {
            $cita = Cita::create($request->all());
            
            return redirect()->route('citas.index')
                ->with('success', 'Cita creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear la cita: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Cita $cita)
    {
        $cita->load(['tramite.datosGenerales', 'proveedor', 'atendidoPor']);
        
        return view('citas.show', compact('cita'));
    }

    public function edit(Cita $cita)
    {
        $tramites = Tramite::with('datosGenerales')->get();
        $proveedores = Proveedor::all();
        $usuarios = User::all();

        return view('citas.edit', compact('cita', 'tramites', 'proveedores', 'usuarios'));
    }

    public function update(Request $request, Cita $cita)
    {
        $request->validate([
            'tramite_id' => 'required|exists:tramites,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha_cita' => 'required|date',
            'tipo_cita' => 'required|in:Revision,Cotejo,Entrega',
            'estado' => 'required|in:Programada,Confirmada,Cancelada,Reagendada,Completada',
            'atendido_por' => 'nullable|exists:users,id'
        ]);

        try {
            $cita->update($request->all());
            
            return redirect()->route('citas.index')
                ->with('success', 'Cita actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la cita: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Cita $cita)
    {
        try {
            $cita->delete();
            
            return redirect()->route('citas.index')
                ->with('success', 'Cita eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar la cita: ' . $e->getMessage());
        }
    }

    // Método para cambiar el estado de una cita
    public function cambiarEstado(Request $request, Cita $cita)
    {
        $request->validate([
            'estado' => 'required|in:Programada,Confirmada,Cancelada,Reagendada,Completada'
        ]);

        try {
            $cita->update(['estado' => $request->estado]);
            
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

    // Método para obtener estadísticas de citas
    public function estadisticas()
    {
        $estadisticas = [
            'total' => Cita::count(),
            'por_estado' => Cita::select('estado', DB::raw('count(*) as total'))
                ->groupBy('estado')
                ->pluck('total', 'estado'),
            'por_tipo' => Cita::select('tipo_cita', DB::raw('count(*) as total'))
                ->groupBy('tipo_cita')
                ->pluck('total', 'tipo_cita'),
            'hoy' => Cita::whereDate('fecha_cita', today())->count(),
            'esta_semana' => Cita::whereBetween('fecha_cita', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count()
        ];

        return response()->json($estadisticas);
    }
}