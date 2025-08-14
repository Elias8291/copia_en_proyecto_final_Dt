<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proveedor;

class GenerarTokensProveedores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:generar-proveedores {--force : Regenerar tokens existentes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generar tokens públicos seguros para todos los proveedores';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔐 Generando tokens seguros para proveedores...');
        
        $force = $this->option('force');
        
        $proveedores = Proveedor::all();
        $generados = 0;
        $existentes = 0;
        
        foreach ($proveedores as $proveedor) {
            if (empty($proveedor->token_publico) || $force) {
                $token = $proveedor->generarTokenPublico();
                $this->line("✅ Proveedor ID {$proveedor->id} - Token: " . substr($token, 0, 16) . "...");
                $generados++;
            } else {
                $this->line("⏭️  Proveedor ID {$proveedor->id} - Ya tiene token");
                $existentes++;
            }
        }
        
        $this->info("\n📊 Resumen:");
        $this->info("- Tokens generados: {$generados}");
        $this->info("- Tokens existentes: {$existentes}");
        $this->info("- Total proveedores: " . $proveedores->count());
        
        $this->info("\n🔒 Los QR codes ahora usan URLs seguras con tokens encriptados");
        
        return Command::SUCCESS;
    }
}
