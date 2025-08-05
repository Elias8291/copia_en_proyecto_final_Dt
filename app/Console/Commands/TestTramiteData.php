<?php

namespace App\Console\Commands;

use App\Models\Tramite;
use App\Services\Tramites\DataRetrievalService;
use App\ViewModels\FormDataViewModel;
use Illuminate\Console\Command;

class TestTramiteData extends Command
{
    protected $signature = 'test:tramite-data {tramite_id}';
    protected $description = 'Test tramite data retrieval with instruments notariales';

    private DataRetrievalService $dataRetrievalService;

    public function __construct(DataRetrievalService $dataRetrievalService)
    {
        parent::__construct();
        $this->dataRetrievalService = $dataRetrievalService;
    }

    public function handle()
    {
        $tramiteId = $this->argument('tramite_id');
        
        $this->info("Testing data retrieval for tramite ID: {$tramiteId}");
        
        try {
            // Test 1: Direct database check
            $this->info("\n=== 1. Direct Database Check ===");
            $tramite = Tramite::with([
                'datosConstitutivos.instrumentoNotarial',
                'apoderadosLegales.instrumentoNotarial',
                'accionistas'
            ])->find($tramiteId);
            
            if (!$tramite) {
                $this->error("Tramite not found!");
                return;
            }
            
            $this->info("Tramite found: {$tramite->id}");
            $this->info("Datos constitutivos count: " . $tramite->datosConstitutivos->count());
            $this->info("Apoderados legales count: " . $tramite->apoderadosLegales->count());
            $this->info("Accionistas count: " . $tramite->accionistas->count());
            
            // Check costitution
            $constitucion = $tramite->datosConstitutivos->first();
            if ($constitucion) {
                $this->info("Constitution found: {$constitucion->id}");
                $instrumento = $constitucion->instrumentoNotarial;
                if ($instrumento) {
                    $this->info("Constitution instrument found: {$instrumento->id}");
                    $this->info("Numero escritura constitutiva: {$instrumento->numero_escritura_constitutiva}");
                } else {
                    $this->error("No constitution instrument found!");
                }
            } else {
                $this->error("No constitution found!");
            }
            
            // Check apoderado
            $apoderado = $tramite->apoderadosLegales->first();
            if ($apoderado) {
                $this->info("Apoderado found: {$apoderado->id}");
                $instrumento = $apoderado->instrumentoNotarial;
                if ($instrumento) {
                    $this->info("Apoderado instrument found: {$instrumento->id}");
                    $this->info("Nombre notario: {$instrumento->nombre_notario}");
                } else {
                    $this->error("No apoderado instrument found!");
                }
            } else {
                $this->warn("No apoderado found (might be normal for persona fisica)");
            }
            
            // Test 2: DataRetrievalService
            $this->info("\n=== 2. DataRetrievalService Test ===");
            $datos = $this->dataRetrievalService->obtenerDatosTramiteHistorico($tramiteId);
            
            $this->info("Constitution data exists: " . (is_null($datos['constitucion']) ? 'NO' : 'YES'));
            if ($datos['constitucion']) {
                $this->info("Constitution data keys: " . implode(', ', array_keys($datos['constitucion'])));
                $this->info("Numero escritura constitutiva: " . ($datos['constitucion']['numero_escritura_constitutiva'] ?? 'NOT FOUND'));
            }
            
            $this->info("Apoderado data exists: " . (is_null($datos['apoderado']) ? 'NO' : 'YES'));
            if ($datos['apoderado']) {
                $this->info("Apoderado data keys: " . implode(', ', array_keys($datos['apoderado'])));
                $this->info("Nombre notario poder: " . ($datos['apoderado']['nombre_notario_poder'] ?? 'NOT FOUND'));
            }
            
            $this->info("Accionistas count: " . count($datos['accionistas'] ?? []));
            if (!empty($datos['accionistas'])) {
                $primer = $datos['accionistas'][0];
                $this->info("First accionista keys: " . implode(', ', array_keys($primer)));
                $this->info("Has constitution data: " . (isset($primer['numero_escritura_constitutiva']) ? 'YES' : 'NO'));
            }
            
            // Test 3: ViewModel
            $this->info("\n=== 3. ViewModel Test ===");
            $viewModel = new FormDataViewModel($datos);
            
            $constitucionVM = $viewModel->getConstitucion();
            $this->info("ViewModel constitution exists: " . (empty($constitucionVM) ? 'NO' : 'YES'));
            if ($constitucionVM) {
                $this->info("ViewModel constitution keys: " . implode(', ', array_keys($constitucionVM)));
            }
            
            $apoderadoVM = $viewModel->getApoderado();
            $this->info("ViewModel apoderado exists: " . (empty($apoderadoVM) ? 'NO' : 'YES'));
            if ($apoderadoVM) {
                $this->info("ViewModel apoderado keys: " . implode(', ', array_keys($apoderadoVM)));
            }
            
            $accionistasVM = $viewModel->getAccionistas();
            $this->info("ViewModel accionistas count: " . count($accionistasVM));
            
            $this->info("\n=== Test completed successfully! ===");
            
        } catch (\Exception $e) {
            $this->error("Error during test: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
        }
    }
} 