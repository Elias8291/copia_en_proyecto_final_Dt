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
                'fechaTexto' => $this->formatearFechaEspanol(Carbon::now()),
                'fechaInicioTramite' => $tramite->fecha_inicio,
                'fechaGeneracionDocumento' => Carbon::now(),
                'fechaVigenciaProveedor' => $proveedor->fecha_vencimiento_padron ?? Carbon::now()->addYear(),
                // Fechas formateadas en español para el contenido del oficio
                'fechaInicioTramiteEspanol' => $tramite->fecha_inicio ? $this->formatearFechaEspanol($tramite->fecha_inicio) : $this->formatearFechaEspanol(Carbon::now()->subDay()),
                'fechaGeneracionDocumentoEspanol' => $this->formatearFechaEspanol(Carbon::now()),
                'fechaVigenciaInicioEspanol' => $this->formatearFechaEspanol($proveedor->fecha_alta_padron ?? Carbon::now()),
                'fechaVigenciaFinEspanol' => $this->formatearFechaEspanol(($proveedor->fecha_vencimiento_padron ?? Carbon::now()->addYear())),
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
                'contactos_count' => $contactos->count(),
                'fechaTexto' => $datos['fechaTexto']
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
     * El QR apunta a la vista pública del proveedor para verificación oficial
     */
    private function generarQrCode(Tramite $tramite): string
    {
        try {
            // URL para ver la información pública del proveedor (verificación oficial)
            $urlPublica = route('proveedores.publico', $tramite->proveedor->id);
            
            // Forzar la URL correcta para desarrollo
            $urlPublica = str_replace('http://localhost', 'http://127.0.0.1:8000', $urlPublica);
            
            Log::info('Generando QR code para oficio', [
                'tramite_id' => $tramite->id,
                'proveedor_id' => $tramite->proveedor->id,
                'url_publica' => $urlPublica
            ]);

            // Usar endroid/qr-code para generar el QR con máxima calidad
            $writer = new \Endroid\QrCode\Writer\PngWriter();
            $qrCode = \Endroid\QrCode\QrCode::create($urlPublica)
                ->setSize(400)  // Tamaño grande para máxima nitidez
                ->setMargin(20)  // Margen adecuado
                ->setErrorCorrectionLevel(\Endroid\QrCode\ErrorCorrectionLevel::High); // Máxima corrección de errores

            $result = $writer->write($qrCode);
            
            // Convertir a base64 para incluir en el PDF como imagen HTML
            $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($result->getString());
            
            // Retornar HTML con estilo para que esté justo arriba del texto C.c.p.- Expediente y Minutario, 2cm más a la izquierda
            $qrCodeHtml = '<div style="position: fixed; bottom: 90px; left: 20px; z-index: 99999 !important; padding: 2px;">
                <img src="' . $qrCodeBase64 . '" alt="QR Code de Verificación" style="width: 75px; height: 75px; display: block; opacity: 1.0; filter: contrast(1.1);" />
            </div>';

            Log::info('QR code generado exitosamente', [
                'tramite_id' => $tramite->id,
                'proveedor_id' => $tramite->proveedor->id,
                'qr_html_length' => strlen($qrCodeHtml),
                'url_publica' => $urlPublica
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
            $urlFallback = route('proveedores.publico', $tramite->proveedor->id);
            // Forzar la URL correcta para desarrollo
            $urlFallback = str_replace('http://localhost', 'http://127.0.0.1:8000', $urlFallback);
            return '<div style="position: fixed; bottom: 90px; left: 20px; z-index: 99999 !important; padding: 2px; width: 75px; height: 75px; display: flex; align-items: center; justify-content: center; text-align: center; background: #f44336; color: white; border-radius: 4px;">
                <div>
                    <p style="margin: 0; font-size: 8px; font-weight: bold;">QR ERROR</p>
                </div>
            </div>';
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

        // Forzar la URL correcta para desarrollo
        $url = str_replace('http://localhost', 'http://127.0.0.1:8000', $url);

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
        $fecha = $this->formatearFechaEspanol(Carbon::now());

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

    /**
     * Formatear fecha en español
     */
    private function formatearFechaEspanol(Carbon $fecha): string
    {
        $meses = [
            1 => 'enero',
            2 => 'febrero', 
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre'
        ];

        $dia = $fecha->day;
        $mes = $meses[$fecha->month];
        $año = $fecha->year;

        $fechaEspanol = "{$dia} de {$mes} de {$año}";
        
        Log::info('Fecha formateada en español', [
            'fecha_original' => $fecha->toDateString(),
            'fecha_español' => $fechaEspanol
        ]);

        return $fechaEspanol;
    }
} 