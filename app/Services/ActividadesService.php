<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tramite;
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

        foreach ($actividades as $actividadId) {
            if (!empty($actividadId) && is_numeric($actividadId)) {
                $actividadesValidas[] = (int) $actividadId;
            }
        }

        return array_unique($actividadesValidas);
    }
} 