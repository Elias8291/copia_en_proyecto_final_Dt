<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tramite;
use App\Services\OficioService;

class RegenerarOficio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oficio:regenerar {tramite_id : ID del trámite}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerar oficio con QR code para un trámite específico';

    
    public function handle()
    {
        $tramiteId = $this->argument('tramite_id');
        
        $tramite = Tramite::with(['proveedor'])->find($tramiteId);
        
        if (!$tramite) {
            $this->error("Trámite {$tramiteId} no encontrado.");
            return 1;
        }
        
        if (!$tramite->proveedor) {
            $this->error("El trámite {$tramiteId} no tiene proveedor asignado.");
            return 1;
        }
        
        try {
            $oficioService = app(OficioService::class);
            $oficio = $oficioService->generarOficioParaTramite($tramite);
            
            $this->info("Oficio regenerado. ID: {$oficio->id}, Número: {$oficio->numero_oficio}, URL: {$oficio->url}");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Error al generar oficio: ' . $e->getMessage());
            
            return 1;
        }
    }
}