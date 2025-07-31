<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\Tramite;
use App\Services\CitaService;
use App\Services\NotificacionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EstadoController extends Controller
{
    protected $citaService;
    protected $notificacionService;

    public function __construct(CitaService $citaService, NotificacionService $notificacionService)
    {
        $this->citaService = $citaService;
        $this->notificacionService = $notificacionService;
    }

    /**
     * Obtiene todos los estados
     */
    public function index()
    {
        try {
            $estados = Estado::orderBy('nombre')->get();

            return response()->json([
                'success' => true,
                'data' => $estados,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los estados',
            ], 500);
        }
    }

    /**
     * Reagendar cita desde la vista de estado
     */
    public function reagendarCita(Tramite $tramite)
    {
        try {
            // Verificar si ya existe una cita activa
            $citaExistente = $this->citaService->obtenerCitaActiva($tramite);
            
            if ($citaExistente) {
                // Reagendar cita existente
                $cita = $this->citaService->reagendarCitaTramite($tramite);
                $mensaje = "Cita reagendada exitosamente para el día {$cita->fecha_cita->format('d/m/Y')} a las {$cita->fecha_cita->format('H:i')}.";
                $tipoAccion = 'reagendada';
            } else {
                // Crear nueva cita
                $cita = $this->citaService->agendarCitaCotejo($tramite);
                $mensaje = "Cita agendada exitosamente para el día {$cita->fecha_cita->format('d/m/Y')} a las {$cita->fecha_cita->format('H:i')}.";
                $tipoAccion = 'agendada';
            }

            if ($cita) {
                // Crear notificación
                $this->notificacionService->crearNotificacion(
                    $tramite->proveedor->user->id,
                    $tramite->id,
                    'Cita',
                    'Cita ' . ucfirst($tipoAccion) . ' Automáticamente',
                    $mensaje
                );

                // Redirigir con mensaje de éxito
                return redirect()->back()
                    ->with('success', $mensaje);
            } else {
                // Redirigir con mensaje de error
                return redirect()->back()
                    ->with('error', 'No se pudo ' . $tipoAccion . ' la cita. No hay horarios disponibles.');
            }
        } catch (\Exception $e) {
            Log::error('Error al reagendar cita desde estado: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error al procesar la cita: ' . $e->getMessage());
        }
    }
}
