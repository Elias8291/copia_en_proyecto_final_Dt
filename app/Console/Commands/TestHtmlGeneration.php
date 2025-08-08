<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tramite;
use App\Services\OficioService;
use Illuminate\Support\Facades\Log;

class TestHtmlGeneration extends Command
{
    protected $signature = 'test:html-generation {tramite_id=13}';
    protected $description = 'Probar la generación del HTML antes de convertirlo a PDF';

    public function handle()
    {
        $tramiteId = $this->argument('tramite_id');
        
        $this->info("🔍 Probando generación de HTML para el trámite ID: {$tramiteId}");
        
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
            
            // Generar QR
            $reflection = new \ReflectionClass($oficioService);
            $method = $reflection->getMethod('generarQrCode');
            $method->setAccessible(true);
            
            $qrCode = $method->invoke($oficioService, $tramite);
            
            $this->info("📱 QR Code generado:");
            $this->line("   - Longitud: " . strlen($qrCode) . " caracteres");
            $this->line("   - Contiene SVG: " . (strpos($qrCode, '<svg') !== false ? 'Sí' : 'No'));
            
            // Preparar datos como lo hace el servicio
            $proveedor = $tramite->proveedor;
            $datosGenerales = $tramite->datosGenerales()->latest()->first();
            $datosConstitutivos = $tramite->datosConstitutivos()->latest()->first();
            $direcciones = $tramite->direcciones()->with('estado')->get();
            $apoderadosLegales = $tramite->apoderadosLegales()->latest()->get();
            $actividades = $tramite->actividades()->get();
            $accionistas = $tramite->accionistas()->get();
            $contactos = $tramite->contactos()->get();

            // Preparar datos completos para la plantilla
            $datos = [
                'oficio' => (object)[
                    'numero_oficio' => \App\Models\Oficio::generarNumeroOficio()
                ],
                'tramite' => $tramite,
                'proveedor' => $proveedor,
                'datosGenerales' => $datosGenerales,
                'datosConstitutivos' => $datosConstitutivos,
                'direcciones' => $direcciones,
                'apoderadosLegales' => $apoderadosLegales,
                'actividades' => $actividades,
                'accionistas' => $accionistas,
                'contactos' => $contactos,
                'fechaTexto' => \Carbon\Carbon::now()->format('d/m/Y'),
                'fechaInicioTramite' => $tramite->fecha_inicio,
                'fechaGeneracionDocumento' => \Carbon\Carbon::now(),
                'fechaVigenciaProveedor' => $proveedor->fecha_vencimiento_padron ?? \Carbon\Carbon::now()->addYear(),
                'qrCode' => $qrCode
            ];

            // Determinar qué plantilla usar
            $tipoPersona = $proveedor->tipo_persona ?? 'Moral';
            $plantilla = $tipoPersona === 'Física' ? 'oficio.documento-fisica-mpdf' : 'oficio.documento-mpdf';

            $this->info("📄 Generando HTML con plantilla: {$plantilla}");
            
            // Generar el HTML
            $html = view($plantilla, $datos)->render();
            
            $this->info("✅ HTML generado:");
            $this->line("   - Longitud: " . strlen($html) . " caracteres");
            $this->line("   - Contiene QR Code: " . (strpos($html, 'qr-code') !== false ? 'Sí' : 'No'));
            $this->line("   - Contiene SVG: " . (strpos($html, '<svg') !== false ? 'Sí' : 'No'));
            $this->line("   - Contiene Validar documento: " . (strpos($html, 'Validar documento') !== false ? 'Sí' : 'No'));
            
            // Buscar la sección del QR en el HTML
            $qrPosition = strpos($html, 'qr-code');
            if ($qrPosition !== false) {
                $this->info("📄 Fragmento del HTML alrededor del QR:");
                $start = max(0, $qrPosition - 200);
                $end = min(strlen($html), $qrPosition + 500);
                $fragment = substr($html, $start, $end - $start);
                $this->line($fragment);
            } else {
                $this->warn("⚠️ No se encontró 'qr-code' en el HTML");
            }
            
            // Guardar el HTML en un archivo para inspección
            $htmlFile = storage_path('app/test-html-' . $tramiteId . '.html');
            file_put_contents($htmlFile, $html);
            $this->info("💾 HTML guardado en: {$htmlFile}");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            Log::error('Error en TestHtmlGeneration', [
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
} 