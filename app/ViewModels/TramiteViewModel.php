<?php

namespace App\ViewModels;

use App\Services\Tramites\ConstanciaService;
use App\Services\RfcProveedorService;

class TramiteViewModel
{
    private array $datosConstancia;
    private ConstanciaService $constanciaService;
    private RfcProveedorService $rfcProveedorService;

    public function __construct(array $datosConstancia = [])
    {
        $this->datosConstancia = $datosConstancia;
        $this->constanciaService = app(ConstanciaService::class);
        $this->rfcProveedorService = app(RfcProveedorService::class);
    }

    /**
     * Verificar si es persona moral
     */
    public function isPersonaMoral(): bool
    {
        $tipoPersona = $this->datosConstancia['datos_generales']['tipo_persona'] ?? 
                      $this->datosConstancia['tipo_persona'] ?? 
                      'Física';
        return strtolower($tipoPersona) === 'moral';
    }

    /**
     * Obtener datos de actividades para el formulario
     */
    public function getActividadesForm(): array
    {
        $actividades = $this->datosConstancia['actividades'] ?? [];
        
        if (is_array($actividades) && !empty($actividades)) {
            return [
                'actividades' => collect($actividades)->map(function($actividad) {
                    return [
                        'id' => $actividad['id'] ?? null,
                        'actividad_economica_id' => $actividad['actividad_economica_id'] ?? $actividad['id'] ?? null,
                        'nombre' => $actividad['nombre'] ?? $actividad['actividad']['nombre'] ?? '',
                        'descripcion' => $actividad['descripcion'] ?? '',
                        'porcentaje' => $actividad['porcentaje'] ?? 0
                    ];
                })->toArray()
            ];
        }
        
        return ['actividades' => []];
    }

    /**
     * Obtener datos de constitución para el formulario
     */
    public function getConstitucionForm(): array
    {
        $constitucion = $this->datosConstancia['constitucion'] ?? [];
        
        if (empty($constitucion)) {
            return [];
        }
        
        return [
            'fecha_constitucion' => $constitucion['fecha_constitucion'] ?? '',
            'numero_escritura' => $constitucion['numero_escritura'] ?? '',
            'nombre_notario' => $constitucion['nombre_notario'] ?? '',
            'numero_notario' => $constitucion['numero_notario'] ?? '',
            'estado_id' => $constitucion['estado_id'] ?? $constitucion['instrumento_notarial']['estado_id'] ?? null,
            'estado_nombre' => $constitucion['estado_nombre'] ?? $constitucion['instrumento_notarial']['estado']['nombre'] ?? '',
            'valor_escritura' => $constitucion['valor_escritura'] ?? '',
            'moneda' => $constitucion['moneda'] ?? 'MXN'
        ];
    }

    /**
     * Obtener datos de accionistas para el formulario
     */
    public function getAccionistasForm(): array
    {
        $accionistas = $this->datosConstancia['accionistas'] ?? [];
        
        if (is_array($accionistas) && !empty($accionistas)) {
            return [
                'accionistas' => collect($accionistas)->map(function($accionista) {
                    return [
                        'id' => $accionista['id'] ?? null,
                        'nombre' => $accionista['nombre'] ?? '',
                        'apellido_paterno' => $accionista['apellido_paterno'] ?? '',
                        'apellido_materno' => $accionista['apellido_materno'] ?? '',
                        'rfc' => $accionista['rfc'] ?? '',
                        'curp' => $accionista['curp'] ?? '',
                        'porcentaje_participacion' => $accionista['porcentaje_participacion'] ?? 0,
                        'nacionalidad' => $accionista['nacionalidad'] ?? 'Mexicana'
                    ];
                })->toArray()
            ];
        }
        
        return ['accionistas' => []];
    }

    /**
     * Obtener datos de apoderado para el formulario
     */
    public function getApoderadoForm(): array
    {
        $apoderado = $this->datosConstancia['apoderado'] ?? [];
        
        if (empty($apoderado)) {
            return [];
        }
        
        return [
            'nombre' => $apoderado['nombre'] ?? '',
            'apellido_paterno' => $apoderado['apellido_paterno'] ?? '',
            'apellido_materno' => $apoderado['apellido_materno'] ?? '',
            'rfc' => $apoderado['rfc'] ?? '',
            'curp' => $apoderado['curp'] ?? '',
            'fecha_nacimiento' => $apoderado['fecha_nacimiento'] ?? '',
            'nacionalidad' => $apoderado['nacionalidad'] ?? 'Mexicana',
            'numero_escritura' => $apoderado['numero_escritura'] ?? '',
            'fecha_escritura' => $apoderado['fecha_escritura'] ?? '',
            'nombre_notario' => $apoderado['nombre_notario'] ?? '',
            'numero_notario' => $apoderado['numero_notario'] ?? '',
            'estado_id' => $apoderado['estado_id'] ?? $apoderado['instrumento_notarial']['estado_id'] ?? null,
            'estado_nombre' => $apoderado['estado_nombre'] ?? $apoderado['instrumento_notarial']['estado']['nombre'] ?? '',
            'valor_escritura' => $apoderado['valor_escritura'] ?? '',
            'moneda' => $apoderado['moneda'] ?? 'MXN'
        ];
    }

    /**
     * Obtener datos de archivos
     */
    public function getArchivos(): array
    {
        $archivos = $this->datosConstancia['archivos'] ?? [];
        
        // Si es una Collection, convertirla a array
        if ($archivos instanceof \Illuminate\Support\Collection) {
            return $archivos->toArray();
        }
        
        // Si ya es un array, devolverlo tal como está
        if (is_array($archivos)) {
            return $archivos;
        }
        
        // Si es null o cualquier otro tipo, devolver array vacío
        return [];
    }

    public function getDatosFinales(array $datos = []): array
    {
        if (empty($this->datosConstancia)) {
            return $datos;
        }

        return [
            'razon_social' => $this->datosConstancia['razon_social'] ?? ($datos['razon_social'] ?? ''),
            'rfc' => $this->datosConstancia['rfc'] ?? ($datos['rfc'] ?? ''),
            'tipo_persona' => $this->datosConstancia['tipo_persona'] ?? ($datos['tipo_persona'] ?? ''),
            'curp' => $this->datosConstancia['curp'] ?? ($datos['curp'] ?? ''),
            'domicilio' => $this->getDatosDomicilio($datos['domicilio'] ?? []),
        ];
    }

    private function getDatosDomicilio(array $datos = []): array
    {
        if (empty($this->datosConstancia['domicilio'])) {
            return $datos;
        }

        return [
            'codigo_postal' => $this->datosConstancia['domicilio']['codigo_postal'] ?? ($datos['codigo_postal'] ?? ''),
            'estado' => $this->datosConstancia['domicilio']['entidad_federativa'] ?? ($datos['estado'] ?? ''),
            'municipio' => $this->datosConstancia['domicilio']['municipio'] ?? ($datos['municipio'] ?? ''),
            'asentamiento' => $this->datosConstancia['domicilio']['colonia'] ?? ($datos['asentamiento'] ?? ''),
            'calle' => $this->datosConstancia['domicilio']['calle'] ?? ($datos['calle'] ?? ''),
            'numero_exterior' => $this->datosConstancia['domicilio']['numero_exterior'] ?? ($datos['numero_exterior'] ?? ''),
            'numero_interior' => $this->datosConstancia['domicilio']['numero_interior'] ?? ($datos['numero_interior'] ?? ''),
            // Los campos entre_calle y y_calle SOLO vienen de los datos del formulario, nunca de la constancia
            'entre_calle' => !empty($datos['entre_calle']) ? $datos['entre_calle'] : '',
            'y_calle' => !empty($datos['y_calle']) ? $datos['y_calle'] : '',
        ];
    }

    public function sonCamposEditables(): bool
    {
        return empty($this->datosConstancia);
    }

    public function determinarTipoPersona(string $rfc): string
    {
        return $this->rfcProveedorService->determinarTipoPersona($rfc);
    }

    public function getDatosGenerales(array $datos = []): array
    {
        // Si tenemos datos de trámite histórico, usarlos
        if (isset($this->datosConstancia['datos_generales'])) {
            $datosGenerales = $this->datosConstancia['datos_generales'];
            return [
                'razon_social' => $datosGenerales['razon_social'] ?? '',
                'rfc' => $datosGenerales['rfc'] ?? '',
                'tipo_persona' => $datosGenerales['tipo_persona'] ?? 'Física',
                'curp' => $datosGenerales['curp'] ?? '',
                'pagina_web' => $datosGenerales['pagina_web'] ?? '',
                'telefono' => $datosGenerales['telefono'] ?? '',
                'correo_electronico' => $datosGenerales['correo_electronico'] ?? '',
            ];
        }
        
        // Fallback al método original
        $datosFinales = $this->getDatosFinales($datos);
        
        return [
            'razon_social' => $datosFinales['razon_social'],
            'rfc' => $datosFinales['rfc'],
            'tipo_persona' => $this->determinarTipoPersona($datosFinales['rfc']),
            'curp' => $datosFinales['curp'],
            'pagina_web' => $datos['pagina_web'] ?? '',
            'telefono' => $datos['telefono'] ?? '',
            'correo_electronico' => $datos['correo_electronico'] ?? '',
        ];
    }

    public function getDatosDomicilioForm(array $datos = []): array
    {
        // Si tenemos datos de trámite histórico, usarlos
        if (isset($this->datosConstancia['domicilio'])) {
            $domicilio = $this->datosConstancia['domicilio'];
            return [
                'codigo_postal' => $domicilio['codigo_postal'] ?? '',
                'estado_id' => $domicilio['estado_id'] ?? null,
                'estado_nombre' => $domicilio['estado_nombre'] ?? $domicilio['estado']['nombre'] ?? '',
                'municipio_id' => $domicilio['municipio_id'] ?? null,
                'municipio_nombre' => $domicilio['municipio_nombre'] ?? $domicilio['municipio']['nombre'] ?? '',
                'asentamiento_id' => $domicilio['asentamiento_id'] ?? null,
                'asentamiento_nombre' => $domicilio['asentamiento_nombre'] ?? $domicilio['asentamiento']['nombre'] ?? '',
                'calle' => $domicilio['calle'] ?? '',
                'numero_exterior' => $domicilio['numero_exterior'] ?? '',
                'numero_interior' => $domicilio['numero_interior'] ?? '',
                'entre_calle' => $domicilio['entre_calle'] ?? '',
                'y_calle' => $domicilio['y_calle'] ?? '',
                'latitud' => $domicilio['latitud'] ?? $domicilio['coordenada']['latitud'] ?? null,
                'longitud' => $domicilio['longitud'] ?? $domicilio['coordenada']['longitud'] ?? null,
            ];
        }
        
        // Fallback al método original
        return $this->getDatosDomicilio($datos);
    }
} 