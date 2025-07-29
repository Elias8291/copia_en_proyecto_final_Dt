<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mpdf\Mpdf;

class ProbarImagenesMpdf extends Command
{
    protected $signature = 'probar:imagenes-mpdf';
    protected $description = 'Probar si mPDF puede cargar las imágenes';

    public function handle()
    {
        $this->info("=== PRUEBA DE IMÁGENES CON MPDF ===");
        
        try {
            // Configurar mPDF
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'letter',
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 0,
                'margin_bottom' => 0
            ]);

            // Verificar rutas de imágenes
            $logoEncabezadoPath = public_path('images/logo_encabezado2022.jpg');
            $logoLateralPath = public_path('images/logo_lateral2022.jpg');
            
            $this->info("Rutas de imágenes:");
            $this->info("Logo encabezado: {$logoEncabezadoPath}");
            $this->info("Logo lateral: {$logoLateralPath}");
            
            $this->info("\nVerificando existencia:");
            $this->info("Logo encabezado: " . (file_exists($logoEncabezadoPath) ? 'EXISTE' : 'NO EXISTE'));
            $this->info("Logo lateral: " . (file_exists($logoLateralPath) ? 'EXISTE' : 'NO EXISTE'));
            
            if (file_exists($logoEncabezadoPath)) {
                $this->info("Tamaño logo encabezado: " . filesize($logoEncabezadoPath) . " bytes");
            }
            
            if (file_exists($logoLateralPath)) {
                $this->info("Tamaño logo lateral: " . filesize($logoLateralPath) . " bytes");
            }

            // Crear HTML simple con imágenes
            $html = '
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial; }
                    .imagen { margin: 10px; border: 1px solid #000; }
                </style>
            </head>
            <body>
                <h1>Prueba de Imágenes con mPDF</h1>
                <div class="imagen">
                    <h3>Logo Encabezado:</h3>
                    <img src="' . $logoEncabezadoPath . '" style="max-width: 200px;">
                </div>
                <div class="imagen">
                    <h3>Logo Lateral:</h3>
                    <img src="' . $logoLateralPath . '" style="max-width: 200px;">
                </div>
            </body>
            </html>';

            // Escribir HTML
            $mpdf->WriteHTML($html);

            // Guardar PDF de prueba
            $testFileName = 'test_imagenes.pdf';
            $pdfContent = $mpdf->Output('', 'S');
            $pdfPath = \Storage::disk('public')->put($testFileName, $pdfContent);

            if ($pdfPath) {
                $fileSize = \Storage::disk('public')->size($testFileName);
                $this->info("\n✅ PDF de prueba generado exitosamente");
                $this->info("Tamaño: {$fileSize} bytes");
                $this->info("URL: " . \Storage::disk('public')->url($testFileName));
                
                if ($fileSize > 10000) {
                    $this->info("✅ El PDF es grande, probablemente contiene las imágenes");
                } else {
                    $this->warn("⚠️ El PDF es pequeño, las imágenes podrían no haberse cargado");
                }
            } else {
                throw new \Exception('Error al guardar el PDF de prueba');
            }

        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            $this->error("Trace: " . $e->getTraceAsString());
        }
    }
} 