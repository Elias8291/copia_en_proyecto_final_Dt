<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tramite;
use App\Services\OficioService;
use Illuminate\Support\Facades\Log;

class VerificarQrEnPdf extends Command
{
    protected $signature = 'verificar:qr-pdf {tramite_id=13}';
    protected $description = 'Verificar si el QR está presente en el PDF generado';

    public function handle()
    {
        $tramiteId = $this->argument('tramite_id');
        
        $this->info("🔍 Verificando QR en PDF para el trámite ID: {$tramiteId}");
        
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
            
            $this->info("✅ Trámite encontrado: {$tramite->proveedor->razon_social}");
            
            // Crear instancia del servicio
            $oficioService = new OficioService();
            
            // Generar PDF
            $reflection = new \ReflectionClass($oficioService);
            $method = $reflection->getMethod('generarPdfOficio');
            $method->setAccessible(true);
            
            $pdfPath = $method->invoke($oficioService, $tramite);
            
            if (!file_exists($pdfPath)) {
                $this->error("❌ No se pudo generar el PDF");
                return 1;
            }
            
            $this->info("✅ PDF generado: {$pdfPath}");
            
            // Leer el contenido del PDF
            $pdfContent = file_get_contents($pdfPath);
            
            // Verificar elementos del QR
            $checks = [
                'SVG presente' => strpos($pdfContent, '<svg') !== false,
                'QR Code class' => strpos($pdfContent, 'qr-code') !== false,
                'QR Text' => strpos($pdfContent, 'Validar documento') !== false,
                'QR HTML' => strpos($pdfContent, 'QR CODE') !== false,
                'Trámite ID' => strpos($pdfContent, 'T:13') !== false,
                'Proveedor ID' => strpos($pdfContent, 'P:11') !== false,
                'URL validación' => strpos($pdfContent, '/oficios/validar/13') !== false,
                'Imagen PNG' => strpos($pdfContent, '.png') !== false,
                'Tag img' => strpos($pdfContent, '<img') !== false,
                'QR codes directory' => strpos($pdfContent, 'qr-codes') !== false,
                'Base64 data' => strpos($pdfContent, 'data:image/png;base64') !== false,
                'Base64 image' => strpos($pdfContent, 'data:image') !== false,
            ];
            
            $this->info("📊 Verificación del QR en el PDF:");
            foreach ($checks as $check => $result) {
                $status = $result ? '✅' : '❌';
                $this->line("   {$status} {$check}");
            }
            
            // Mostrar fragmento del contenido donde debería estar el QR
            $qrPosition = strpos($pdfContent, 'QR CODE');
            if ($qrPosition !== false) {
                $this->info("📄 Fragmento del PDF alrededor del QR:");
                $start = max(0, $qrPosition - 200);
                $end = min(strlen($pdfContent), $qrPosition + 500);
                $fragment = substr($pdfContent, $start, $end - $start);
                $this->line($fragment);
            } else {
                $this->warn("⚠️ No se encontró 'QR CODE' en el PDF");
                
                // Buscar cualquier contenido relacionado con el QR
                $qrPosition = strpos($pdfContent, 'qr-code');
                if ($qrPosition !== false) {
                    $this->info("📄 Fragmento del PDF con clase 'qr-code':");
                    $start = max(0, $qrPosition - 200);
                    $end = min(strlen($pdfContent), $qrPosition + 500);
                    $fragment = substr($pdfContent, $start, $end - $start);
                    $this->line($fragment);
                }
            }
            
            // Verificar el tamaño del archivo
            $fileSize = filesize($pdfPath);
            $this->info("📏 Tamaño del archivo: {$fileSize} bytes");
            
            if ($fileSize < 10000) {
                $this->warn("⚠️ El archivo parece ser muy pequeño, puede que no se haya generado correctamente");
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            Log::error('Error en VerificarQrEnPdf', [
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
} 