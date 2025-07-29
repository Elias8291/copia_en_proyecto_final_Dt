<?php

namespace App\Console\Commands;

use App\Models\Oficio;
use Illuminate\Console\Command;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class RegenerarPdfs extends Command
{
    protected $signature = 'regenerar:pdfs';
    protected $description = 'Regenerar todos los PDFs de oficios';

    public function handle()
    {
        $this->info('🔍 Regenerando PDFs...');
        
        $oficios = Oficio::all();
        
        foreach ($oficios as $oficio) {
            try {
                $this->info("Generando PDF para oficio ID: {$oficio->id}");
                
                // Cargar relaciones
                $tramite = $oficio->tramite;
                $tramite->load([
                    'proveedor',
                    'datosGenerales',
                    'datosConstitutivos',
                    'direcciones.estado'
                ]);
                
                $proveedor = $tramite->proveedor;
                $datosGenerales = $tramite->datosGenerales;
                $datosConstitutivos = $tramite->datosConstitutivos;
                $direcciones = $tramite->direcciones;
                
                // Generar QR
                $qrData = json_encode([
                    'oficio_id' => $oficio->id,
                    'numero_oficio' => $oficio->numero_oficio,
                    'fecha_oficio' => $oficio->fecha_oficio->format('Y-m-d'),
                    'tramite_id' => $tramite->id,
                    'proveedor_id' => $proveedor->id
                ]);
                
                $qrCode = QrCode::size(100)->generate($qrData);
                
                // Preparar datos simplificados
                $data = [
                    'oficio' => $oficio,
                    'tramite' => $tramite,
                    'qrCode' => $qrCode,
                    'fechaTexto' => $this->formatearFecha($oficio->fecha_oficio),
                    'fechaInicioTramite' => $tramite->fecha_inicio,
                    'fechaGeneracionDocumento' => $oficio->fecha_oficio,
                    'tipoTramite' => $this->obtenerTipoTramite($tramite)
                ];
                
                // Generar PDF
                $pdf = Pdf::loadView('oficio.documento', $data);
                $pdf->setPaper('letter', 'portrait');
                
                // Guardar
                $fileName = 'oficios/oficio_' . $oficio->numero_oficio . '_' . $oficio->id . '.pdf';
                $pdfPath = Storage::disk('public')->put($fileName, $pdf->output());
                
                if ($pdfPath) {
                    $oficio->update(['url_documento' => $fileName]);
                    $this->info("✅ PDF guardado: {$fileName}");
                } else {
                    $this->error("❌ Error al guardar PDF");
                }
                
            } catch (\Exception $e) {
                $this->error("❌ Error en oficio {$oficio->id}: {$e->getMessage()}");
            }
        }
        
        $this->info('✅ Proceso completado');
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
    
    private function obtenerTipoTramite($tramite)
    {
        if ($tramite->datosConstitutivos) {
            return 'REGISTRO DE PERSONA MORAL';
        } elseif ($tramite->datosGenerales && $tramite->datosGenerales->razon_social) {
            return 'REGISTRO DE PERSONA MORAL';
        } else {
            return 'REGISTRO DE PERSONA FÍSICA';
        }
    }
} 