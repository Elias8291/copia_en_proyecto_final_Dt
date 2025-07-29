<?php

namespace App\Console\Commands;

use App\Models\Oficio;
use Illuminate\Console\Command;
use Mpdf\Mpdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerarPdfFinal extends Command
{
    protected $signature = 'generar:pdf-final {oficio_id}';
    protected $description = 'Generar PDF final con mPDF e imágenes';

    public function handle()
    {
        $oficioId = $this->argument('oficio_id');
        
        $this->info("=== GENERACIÓN FINAL DE PDF ===");
        $this->info("Oficio ID: {$oficioId}");
        
        $oficio = Oficio::find($oficioId);
        if (!$oficio) {
            $this->error("No se encontró oficio con ID: {$oficioId}");
            return;
        }

        try {
            $tramite = $oficio->tramite;
            $tramite->load(['proveedor', 'datosGenerales', 'datosConstitutivos', 'direcciones.estado']);

            $proveedor = $tramite->proveedor;
            $datosGenerales = $tramite->datosGenerales;
            $datosConstitutivos = $tramite->datosConstitutivos;
            $direcciones = $tramite->direcciones;

            // Generar código QR
            $qrData = json_encode([
                'oficio_id' => $oficio->id,
                'numero_oficio' => $oficio->numero_oficio,
                'fecha_oficio' => $oficio->fecha_oficio->format('Y-m-d'),
                'tramite_id' => $tramite->id
            ]);

            $qrCode = QrCode::size(100)->generate($qrData);

            // Preparar datos para la vista
            $data = [
                'oficio' => $oficio,
                'tramite' => $tramite,
                'proveedor' => $proveedor,
                'datosGenerales' => $datosGenerales,
                'datosConstitutivos' => $datosConstitutivos,
                'direcciones' => $direcciones,
                'qrCode' => $qrCode,
                'fechaTexto' => $this->formatearFecha($oficio->fecha_oficio),
                'detalleTramite' => $datosGenerales,
                'solicitante' => $proveedor,
                'fechaInicioTramite' => $tramite->fecha_inicio,
                'fechaGeneracionDocumento' => $oficio->fecha_oficio,
                'fechaVigenciaProveedor' => $proveedor ? $proveedor->fecha_vencimiento : null,
                'tipoTramite' => 'REGISTRO EN EL PADRÓN DE PROVEEDORES'
            ];

            // Generar HTML
            $html = view('oficio.documento-mpdf', $data)->render();

            // Configurar mPDF
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'letter',
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 0,
                'margin_bottom' => 0
            ]);

            // Escribir HTML
            $mpdf->WriteHTML($html);

            // Guardar PDF en storage
            $fileName = 'oficios/oficio_' . $oficio->numero_oficio . '_' . $oficio->id . '.pdf';
            $pdfContent = $mpdf->Output('', 'S');
            $pdfPath = \Storage::disk('public')->put($fileName, $pdfContent);

            if ($pdfPath) {
                // Actualizar la URL del documento en la base de datos
                $oficio->update(['url_documento' => $fileName]);
                
                $fileSize = \Storage::disk('public')->size($fileName);
                $this->info("✅ PDF generado exitosamente");
                $this->info("Tamaño: {$fileSize} bytes");
                $this->info("URL: " . \Storage::disk('public')->url($fileName));
                
                if ($fileSize > 100000) {
                    $this->info("✅ PDF contiene imágenes (tamaño grande)");
                } else {
                    $this->warn("⚠️ PDF pequeño, posiblemente sin imágenes");
                }
            } else {
                throw new \Exception('Error al guardar el PDF');
            }

        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            $this->error("Trace: " . $e->getTraceAsString());
        }
    }

    private function formatearFecha($fecha)
    {
        $meses = [
            1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
            5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
            9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
        ];

        $dia = $fecha->format('j');
        $mes = $meses[(int)$fecha->format('n')];
        $anio = $fecha->format('Y');

        return $dia . ' de ' . $mes . ' de ' . $anio;
    }
} 