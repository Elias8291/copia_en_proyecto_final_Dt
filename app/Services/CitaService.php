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
        if ($fechaActual->hour >= self::HORA_FIN) $fechaActual->addDay();

        for ($dia = 0; $dia < 30; $dia++) {
            $fechaBusqueda = $fechaActual->copy()->addDays($dia);
            if (DiaInhabil::esHabil($fechaBusqueda)) {
                $horarioDisponible = $this->buscarHorarioEnDia($fechaBusqueda);
                if ($horarioDisponible) return $horarioDisponible;
            }
        }

        return null;
    }

    /**
     * Buscar horario en día específico
     */
    private function buscarHorarioEnDia(Carbon $fecha): ?Carbon
    {
        $citasDelDia = Cita::whereDate('fecha_cita', $fecha->format('Y-m-d'))
            ->where('estado', '!=', 'Cancelada')
            ->pluck('fecha_cita')
            ->map(fn($fecha) => Carbon::parse($fecha)->format('H:i'))
            ->toArray();

        $horaActual = $fecha->copy()->setTime(self::HORA_INICIO, 0, 0);
        $horaFin = $fecha->copy()->setTime(self::HORA_FIN, 0, 0);

        while ($horaActual < $horaFin) {
            if (!in_array($horaActual->format('H:i'), $citasDelDia)) {
                return $horaActual;
            }
            $horaActual->addMinutes(self::DURACION_CITA);
        }

        return null;
    }
} 