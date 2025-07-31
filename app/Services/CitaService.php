<?php

namespace App\Services;

use App\Models\Cita;
use App\Models\Tramite;
use App\Models\DiaInhabil;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CitaService
{
    private const HORA_INICIO = 9;
    private const HORA_FIN = 14;
    private const DURACION_CITA = 15;

    /**
     * Agendar cita automática para cotejo
     */
    public function agendarCitaCotejo(Tramite $tramite): ?Cita
    {
        try {
            DB::beginTransaction();

            $tramite->load('proveedor');
            $fechaCita = $this->obtenerProximoHorarioDisponible();
            
            if (!$fechaCita || !$tramite->proveedor->usuario_id) {
                DB::rollBack();
                return null;
            }

            $cita = Cita::create([
                'tramite_id' => $tramite->id,
                'id_tramite' => $tramite->id,
                'user_id' => $tramite->proveedor->usuario_id,
                'fecha_cita' => $fechaCita,
                'tipo_cita' => 'Cotejo',
                'estado' => 'Programada',
                'contador_reagendamientos' => 0,
                'max_reagendamientos' => 2,
                'motivo' => 'Cotejo presencial de documentos para trámite #' . $tramite->id,
                'observaciones' => 'Cita automática generada al enviar trámite a cotejo'
            ]);

            DB::commit();
            return $cita;

        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    /**
     * Reagendar cita existente para un trámite
     */
    public function reagendarCitaTramite(Tramite $tramite): ?Cita
    {
        try {
            DB::beginTransaction();

            // Buscar cita existente para este trámite
            $citaExistente = Cita::where('tramite_id', $tramite->id)
                ->whereIn('estado', ['Programada', 'Confirmada', 'Reagendada'])
                ->first();

            if (!$citaExistente) {
                // Si no existe cita, crear una nueva
                return $this->agendarCitaCotejo($tramite);
            }

            // Verificar si ya alcanzó el límite de reagendamientos
            if ($citaExistente->contador_reagendamientos >= $citaExistente->max_reagendamientos) {
                DB::rollBack();
                throw new \Exception('Se ha alcanzado el límite máximo de reagendamientos para este trámite.');
            }

            // Obtener nuevo horario disponible
            $tramite->load('proveedor');
            $nuevaFechaCita = $this->obtenerProximoHorarioDisponible();
            
            if (!$nuevaFechaCita || !$tramite->proveedor->usuario_id) {
                DB::rollBack();
                return null;
            }

            // Actualizar la cita existente con la nueva fecha
            $citaExistente->fecha_cita = $nuevaFechaCita;
            $citaExistente->estado = 'Reagendada';
            $citaExistente->contador_reagendamientos = $citaExistente->contador_reagendamientos + 1;
            $citaExistente->observaciones = ($citaExistente->observaciones ?: '') . ' - Cita reagendada automáticamente el ' . now()->format('d/m/Y H:i') . ' (Reagendamiento #' . $citaExistente->contador_reagendamientos . ')';
            $citaExistente->save();

            DB::commit();
            return $citaExistente;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al reagendar cita: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verificar si existe una cita activa para un trámite
     */
    public function existeCitaActiva(Tramite $tramite): bool
    {
        return Cita::where('tramite_id', $tramite->id)
            ->whereIn('estado', ['Programada', 'Confirmada', 'Reagendada'])
            ->exists();
    }

    /**
     * Obtener cita activa para un trámite
     */
    public function obtenerCitaActiva(Tramite $tramite): ?Cita
    {
        return Cita::where('tramite_id', $tramite->id)
            ->whereIn('estado', ['Programada', 'Confirmada', 'Reagendada'])
            ->first();
    }

    /**
     * Cancelar cita
     */
    public function cancelarCita($citaId, $motivo = null): Cita
    {
        $cita = Cita::findOrFail($citaId);
        $cita->estado = 'Cancelada';
        if ($motivo) $cita->observaciones = $motivo;
        $cita->save();
        return $cita;
    }

    /**
     * Verificar disponibilidad
     */
    public function verificarDisponibilidadFechaHora(Carbon $fechaHora): bool
    {
        if (!DiaInhabil::esHabil($fechaHora)) return false;
        if ($fechaHora->hour < self::HORA_INICIO || $fechaHora->hour >= self::HORA_FIN) return false;
        if ($fechaHora->minute % self::DURACION_CITA !== 0) return false;

        return !Cita::whereDate('fecha_cita', $fechaHora->format('Y-m-d'))
            ->where('estado', '!=', 'Cancelada')
            ->whereTime('fecha_cita', $fechaHora->format('H:i:s'))
            ->exists();
    }

    /**
     * Obtener horarios disponibles
     */
    public function obtenerHorariosDisponibles(Carbon $fecha): array
    {
        if (!DiaInhabil::esHabil($fecha)) return [];

        $horariosOcupados = Cita::whereDate('fecha_cita', $fecha->format('Y-m-d'))
            ->where('estado', '!=', 'Cancelada')
            ->pluck('fecha_cita')
            ->map(fn($fecha) => Carbon::parse($fecha)->format('H:i'))
            ->toArray();

        $horarios = [];
        $horaActual = $fecha->copy()->setTime(self::HORA_INICIO, 0, 0);
        $horaFin = $fecha->copy()->setTime(self::HORA_FIN, 0, 0);

        while ($horaActual < $horaFin) {
            if (!in_array($horaActual->format('H:i'), $horariosOcupados)) {
                $horarios[] = $horaActual->copy();
            }
            $horaActual->addMinutes(self::DURACION_CITA);
        }

        return $horarios;
    }

    /**
     * Obtener días hábiles
     */
    public function obtenerProximosDiasHabiles(int $cantidad = 10): array
    {
        $dias = [];
        $fechaActual = Carbon::now();

        for ($i = 0; count($dias) < $cantidad; $i++) {
            $fecha = $fechaActual->copy()->addDays($i);
            if (DiaInhabil::esHabil($fecha)) {
                $dias[] = $fecha->format('Y-m-d');
            }
        }

        return $dias;
    }

    /**
     * Total de citas por día
     */
    public function obtenerTotalCitasPorDia(): int
    {
        return (self::HORA_FIN - self::HORA_INICIO) * (60 / self::DURACION_CITA);
    }

    /**
     * Obtener próximo horario disponible
     */
    private function obtenerProximoHorarioDisponible(): ?Carbon
    {
        $fechaActual = Carbon::now();
        
        // Si es después de las 14:00, empezar desde mañana
        if ($fechaActual->hour >= self::HORA_FIN) {
            $fechaActual->addDay();
        }
        
        // Buscar solo en los próximos 7 días para mayor velocidad
        for ($dia = 0; $dia < 7; $dia++) {
            $fechaBusqueda = $fechaActual->copy()->addDays($dia);
            
            if (DiaInhabil::esHabil($fechaBusqueda)) {
                $horarioDisponible = $this->buscarHorarioEnDia($fechaBusqueda);
                if ($horarioDisponible) {
                    return $horarioDisponible;
                }
            }
        }

        return null;
    }

    /**
     * Buscar horario en día específico
     */
    private function buscarHorarioEnDia(Carbon $fecha): ?Carbon
    {
        // Obtener todas las citas del día en una sola consulta
        $citasDelDia = Cita::whereDate('fecha_cita', $fecha->format('Y-m-d'))
            ->whereIn('estado', ['Programada', 'Confirmada', 'Reagendada'])
            ->pluck('fecha_cita')
            ->map(fn($fecha) => Carbon::parse($fecha)->format('H:i'))
            ->toArray();

        $horaActual = $fecha->copy()->setTime(self::HORA_INICIO, 0, 0);
        $horaFin = $fecha->copy()->setTime(self::HORA_FIN, 0, 0);

        // Si es hoy, empezar desde la próxima hora disponible
        if ($fecha->isToday()) {
            $horaActual = Carbon::now()->addMinutes(15)->startOfMinute();
            $horaActual->setMinute(($horaActual->minute / self::DURACION_CITA) * self::DURACION_CITA);
        }

        while ($horaActual < $horaFin) {
            if (!in_array($horaActual->format('H:i'), $citasDelDia)) {
                return $horaActual;
            }
            $horaActual->addMinutes(self::DURACION_CITA);
        }

        return null;
    }

    /**
     * Verificar si se puede reagendar una cita
     */
    public function puedeReagendar(Tramite $tramite): bool
    {
        $citaActiva = $this->obtenerCitaActiva($tramite);
        
        if (!$citaActiva) {
            return true; // No hay cita activa, se puede crear una nueva
        }
        
        return $citaActiva->contador_reagendamientos < $citaActiva->max_reagendamientos;
    }

    /**
     * Obtener información de reagendamientos
     */
    public function obtenerInfoReagendamientos(Tramite $tramite): array
    {
        $citaActiva = $this->obtenerCitaActiva($tramite);
        
        if (!$citaActiva) {
            return [
                'puede_reagendar' => true,
                'reagendamientos_usados' => 0,
                'reagendamientos_disponibles' => 2,
                'limite_alcanzado' => false
            ];
        }
        
        return [
            'puede_reagendar' => $citaActiva->contador_reagendamientos < $citaActiva->max_reagendamientos,
            'reagendamientos_usados' => $citaActiva->contador_reagendamientos,
            'reagendamientos_disponibles' => $citaActiva->max_reagendamientos - $citaActiva->contador_reagendamientos,
            'limite_alcanzado' => $citaActiva->contador_reagendamientos >= $citaActiva->max_reagendamientos
        ];
    }
} 