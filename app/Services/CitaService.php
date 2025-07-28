<?php

namespace App\Services;

use App\Models\Cita;
use App\Models\Tramite;
use App\Models\DiaInhabil;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CitaService
{
    private const HORA_INICIO = 9; // 9:00 AM
    private const HORA_FIN = 14; // 2:00 PM
    private const DURACION_CITA = 30; // 30 minutos por cita

    /**
     * Verificar disponibilidad
     */
    public function verificarDisponibilidad($fechaCita)
    {
        return !Cita::where('fecha_cita', $fechaCita)->exists();
    }

    /**
     * Cancelar cita
     */
    public function cancelarCita($citaId, $motivo = null)
    {
        $cita = Cita::findOrFail($citaId);
        $cita->estado = 'Cancelada';
        if ($motivo) {
            $cita->observaciones = $motivo;
        }
        $cita->save();
        return $cita;
    }

    /**
     * Agendar cita automática para cotejo
     */
    public function agendarCitaCotejo(Tramite $tramite): ?Cita
    {
        try {
            DB::beginTransaction();

            // Obtener el próximo horario disponible
            $fechaCita = $this->obtenerProximoHorarioDisponible();

            if (!$fechaCita) {
                Log::warning('No se pudo encontrar horario disponible para cita de cotejo', [
                    'tramite_id' => $tramite->id
                ]);
                DB::rollBack();
                return null;
            }

            // Crear la cita
            $cita = Cita::create([
                'tramite_id' => $tramite->id,
                'user_id' => $tramite->proveedor->user_id,
                'fecha_cita' => $fechaCita,
                'tipo_cita' => 'Cotejo',
                'estado' => 'Programada',
                'motivo' => 'Cotejo presencial de documentos para trámite #' . $tramite->id,
                'observaciones' => 'Cita automática generada al enviar trámite a cotejo'
            ]);

            Log::info('Cita de cotejo agendada automáticamente', [
                'cita_id' => $cita->id,
                'tramite_id' => $tramite->id,
                'fecha_cita' => $fechaCita->format('Y-m-d H:i:s')
            ]);

            DB::commit();
            return $cita;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al agendar cita de cotejo automática', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Obtener el próximo horario disponible para cita
     */
    private function obtenerProximoHorarioDisponible(): ?Carbon
    {
        $fechaActual = Carbon::now();
        
        // Si es después de las 2 PM, empezar desde el siguiente día
        if ($fechaActual->hour >= self::HORA_FIN) {
            $fechaActual->addDay();
        }

        // Buscar el próximo día hábil
        $diaHabil = DiaInhabil::proximoDiaHabil($fechaActual);
        
        // Buscar horario disponible en los próximos 30 días
        for ($dia = 0; $dia < 30; $dia++) {
            $fechaBusqueda = $diaHabil->copy()->addDays($dia);
            
            // Verificar que sea día hábil
            if (!DiaInhabil::esHabil($fechaBusqueda)) {
                continue;
            }

            // Buscar horario disponible en ese día
            $horarioDisponible = $this->buscarHorarioEnDia($fechaBusqueda);
            
            if ($horarioDisponible) {
                return $horarioDisponible;
            }
        }

        return null;
    }

    /**
     * Buscar horario disponible en un día específico
     */
    private function buscarHorarioEnDia(Carbon $fecha): ?Carbon
    {
        // Obtener todas las citas del día
        $citasDelDia = Cita::whereDate('fecha_cita', $fecha->format('Y-m-d'))
            ->where('estado', '!=', 'Cancelada')
            ->orderBy('fecha_cita')
            ->get();

        // Horarios disponibles (cada 30 minutos de 9 AM a 2 PM)
        $horariosDisponibles = [];
        $horaActual = $fecha->copy()->setTime(self::HORA_INICIO, 0, 0);
        $horaFin = $fecha->copy()->setTime(self::HORA_FIN, 0, 0);

        while ($horaActual < $horaFin) {
            $horariosDisponibles[] = $horaActual->copy();
            $horaActual->addMinutes(self::DURACION_CITA);
        }

        // Filtrar horarios ocupados
        foreach ($citasDelDia as $cita) {
            $fechaCita = Carbon::parse($cita->fecha_cita);
            
            // Remover horarios que se solapan
            $horariosDisponibles = array_filter($horariosDisponibles, function($horario) use ($fechaCita) {
                return $horario->diffInMinutes($fechaCita) >= self::DURACION_CITA;
            });
        }

        // Retornar el primer horario disponible
        return !empty($horariosDisponibles) ? reset($horariosDisponibles) : null;
    }

    /**
     * Verificar si una fecha y hora específica está disponible
     */
    public function verificarDisponibilidadFechaHora(Carbon $fechaHora): bool
    {
        // Verificar que sea día hábil
        if (!DiaInhabil::esHabil($fechaHora)) {
            return false;
        }

        // Verificar que esté en horario laboral (9 AM a 2 PM)
        $hora = $fechaHora->hour;
        if ($hora < self::HORA_INICIO || $hora >= self::HORA_FIN) {
            return false;
        }

        // Verificar que no haya citas solapadas
        $citasSolapadas = Cita::whereDate('fecha_cita', $fechaHora->format('Y-m-d'))
            ->where('estado', '!=', 'Cancelada')
            ->where(function($query) use ($fechaHora) {
                $query->where('fecha_cita', '<=', $fechaHora)
                      ->where('fecha_cita', '>', $fechaHora->copy()->subMinutes(self::DURACION_CITA));
            })
            ->exists();

        return !$citasSolapadas;
    }

    /**
     * Obtener horarios disponibles para una fecha específica
     */
    public function obtenerHorariosDisponibles(Carbon $fecha): array
    {
        if (!DiaInhabil::esHabil($fecha)) {
            return [];
        }

        $horarios = [];
        $horaActual = $fecha->copy()->setTime(self::HORA_INICIO, 0, 0);
        $horaFin = $fecha->copy()->setTime(self::HORA_FIN, 0, 0);

        while ($horaActual < $horaFin) {
            if ($this->verificarDisponibilidadFechaHora($horaActual)) {
                $horarios[] = $horaActual->copy();
            }
            $horaActual->addMinutes(self::DURACION_CITA);
        }

        return $horarios;
    }

    /**
     * Obtener próximos días hábiles disponibles
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
} 