<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\Proveedor;
use Illuminate\Support\Collection;

class DataRetrievalService
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

    /**
     * Obtiene datos de un trámite específico
     */
    public function obtenerDatosTramite(Tramite $tramite): array
    {
        \Log::info('DataRetrievalService: Iniciando obtención de datos', [
            'tramite_id' => $tramite->id,
            'relaciones_cargadas' => $tramite->getRelations()
        ]);
        
        $datos = [
            'datos_generales' => $this->datosGeneralesService->obtener($tramite),
            'domicilio' => $this->domicilioService->obtener($tramite),
            'actividades' => $this->actividadesService->obtener($tramite),
            'accionistas' => $this->accionistasService->obtener($tramite),
            'apoderado' => $this->apoderadoService->obtener($tramite),
            'archivos' => $this->archivosService->obtener($tramite),
            'constitucion' => $this->constitucionService->obtener($tramite),
            'contacto' => $this->contactoService->obtener($tramite),
        ];
        
        \Log::info('DataRetrievalService: Datos obtenidos', [
            'tramite_id' => $tramite->id,
            'constitucion_existe' => !is_null($datos['constitucion']),
            'apoderado_existe' => !is_null($datos['apoderado']),
            'accionistas_count' => count($datos['accionistas'] ?? [])
        ]);
        
        return $datos;
    }

    /**
     * Obtiene datos del proveedor vigente
     */
    public function obtenerDatosProveedorVigente(string $rfc): array
    {
        $proveedor = Proveedor::where('rfc', $rfc)
            ->where('estado_padron', 'vigente')
            ->first();

        if (!$proveedor) {
            return [];
        }

        return [
            'datos_generales' => $this->obtenerDatosGeneralesProveedor($proveedor),
            'domicilio' => $this->obtenerDomicilioProveedor($proveedor),
            'actividades' => $this->obtenerActividadesProveedor($proveedor),
            'accionistas' => $this->obtenerAccionistasProveedor($proveedor),
            'apoderado' => $this->obtenerApoderadoProveedor($proveedor),
            'archivos' => $this->obtenerArchivosProveedor($proveedor),
            'constitucion' => $this->obtenerConstitucionProveedor($proveedor),
            'contacto' => $this->obtenerContactoProveedor($proveedor),
        ];
    }

    /**
     * Obtiene historial de trámites de un proveedor
     */
    public function obtenerHistorialTramites(string $rfc): Collection
    {
        return Tramite::whereHas('proveedor', function($query) use ($rfc) {
            $query->where('rfc', $rfc);
        })
        ->where('status', '!=', 'Pendiente')
        ->with(['datosGenerales', 'direcciones', 'actividades', 'accionistas', 'apoderadosLegales', 'archivos', 'contactos', 'datosConstitutivos'])
        ->orderBy('created_at', 'desc')
        ->get();
    }

    /**
     * Obtiene datos de un trámite histórico específico
     */
    public function obtenerDatosTramiteHistorico(int $tramiteId): array
    {
        $tramite = Tramite::with([
            'datosGenerales', 
            'direcciones', 
            'actividades', 
            'accionistas', 
            'apoderadosLegales.instrumentoNotarial.estado', 
            'archivos', 
            'contactos', 
            'datosConstitutivos.instrumentoNotarial.estado'
        ])->findOrFail($tramiteId);

        return $this->obtenerDatosTramite($tramite);
    }

    // Métodos privados para obtener datos específicos de proveedores
    private function obtenerDatosGeneralesProveedor(Proveedor $proveedor): array
    {
        return [
            'razon_social' => $proveedor->razon_social,
            'rfc' => $proveedor->rfc,
            'tipo_persona' => $proveedor->tipo_persona,
            'curp' => $proveedor->curp,
            'pagina_web' => $proveedor->pagina_web,
            'telefono' => $proveedor->telefono,
        ];
    }

    private function obtenerDomicilioProveedor(Proveedor $proveedor): ?array
    {
        $domicilio = $proveedor->direcciones()->latest()->first();
        return $domicilio ? $domicilio->toArray() : null;
    }

    private function obtenerActividadesProveedor(Proveedor $proveedor): Collection
    {
        return $proveedor->actividades()->get();
    }

    private function obtenerAccionistasProveedor(Proveedor $proveedor): Collection
    {
        return $proveedor->accionistas()->get();
    }

    private function obtenerApoderadoProveedor(Proveedor $proveedor): ?array
    {
        $apoderado = $proveedor->apoderadoLegal()->latest()->first();
        return $apoderado ? $apoderado->toArray() : null;
    }

    private function obtenerArchivosProveedor(Proveedor $proveedor): Collection
    {
        return $proveedor->archivos()->with('catalogoArchivo')->get();
    }

    private function obtenerConstitucionProveedor(Proveedor $proveedor): ?array
    {
        $constitucion = $proveedor->datosConstitutivos()->latest()->first();
        return $constitucion ? $constitucion->toArray() : null;
    }

    private function obtenerContactoProveedor(Proveedor $proveedor): ?array
    {
        $contacto = $proveedor->contactos()->latest()->first();
        return $contacto ? $contacto->toArray() : null;
    }
} 