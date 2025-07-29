<?php

namespace App\Console\Commands;

use App\Models\Oficio;
use Illuminate\Console\Command;

class VerificarUrlsOficios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oficios:verificar-urls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica las URLs de los PDFs de oficios';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("🔍 Verificando URLs de PDFs de oficios...");

        $oficios = Oficio::all();

        if ($oficios->isEmpty()) {
            $this->warn("⚠️ No hay oficios en la base de datos");
            return;
        }

        $this->info("✅ Encontrados {$oficios->count()} oficios");

        foreach ($oficios as $oficio) {
            $this->verificarOficio($oficio);
        }
    }

    private function verificarOficio($oficio)
    {
        $this->info("\n📄 Verificando oficio ID: {$oficio->id}");
        $this->line("   - Número: {$oficio->numero_oficio}");
        $this->line("   - Fecha: {$oficio->fecha_formateada}");
        $this->line("   - Trámite ID: {$oficio->tramite_id}");

        if ($oficio->url_documento) {
            $this->line("   - URL en BD: {$oficio->url_documento}");
            
            if (\Storage::disk('public')->exists($oficio->url_documento)) {
                $url = \Storage::disk('public')->url($oficio->url_documento);
                $this->line("   - URL pública: {$url}");
                $this->info("   ✅ PDF existe y es accesible");
            } else {
                $this->error("   ❌ PDF no existe en storage");
            }
        } else {
            $this->warn("   ⚠️ No tiene URL en la base de datos");
        }
    }
} 