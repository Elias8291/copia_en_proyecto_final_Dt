<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\SeccionRevision;
use App\Models\Archivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CorreccionService
{
    private DatosGeneralesService $datosGeneralesService;
    private DomicilioService $domicilioService;
    private ActividadesService $actividadesService;
    private AccionistasService $accionistasService;
    private ApoderadoService $apoderadoService;
    private ArchivosService $archivosService;
    private ConstitucionService $constitucionService;
    private ContactoService $contactoService;

    public function __construct(
        DatosGeneralesService $datosGeneralesService,
        DomicilioService $domicilioService,
        ActividadesService $actividadesService,
        AccionistasService $accionistasService,
        ApoderadoService $apoderadoService,
        ArchivosService $archivosService,
        ConstitucionService $constitucionService,
        ContactoService $contactoService
    ) {
        $this->datosGeneralesService = $datosGeneralesService;
        $this->domicilioService = $domicilioService;
        $this->actividadesService = $actividadesService;
        $this->accionistasService = $accionistasService;
        $this->apoderadoService = $apoderadoService;
        $this->archivosService = $archivosService;
        $this->constitucionService = $constitucionService;
        $this->contactoService = $contactoService;
    }

    /** Procesar corrección de trámite */
    public function procesarCorreccion(Tramite $tramite, Request $request): Tramite
    {
        return DB::transaction(function () use ($tramite, $request) {
            Log::info('CorreccionService: Iniciando corrección', [
                'tramite_id' => $tramite->id,
                'user_id' => auth()->id()
            ]);

            $seccionesCorregidas = $this->obtenerSeccionesCorregidas($request);
            
            Log::info('CorreccionService: Secciones identificadas', [
                'tramite_id' => $tramite->id,
                'secciones_corregidas' => $seccionesCorregidas
            ]);

            $this->actualizarSeccionesCorregidas($tramite, $request, $seccionesCorregidas);
            $this->actualizarArchivosCorreccion($tramite, $request, $seccionesCorregidas);
            $this->actualizarEstadosCorreccion($tramite, $seccionesCorregidas);

            $tramite->update([
                'status' => 'Revision_Digital',
                'observaciones' => null,
                'correcciones_count' => $tramite->correcciones_count + 1
            ]);

            Log::info('CorreccionService: Corrección completada', [
                'tramite_id' => $tramite->id,
                'secciones_corregidas' => $seccionesCorregidas,
                'correcciones_count' => $tramite->correcciones_count
            ]);

            return $tramite->fresh();
        });
    }

    /** Identificar secciones corregidas */
    private function obtenerSeccionesCorregidas(Request $request): array
    {
        $seccionesCorregidas = [];

        if ($request->filled(['razon_social', 'rfc', 'tipo_persona'])) {
            $seccionesCorregidas[] = 'datos_generales';
        }

        if ($request->filled(['calle', 'numero_exterior', 'codigo_postal'])) {
            $seccionesCorregidas[] = 'domicilio';
        }

        if ($request->filled('actividades_seleccionadas')) {
            $seccionesCorregidas[] = 'actividades';
        }

        if ($request->filled(['nombre_contacto', 'correo_contacto'])) {
            $seccionesCorregidas[] = 'contacto';
        }

        if ($request->filled(['numero_escritura_constitutiva', 'fecha_constitucion'])) {
            $seccionesCorregidas[] = 'constitucion';
        }

        if ($request->filled('accionistas')) {
            $seccionesCorregidas[] = 'accionistas';
        }

        if ($request->filled(['nombre_apoderado', 'rfc_apoderado'])) {
            $seccionesCorregidas[] = 'apoderado';
        }

        if ($request->hasFile('archivos')) {
            $seccionesCorregidas[] = 'archivos';
        }

        return array_unique($seccionesCorregidas);
    }

    /** Actualizar secciones corregidas */
    private function actualizarSeccionesCorregidas(Tramite $tramite, Request $request, array $seccionesCorregidas): void
    {
        foreach ($seccionesCorregidas as $seccion) {
            Log::info("CorreccionService: Actualizando sección: {$seccion}", [
                'tramite_id' => $tramite->id
            ]);

            switch ($seccion) {
                case 'datos_generales':
                    $this->datosGeneralesService->actualizar($tramite, $request);
                    break;
                case 'domicilio':
                    $this->domicilioService->actualizar($tramite, $request);
                    break;
                case 'actividades':
                    $this->actividadesService->actualizar($tramite, $request);
                    break;
                case 'contacto':
                    $this->contactoService->actualizar($tramite, $request);
                    break;
                case 'constitucion':
                    $this->constitucionService->actualizar($tramite, $request);
                    break;
                case 'accionistas':
                    $this->accionistasService->actualizar($tramite, $request);
                    break;
                case 'apoderado':
                    $this->apoderadoService->actualizar($tramite, $request);
                    break;
            }
        }
    }

    /** Actualizar archivos de corrección */
    private function actualizarArchivosCorreccion(Tramite $tramite, Request $request, array $seccionesCorregidas): void
    {
        // Verificar si hay archivos en el campo 'archivos' o 'documentos'
        $archivos = $request->file('archivos') ?: $request->file('documentos');
        
        if ($archivos) {
            Log::info('CorreccionService: Actualizando archivos', [
                'tramite_id' => $tramite->id,
                'archivos_subidos' => array_keys($archivos)
            ]);

            // Los archivos nuevos se crearán con status 'Pendiente' automáticamente
            $this->archivosService->actualizar($tramite, $request);
            
            Log::info('CorreccionService: Archivos corregidos procesados', [
                'tramite_id' => $tramite->id
            ]);
        } else {
            Log::info('CorreccionService: No hay archivos para actualizar', [
                'tramite_id' => $tramite->id
            ]);
        }
    }

    /** Actualizar estados de secciones y archivos */
    private function actualizarEstadosCorreccion(Tramite $tramite, array $seccionesCorregidas): void
    {
        Log::info('CorreccionService: Actualizando estados', [
            'tramite_id' => $tramite->id,
            'secciones_corregidas' => $seccionesCorregidas
        ]);

        foreach ($seccionesCorregidas as $seccion) {
            SeccionRevision::updateOrCreate(
                [
                    'tramite_id' => $tramite->id,
                    'seccion' => $seccion
                ],
                [
                    'estado' => 'Pendiente',
                    'comentario' => null,
                    'revisado_por' => null
                ]
            );
        }

        // Los archivos nuevos se crean automáticamente con status 'Pendiente' en ArchivosService
        if (in_array('archivos', $seccionesCorregidas)) {
            Log::info('CorreccionService: Archivos corregidos - nuevos archivos creados con status Pendiente', [
                'tramite_id' => $tramite->id
            ]);
        }
    }

    /** Obtener secciones para corrección */
    public function obtenerSeccionesParaCorreccion(Tramite $tramite): array
    {
        $seccionesEncontradas = [];
        
        // Obtener secciones rechazadas con sus comentarios
        $seccionesRechazadas = SeccionRevision::where('tramite_id', $tramite->id)
            ->where('estado', 'Rechazado')
            ->get();

        \Log::info('CorreccionService: Secciones rechazadas en BD', [
            'tramite_id' => $tramite->id,
            'secciones_bd' => $seccionesRechazadas->map(function($s) {
                return [
                    'seccion' => $s->seccion,
                    'estado' => $s->estado,
                    'comentario' => $s->comentario
                ];
            })->toArray()
        ]);

        // Crear un mapa de secciones rechazadas para fácil acceso
        $mapaSecciones = [];
        foreach ($seccionesRechazadas as $seccion) {
            $mapaSecciones[$seccion->seccion] = [
                'seccion' => $seccion->seccion,
                'nombre' => $this->obtenerNombreSeccion($seccion->seccion),
                'comentario' => $seccion->comentario
            ];
        }

        // Verificar archivos rechazados
        $archivosRechazados = $tramite->archivos()
            ->where('status', 'Rechazado')
            ->exists();

        \Log::info('CorreccionService: Verificación de archivos rechazados', [
            'tramite_id' => $tramite->id,
            'archivos_rechazados' => $archivosRechazados,
        ]);

        if ($archivosRechazados) {
            $mapaSecciones['archivos'] = [
                'seccion' => 'archivos',
                'nombre' => 'Documentos',
                'comentario' => 'Algunos archivos fueron rechazados y necesitan corrección'
            ];
        }

        // Ordenar según el orden lógico del flujo del trámite
        $ordenSecciones = [
            'datos_generales',
            'actividades', 
            'domicilio',
            'constitucion',
            'accionistas',
            'apoderado',
            'archivos'
        ];

        // Construir array final ordenado
        $secciones = [];
        foreach ($ordenSecciones as $nombreSeccion) {
            if (isset($mapaSecciones[$nombreSeccion])) {
                $secciones[] = $mapaSecciones[$nombreSeccion];
            }
        }

        \Log::info('CorreccionService: Secciones para corrección obtenidas', [
            'tramite_id' => $tramite->id,
            'secciones_encontradas' => $secciones,
            'total_secciones' => count($secciones),
            'orden_aplicado' => array_column($secciones, 'seccion')
        ]);

        return $secciones;
    }

    /** Obtener nombre legible de la sección */
    private function obtenerNombreSeccion(string $seccion): string
    {
        $nombres = [
            'datos_generales' => 'Datos Generales',
            'actividades' => 'Actividades Económicas',
            'domicilio' => 'Domicilio',
            'constitucion' => 'Constitución',
            'accionistas' => 'Accionistas',
            'apoderado' => 'Apoderado Legal',
            'archivos' => 'Documentos'
        ];

        return $nombres[$seccion] ?? ucfirst(str_replace('_', ' ', $seccion));
    }

    /** Verificar si tiene secciones para corregir */
    public function tieneSeccionesParaCorregir(Tramite $tramite): bool
    {
        $seccionesParaCorregir = $this->obtenerSeccionesParaCorreccion($tramite);
        return !empty($seccionesParaCorregir);
    }

    /** Obtener resumen de correcciones */
    public function obtenerResumenCorrecciones(Tramite $tramite): array
    {
        $seccionesRechazadas = SeccionRevision::where('tramite_id', $tramite->id)
            ->where('estado', 'Rechazado')
            ->get();

        $archivosRechazados = $tramite->archivos()
            ->where('status', 'Rechazado')
            ->count();

        return [
            'secciones_rechazadas' => $seccionesRechazadas->pluck('seccion')->toArray(),
            'archivos_rechazados' => $archivosRechazados,
            'total_correcciones' => $seccionesRechazadas->count() + ($archivosRechazados > 0 ? 1 : 0)
        ];
    }
}
