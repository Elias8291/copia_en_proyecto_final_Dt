<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tramite;
use App\Services\OficioService;
use Illuminate\Support\Facades\Log;

class TestQrCode extends Command
{
    protected $signature = 'test:qr {tramite_id=13}';
    protected $description = 'Probar la generación del QR code para un trámite';

    public function handle()
    {
        $tramiteId = $this->argument('tramite_id');
        
        $this->info("🔍 Probando generación de QR para el trámite ID: {$tramiteId}");
        
        try {
            // Buscar el trámite
            $tramite = Tramite::with([
                'proveedor',
                'datosGenerales',
                'datosConstitutivos',
                'direcciones.estado',
                'apoderadosLegales',
                'actividades',
                'accionistas',
                'contactos'
            ])->find($tramiteId);
            
            if (!$tramite) {
                $this->error("❌ No se encontró el trámite con ID: {$tramiteId}");
                return 1;
            }
            
            $this->info("✅ Trámite encontrado:");
            $this->line("   - ID: {$tramite->id}");
            $this->line("   - Tipo: {$tramite->tipo_tramite}");
            $this->line("   - Status: {$tramite->status}");
            $this->line("   - Proveedor: {$tramite->proveedor->rfc} ({$tramite->proveedor->razon_social})");
            
            // Crear instancia del servicio
            $oficioService = new OficioService();
            
            // Generar QR usando reflexión para acceder al método privado
            $reflection = new \ReflectionClass($oficioService);
            $method = $reflection->getMethod('generarQrCode');
            $method->setAccessible(true);
            
            $qrCode = $method->invoke($oficioService, $tramite);
            
            $this->info("📱 QR Code generado:");
            $this->line("   - Longitud: " . strlen($qrCode) . " caracteres");
            $this->line("   - Tipo: SVG");
            $this->line("   - Preview: " . substr($qrCode, 0, 100) . "...");
            
            // Verificar que sea SVG válido
            if (strpos($qrCode, '<svg') !== false) {
                $this->info("✅ QR Code es un SVG válido");
            } else {
                $this->error("❌ QR Code no es un SVG válido");
            }
            
            // Mostrar el contenido completo del QR
            $this->info("📄 Contenido completo del QR:");
            $this->line($qrCode);
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Error al generar QR: " . $e->getMessage());
            Log::error('Error en comando TestQrCode', [
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
} 