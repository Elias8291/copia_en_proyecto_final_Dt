<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\Tramite;
use App\Services\OficioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mpdf\Mpdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class OficioMpdfController extends Controller
{
    protected $oficioService;

    public function __construct(OficioService $oficioService)
    {
        $this->oficioService = $oficioService;
    }

    /**
     * Generar PDF del oficio usando mPDF
     */
    public function generarPdf(Oficio $oficio)
    {
        try {
            // Verificar si ya existe el PDF guardado
            if ($oficio->url_documento && \Storage::disk('public')->exists($oficio->url_documento)) {
                return redirect(\Storage::disk('public')->url($oficio->url_documento));
            }

            // Cargar el trámite con sus relaciones
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
                'margin_bottom' => 0,
                'margin_header' => 0,
                'margin_footer' => 0
            ]);

            // Configurar directorio de imágenes
            $mpdf->SetBasePath(public_path());

            // Escribir HTML
            $mpdf->WriteHTML($html);

            // Guardar PDF en storage
            $fileName = 'oficios/oficio_' . $oficio->numero_oficio . '_' . $oficio->id . '.pdf';
            $pdfContent = $mpdf->Output('', 'S');
            $pdfPath = \Storage::disk('public')->put($fileName, $pdfContent);

            if ($pdfPath) {
                // Actualizar la URL del documento en la base de datos
                $oficio->update(['url_documento' => $fileName]);
                return redirect(\Storage::disk('public')->url($fileName));
            } else {
                throw new \Exception('Error al guardar el PDF');
            }

        } catch (\Exception $e) {
            Log::error('Error al generar PDF del oficio con mPDF', [
                'oficio_id' => $oficio->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Error al generar el PDF del oficio: ' . $e->getMessage());
        }
    }

    /**
     * Formatear fecha en español
     */
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