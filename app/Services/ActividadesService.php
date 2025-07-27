<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tramite;
use App\Models\ActividadEconomica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ActividadesService
{
    /**
     * Guarda las actividades económicas del trámite
     */
    public function guardar(Tramite $tramite, Request $request): void
    {
        $actividades = $request->input('actividades', []);

        if (empty($actividades) || !is_array($actividades)) {
            Log::info('No se enviaron actividades económicas', ['tramite_id' => $tramite->id]);
            return;
        }

        $actividadesValidas = $this->procesarActividades($actividades);

        if (empty($actividadesValidas)) {
            Log::warning('No se encontraron actividades válidas', [
                'tramite_id' => $tramite->id,
                'actividades_originales' => $actividades
            ]);
            return;
        }

        try {
            // Usar la relación belongsToMany para insertar en la tabla pivot
            $tramite->actividades()->attach($actividadesValidas);

            Log::info('Actividades económicas guardadas exitosamente', [
                'tramite_id' => $tramite->id,
                'actividades_ids' => $actividadesValidas,
                'total_guardadas' => count($actividadesValidas)
            ]);

        } catch (\Exception $e) {
            Log::error('Error al guardar actividades económicas', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Procesa y valida las actividades recibidas
     */
    private function procesarActividades(array $actividades): array
    {
        $actividadesValidas = [];

        foreach ($actividades as $actividad) {
            if (is_numeric($actividad) && $actividad > 0) {
                // Es una actividad existente
                $actividadesValidas[] = (int) $actividad;
            } elseif (is_numeric($actividad) && $actividad < 0) {
                // Es una actividad temporal, se procesará en otro lugar
                continue;
            }
        }

        return $actividadesValidas;
    }

    /**
     * Busca actividades económicas
     */
    public function buscarActividades(string $query): array
    {
        $resultados = ActividadEconomica::where('nombre', 'like', '%'.$query.'%')
            ->where('estado_validacion', 'Validada')
            ->take(10)
            ->get(['id', 'nombre']);

        // Si no se encontraron resultados, agregar opción para crear nueva actividad
        if ($resultados->isEmpty()) {
            $resultados->push([
                'id' => 'nueva_actividad',
                'nombre' => "Crear nueva actividad: '{$query}'",
                'es_nueva' => true
            ]);
        }

        return $resultados->toArray();
    }

    /**
     * Procesar actividades temporales y crear las que no existen
     */
    public function procesarActividadesTemporales(array $actividades, array $nombresTemporales): array
    {
        $actividadesCreadas = [];
        $errores = [];

        foreach ($actividades as $actividad) {
            // Si es una actividad temporal (ID negativo)
            if (is_numeric($actividad) && $actividad < 0) {
                // Buscar el nombre en los datos adicionales
                $nombre = $nombresTemporales[$actividad] ?? null;
                
                if (!$nombre) {
                    $errores[] = "No se encontró el nombre para la actividad temporal";
                    continue;
                }

                try {
                    // Verificar si ya existe una actividad con el mismo nombre
                    $actividadExistente = ActividadEconomica::where('nombre', $nombre)->first();
                    if ($actividadExistente) {
                        $actividadesCreadas[] = $actividadExistente->id;
                        continue;
                    }

                    // Crear la nueva actividad
                    $nuevaActividad = ActividadEconomica::create([
                        'nombre' => trim($nombre),
                        'codigo_scian' => null,
                        'descripcion' => null,
                        'sector_id' => null,
                        'estado_validacion' => 'Pendiente',
                        'fuente' => 'MANUAL'
                    ]);

                    $actividadesCreadas[] = $nuevaActividad->id;

                    Log::info('Actividad temporal creada durante envío de formulario', [
                        'id' => $nuevaActividad->id,
                        'nombre' => $nuevaActividad->nombre,
                        'usuario' => auth()->id()
                    ]);

                } catch (\Exception $e) {
                    $errores[] = "Error al crear actividad '{$nombre}': " . $e->getMessage();
                    Log::error('Error al crear actividad temporal', [
                        'nombre' => $nombre,
                        'error' => $e->getMessage(),
                        'usuario' => auth()->id()
                    ]);
                }
            } else {
                // Es una actividad existente, solo agregar el ID
                $actividadesCreadas[] = $actividad;
            }
        }

        return [
            'success' => empty($errores),
            'actividades_creadas' => $actividadesCreadas,
            'errores' => $errores
        ];
    }
} 