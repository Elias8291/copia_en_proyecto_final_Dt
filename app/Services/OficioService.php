<?php

namespace App\Services;

use App\Models\Oficio;
use App\Models\Tramite;
use App\Models\Proveedor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class OficioService
{
    /**
     * Generar oficio para un trámite aprobado
     */
    public function generarOficioParaTramite(Tramite $tramite, string $url = null): Oficio
    {
        try {
            Log::info('Iniciando generación de oficio para trámite', [
                'tramite_id' => $tramite->id,
                'proveedor_id' => $tramite->proveedor_id,
                'url_provista' => $url
            ]);

            // Verificar que el trámite tenga proveedor asignado
            if (!$tramite->proveedor_id) {
                throw new \Exception('El trámite no tiene proveedor asignado');
            }

            // Cargar las relaciones necesarias
            $tramite->load([
                'proveedor', 
                'datosGenerales', 
                'datosConstitutivos', 
                'direcciones.estado',
                'apoderadosLegales'
            ]);

            // Generar el PDF del oficio
            $pdfPath = $this->generarPdfOficio($tramite);

            // Generar URL para descargar el PDF
            if (!$url) {
                $url = $this->generarUrlOficio($tramite, $pdfPath);
            }

            // Generar contenido del oficio
            $contenido = $this->generarContenidoOficio($tramite);

            // Crear el oficio
            $oficio = Oficio::crearOficioConProveedor($tramite, $url, $contenido);

            Log::info('Oficio generado exitosamente', [
                'oficio_id' => $oficio->id,
                'numero_oficio' => $oficio->numero_oficio,
                'tramite_id' => $tramite->id,
                'proveedor_id' => $tramite->proveedor_id,
                'url' => $url,
                'pdf_path' => $pdfPath
            ]);

            return $oficio;

        } catch (\Exception $e) {
            Log::error('Error al generar oficio para trámite', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Generar PDF del oficio usando la plantilla
     */
    private function generarPdfOficio(Tramite $tramite): string
    {
        try {
            // Cargar todas las relaciones necesarias del trámite
            $tramite->load([
                'proveedor',
                'datosGenerales',
                'datosConstitutivos',
                'direcciones.estado',
                'apoderadosLegales',
                'actividades',
                'accionistas',
                'contactos'
            ]);

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
                    'numero_oficio' => Oficio::generarNumeroOficio()
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
                'fechaTexto' => Carbon::now()->format('d/m/Y'),
                'fechaInicioTramite' => $tramite->fecha_inicio,
                'fechaGeneracionDocumento' => Carbon::now(),
                'fechaVigenciaProveedor' => $proveedor->fecha_vencimiento_padron ?? Carbon::now()->addYear(),
                'qrCode' => $this->generarQrCode($tramite)
            ];

            Log::info('Datos preparados para generar PDF del oficio', [
                'tramite_id' => $tramite->id,
                'proveedor_id' => $proveedor->id,
                'datos_generales_id' => $datosGenerales ? $datosGenerales->id : null,
                'datos_constitutivos_id' => $datosConstitutivos ? $datosConstitutivos->id : null,
                'direcciones_count' => $direcciones->count(),
                'apoderados_count' => $apoderadosLegales->count(),
                'actividades_count' => $actividades->count(),
                'accionistas_count' => $accionistas->count(),
                'contactos_count' => $contactos->count()
            ]);

            // Verificar que todos los datos del trámite estén disponibles
            $datosVerificados = $this->verificarDatosTramite($tramite);

            // Determinar qué plantilla usar según el tipo de persona
            $tipoPersona = $proveedor->tipo_persona ?? 'Moral';
            $plantilla = $tipoPersona === 'Física' ? 'oficio.documento-fisica-mpdf' : 'oficio.documento-mpdf';

            // Generar el PDF
            $pdf = \PDF::loadView($plantilla, $datos);
            $pdf->setPaper('letter', 'portrait');

            // Crear directorio si no existe
            $directorio = storage_path('app/public/oficios');
            if (!file_exists($directorio)) {
                mkdir($directorio, 0755, true);
            }

            // Generar nombre del archivo
            $nombreArchivo = $this->generarNombreArchivoOficio($tramite);
            $rutaCompleta = $directorio . '/' . $nombreArchivo;

            // Guardar el PDF
            $pdf->save($rutaCompleta);

            Log::info('PDF de oficio generado exitosamente', [
                'tramite_id' => $tramite->id,
                'ruta_pdf' => $rutaCompleta,
                'tipo_persona' => $tipoPersona,
                'plantilla_usada' => $plantilla
            ]);

            return $rutaCompleta;

        } catch (\Exception $e) {
            Log::error('Error al generar PDF del oficio', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Generar código QR para validación del documento usando endroid/qr-code
     * El QR apunta a la URL de descarga del oficio para verificación
     */
    private function generarQrCode(Tramite $tramite): string
    {
        try {
            // Generar nombre del archivo del oficio
            $nombreArchivo = $this->generarNombreArchivoOficio($tramite);
            
            // URL para descargar el oficio (verificación oficial)
            $urlDescarga = route('oficios.descargar', [
                'tramite_id' => $tramite->id,
                'proveedor_id' => $tramite->proveedor->id,
                'archivo' => $nombreArchivo
            ]);
            
            Log::info('Generando QR code para oficio', [
                'tramite_id' => $tramite->id,
                'proveedor_id' => $tramite->proveedor->id,
                'nombre_archivo' => $nombreArchivo,
                'url_descarga' => $urlDescarga
            ]);

            // Usar endroid/qr-code para generar el QR
            $writer = new \Endroid\QrCode\Writer\PngWriter();
            $qrCode = \Endroid\QrCode\QrCode::create($urlDescarga)
                ->setSize(150)
                ->setMargin(10);

            $result = $writer->write($qrCode);
            
            // Convertir a base64 para incluir en el PDF como imagen HTML
            $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($result->getString());
            
            // Retornar HTML con la imagen base64 para que se muestre en el PDF
            $qrCodeHtml = '<img src="' . $qrCodeBase64 . '" alt="QR Code" style="width: 100%; height: 100%;" />';

            Log::info('QR code generado exitosamente', [
                'tramite_id' => $tramite->id,
                'proveedor_id' => $tramite->proveedor->id,
                'qr_html_length' => strlen($qrCodeHtml),
                'url_descarga' => $urlDescarga
            ]);

            return $qrCodeHtml;

        } catch (\Exception $e) {
            Log::error('Error al generar QR code', [
                'tramite_id' => $tramite->id,
                'proveedor_id' => $tramite->proveedor->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Fallback: devolver un mensaje de texto si falla la generación del QR
            $nombreArchivo = $this->generarNombreArchivoOficio($tramite);
            $urlFallback = route('oficios.descargar', [
                'tramite_id' => $tramite->id,
                'proveedor_id' => $tramite->proveedor->id,
                'archivo' => $nombreArchivo
            ]);
            return '<div style="text-align: center; font-size: 6pt; padding: 10px;">QR no disponible<br/>Descargar: ' . $urlFallback . '</div>';
        }
    }

    /**
     * Generar URL para el oficio
     */
    private function generarUrlOficio(Tramite $tramite, string $pdfPath): string
    {
        $proveedor = $tramite->proveedor;
        $datosGenerales = $tramite->datosGenerales()->latest()->first();

        // Generar nombre del archivo
        $nombreArchivo = $this->generarNombreArchivoOficio($tramite);

        // Crear URL para descargar el oficio
        $url = route('oficios.descargar', [
            'tramite_id' => $tramite->id,
            'proveedor_id' => $proveedor->id,
            'archivo' => $nombreArchivo
        ]);

        Log::info('URL de oficio generada', [
            'tramite_id' => $tramite->id,
            'url' => $url,
            'nombre_archivo' => $nombreArchivo,
            'pdf_path' => $pdfPath
        ]);

        return $url;
    }

    /**
     * Generar nombre del archivo del oficio
     */
    private function generarNombreArchivoOficio(Tramite $tramite): string
    {
        $proveedor = $tramite->proveedor;
        $datosGenerales = $tramite->datosGenerales()->latest()->first();
        
        $razonSocial = $datosGenerales ? $datosGenerales->razon_social : $proveedor->razon_social;
        $rfc = $proveedor->rfc;
        $numeroProveedor = $proveedor->pv_numero;
        
        // Limpiar caracteres especiales del nombre
        $razonSocialLimpia = preg_replace('/[^a-zA-Z0-9\s]/', '', $razonSocial);
        $razonSocialLimpia = str_replace(' ', '_', $razonSocialLimpia);
        
        $fecha = Carbon::now()->format('Y-m-d');
        
        return "Oficio_{$razonSocialLimpia}_{$rfc}_{$numeroProveedor}_{$fecha}.pdf";
    }

    /**
     * Obtener oficios por proveedor
     */
    public function obtenerOficiosPorProveedor(int $proveedorId): \Illuminate\Database\Eloquent\Collection
    {
        return Oficio::where('proveedor_id', $proveedorId)
            ->with(['tramite', 'proveedor'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obtener oficios por trámite
     */
    public function obtenerOficiosPorTramite(int $tramiteId): \Illuminate\Database\Eloquent\Collection
    {
        return Oficio::where('tramite_id', $tramiteId)
            ->with(['tramite', 'proveedor'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Actualizar estado del oficio
     */
    public function actualizarEstadoOficio(int $oficioId, string $estado): bool
    {
        try {
            $oficio = Oficio::findOrFail($oficioId);
            $oficio->update(['estado' => $estado]);

            Log::info('Estado de oficio actualizado', [
                'oficio_id' => $oficioId,
                'estado_anterior' => $oficio->getOriginal('estado'),
                'estado_nuevo' => $estado
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error al actualizar estado del oficio', [
                'oficio_id' => $oficioId,
                'estado' => $estado,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Verificar que todos los datos del trámite estén disponibles
     */
    public function verificarDatosTramite(Tramite $tramite): array
    {
        $tramite->load([
            'proveedor',
            'datosGenerales',
            'datosConstitutivos',
            'direcciones.estado',
            'apoderadosLegales',
            'actividades',
            'accionistas',
            'contactos'
        ]);

        $verificacion = [
            'tramite' => [
                'id' => $tramite->id,
                'tipo_tramite' => $tramite->tipo_tramite,
                'status' => $tramite->status,
                'fecha_inicio' => $tramite->fecha_inicio,
                'fecha_finalizacion' => $tramite->fecha_finalizacion,
                'observaciones' => $tramite->observaciones,
                'proveedor_id' => $tramite->proveedor_id
            ],
            'proveedor' => $tramite->proveedor ? [
                'id' => $tramite->proveedor->id,
                'rfc' => $tramite->proveedor->rfc,
                'razon_social' => $tramite->proveedor->razon_social,
                'pv_numero' => $tramite->proveedor->pv_numero,
                'tipo_persona' => $tramite->proveedor->tipo_persona,
                'estado_padron' => $tramite->proveedor->estado_padron,
                'fecha_vencimiento_padron' => $tramite->proveedor->fecha_vencimiento_padron
            ] : null,
            'datos_generales' => $tramite->datosGenerales()->latest()->first() ? [
                'id' => $tramite->datosGenerales()->latest()->first()->id,
                'razon_social' => $tramite->datosGenerales()->latest()->first()->razon_social,
                'giro' => $tramite->datosGenerales()->latest()->first()->giro,
                'fecha_constitucion' => $tramite->datosGenerales()->latest()->first()->fecha_constitucion
            ] : null,
            'datos_constitutivos' => $tramite->datosConstitutivos()->latest()->first() ? [
                'id' => $tramite->datosConstitutivos()->latest()->first()->id,
                'capital_social' => $tramite->datosConstitutivos()->latest()->first()->capital_social,
                'numero_notario' => $tramite->datosConstitutivos()->latest()->first()->numero_notario
            ] : null,
            'direcciones' => $tramite->direcciones()->with('estado')->get()->map(function($direccion) {
                return [
                    'id' => $direccion->id,
                    'calle' => $direccion->calle,
                    'numero_exterior' => $direccion->numero_exterior,
                    'numero_interior' => $direccion->numero_interior,
                    'colonia_asentamiento' => $direccion->colonia_asentamiento,
                    'municipio' => $direccion->municipio,
                    'estado' => $direccion->estado ? $direccion->estado->nombre : null,
                    'codigo_postal' => $direccion->codigo_postal
                ];
            })->toArray(),
            'apoderados_legales' => $tramite->apoderadosLegales()->latest()->get()->map(function($apoderado) {
                return [
                    'id' => $apoderado->id,
                    'nombre_completo' => $apoderado->nombre_completo,
                    'cargo' => $apoderado->cargo,
                    'rfc' => $apoderado->rfc
                ];
            })->toArray(),
            'actividades' => $tramite->actividades()->get()->map(function($actividad) {
                return [
                    'id' => $actividad->id,
                    'nombre' => $actividad->nombre,
                    'descripcion' => $actividad->descripcion
                ];
            })->toArray(),
            'accionistas' => $tramite->accionistas()->get()->map(function($accionista) {
                return [
                    'id' => $accionista->id,
                    'nombre' => $accionista->nombre,
                    'porcentaje' => $accionista->porcentaje
                ];
            })->toArray(),
            'contactos' => $tramite->contactos()->get()->map(function($contacto) {
                return [
                    'id' => $contacto->id,
                    'nombre' => $contacto->nombre,
                    'email' => $contacto->email,
                    'telefono' => $contacto->telefono
                ];
            })->toArray()
        ];

        Log::info('Verificación completa de datos del trámite', [
            'tramite_id' => $tramite->id,
            'datos_disponibles' => $verificacion
        ]);

        return $verificacion;
    }

    /**
     * Generar contenido del oficio
     */
    public function generarContenidoOficio(Tramite $tramite): string
    {
        $proveedor = $tramite->proveedor;
        $datosGenerales = $tramite->datosGenerales()->latest()->first();
        
        $razonSocial = $datosGenerales ? $datosGenerales->razon_social : $proveedor->razon_social;
        $rfc = $proveedor->rfc;
        $numeroProveedor = $proveedor->pv_numero;
        $fecha = Carbon::now()->format('d/m/Y');

        $contenido = "
        OFICIO DE ASIGNACIÓN DE PROVEEDOR

        Fecha: {$fecha}
        Número de Oficio: " . Oficio::generarNumeroOficio() . "

        Por medio del presente se informa que se ha asignado el número de proveedor 
        {$numeroProveedor} a la empresa:

        RAZÓN SOCIAL: {$razonSocial}
        RFC: {$rfc}
        NÚMERO DE PROVEEDOR: {$numeroProveedor}

        El trámite {$tramite->id} ha sido aprobado exitosamente y se ha procedido 
        a la asignación del número de proveedor correspondiente.

        Este oficio debe ser presentado para cualquier trámite administrativo 
        relacionado con la contratación de servicios.

        Atentamente,
        Dirección de Contrataciones
        ";

        return trim($contenido);
    }
} 