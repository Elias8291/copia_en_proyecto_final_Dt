<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Tramite;
use App\Services\CitaService;
use App\Services\NotificacionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CitaController extends Controller
{
    protected $citaService;
    protected $notificacionService;

    public function __construct(CitaService $citaService, NotificacionService $notificacionService)
    {
        $this->citaService = $citaService;
        $this->notificacionService = $notificacionService;
    }

    /**
     * Muestra el listado de citas
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $estado = $request->get('estado');
        $tipo = $request->get('tipo');

        $query = Cita::with(['tramite', 'proveedor', 'atendidoPor']);

        if ($estado) {
            $query->where('estado', $estado);
        }

        if ($tipo) {
            $query->where('tipo_cita', $tipo);
        }

        $citas = $query->orderBy('fecha_cita', 'desc')->paginate($perPage);

        return view('citas.index', compact('citas', 'perPage'));
    }

    /**
     * Muestra el formulario para crear una nueva cita (general)
     */
    public function createGeneral()
    {
        // Obtener trámites disponibles para crear citas
        $tramites = Tramite::where('estado', 'Por_Cotejar')
            ->orWhere('estado', 'En_Revision')
            ->with('proveedor')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('citas.create-general', compact('tramites'));
    }

    /**
     * Muestra el formulario para crear una cita general
     */
    public function createGeneralForm()
    {
        // Obtener todos los proveedores para el selector
        $proveedores = \App\Models\Proveedor::with('user')->orderBy('razon_social')->get();
        
        return view('citas.create-general-form', compact('proveedores'));
    }

    /**
     * Almacena una nueva cita general (sin trámite específico)
     */
    public function storeGeneral(Request $request)
    {
        $request->validate([
            'fecha_cita' => 'required|date|after:now',
            'tipo_cita' => 'required|string|in:Cotejo,Consulta,Otro,Reunion,Administrativa',
            'observaciones' => 'nullable|string|max:500',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'motivo' => 'required|string|max:200'
        ]);

        try {
            // Verificar disponibilidad
            if (!$this->citaService->verificarDisponibilidad($request->fecha_cita)) {
                return back()->withErrors(['fecha_cita' => 'El horario seleccionado no está disponible.']);
            }

            $cita = Cita::create([
                'tramite_id' => null, // Cita general sin trámite
                'proveedor_id' => $request->proveedor_id,
                'fecha_cita' => $request->fecha_cita,
                'tipo_cita' => $request->tipo_cita,
                'estado' => 'Programada',
                'observaciones' => $request->observaciones,
                'motivo' => $request->motivo,
                'atendido_por' => Auth::id()
            ]);

            // Notificar al proveedor si se especificó uno
            if ($request->proveedor_id) {
                $proveedor = \App\Models\Proveedor::find($request->proveedor_id);
                if ($proveedor && $proveedor->user) {
                    $this->notificacionService->crearNotificacion(
                        $proveedor->user->id,
                        $cita->id,
                        'Cita',
                        'Nueva cita programada',
                        "Se ha programado una cita para el {$cita->fecha_cita->format('d/m/Y H:i')}. Motivo: {$request->motivo}"
                    );
                }
            }

            return redirect()->route('citas.show', $cita)
                ->with('success', 'Cita general agendada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al crear cita general', [
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Error al agendar la cita: ' . $e->getMessage()]);
        }
    }

    /**
     * Muestra el formulario para crear una nueva cita para un trámite específico
     */
    public function create(Tramite $tramite)
    {
        return view('citas.create', compact('tramite'));
    }

    /**
     * Almacena una nueva cita
     */
    public function store(Request $request, Tramite $tramite)
    {
        $request->validate([
            'fecha_cita' => 'required|date|after:now',
            'tipo_cita' => 'required|string|in:Cotejo,Consulta,Otro',
            'observaciones' => 'nullable|string|max:500'
        ]);

        try {
            // Verificar disponibilidad
            if (!$this->citaService->verificarDisponibilidad($request->fecha_cita)) {
                return back()->withErrors(['fecha_cita' => 'El horario seleccionado no está disponible.']);
            }

            $cita = $this->citaService->agendarCitaCotejo(
                $tramite,
                $request->fecha_cita,
                Auth::id()
            );

            return redirect()->route('citas.show', $cita)
                ->with('success', 'Cita agendada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al crear cita', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Error al agendar la cita: ' . $e->getMessage()]);
        }
    }

    /**
     * Muestra una cita específica
     */
    public function show(Cita $cita)
    {
        $cita->load(['tramite', 'proveedor', 'atendidoPor']);
        return view('citas.show', compact('cita'));
    }

    /**
     * Muestra el formulario para editar una cita
     */
    public function edit(Cita $cita)
    {
        return view('citas.edit', compact('cita'));
    }

    /**
     * Actualiza una cita
     */
    public function update(Request $request, Cita $cita)
    {
        $request->validate([
            'fecha_cita' => 'required|date',
            'estado' => 'required|string|in:Agendada,En Proceso,Completada,Cancelada',
            'observaciones' => 'nullable|string|max:500'
        ]);

        try {
            $cita->update([
                'fecha_cita' => $request->fecha_cita,
                'estado' => $request->estado,
                'observaciones' => $request->observaciones
            ]);

            return redirect()->route('citas.show', $cita)
                ->with('success', 'Cita actualizada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar cita', [
                'cita_id' => $cita->id,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Error al actualizar la cita.']);
        }
    }

    /**
     * Cancela una cita
     */
    public function cancelar(Request $request, Cita $cita)
    {
        $request->validate([
            'motivo' => 'nullable|string|max:500'
        ]);

        try {
            $this->citaService->cancelarCita($cita->id, $request->motivo);

            return response()->json([
                'success' => true,
                'message' => 'Cita cancelada exitosamente.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al cancelar cita', [
                'cita_id' => $cita->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar la cita.'
            ], 500);
        }
    }

    /**
     * Obtiene las citas de un trámite específico
     */
    public function citasTramite(Tramite $tramite)
    {
        $citas = $this->citaService->obtenerCitasTramite($tramite->id);
        
        return response()->json([
            'success' => true,
            'citas' => $citas
        ]);
    }

    /**
     * Verifica disponibilidad de horario
     */
    public function verificarDisponibilidad(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date|after:now'
        ]);

        $disponible = $this->citaService->verificarDisponibilidad($request->fecha);

        return response()->json([
            'success' => true,
            'disponible' => $disponible
        ]);
    }
} 