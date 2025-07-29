<?php

namespace App\Console\Commands;

use App\Models\Oficio;
use Illuminate\Console\Command;

class TestPdfData extends Command
{
    protected $signature = 'test:pdf-data {oficio_id}';
    protected $description = 'Test PDF data';

    public function handle()
    {
        $oficioId = $this->argument('oficio_id');
        
        $oficio = Oficio::find($oficioId);
        if (!$oficio) {
            $this->error("Oficio no encontrado");
            return;
        }

        $tramite = $oficio->tramite;
        $tramite->load(['proveedor', 'datosGenerales', 'datosConstitutivos', 'direcciones.estado']);

        $this->info("=== DATOS DISPONIBLES ===");
        $this->info("Proveedor RFC: " . ($tramite->proveedor ? $tramite->proveedor->rfc : 'NULL'));
        $this->info("Datos Generales: " . ($tramite->datosGenerales ? 'EXISTE' : 'NULL'));
        if ($tramite->datosGenerales) {
            $this->info("  - Razón Social: " . ($tramite->datosGenerales->razon_social ?: 'NULL'));
        }
        $this->info("Direcciones: " . ($tramite->direcciones ? $tramite->direcciones->count() : '0'));
        
        if ($tramite->direcciones && $tramite->direcciones->count() > 0) {
            $direccion = $tramite->direcciones->first();
            $this->info("  - Calle: " . ($direccion->calle ?: 'NULL'));
            $this->info("  - Número: " . ($direccion->numero_exterior ?: 'NULL'));
        }
    }
} 