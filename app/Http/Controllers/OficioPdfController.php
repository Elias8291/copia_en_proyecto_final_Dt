<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\Tramite;
use App\Services\OficioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class OficioPdfController extends Controller
{
    protected $oficioService;

    public function __construct(OficioService $oficioService)
    {
        $this->oficioService = $oficioService;
    }

    /**
     * Generar PDF del oficio
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
                'tipoTramite' => 'REGISTRO EN EL PADRÓN DE PROVEEDORES',
                'logoEncabezado' => 'data:image/jpeg;base64,' . base64_encode(file_get_contents(base_path('public/images/logo_encabezado2022.jpg'))),
                'logoLateral' => 'data:image/jpeg;base64,' . base64_encode(file_get_contents(base_path('public/images/logo_lateral2022.jpg')))
            ];

            // Generar PDF
            $pdf = Pdf::loadView('oficio.documento', $data);
            $pdf->setPaper('letter', 'portrait');

            // Guardar PDF en storage
            $fileName = 'oficios/oficio_' . $oficio->numero_oficio . '_' . $oficio->id . '.pdf';
            $pdfPath = \Storage::disk('public')->put($fileName, $pdf->output());

            if ($pdfPath) {
                // Actualizar la URL del documento en la base de datos
                $oficio->update(['url_documento' => $fileName]);
                return redirect(\Storage::disk('public')->url($fileName));
            } else {
                throw new \Exception('Error al guardar el PDF');
            }

        } catch (\Exception $e) {
            Log::error('Error al generar PDF del oficio', [
                'oficio_id' => $oficio->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Error al generar el PDF del oficio: ' . $e->getMessage());
        }
    }

    /**
     * Generar oficio y PDF para un trámite
     */
    public function generarOficioTramite(Tramite $tramite)
    {
        try {
            // Verificar si ya existe un oficio para este trámite
            $oficioExistente = Oficio::where('tramite_id', $tramite->id)->first();
            
            if ($oficioExistente) {
                return $this->generarPdf($oficioExistente);
            }

            // Crear nuevo oficio
            $oficio = $this->oficioService->crearOficioAprobacion($tramite);
            return $this->generarPdf($oficio);

        } catch (\Exception $e) {
            Log::error('Error al generar oficio para trámite', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Error al generar oficio para trámite: ' . $e->getMessage());
        }
    }

    /**
     * Ver PDF del oficio en el navegador
     */
    public function verPdf(Oficio $oficio)
    {
        try {
            // Verificar si existe el PDF guardado
            if (!$oficio->url_documento || !\Storage::disk('public')->exists($oficio->url_documento)) {
                return $this->generarPdf($oficio);
            }

            $filePath = \Storage::disk('public')->path($oficio->url_documento);
            
            if (!file_exists($filePath)) {
                throw new \Exception('Archivo PDF no encontrado');
            }

            return response()->file($filePath, ['Content-Type' => 'application/pdf']);

        } catch (\Exception $e) {
            Log::error('Error al mostrar PDF del oficio', [
                'oficio_id' => $oficio->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Error al mostrar el PDF del oficio: ' . $e->getMessage());
        }
    }

    /**
     * Descargar PDF del oficio
     */
    public function descargarPdf(Oficio $oficio)
    {
        try {
            // Verificar si existe el PDF guardado
            if (!$oficio->url_documento || !\Storage::disk('public')->exists($oficio->url_documento)) {
                return $this->generarPdf($oficio);
            }

            $filePath = \Storage::disk('public')->path($oficio->url_documento);
            
            if (!file_exists($filePath)) {
                throw new \Exception('Archivo PDF no encontrado');
            }

            $filename = 'oficio_' . $oficio->numero_oficio . '.pdf';
            
            return response()->download($filePath, $filename, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al descargar PDF del oficio', [
                'oficio_id' => $oficio->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Error al descargar el PDF del oficio: ' . $e->getMessage());
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
