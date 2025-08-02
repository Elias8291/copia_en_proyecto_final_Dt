<?php

declare(strict_types=1);

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Services\CitaService;
use Illuminate\Support\Facades\Log;

/**
 * Servicio para gestión de citas específicas de trámites
 * Responsabilidad: Manejar la lógica de citas relacionadas con trámites
 */
class CitaTramiteService
{
    public function __construct(
        private CitaService $citaService
    ) {}

    /**
     * Agenda una cita automática para cotejo de trámite
     */
    public function agendarCotejo(Tramite $tramite)
    {
        try {
            $cita = $this->citaService->agendarCitaCotejo($tramite);

            if ($cita) {
                Log::info('Cita de cotejo agendada automáticamente', [
                    'cita_id' => $cita->id,
                    'tramite_id' => $tramite->id,
                    'fecha_cita' => $cita->fecha_cita
                ]);
                
                return $cita;
            } else {
                Log::warning('No se pudo agendar cita automática para cotejo', [
                    'tramite_id' => $tramite->id
                ]);
                
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Error al agendar cita de cotejo', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Verifica si se puede reagendar una cita para el trámite
     */
    public function puedeReagendar(Tramite $tramite): bool
    {
        return $this->citaService->puedeReagendar($tramite);
    }

    /**
     * Obtiene la cita activa para un trámite
     */
    public function obtenerCitaActiva(Tramite $tramite)
    {
        return $this->citaService->obtenerCitaActiva($tramite);
    }

    /**
     * Reagenda una cita existente para un trámite
     */
    public function reagendarCita(Tramite $tramite)
    {
        try {
            // Verificar si se puede reagendar
            if (!$this->puedeReagendar($tramite)) {
                throw new \Exception('Se ha alcanzado el límite máximo de reagendamientos para este trámite.');
            }
            
            // Verificar si ya existe una cita activa
            $citaExistente = $this->obtenerCitaActiva($tramite);
            
            if ($citaExistente) {
                // Reagendar cita existente
                $cita = $this->citaService->reagendarCitaTramite($tramite);
                $tipoAccion = 'reagendada';
            } else {
                // Crear nueva cita
                $cita = $this->agendarCotejo($tramite);
                $tipoAccion = 'agendada';
            }

            if ($cita) {
                $mensaje = "Cita {$tipoAccion} exitosamente para el día {$cita->fecha_cita->format('d/m/Y')} a las {$cita->fecha_cita->format('H:i')}.";
                
                Log::info("Cita {$tipoAccion} automáticamente", [
                    'tramite_id' => $tramite->id,
                    'cita_id' => $cita->id,
                    'fecha_cita' => $cita->fecha_cita
                ]);
                
                return [
                    'success' => true,
                    'cita' => $cita,
                    'mensaje' => $mensaje,
                    'tipo_accion' => $tipoAccion
                ];
            }

            return [
                'success' => false,
                'mensaje' => "No se pudo {$tipoAccion} la cita. No hay horarios disponibles.",
                'tipo_accion' => $tipoAccion
            ];

        } catch (\Exception $e) {
            Log::error('Error al reagendar cita automática', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'mensaje' => 'Error al procesar la cita: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtiene información completa de reagendamientos para un trámite
     */
    public function obtenerInfoReagendamientos(Tramite $tramite): array
    {
        return $this->citaService->obtenerInfoReagendamientos($tramite);
    }

    /**
     * Verifica si existe una cita activa para el trámite
     */
    public function existeCitaActiva(Tramite $tramite): bool
    {
        return $this->citaService->existeCitaActiva($tramite);
    }
}