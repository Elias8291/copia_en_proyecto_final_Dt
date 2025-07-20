<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ActividadesSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Cargando actividades...');

        $jsonPathDenue = database_path('json/denue/actividades_denue.json');

        if (File::exists($jsonPathDenue)) {
            $this->poblarDesdeDenueJson($jsonPathDenue);
        } else {
            $this->poblarDesdeJsonLocal();
        }
    }

    private function poblarDesdeDenueJson($jsonPath): void
    {
        try {
            $jsonData = File::get($jsonPath);
            $data = json_decode($jsonData, true);

            if (!isset($data['sectores']) || !isset($data['actividades'])) {
                throw new \Exception('Formato de archivo JSON inválido');
            }

            DB::beginTransaction();

            foreach ($data['sectores'] as $sector) {
                DB::table('sectores')->insertOrIgnore([
                    'nombre' => $sector['nombre'],
                ]);
            }

            $sectoresDB = DB::table('sectores')->get();
            $actividadesLote = [];
            $sectorIndex = 0;

            foreach ($data['actividades'] as $actividad) {
                $sectorId = $sectoresDB->get($sectorIndex % count($sectoresDB))->id ?? 1;
                $sectorIndex++;

                $actividadesLote[] = [
                    'nombre' => $actividad['nombre'],
                    'codigo_scian' => $actividad['codigo_scian'] ?? null,
                    'sector_id' => $sectorId,
                    'estado_validacion' => 'Validada',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($actividadesLote) >= 1000) {
                    DB::table('actividades_economicas')->insert($actividadesLote);
                    $actividadesLote = [];
                }
            }

            if (!empty($actividadesLote)) {
                DB::table('actividades_economicas')->insert($actividadesLote);
            }

            DB::commit();
            $this->command->info('Actividades cargadas exitosamente desde DENUE');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error al cargar desde DENUE: ' . $e->getMessage());
            $this->poblarDesdeJsonLocal();
        }
    }

    private function poblarDesdeJsonLocal(): void
    {
        try {
            $jsonPath = database_path('json/actividades.json');

            if (!File::exists($jsonPath)) {
                $this->command->error('No se encontró el archivo de actividades.json');
                return;
            }

            $data = json_decode(File::get($jsonPath), true);

            $dataKey = null;
            if (isset($data['Hoja1'])) {
                $dataKey = 'Hoja1';
            } elseif (isset($data['Sheet1'])) {
                $dataKey = 'Sheet1';
            } elseif (is_array($data) && count($data) > 0) {
                $dataKey = null;
            } else {
                $this->command->error('Formato de archivo JSON inválido');
                return;
            }

            $this->crearSectoresBasicos();

            $actividades = [];
            $sectores = DB::table('sectores')->get();

            if ($dataKey !== null) {
                foreach ($data[$dataKey] as $item) {
                    $sectorId = $item['id_sector'] ?? $item['sector_id'] ?? 1;
                    if ($sectorId > $sectores->count()) {
                        $sectorId = 1;
                    }

                    $actividades[] = [
                        'nombre' => $item['actividad'] ?? $item['nombre'] ?? '',
                        'sector_id' => $sectorId,
                        'codigo_scian' => $item['codigo_scian'] ?? null,
                        'estado_validacion' => 'Validada',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            } else {
                foreach ($data as $item) {
                    $sectorId = $item['id_sector'] ?? $item['sector_id'] ?? 1;
                    if ($sectorId > $sectores->count()) {
                        $sectorId = 1;
                    }

                    $actividades[] = [
                        'nombre' => $item['actividad'] ?? $item['nombre'] ?? '',
                        'sector_id' => $sectorId,
                        'codigo_scian' => $item['codigo_scian'] ?? null,
                        'estado_validacion' => 'Validada',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($actividades)) {
                $chunks = array_chunk($actividades, 500);
                foreach ($chunks as $chunk) {
                    DB::table('actividades_economicas')->insert($chunk);
                }
                $this->command->info('Actividades cargadas exitosamente desde JSON local');
            }
        } catch (\Exception $e) {
            $this->command->error('Error al cargar actividades: ' . $e->getMessage());
        }
    }

    private function crearSectoresBasicos(): void
    {
        $sectores = [
            ['nombre' => 'Agricultura y ganadería'],
            ['nombre' => 'Comercio'],
            ['nombre' => 'Servicios'],
            ['nombre' => 'Industria'],
            ['nombre' => 'Construcción'],
        ];

        $sectorCount = DB::table('sectores')->count();

        if ($sectorCount == 0) {
            DB::table('sectores')->insert($sectores);
            $this->command->info('Sectores básicos creados');
        }
    }
}
