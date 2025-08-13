<?php

namespace App\Services;

use App\Models\User;
use App\Models\Tramite;
use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CitaRevisionService
{
    // Coordenadas de Oaxaca como punto de referencia
    private const OAXACA_LAT = 17.055195;
    private const OAXACA_LNG = -96.653423;
    
    // Configuración de horarios
    private const HORA_INICIO = 9;
    private const HORA_FIN = 14;
    private const DURACION_CITA_MINUTOS = 30;

    /**
     * Obtener revisores disponibles por tipo con información de distancia
     */
    public function obtenerRevisoresDisponibles(string $tipoRevision): Collection
    {
        $roleName = match($tipoRevision) {
            'Digital' => 'Revisor Digital',
            'Presencial' => 'Revisor Presencial',
            'Domiciliaria' => 'Revisor Domiciliario',
            default => 'Revisor Digital'
        };

        $revisores = User::role($roleName)
            ->where('confirmacion', true)
            ->get();

        return $revisores->map(function ($revisor) {
            // Calcular distancia desde Oaxaca (simulada para demo)
            $distancia = $this->calcularDistanciaSimulada($revisor->id);
            
            // Obtener próximas citas disponibles
            $proximasCitas = $this->obtenerProximasCitasDisponibles($revisor->id, 5);
            
            return [
                'id' => $revisor->id,
                'nombre' => $revisor->nombre,
                'correo' => $revisor->correo,
                'distancia' => $distancia,
                'distancia_texto' => $this->formatearDistancia($distancia),
                'categoria_distancia' => $this->categorizarDistancia($distancia),
                'proximas_citas' => $proximasCitas,
                'disponibilidad' => $this->evaluarDisponibilidad($proximasCitas),
                'citas_pendientes' => $this->contarCitasPendientes($revisor->id)
            ];
        })->sortBy([
            ['categoria_distancia', 'asc'],
            ['distancia', 'asc'],
            ['citas_pendientes', 'asc']
        ])->values();
    }

    /**
     * Calcular distancia simulada basada en el ID del revisor
     * En un entorno real, esto usaría coordenadas reales
     */
    private function calcularDistanciaSimulada(int $revisorId): float
    {
        // Simulamos distancias variadas basadas en el ID
        $seed = $revisorId * 7; // Multiplicador para variación
        $distanciaBase = ($seed % 100) + 5; // Entre 5 y 104 km
        
        // Algunos revisores "cerca" de Oaxaca
        if ($revisorId % 3 === 0) {
            $distanciaBase = min($distanciaBase, 25); // Máximo 25km
        }
        
        return round($distanciaBase, 1);
    }

    /**
     * Calcular distancia real entre dos puntos usando la fórmula de Haversine
     */
    public function calcularDistanciaReal(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // Radio de la Tierra en kilómetros

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng/2) * sin($dLng/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return round($earthRadius * $c, 1);
    }

    /**
     * Formatear distancia para mostrar
     */
    private function formatearDistancia(float $distancia): string
    {
        if ($distancia < 1) {
            return number_format($distancia * 1000, 0) . ' m';
        }
        
        return number_format($distancia, 1) . ' km';
    }

    /**
     * Categorizar distancia para ordenamiento y estilos
     */
    private function categorizarDistancia(float $distancia): string
    {
        if ($distancia <= 20) {
            return 'cerca'; // Verde
        } elseif ($distancia <= 50) {
            return 'media'; // Amarillo
        } else {
            return 'lejos'; // Rojo
        }
    }

    /**
     * Obtener próximas citas disponibles para un revisor
     */
    private function obtenerProximasCitasDisponibles(int $revisorId, int $limite = 5): Collection
    {
        $fechaInicio = Carbon::now()->addDay();
        $fechaFin = $fechaInicio->copy()->addDays(14); // Próximos 14 días
        
        $citasDisponibles = collect();
        
        for ($fecha = $fechaInicio->copy(); $fecha->lte($fechaFin) && $citasDisponibles->count() < $limite; $fecha->addDay()) {
            if (!$this->esDiaLaboral($fecha)) {
                continue;
            }
            
            $horariosDelDia = $this->obtenerHorariosDisponiblesDelDia($revisorId, $fecha);
            
            foreach ($horariosDelDia as $horario) {
                if ($citasDisponibles->count() >= $limite) {
                    break;
                }
                
                $citasDisponibles->push([
                    'fecha' => $horario->format('Y-m-d'),
                    'hora' => $horario->format('H:i'),
                    'fecha_formateada' => $horario->format('d/m/Y'),
                    'hora_formateada' => $horario->format('H:i'),
                    'datetime' => $horario->toISOString()
                ]);
            }
        }
        
        return $citasDisponibles;
    }

    /**
     * Obtener horarios disponibles de un día específico
     */
    private function obtenerHorariosDisponiblesDelDia(int $revisorId, Carbon $fecha): Collection
    {
        $horarios = collect();
        $horaInicio = $fecha->copy()->setTime(self::HORA_INICIO, 0);
        $horaFin = $fecha->copy()->setTime(self::HORA_FIN, 0);
        
        for ($hora = $horaInicio->copy(); $hora->lt($horaFin); $hora->addMinutes(self::DURACION_CITA_MINUTOS)) {
            if ($this->revisorDisponibleEnHorario($revisorId, $hora)) {
                $horarios->push($hora->copy());
            }
        }
        
        return $horarios;
    }

    /**
     * Verificar si un revisor está disponible en un horario específico
     */
    private function revisorDisponibleEnHorario(int $revisorId, Carbon $fechaHora): bool
    {
        $citasExistentes = Cita::where('asignado_a', $revisorId)
            ->whereDate('fecha_cita', $fechaHora->format('Y-m-d'))
            ->whereTime('fecha_cita', $fechaHora->format('H:i:s'))
            ->where('estado', '!=', 'Cancelada')
            ->count();
        
        return $citasExistentes === 0;
    }

    /**
     * Verificar si es día laboral
     */
    private function esDiaLaboral(Carbon $fecha): bool
    {
        // Lunes a Viernes (1-5)
        return in_array($fecha->dayOfWeek, [1, 2, 3, 4, 5]);
    }

    /**
     * Evaluar disponibilidad general del revisor
     */
    private function evaluarDisponibilidad(Collection $proximasCitas): string
    {
        $cantidad = $proximasCitas->count();
        
        if ($cantidad >= 4) {
            return 'alta'; // Verde
        } elseif ($cantidad >= 2) {
            return 'media'; // Amarillo
        } else {
            return 'baja'; // Rojo
        }
    }

    /**
     * Contar citas pendientes del revisor
     */
    private function contarCitasPendientes(int $revisorId): int
    {
        return Cita::where('asignado_a', $revisorId)
            ->whereIn('estado', ['Asignada', 'En Proceso'])
            ->count();
    }

    /**
     * Agendar cita manualmente
     */
    public function agendarCita(array $datos): array
    {
        try {
            $tramite = Tramite::findOrFail($datos['tramite_id']);
            $revisor = User::findOrFail($datos['revisor_id']);
            
            $fechaHora = Carbon::parse($datos['fecha_cita'] . ' ' . $datos['hora_cita']);
            
            // Verificar disponibilidad
            if (!$this->revisorDisponibleEnHorario($revisor->id, $fechaHora)) {
                return [
                    'success' => false,
                    'message' => 'El revisor no está disponible en el horario seleccionado'
                ];
            }
            
            // Crear la cita
            $cita = Cita::create([
                'tramite_id' => $tramite->id,
                'tipo_cita' => $datos['tipo_revision'],
                'fecha_cita' => $fechaHora,
                'estado' => 'Asignada',
                'asignado_a' => $revisor->id,
                'observaciones' => $datos['observaciones'] ?? null,
                'intento' => 1
            ]);

            // Actualizar estado del trámite si es necesario
            if ($tramite->status === 'En Revisión Digital') {
                $nuevoStatus = match($datos['tipo_revision']) {
                    'Presencial' => 'Cita Presencial Agendada',
                    'Domiciliaria' => 'Cita Domiciliaria Agendada',
                    default => $tramite->status
                };
                
                if ($nuevoStatus !== $tramite->status) {
                    $tramite->update(['status' => $nuevoStatus]);
                }
            }

            return [
                'success' => true,
                'cita' => $cita,
                'revisor' => $revisor,
                'fecha_formateada' => $fechaHora->format('d/m/Y H:i'),
                'message' => 'Cita agendada exitosamente'
            ];
            
        } catch (\Exception $e) {
            \Log::error('Error al agendar cita de revisión', [
                'datos' => $datos,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Error al agendar la cita: ' . $e->getMessage()
            ];
        }
    }
}
