<?php

namespace App\ViewModels;

class FormDataViewModel
{
    private array $datos;

    public function __construct(array $datos)
    {
        $this->datos = $datos;
    }

    /**
     * Obtiene datos generales
     */
    public function getDatosGenerales(): array
    {
        $datosGenerales = $this->datos['datos_generales'] ?? [];
        $contacto = $this->datos['contacto'] ?? [];
        
        // Combinar datos generales con datos de contacto
        return array_merge($datosGenerales, [
            'telefono' => $contacto['telefono'] ?? '',
            'nombre_contacto' => $contacto['nombre_contacto'] ?? '',
            'cargo' => $contacto['cargo'] ?? '',
            'telefono_contacto' => $contacto['telefono'] ?? '',
            'correo_contacto' => $contacto['correo_electronico'] ?? '',
        ]);
    }

    /**
     * Obtiene datos de domicilio
     */
    public function getDatosDomicilio(): array
    {
        return $this->datos['domicilio'] ?? [];
    }

    /**
     * Obtiene datos de domicilio formateados para formularios
     */
    public function getDatosDomicilioForm(): array
    {
        $domicilio = $this->getDatosDomicilio();
        
        // Extraer coordenadas de la relación si existe
        $latitud = '';
        $longitud = '';
        
        if (isset($domicilio['coordenada']) && is_array($domicilio['coordenada'])) {
            $latitud = $domicilio['coordenada']['latitud'] ?? '';
            $longitud = $domicilio['coordenada']['longitud'] ?? '';
        } elseif (isset($domicilio['latitud']) && isset($domicilio['longitud'])) {
            // Fallback por si las coordenadas están directamente en el domicilio
            $latitud = $domicilio['latitud'];
            $longitud = $domicilio['longitud'];
        }
        
        // Si no hay coordenadas, usar coordenadas por defecto (Centro de CDMX)
        if (empty($latitud) || empty($longitud)) {
            $latitud = '19.4326';
            $longitud = '-99.1332';
        }
        
        return [
            'calle' => $domicilio['calle'] ?? '',
            'numero_exterior' => $domicilio['numero_exterior'] ?? '',
            'numero_interior' => $domicilio['numero_interior'] ?? '',
            'colonia' => $domicilio['colonia'] ?? '',
            'asentamiento' => $domicilio['asentamiento'] ?? '',
            'codigo_postal' => $domicilio['codigo_postal'] ?? '',
            'municipio' => $domicilio['municipio'] ?? '',
            'estado' => $domicilio['estado']['nombre'] ?? ($domicilio['estado'] ?? ''),
            'estado_id' => $domicilio['estado_id'] ?? '',
            'entre_calle' => $domicilio['entre_calle'] ?? '',
            'y_calle' => $domicilio['y_calle'] ?? '',
            'latitud' => $latitud,
            'longitud' => $longitud,
        ];
    }

    /**
     * Obtiene actividades económicas
     */
    public function getActividades(): array
    {
        $actividades = $this->datos['actividades'] ?? [];
        // Si es una colección, convertir a array
        if ($actividades instanceof \Illuminate\Support\Collection) {
            return $actividades->toArray();
        }
        return $actividades;
    }

    /**
     * Obtiene actividades formateadas para formularios
     */
    public function getActividadesForm(): array
    {
        $actividades = $this->getActividades();
        $formateadas = [];
        
        foreach ($actividades as $actividad) {
            // Si la actividad tiene una relación anidada con 'actividad'
            if (isset($actividad['actividad']) && is_array($actividad['actividad'])) {
                $formateadas[] = [
                    'id' => $actividad['actividad']['id'] ?? null,
                    'nombre' => $actividad['actividad']['nombre'] ?? '',
                    'codigo' => $actividad['actividad']['codigo'] ?? '',
                ];
            } else {
                // Formato directo (fallback)
                $formateadas[] = [
                    'id' => $actividad['id'] ?? $actividad['actividad_id'] ?? null,
                    'nombre' => $actividad['nombre'] ?? $actividad['descripcion'] ?? '',
                    'codigo' => $actividad['codigo'] ?? '',
                ];
            }
        }
        
        return $formateadas;
    }

    /**
     * Obtiene accionistas
     */
    public function getAccionistas(): array
    {
        $accionistas = $this->datos['accionistas'] ?? [];
        // Si es una colección, convertir a array
        if ($accionistas instanceof \Illuminate\Support\Collection) {
            $accionistas = $accionistas->toArray();
        }
        
        // Normalizar formato de accionistas
        $accionistasNormalizados = [];
        foreach ($accionistas as $accionista) {
            if (is_array($accionista)) {
                $accionistasNormalizados[] = $accionista;
            } elseif (is_object($accionista)) {
                $accionistasNormalizados[] = [
                    'nombre' => $accionista->nombre ?? '',
                    'rfc' => $accionista->rfc ?? '',
                    'porcentaje_participacion' => $accionista->porcentaje_participacion ?? '',
                ];
            }
        }
        
        return $accionistasNormalizados;
    }

    /**
     * Obtiene accionistas formateados para formularios
     */
    public function getAccionistasForm(): array
    {
        $accionistas = $this->getAccionistas();
        $formateados = [];
        
        foreach ($accionistas as $accionista) {
            $formateados[] = [
                'nombre' => $accionista['nombre'] ?? '',
                'rfc' => $accionista['rfc'] ?? '',
                'porcentaje_participacion' => $accionista['porcentaje_participacion'] ?? '',
            ];
        }
        
        return $formateados;
    }

    /**
     * Obtiene datos del apoderado
     */
    public function getApoderado(): array
    {
        return $this->datos['apoderado'] ?? [];
    }

    /**
     * Obtiene datos del apoderado formateados para formularios
     */
    public function getApoderadoForm(): array
    {
        $apoderado = $this->getApoderado();
        
        // Formatear fechas
        $fechaInscripcionPoder = '';
        if (!empty($apoderado['fecha_inscripcion_poder'])) {
            $fechaInscripcionPoder = is_string($apoderado['fecha_inscripcion_poder']) 
                ? $apoderado['fecha_inscripcion_poder'] 
                : \Carbon\Carbon::parse($apoderado['fecha_inscripcion_poder'])->format('Y-m-d');
        }
        
        $fechaPoder = '';
        if (!empty($apoderado['fecha_poder'])) {
            $fechaPoder = is_string($apoderado['fecha_poder']) 
                ? $apoderado['fecha_poder'] 
                : \Carbon\Carbon::parse($apoderado['fecha_poder'])->format('Y-m-d');
        }
        
        $fechaInscripcion = '';
        if (!empty($apoderado['fecha_inscripcion'])) {
            $fechaInscripcion = is_string($apoderado['fecha_inscripcion']) 
                ? $apoderado['fecha_inscripcion'] 
                : \Carbon\Carbon::parse($apoderado['fecha_inscripcion'])->format('Y-m-d');
        }
        
        return [
            'nombre_apoderado' => $apoderado['nombre_apoderado'] ?? $apoderado['nombre'] ?? '',
            'rfc' => $apoderado['rfc'] ?? '',
            'rfc_apoderado' => $apoderado['rfc'] ?? '',
            'numero_escritura_constitutiva_poder' => $apoderado['numero_escritura_constitutiva_poder'] ?? '',
            'numero_registro_publico_poder' => $apoderado['numero_registro_publico_poder'] ?? '',
            'fecha_inscripcion_poder' => $fechaInscripcionPoder,
            // Datos del instrumento notarial
            'numero_escritura' => $apoderado['numero_escritura'] ?? '',
            'numero_escritura_poder' => $apoderado['numero_escritura_poder'] ?? '',
            'fecha_poder' => $fechaPoder,
            'nombre_notario_poder' => $apoderado['nombre_notario_poder'] ?? '',
            'numero_notario_poder' => $apoderado['numero_notario_poder'] ?? '',
            'estado_id' => $apoderado['estado_id'] ?? '',
            'estado_nombre' => $apoderado['estado_nombre'] ?? '',
            'numero_registro_publico' => $apoderado['numero_registro_publico'] ?? '',
            'fecha_inscripcion' => $fechaInscripcion,
        ];
    }

    /**
     * Obtiene datos de constitución
     */
    public function getConstitucion(): array
    {
        return $this->datos['constitucion'] ?? [];
    }

    /**
     * Obtiene datos de constitución formateados para formularios
     */
    public function getConstitucionForm(): array
    {
        $constitucion = $this->getConstitucion();
        

        
        // Formatear fechas
        $fechaConstitucion = '';
        if (!empty($constitucion['fecha_constitucion'])) {
            $fechaConstitucion = is_string($constitucion['fecha_constitucion']) 
                ? $constitucion['fecha_constitucion'] 
                : \Carbon\Carbon::parse($constitucion['fecha_constitucion'])->format('Y-m-d');
        }
        
        $fechaInscripcion = '';
        if (!empty($constitucion['fecha_inscripcion'])) {
            $fechaInscripcion = is_string($constitucion['fecha_inscripcion']) 
                ? $constitucion['fecha_inscripcion'] 
                : \Carbon\Carbon::parse($constitucion['fecha_inscripcion'])->format('Y-m-d');
        }
        
        return [
            'numero_escritura' => $constitucion['numero_escritura'] ?? '',
            'numero_escritura_constitutiva' => $constitucion['numero_escritura_constitutiva'] ?? '',
            'fecha_constitucion' => $fechaConstitucion,
            'nombre_notario' => $constitucion['nombre_notario'] ?? '',
            'numero_notario' => $constitucion['numero_notario'] ?? '',
            'estado_id' => $constitucion['estado_id'] ?? '',
            'estado_nombre' => $constitucion['estado_nombre'] ?? '',
            'numero_registro_publico' => $constitucion['numero_registro_publico'] ?? '',
            'fecha_inscripcion' => $fechaInscripcion,
        ];
    }

    /**
     * Obtiene archivos
     */
    public function getArchivos(): array
    {
        $archivos = $this->datos['archivos'] ?? [];
        // Si es una colección, convertir a array
        if ($archivos instanceof \Illuminate\Support\Collection) {
            return $archivos->toArray();
        }
        return $archivos;
    }

    /**
     * Obtiene archivos formateados para formularios
     */
    public function getArchivosForm(): array
    {
        $archivos = $this->getArchivos();
        $formateados = [];
        
        foreach ($archivos as $archivo) {
            $formateados[] = [
                'id' => $archivo['id'] ?? null,
                'nombre_original' => $archivo['nombre_original'] ?? '',
                'nombre_archivo' => $archivo['nombre_archivo'] ?? '',
                'ruta' => $archivo['ruta'] ?? '',
                'extension' => $archivo['extension'] ?? '',
                'tamaño' => $archivo['tamaño'] ?? 0,
                'status' => $archivo['status'] ?? 'Pendiente',
                'catalogo_archivo_id' => $archivo['catalogo_archivo_id'] ?? null,
                'nombre_catalogo' => $archivo['catalogo_archivo']['nombre'] ?? '',
                'catalogoArchivo' => [
                    'nombre' => $archivo['catalogo_archivo']['nombre'] ?? '',
                    'descripcion' => $archivo['catalogo_archivo']['descripcion'] ?? '',
                    'tipo_archivo' => $archivo['catalogo_archivo']['tipo_archivo'] ?? '',
                    'obligatorio' => $archivo['catalogo_archivo']['obligatorio'] ?? false,
                ]
            ];
        }
        
        return $formateados;
    }

    /**
     * Obtiene datos de contacto
     */
    public function getContacto(): array
    {
        return $this->datos['contacto'] ?? [];
    }

    /**
     * Obtiene datos de contacto formateados para formularios
     */
    public function getContactoForm(): array
    {
        $contacto = $this->getContacto();
        
        return [
            'correo_electronico' => $contacto['correo_electronico'] ?? '',
            'telefono' => $contacto['telefono'] ?? '',
        ];
    }

    /**
     * Obtiene todos los datos formateados para formularios
     */
    public function getAllFormData(): array
    {
        return [
            'datos_generales' => $this->getDatosGenerales(),
            'domicilio' => $this->getDatosDomicilioForm(),
            'actividades' => $this->getActividadesForm(),
            'accionistas' => $this->getAccionistasForm(),
            'apoderado' => $this->getApoderadoForm(),
            'constitucion' => $this->getConstitucionForm(),
            'contacto' => $this->getContactoForm(),
            'archivos' => $this->getArchivos(),
        ];
    }

    /**
     * Verifica si tiene datos de persona moral
     */
    public function isPersonaMoral(): bool
    {
        $datosGenerales = $this->getDatosGenerales();
        return ($datosGenerales['tipo_persona'] ?? '') === 'Moral';
    }

    /**
     * Obtiene el RFC
     */
    public function getRfc(): string
    {
        $datosGenerales = $this->getDatosGenerales();
        return $datosGenerales['rfc'] ?? '';
    }

    /**
     * Obtiene la razón social
     */
    public function getRazonSocial(): string
    {
        $datosGenerales = $this->getDatosGenerales();
        return $datosGenerales['razon_social'] ?? '';
    }
} 