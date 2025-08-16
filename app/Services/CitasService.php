<?php

namespace App\Services;

use App\Models\Tramite;
use App\Models\Cita;
use App\Models\User;
use App\Models\DiaInhabil;
use App\Models\RevisionTramite;
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

    public function __construct(NotificacionService $notificacionService = null)
    {
        $this->notificacionService = $notificacionService;
    }

    public function agendarCitaRevisionDigital(int $tramiteId, array $datos = []): array
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

            if ($this->notificacionService) {
                $this->notificacionService->notificarCitaAgendada($cita);
            }

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

    public function agendarCitaCotejoDomiciliario(int $tramiteId): array
    {
        \Log::info('Iniciando agendamiento de cita para cotejo domiciliario', ['tramite_id' => $tramiteId]);
        
        $tramite = Tramite::findOrFail($tramiteId);
        $revisores = $this->obtenerRevisoresDomiciliarios();
        
        \Log::info('Revisores domiciliarios encontrados', [
            'tramite_id' => $tramiteId,
            'cantidad_revisores' => $revisores->count(),
            'revisores' => $revisores->pluck('name', 'id')->toArray()
        ]);
        
        if ($revisores->isEmpty()) {
            \Log::warning('No hay revisores domiciliarios disponibles', ['tramite_id' => $tramiteId]);
            return ['success' => false, 'message' => 'No hay revisores domiciliarios disponibles'];
        }

        $fechaHora = $this->buscarPrimerHorarioDisponible($revisores);
        
        \Log::info('Horario encontrado para cotejo domiciliario', [
            'tramite_id' => $tramiteId,
            'fecha_hora' => $fechaHora ? $fechaHora['datetime'] : null,
            'revisor_id' => $fechaHora ? $fechaHora['revisor_id'] : null
        ]);
        
        if (!$fechaHora) {
            \Log::warning('No hay horarios disponibles para cotejo domiciliario', ['tramite_id' => $tramiteId]);
            return ['success' => false, 'message' => 'No hay horarios disponibles en los próximos 30 días'];
        }

        try {
            $cita = Cita::create([
                'tramite_id' => $tramiteId,
                'tipo_cita' => 'Domiciliaria',
                'fecha_cita' => $fechaHora['datetime'],
                'estado' => 'Asignada',
                'asignado_a' => $fechaHora['revisor_id'],
                'intento' => 1
            ]);

            \Log::info('Cita de cotejo domiciliario creada exitosamente', [
                'tramite_id' => $tramiteId,
                'cita_id' => $cita->id,
                'fecha_cita' => $cita->fecha_cita,
                'revisor_id' => $cita->asignado_a
            ]);

            // Actualizar estado del trámite a revisión domiciliaria
            $tramite->update(['status' => TramiteStatus::REVISION_DOMICILIARIA->value]);

            // Crear entrada en revisiones_tramite para la revisión domiciliaria
            $revisionDomiciliaria = RevisionTramite::create([
                'tramite_id' => $tramiteId,
                'tipo_revision' => 'Domiciliaria',
                'revisor_id' => $fechaHora['revisor_id'],
                'estado' => 'Pendiente',
                'observaciones' => 'Revisión domiciliaria programada',
                'fecha_inicio' => $fechaHora['datetime'],
                'intento' => 1
            ]);

            \Log::info('Revisión domiciliaria creada en tabla revisiones_tramite', [
                'tramite_id' => $tramiteId,
                'revision_id' => $revisionDomiciliaria->id,
                'revisor_id' => $fechaHora['revisor_id'],
                'fecha_inicio' => $fechaHora['datetime']
            ]);

            if ($this->notificacionService) {
                $this->notificacionService->notificarCitaAgendada($cita);
            }

            return [
                'success' => true,
                'cita' => $cita,
                'revisor' => User::find($fechaHora['revisor_id']),
                'fecha_formateada' => Carbon::parse($fechaHora['datetime'])->format('d/m/Y H:i'),
                'message' => 'Cita de cotejo domiciliario agendada exitosamente'
            ];
        } catch (\Exception $e) {
            \Log::error('Error al crear cita de cotejo domiciliario', [
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return ['success' => false, 'message' => 'Error al crear la cita de cotejo domiciliario: ' . $e->getMessage()];
        }
    }

    public function reagendarCita(int $citaId, array $datos = []): array
    {
        \Log::info('Iniciando reagendamiento de cita', ['cita_id' => $citaId]);
        
        $cita = Cita::findOrFail($citaId);
        $revisores = $this->obtenerRevisoresPresenciales();
        
        if ($revisores->isEmpty()) {
            return ['success' => false, 'message' => 'No hay revisores presenciales disponibles'];
        }

        $fechaHora = $this->buscarPrimerHorarioDisponible($revisores);
        
        if (!$fechaHora) {
            return ['success' => false, 'message' => 'No hay horarios disponibles en los próximos 30 días'];
        }

        try {
            $cita->update([
                'fecha_cita' => $fechaHora['datetime'],
                'asignado_a' => $fechaHora['revisor_id'],
                'intento' => $cita->intento + 1,
                'estado' => 'Reagendada'
            ]);

            if ($this->notificacionService) {
                $this->notificacionService->notificarCitaReagendada($cita);
            }

            return [
                'success' => true,
                'cita' => $cita,
                'revisor' => User::find($fechaHora['revisor_id']),
                'fecha_formateada' => Carbon::parse($fechaHora['datetime'])->format('d/m/Y H:i'),
                'message' => 'Cita reagendada exitosamente'
            ];
        } catch (\Exception $e) {
            \Log::error('Error al reagendar cita', [
                'cita_id' => $citaId,
                'error' => $e->getMessage()
            ]);
            
            return ['success' => false, 'message' => 'Error al reagendar la cita: ' . $e->getMessage()];
        }
    }

    /**
     * Agendar cita manual
     */
    public function agendarCita(int $tramiteId, array $datos): array
    {
        \Log::info('Iniciando agendamiento manual de cita', ['tramite_id' => $tramiteId, 'datos' => $datos]);
        
        $tramite = Tramite::findOrFail($tramiteId);
        
        // Validar datos requeridos
        if (!isset($datos['fecha']) || !isset($datos['hora']) || !isset($datos['tipo_cita'])) {
            return ['success' => false, 'message' => 'Faltan datos requeridos para agendar la cita'];
        }

        $fechaHora = Carbon::parse($datos['fecha'] . ' ' . $datos['hora']);
        
        // Verificar que la fecha sea futura
        if ($fechaHora->isPast()) {
            return ['success' => false, 'message' => 'La fecha y hora deben ser futuras'];
        }

        // Obtener revisores según el tipo de cita
        $revisores = $this->obtenerRevisoresPorTipo($datos['tipo_cita']);
        
        if ($revisores->isEmpty()) {
            return ['success' => false, 'message' => 'No hay revisores disponibles para este tipo de cita'];
        }

        // Buscar revisor disponible
        $revisorDisponible = null;
        foreach ($revisores as $revisor) {
            if ($this->revisorDisponibleEnHorario($revisor->id, $fechaHora)) {
                $revisorDisponible = $revisor;
                break;
            }
        }

        if (!$revisorDisponible) {
            return ['success' => false, 'message' => 'No hay revisores disponibles en el horario seleccionado'];
        }

        try {
            $cita = Cita::create([
                'tramite_id' => $tramiteId,
                'tipo_cita' => $datos['tipo_cita'],
                'fecha_cita' => $fechaHora,
                'estado' => 'Asignada',
                'asignado_a' => $revisorDisponible->id,
                'intento' => 1
            ]);

            \Log::info('Cita agendada manualmente', [
                'tramite_id' => $tramiteId,
                'cita_id' => $cita->id,
                'fecha_cita' => $cita->fecha_cita,
                'revisor_id' => $cita->asignado_a
            ]);

            if ($this->notificacionService) {
                $this->notificacionService->notificarCitaAgendada($cita);
            }

            return [
                'success' => true,
                'cita' => $cita,
                'revisor' => $revisorDisponible,
                'fecha_formateada' => $fechaHora->format('d/m/Y H:i'),
                'message' => 'Cita agendada exitosamente'
            ];
        } catch (\Exception $e) {
            \Log::error('Error al agendar cita manualmente', [
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage()
            ]);
            
            return ['success' => false, 'message' => 'Error al agendar la cita: ' . $e->getMessage()];
        }
    }

    /**
     * Obtener revisores digitales
     */
    private function obtenerRevisoresDigitales(): Collection
    {
        return User::role('Revisor Digital')->get();
    }

    /**
     * Obtener revisores presenciales
     */
    private function obtenerRevisoresPresenciales(): Collection
    {
        return User::role('Revisor Presencial')->get();
    }

    /**
     * Obtener revisores domiciliarios
     */
    private function obtenerRevisoresDomiciliarios(): Collection
    {
        return User::role('Revisor Domiciliario')->get();
    }

    /**
     * Obtener revisores según tipo de cita
     */
    private function obtenerRevisoresPorTipo(string $tipoCita): Collection
    {
        return match($tipoCita) {
            'Digital' => $this->obtenerRevisoresDigitales(),
            'Presencial' => $this->obtenerRevisoresPresenciales(),
            'Domiciliaria' => $this->obtenerRevisoresDomiciliarios(),
            default => collect()
        };
    }

    /**
     * Buscar primer horario disponible
     */
    private function buscarPrimerHorarioDisponible(Collection $revisores, Carbon $fechaInicio = null): ?array
    {
        $fechaInicio = $fechaInicio ?? Carbon::now()->addDay();
        $fechaFin = $fechaInicio->copy()->addDays(30);

        for ($fecha = $fechaInicio->copy(); $fecha->lte($fechaFin); $fecha->addDay()) {
            if ($this->esDiaInhabil($fecha)) {
                continue;
            }

            $horario = $this->buscarHorarioEnDia($fecha, $revisores);
            if ($horario) {
                return $horario;
            }
        }

        return null;
    }

    /**
     * Buscar horario disponible en un día específico
     */
    private function buscarHorarioEnDia(Carbon $fecha, Collection $revisores): ?array
    {
        $horaInicio = $fecha->copy()->setTime(self::HORA_INICIO, 0);
        $horaFin = $fecha->copy()->setTime(self::HORA_FIN, 0);

        for ($hora = $horaInicio->copy(); $hora->lt($horaFin); $hora->addMinutes(self::DURACION_CITA_MINUTOS)) {
            foreach ($revisores as $revisor) {
                if ($this->revisorDisponibleEnHorario($revisor->id, $hora)) {
                    return [
                        'datetime' => $hora->copy(),
                        'revisor_id' => $revisor->id
                    ];
                }
            }
        }

        return null;
    }

    /**
     * Verificar si un revisor está disponible en un horario específico
     */
    private function revisorDisponibleEnHorario(int $revisorId, Carbon $fechaHora): bool
    {
        $citasExistentes = Cita::where('asignado_a', $revisorId)
            ->where('fecha_cita', '>=', $fechaHora->copy()->subMinutes(self::DURACION_CITA_MINUTOS))
            ->where('fecha_cita', '<=', $fechaHora->copy()->addMinutes(self::DURACION_CITA_MINUTOS))
            ->whereIn('estado', ['Asignada', 'Confirmada'])
            ->count();

        return $citasExistentes === 0;
    }

    /**
     * Verificar si un día es inhábil
     */
    private function esDiaInhabil(Carbon $fecha): bool
    {
        return DiaInhabil::where('fecha', $fecha->format('Y-m-d'))->exists() ||
               !in_array($fecha->dayOfWeek, self::DIAS_LABORALES);
    }

    /**
     * Obtener horarios disponibles para una fecha específica
     */
    public function obtenerHorariosDisponibles($fecha, string $tipoCita = 'Digital'): array
    {
        $fecha = $fecha instanceof Carbon ? $fecha : Carbon::parse($fecha);
        
        if ($this->esDiaInhabil($fecha)) {
            return [];
        }

        $revisores = $this->obtenerRevisoresPorTipo($tipoCita);
        $horarios = [];

        $horaInicio = $fecha->copy()->setTime(self::HORA_INICIO, 0);
        $horaFin = $fecha->copy()->setTime(self::HORA_FIN, 0);

        for ($hora = $horaInicio->copy(); $hora->lt($horaFin); $hora->addMinutes(self::DURACION_CITA_MINUTOS)) {
            $revisoresDisponibles = $revisores->filter(function($revisor) use ($hora) {
                return $this->revisorDisponibleEnHorario($revisor->id, $hora);
            });

            if ($revisoresDisponibles->isNotEmpty()) {
                $horarios[] = [
                    'hora' => $hora->format('H:i'),
                    'revisores_disponibles' => $revisoresDisponibles->count()
                ];
            }
        }

        return $horarios;
    }

    /**
     * Obtener citas por revisor
     */
    public function obtenerCitasPorRevisor(int $revisorId, Carbon $fechaInicio = null, Carbon $fechaFin = null): Collection
    {
        $query = Cita::where('asignado_a', $revisorId);

        if ($fechaInicio) {
            $query->where('fecha_cita', '>=', $fechaInicio);
        }

        if ($fechaFin) {
            $query->where('fecha_cita', '<=', $fechaFin);
        }

        return $query->with(['tramite.proveedor'])->orderBy('fecha_cita')->get();
    }
} 