<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tramite;
use App\Services\OficioService;
use Illuminate\Support\Facades\Log;

class TestPdfQr extends Command
{
    protected $signature = 'test:pdf-qr {tramite_id=13}';
    protected $description = 'Probar la generación del PDF con QR para un trámite';

    public function handle()
    {
        $tramiteId = $this->argument('tramite_id');
        
        $this->info("🔍 Probando generación de PDF con QR para el trámite ID: {$tramiteId}");
        
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
            
            // Generar PDF usando reflexión para acceder al método privado
            $reflection = new \ReflectionClass($oficioService);
            $method = $reflection->getMethod('generarPdfOficio');
            $method->setAccessible(true);
            
            $pdfPath = $method->invoke($oficioService, $tramite);
            
            $this->info("📄 PDF generado:");
            $this->line("   - Ruta: {$pdfPath}");
            $this->line("   - Existe: " . (file_exists($pdfPath) ? 'Sí' : 'No'));
            $this->line("   - Tamaño: " . (file_exists($pdfPath) ? filesize($pdfPath) . ' bytes' : 'N/A'));
            
            if (file_exists($pdfPath)) {
                $this->info("✅ PDF generado exitosamente");
                $this->line("📁 Puede encontrar el PDF en: {$pdfPath}");
                
                // Verificar si el PDF contiene el QR
                $pdfContent = file_get_contents($pdfPath);
                if (strpos($pdfContent, 'svg') !== false) {
                    $this->info("✅ El PDF contiene elementos SVG (QR)");
                } else {
                    $this->warn("⚠️ El PDF no contiene elementos SVG");
                }
                
                if (strpos($pdfContent, 'qr-code') !== false) {
                    $this->info("✅ El PDF contiene la clase CSS 'qr-code'");
                } else {
                    $this->warn("⚠️ El PDF no contiene la clase CSS 'qr-code'");
                }
                
            } else {
                $this->error("❌ No se pudo generar el PDF");
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Error al generar PDF: " . $e->getMessage());
            Log::error('Error en comando TestPdfQr', [
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
} 