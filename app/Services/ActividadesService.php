<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tramite;
use App\Models\ActividadEconomica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ActividadesService
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Guarda las actividades económicas del trámite
     */
    public function guardar(Tramite $tramite, Request $request): void
    {
        $actividades = $request->input('actividades', []);

        // Log para debug
        Log::info('Datos de actividades recibidos', [
            'tramite_id' => $tramite->id,
            'actividades' => $actividades,
            'tipo_actividades' => gettype($actividades),
            'es_array' => is_array($actividades),
            'count' => is_array($actividades) ? count($actividades) : 'N/A'
        ]);

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

        // Eliminar duplicados antes de insertar
        $actividadesValidas = array_unique($actividadesValidas);
        
        // Obtener actividades ya existentes en el trámite
        $actividadesExistentes = $tramite->actividades()->pluck('actividad_id')->toArray();
        
        // Filtrar solo las actividades que no existen ya
        $actividadesNuevas = array_diff($actividadesValidas, $actividadesExistentes);
        
        Log::info('Actividades procesadas (sin duplicados)', [
            'tramite_id' => $tramite->id,
            'actividades_unicas' => $actividadesValidas,
            'actividades_existentes' => $actividadesExistentes,
            'actividades_nuevas' => $actividadesNuevas,
            'total_unicas' => count($actividadesValidas),
            'total_nuevas' => count($actividadesNuevas)
        ]);

        if (empty($actividadesNuevas)) {
            Log::info('No hay actividades nuevas para agregar', ['tramite_id' => $tramite->id]);
            return;
        }

        try {
            // Primero, eliminar todas las actividades existentes para evitar duplicados
            $tramite->actividades()->detach();
            
            // Luego, agregar todas las actividades válidas
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
        $nombresTemporales = [];

        // Recopilar nombres de actividades temporales del request
        foreach ($this->request->all() as $key => $value) {
            if (strpos($key, 'actividad_temp_nombre_') === 0) {
                $idTemporal = str_replace('actividad_temp_nombre_', '', $key);
                $nombresTemporales[$idTemporal] = $value;
            }
        }

        // Log de nombres temporales encontrados
        Log::info('Nombres temporales encontrados', [
            'nombres_temporales' => $nombresTemporales,
            'total_nombres' => count($nombresTemporales)
        ]);

        foreach ($actividades as $actividad) {
            // Log para debug
            Log::info('Procesando actividad', [
                'actividad' => $actividad,
                'tipo' => gettype($actividad),
                'es_numeric' => is_numeric($actividad)
            ]);

            if (is_numeric($actividad) && $actividad > 0) {
                // Es una actividad existente
                $actividadesValidas[] = (int) $actividad;
            } elseif (is_numeric($actividad) && $actividad < 0) {
                // Es una actividad temporal, procesarla
                $idTemporal = abs((float) $actividad);
                $nombre = $nombresTemporales[(string) $idTemporal] ?? null;
                
                Log::info('Procesando actividad temporal', [
                    'actividad_original' => $actividad,
                    'id_temporal' => $idTemporal,
                    'nombre_encontrado' => $nombre,
                    'claves_disponibles' => array_keys($nombresTemporales)
                ]);
                
                if ($nombre) {
                    $actividadId = $this->crearActividadTemporal($nombre);
                    if ($actividadId) {
                        $actividadesValidas[] = $actividadId;
                    }
                } else {
                    // Si no se encuentra el nombre, crear una actividad con nombre genérico
                    Log::warning('No se encontró nombre para actividad temporal', [
                        'id_temporal' => $idTemporal,
                        'actividad_original' => $actividad
                    ]);
                    
                    $nombreGenerico = "Actividad Temporal #{$idTemporal}";
                    $actividadId = $this->crearActividadTemporal($nombreGenerico);
                    if ($actividadId) {
                        $actividadesValidas[] = $actividadId;
                    }
                }
            } elseif (is_string($actividad) && !empty(trim($actividad))) {
                // Es un nombre de actividad directo, crear nueva
                $actividadId = $this->crearActividadTemporal(trim($actividad));
                if ($actividadId) {
                    $actividadesValidas[] = $actividadId;
                }
            } elseif (is_string($actividad) && empty(trim($actividad))) {
                // Actividad vacía, ignorar
                continue;
            } else {
                // Otro tipo de valor, intentar convertirlo a string y procesarlo
                Log::warning('Tipo de actividad no reconocido, intentando convertir', [
                    'actividad' => $actividad,
                    'tipo' => gettype($actividad)
                ]);
                
                $actividadString = (string) $actividad;
                if (!empty(trim($actividadString))) {
                    $actividadId = $this->crearActividadTemporal(trim($actividadString));
                    if ($actividadId) {
                        $actividadesValidas[] = $actividadId;
                    }
                }
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
                'nombre' => $query,
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

    /**
     * Crea una actividad temporal
     */
    private function crearActividadTemporal(string $nombre): ?int
    {
        try {
            // Verificar si ya existe una actividad con el mismo nombre
            $actividadExistente = ActividadEconomica::where('nombre', trim($nombre))->first();
            if ($actividadExistente) {
                return $actividadExistente->id;
            }

            // Crear la nueva actividad
            $nuevaActividad = ActividadEconomica::create([
                'nombre' => trim($nombre),
                'codigo_scian' => null,
                'descripcion' => null,
                'sector_id' => 1, // Sector por defecto
                'estado_validacion' => 'Pendiente',
                'fuente' => 'MANUAL'
            ]);

            Log::info('Actividad temporal creada', [
                'id' => $nuevaActividad->id,
                'nombre' => $nuevaActividad->nombre,
                'usuario' => auth()->id()
            ]);

            return $nuevaActividad->id;

        } catch (\Exception $e) {
            Log::error('Error al crear actividad temporal', [
                'nombre' => $nombre,
                'error' => $e->getMessage(),
                'usuario' => auth()->id()
            ]);
            return null;
        }
    }
} 