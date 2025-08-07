<?php

namespace App\Services;

use App\Models\Tramite;
use App\Models\Cita;
use App\Models\User;
use App\Models\DiaInhabil;
use App\Enums\UserRole;
use App\Enums\TramiteStatus;
use App\Services\NotificacionService;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CitasService
{
    private const HORA_INICIO = 9;
    private const HORA_FIN = 14;
    private const DURACION_CITA_MINUTOS = 25;
    private const DIAS_LABORALES = [1, 2, 3, 4, 5];

    protected NotificacionService $notificacionService;

    public function __construct(NotificacionService $notificacionService)
    {
        $this->notificacionService = $notificacionService;
    }

    public function agendarCitaRevisionDigital(int $tramiteId): array
    {
        \Log::info('Iniciando agendamiento de cita para trámite', ['tramite_id' => $tramiteId]);
        
        $tramite = Tramite::findOrFail($tramiteId);
        $revisores = $this->obtenerRevisoresPresenciales();
        
        \Log::info('Revisores presenciales encontrados', [
            'tramite_id' => $tramiteId,
            'cantidad_revisores' => $revisores->count(),
            'revisores' => $revisores->pluck('name', 'id')->toArray()
        ]);
        
        if ($revisores->isEmpty()) {
            \Log::warning('No hay revisores presenciales disponibles', ['tramite_id' => $tramiteId]);
            return ['success' => false, 'message' => 'No hay revisores presenciales disponibles'];
        }

        $fechaHora = $this->buscarPrimerHorarioDisponible($revisores);
        
        \Log::info('Horario encontrado', [
            'tramite_id' => $tramiteId,
            'fecha_hora' => $fechaHora ? $fechaHora['datetime'] : null,
            'revisor_id' => $fechaHora ? $fechaHora['revisor_id'] : null
        ]);
        
        if (!$fechaHora) {
            \Log::warning('No hay horarios disponibles', ['tramite_id' => $tramiteId]);
            return ['success' => false, 'message' => 'No hay horarios disponibles en los próximos 30 días'];
        }

        try {
            $cita = Cita::create([
                'tramite_id' => $tramiteId,
                'tipo_cita' => 'Presencial',
                'fecha_cita' => $fechaHora['datetime'],
                'estado' => 'Asignada',
                'asignado_a' => $fechaHora['revisor_id'],
                'intento' => 1
            ]);

            \Log::info('Cita creada exitosamente', [
                'tramite_id' => $tramiteId,
                'cita_id' => $cita->id,
                'fecha_cita' => $cita->fecha_cita,
                'revisor_id' => $cita->asignado_a
            ]);

            // Mantener el estado en revisión digital, no cambiar a presencial
            // $tramite->update(['status' => TramiteStatus::REVISION_PRESENCIAL->value]);

            $this->notificacionService->notificarCitaAgendada($cita);

            return [
                'success' => true,
                'cita' => $cita,
                'revisor' => User::find($fechaHora['revisor_id']),
                'fecha_formateada' => Carbon::parse($fechaHora['datetime'])->format('d/m/Y H:i'),
                'message' => 'Cita agendada exitosamente'
            ];
        } catch (\Exception $e) {
            \Log::error('Error al crear cita', [
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return ['success' => false, 'message' => 'Error al crear la cita: ' . $e->getMessage()];
        }
    }

    public function reagendarCita(int $citaId): array
    {
        $cita = Cita::findOrFail($citaId);
        $revisores = $this->obtenerRevisoresPorTipo($cita->tipo_cita);
        $fechaHora = $this->buscarPrimerHorarioDisponible($revisores, $cita->fecha_cita);
        
        if (!$fechaHora) {
            return ['success' => false, 'message' => 'No hay horarios disponibles para reagendar'];
        }

        $cita->update([
            'fecha_cita' => $fechaHora['datetime'],
            'asignado_a' => $fechaHora['revisor_id'],
            'intento' => $cita->intento + 1,
            'estado' => 'Asignada'
        ]);

        $citaActualizada = $cita->fresh();
        $this->notificacionService->notificarCitaReagendada($citaActualizada);

        return [
            'success' => true,
            'cita' => $citaActualizada,
            'revisor' => User::find($fechaHora['revisor_id']),
            'fecha_formateada' => Carbon::parse($fechaHora['datetime'])->format('d/m/Y H:i'),
            'message' => 'Cita reagendada exitosamente'
        ];
    }

    private function obtenerRevisoresDigitales(): Collection
    {
        return User::whereHas('roles', function($query) {
            $query->where('name', UserRole::REVISOR_DIGITAL->value);
        })->get();
    }

    private function obtenerRevisoresPresenciales(): Collection
    {
        return User::whereHas('roles', function($query) {
            $query->where('name', UserRole::REVISOR_PRESENCIAL->value);
        })->get();
    }

    private function obtenerRevisoresPorTipo(string $tipoCita): Collection
    {
        $rol = match($tipoCita) {
            'Digital' => UserRole::REVISOR_DIGITAL->value,
            'Presencial' => UserRole::REVISOR_PRESENCIAL->value,
            'Domiciliaria' => UserRole::REVISOR_DOMICILIARIO->value,
            default => UserRole::REVISOR_DIGITAL->value
        };

        return User::whereHas('roles', function($query) use ($rol) {
            $query->where('name', $rol);
        })->get();
    }

    private function buscarPrimerHorarioDisponible(Collection $revisores, Carbon $fechaInicio = null): ?array
    {
        $fechaActual = $fechaInicio ? $fechaInicio->copy()->addDay() : Carbon::now();
        $fechaLimite = Carbon::now()->addDays(30);

        while ($fechaActual->lte($fechaLimite)) {
            if (!in_array($fechaActual->dayOfWeek, self::DIAS_LABORALES)) {
                $fechaActual->addDay();
                continue;
            }

            if ($this->esDiaInhabil($fechaActual)) {
                $fechaActual->addDay();
                continue;
            }

            $horario = $this->buscarHorarioEnDia($fechaActual, $revisores);
            if ($horario) {
                return $horario;
            }

            $fechaActual->addDay();
        }

        return null;
    }

    private function buscarHorarioEnDia(Carbon $fecha, Collection $revisores): ?array
    {
        $horaActual = self::HORA_INICIO;
        
        while ($horaActual < self::HORA_FIN) {
            $fechaHora = $fecha->copy()->setTime($horaActual, 0, 0);
            
            if ($fecha->isToday() && $fechaHora->lt(Carbon::now())) {
                $horaActual++;
                continue;
            }

            foreach ($revisores as $revisor) {
                if ($this->revisorDisponibleEnHorario($revisor->id, $fechaHora)) {
                    return ['datetime' => $fechaHora, 'revisor_id' => $revisor->id];
                }
            }

            $horaActual++;
        }

        return null;
    }

    private function revisorDisponibleEnHorario(int $revisorId, Carbon $fechaHora): bool
    {
        $horaInicio = $fechaHora->copy();
        $horaFin = $fechaHora->copy()->addMinutes(self::DURACION_CITA_MINUTOS);

        return !Cita::where('asignado_a', $revisorId)
            ->where('estado', 'Asignada')
            ->where(function($query) use ($horaInicio, $horaFin) {
                $query->whereBetween('fecha_cita', [$horaInicio, $horaFin])
                      ->orWhere(function($q) use ($horaInicio, $horaFin) {
                          $q->where('fecha_cita', '<=', $horaInicio)
                            ->whereRaw('DATE_ADD(fecha_cita, INTERVAL ? MINUTE) > ?', 
                                [self::DURACION_CITA_MINUTOS, $horaInicio]);
                      });
            })
            ->exists();
    }

    private function esDiaInhabil(Carbon $fecha): bool
    {
        if (in_array($fecha->dayOfWeek, [0, 6])) {
            return true;
        }

        return DiaInhabil::where('fecha', $fecha->format('Y-m-d'))->exists();
    }

    public function obtenerHorariosDisponibles(Carbon $fecha, string $tipoCita = 'Digital'): array
    {
        if (!in_array($fecha->dayOfWeek, self::DIAS_LABORALES) || $this->esDiaInhabil($fecha)) {
            return [];
        }

        $revisores = $this->obtenerRevisoresPorTipo($tipoCita);
        $horarios = [];

        for ($hora = self::HORA_INICIO; $hora < self::HORA_FIN; $hora++) {
            $fechaHora = $fecha->copy()->setTime($hora, 0, 0);
            
            if ($fecha->isToday() && $fechaHora->lt(Carbon::now())) {
                continue;
            }

            foreach ($revisores as $revisor) {
                if ($this->revisorDisponibleEnHorario($revisor->id, $fechaHora)) {
                    $horarios[] = [
                        'hora' => $fechaHora->format('H:i'),
                        'datetime' => $fechaHora,
                        'revisor_id' => $revisor->id,
                        'revisor_nombre' => $revisor->name
                    ];
                    break;
                }
            }
        }

        return $horarios;
    }

    public function obtenerCitasPorRevisor(int $revisorId, Carbon $fechaInicio = null, Carbon $fechaFin = null): Collection
    {
        $query = Cita::where('asignado_a', $revisorId)
                     ->with(['tramite.proveedor']);

        if ($fechaInicio) {
            $query->where('fecha_cita', '>=', $fechaInicio);
        }

        if ($fechaFin) {
            $query->where('fecha_cita', '<=', $fechaFin);
        }

        return $query->orderBy('fecha_cita')->get();
    }
} 