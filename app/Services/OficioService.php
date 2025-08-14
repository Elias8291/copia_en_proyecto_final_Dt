<?php

namespace App\Services;

use App\Models\Oficio;
use App\Models\Tramite;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OficioService
{
    // Generar oficio completo para trámite
    public function generarOficioParaTramite(Tramite $tramite, string $url = null): Oficio
    {
        $tramite->load(['proveedor', 'datosGenerales', 'direcciones.estado']);
        
            $pdfPath = $this->generarPdfOficio($tramite);
        $url = $url ?: $this->generarUrlOficio($tramite, $pdfPath);
            $contenido = $this->generarContenidoOficio($tramite);

        return Oficio::crearOficioConProveedor($tramite, $url, $contenido);
    }

    // Generar PDF usando plantilla apropiada
    private function generarPdfOficio(Tramite $tramite): string
    {
        $tramite->load(['proveedor', 'datosGenerales', 'direcciones.estado']);
        
            $datos = [
            'oficio' => (object)['numero_oficio' => Oficio::generarNumeroOficio()],
                'tramite' => $tramite,
            'proveedor' => $tramite->proveedor,
            'datosGenerales' => $tramite->datosGenerales()->latest()->first(),
            'direcciones' => $tramite->direcciones()->with('estado')->get(),
            'fechaTexto' => $this->formatearFechaEspanol(Carbon::now()),
            'fechaInicioTramiteEspanol' => $this->formatearFechaEspanol($tramite->fecha_inicio ?: Carbon::now()->subDay()),
            'fechaGeneracionDocumentoEspanol' => $this->formatearFechaEspanol(Carbon::now()),
            'fechaVigenciaInicioEspanol' => $this->formatearFechaEspanol($tramite->proveedor->fecha_alta_padron ?: Carbon::now()),
            'fechaVigenciaFinEspanol' => $this->formatearFechaEspanol($tramite->proveedor->fecha_vencimiento_padron ?: Carbon::now()->addYear()),
                'qrCode' => $this->generarQrCode($tramite)
            ];

        $tipoPersona = $tramite->proveedor->tipo_persona ?? 'Moral';
            $plantilla = $tipoPersona === 'Física' ? 'oficio.documento-fisica-mpdf' : 'oficio.documento-mpdf';

        $pdf = \PDF::loadView($plantilla, $datos)->setPaper('letter', 'portrait');

            $directorio = storage_path('app/public/oficios');
            if (!file_exists($directorio)) {
                mkdir($directorio, 0755, true);
            }

        $rutaCompleta = $directorio . '/' . $this->generarNombreArchivoOficio($tramite);
            $pdf->save($rutaCompleta);

            return $rutaCompleta;
    }

    // Generar QR code seguro con token
    private function generarQrCode(Tramite $tramite): string
    {
        try {
            $tokenSeguro = $tramite->proveedor->obtenerTokenPublico();
            $urlPublica = route('proveedores.publico.token', $tokenSeguro);
            $urlPublica = str_replace('http://localhost', 'http://127.0.0.1:8000', $urlPublica);

            $writer = new \Endroid\QrCode\Writer\PngWriter();
            $qrCode = \Endroid\QrCode\QrCode::create($urlPublica)
                ->setSize(400)
                ->setMargin(20)
                ->setErrorCorrectionLevel(\Endroid\QrCode\ErrorCorrectionLevel::High);

            $result = $writer->write($qrCode);
            $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($result->getString());
            
            return '<div style="position: fixed; bottom: 90px; left: 20px; z-index: 99999 !important; padding: 2px;">
                <img src="' . $qrCodeBase64 . '" alt="QR Code de Verificación" style="width: 75px; height: 75px; display: block; opacity: 1.0; filter: contrast(1.1);" />
            </div>';

        } catch (\Exception $e) {
            return '<div style="position: fixed; bottom: 90px; left: 20px; z-index: 99999 !important; padding: 2px; width: 75px; height: 75px; display: flex; align-items: center; justify-content: center; text-align: center; background: #f44336; color: white; border-radius: 4px;">
                <div><p style="margin: 0; font-size: 8px; font-weight: bold;">QR ERROR</p></div>
            </div>';
        }
    }

    // Generar URL de descarga del oficio
    private function generarUrlOficio(Tramite $tramite, string $pdfPath): string
    {
        $url = route('oficios.descargar', [
            'tramite_id' => $tramite->id,
            'proveedor_id' => $tramite->proveedor->id,
            'archivo' => $this->generarNombreArchivoOficio($tramite)
        ]);

        return str_replace('http://localhost', 'http://127.0.0.1:8000', $url);
    }

    // Generar nombre único del archivo PDF
    private function generarNombreArchivoOficio(Tramite $tramite): string
    {
        $datosGenerales = $tramite->datosGenerales()->latest()->first();
        $razonSocial = $datosGenerales ? $datosGenerales->razon_social : $tramite->proveedor->razon_social;
        
        $razonSocialLimpia = str_replace(' ', '_', preg_replace('/[^a-zA-Z0-9\s]/', '', $razonSocial));
        $fecha = Carbon::now()->format('Y-m-d');
        
        return "Oficio_{$razonSocialLimpia}_{$tramite->proveedor->rfc}_{$tramite->proveedor->pv_numero}_{$fecha}.pdf";
    }

    // Generar contenido textual del oficio
    public function generarContenidoOficio(Tramite $tramite): string
    {
        $datosGenerales = $tramite->datosGenerales()->latest()->first();
        $razonSocial = $datosGenerales ? $datosGenerales->razon_social : $tramite->proveedor->razon_social;
        
        return "OFICIO DE ASIGNACIÓN DE PROVEEDOR\n\n" .
               "Fecha: " . $this->formatearFechaEspanol(Carbon::now()) . "\n" .
               "Número de Oficio: " . Oficio::generarNumeroOficio() . "\n\n" .
               "RAZÓN SOCIAL: {$razonSocial}\n" .
               "RFC: {$tramite->proveedor->rfc}\n" .
               "NÚMERO DE PROVEEDOR: {$tramite->proveedor->pv_numero}";
    }

    // Formatear fecha en español
    private function formatearFechaEspanol(Carbon $fecha): string
    {
        $meses = [
            1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
            5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
            9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
        ];

        return "{$fecha->day} de {$meses[$fecha->month]} de {$fecha->year}";
    }
} 