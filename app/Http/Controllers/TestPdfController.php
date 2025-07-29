<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Log;

class TestPdfController extends Controller
{
    public function testPdf()
    {
        try {
            // Configurar mPDF
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'letter',
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 0,
                'margin_bottom' => 0,
                'margin_header' => 0,
                'margin_footer' => 0,
                'tempDir' => storage_path('app/tmp'),
                'default_font' => 'arial'
            ]);

            // Configurar directorio de imágenes
            $mpdf->SetBasePath(public_path());
            $mpdf->img_dpi = 96;
            $mpdf->img_cache_dir = storage_path('app/tmp');
            $mpdf->showImageErrors = true;
            $mpdf->debug = true;

            // HTML de prueba con imágenes
            $html = '
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .logo { width: 200px; height: auto; }
                    .logo-lateral { width: 150px; height: auto; opacity: 0.3; }
                </style>
            </head>
            <body>
                <h1>Prueba de Imágenes en PDF</h1>
                
                <h2>Logo Principal:</h2>
                <img src="images/logo_administracion.png" alt="Logo" class="logo">
                
                <h2>Logo Lateral:</h2>
                <img src="images/membretep.png" alt="Logo Lateral" class="logo-lateral">
                
                <h2>Prueba con ruta absoluta:</h2>
                <img src="' . public_path('images/logo_administracion.png') . '" alt="Logo Absoluto" class="logo">
                
                <p>Si puedes ver las imágenes arriba, el problema está resuelto.</p>
            </body>
            </html>';

            // Escribir HTML
            $mpdf->WriteHTML($html);

            // Generar PDF
            $pdfContent = $mpdf->Output('', 'S');
            
            // Devolver PDF como respuesta
            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="test_images.pdf"');

        } catch (\Exception $e) {
            Log::error('Error en prueba de PDF', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
} 