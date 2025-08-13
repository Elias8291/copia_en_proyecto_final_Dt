<?php

namespace App\Services\Citas;

use App\Models\Cita;
use App\Models\Tramite;
use App\Services\Core\BaseService;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Servicio simplificado para gestión de citas
 */
class CitaService extends BaseService
{
    private const HORARIOS = [
        'Presencial' => ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00'],
        'Domiciliaria' => ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00']
    ];

    // Coordenadas de la oficina central
    private const OFICINA_LAT = 19.4326; // Ejemplo: CDMX
    private const OFICINA_LON = -99.1332;
    private const RADIO_MAXIMO_KM = 50; // Radio máximo para citas domiciliarias

    /**
     * Agendar cita
     */
    public function agendar(int $tramiteId, array $datos): Cita
    {
        return $this->ejecutarEnTransaccion(function () use ($tramiteId, $datos) {
            $this->validarDatos($datos);
            
            // Calcular duración estimada según tipo y distancia
            $duracion = $this->calcularDuracionEstimada($datos);
            
            $cita = Cita::create([
                'tramite_id' => $tramiteId,
                'fecha' => $datos['fecha'],
                'hora' => $datos['hora'],
                'tipo_cita' => $datos['tipo_cita'],
                'estado' => 'Agendada',
                'observaciones' => $datos['observaciones'] ?? null,
                'direccion_visita' => $datos['direccion_visita'] ?? null,
                'latitud' => $datos['latitud'] ?? null,
                'longitud' => $datos['longitud'] ?? null,
                'distancia_km' => $datos['tipo_cita'] === 'Domiciliaria' ? $this->calcularDistancia($datos) : null,
                'duracion_estimada' => $duracion
            ]);

            Tramite::where('id', $tramiteId)->update(['estado' => 'Cita Agendada']);
            return $cita;
        });
    }

    /**
     * Reagendar cita
     */
    public function reagendar(int $citaId, array $datos): Cita
    {
        return $this->ejecutarEnTransaccion(function () use ($citaId, $datos) {
            $cita = Cita::findOrFail($citaId);
            $this->validarDatos($datos);
            
            $cita->update([
                'fecha' => $datos['fecha'],
                'hora' => $datos['hora'],
                'observaciones' => $datos['observaciones'] ?? $cita->observaciones,
                'estado' => 'Reagendada'
            ]);

            return $cita;
        });
    }

    /**
     * Obtener horarios disponibles
     */
    public function obtenerHorarios(string $fecha, string $tipoCita): array
    {
        // Validar que no sea fin de semana
        $fechaCarbon = Carbon::parse($fecha);
        if ($fechaCarbon->isWeekend()) {
            return [];
        }

        // Validar que no sea día inhábil
        if ($this->esDiaInhabil($fecha)) {
            return [];
        }

        $citasAgendadas = Cita::whereDate('fecha', $fecha)
            ->where('tipo_cita', $tipoCita)
            ->pluck('hora')
            ->toArray();

        return array_values(array_diff(self::HORARIOS[$tipoCita] ?? [], $citasAgendadas));
    }

    /**
     * Cancelar cita
     */
    public function cancelar(int $citaId, string $motivo = null): Cita
    {
        return $this->ejecutarEnTransaccion(function () use ($citaId, $motivo) {
            $cita = Cita::findOrFail($citaId);
            
            $cita->update([
                'estado' => 'Cancelada',
                'motivo_cancelacion' => $motivo
            ]);

            Tramite::where('id', $cita->tramite_id)->update(['estado' => 'Cita Cancelada']);
            return $cita;
        });
    }

    /**
     * Completar cita
     */
    public function completar(int $citaId, array $resultados = []): Cita
    {
        return $this->ejecutarEnTransaccion(function () use ($citaId, $resultados) {
            $cita = Cita::findOrFail($citaId);
            
            $cita->update([
                'estado' => 'Completada',
                'resultados_visita' => $resultados['resultados'] ?? null
            ]);

            $nuevoEstado = $cita->tipo_cita === 'Presencial' ? 'Revisión Presencial Completada' : 'Revisión Domiciliaria Completada';
            Tramite::where('id', $cita->tramite_id)->update(['estado' => $nuevoEstado]);

            return $cita;
        });
    }

    /**
     * Obtener citas pendientes
     */
    public function obtenerPendientes(Request $request)
    {
        $query = Cita::with(['tramite.proveedor', 'tramite.user'])
            ->whereIn('estado', ['Agendada', 'Reagendada']);

        if ($request->filled('tipo_cita')) {
            $query->where('tipo_cita', $request->tipo_cita);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        return $query->orderBy('fecha')->orderBy('hora')->paginate(15);
    }

    /**
     * Validar datos de cita
     */
    private function validarDatos(array $datos): void
    {
        $camposRequeridos = ['fecha', 'hora', 'tipo_cita'];
        
        if (!$this->validarDatosRequeridos($datos, $camposRequeridos)) {
            throw new \InvalidArgumentException('Fecha, hora y tipo de cita son requeridos');
        }

        // Validar que no sea fin de semana
        $fechaCarbon = Carbon::parse($datos['fecha']);
        if ($fechaCarbon->isWeekend()) {
            throw new \InvalidArgumentException('No se pueden agendar citas en fines de semana');
        }

        // Validar que no sea día inhábil
        if ($this->esDiaInhabil($datos['fecha'])) {
            throw new \InvalidArgumentException('La fecha seleccionada es un día inhábil');
        }

        if ($datos['tipo_cita'] === 'Domiciliaria') {
            if (empty($datos['direccion_visita'])) {
                throw new \InvalidArgumentException('Dirección es requerida para citas domiciliarias');
            }
            
            if (empty($datos['latitud']) || empty($datos['longitud'])) {
                throw new \InvalidArgumentException('Coordenadas son requeridas para citas domiciliarias');
            }

            // Validar distancia máxima
            $distancia = $this->calcularDistancia($datos);
            if ($distancia > self::RADIO_MAXIMO_KM) {
                throw new \InvalidArgumentException("La distancia ({$distancia} km) excede el límite máximo de " . self::RADIO_MAXIMO_KM . " km");
            }
        }

        if (!in_array($datos['hora'], self::HORARIOS[$datos['tipo_cita']] ?? [])) {
            throw new \InvalidArgumentException('Horario no válido para este tipo de cita');
        }
    }

    /**
     * Calcular duración estimada según tipo y distancia
     */
    private function calcularDuracionEstimada(array $datos): int
    {
        if ($datos['tipo_cita'] === 'Presencial') {
            return rand(40, 60); // 40-60 minutos para presencial
        }

        // Para domiciliaria: tiempo base + tiempo de viaje
        $distancia = $this->calcularDistancia($datos);
        $tiempoBase = 90; // 90 minutos base para domiciliaria
        $tiempoViaje = $distancia * 2; // 2 minutos por km de viaje

        return min($tiempoBase + $tiempoViaje, 180); // Máximo 3 horas
    }

    /**
     * Calcular distancia usando fórmula de Haversine
     */
    private function calcularDistancia(array $datos): float
    {
        if (empty($datos['latitud']) || empty($datos['longitud'])) {
            return 0;
        }

        $lat1 = deg2rad(self::OFICINA_LAT);
        $lon1 = deg2rad(self::OFICINA_LON);
        $lat2 = deg2rad($datos['latitud']);
        $lon2 = deg2rad($datos['longitud']);

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat/2) * sin($dlat/2) + cos($lat1) * cos($lat2) * sin($dlon/2) * sin($dlon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distancia = 6371 * $c; // Radio de la Tierra en km

        return round($distancia, 2);
    }

    /**
     * Verificar si es día inhábil
     */
    private function esDiaInhabil(string $fecha): bool
    {
        return \DB::table('dias_inhabiles')
            ->where('fecha', $fecha)
            ->exists();
    }

    /**
     * Obtener coordenadas desde dirección usando OpenStreetMap
     */
    public function obtenerCoordenadas(string $direccion): array
    {
        $url = 'https://nominatim.openstreetmap.org/search?' . http_build_query([
            'q' => $direccion,
            'format' => 'json',
            'limit' => 1
        ]);

        $context = stream_context_create([
            'http' => [
                'header' => 'User-Agent: SistemaCitas/1.0'
            ]
        ]);

        $response = file_get_contents($url, false, $context);
        $data = json_decode($response, true);

        if (!empty($data)) {
            return [
                'latitud' => (float) $data[0]['lat'],
                'longitud' => (float) $data[0]['lon']
            ];
        }

        return ['latitud' => null, 'longitud' => null];
    }
}
