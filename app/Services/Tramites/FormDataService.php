<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\Proveedor;
use App\Services\RfcProveedorService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FormDataService
{
    private RfcProveedorService $rfcService;
    private DatosGeneralesService $datosGeneralesService;
    private DomicilioService $domicilioService;
    private ActividadesService $actividadesService;
    private ConstitucionService $constitucionService;
    private AccionistasService $accionistasService;
    private ApoderadoService $apoderadoService;
    private ArchivosService $archivosService;
    private ContactoService $contactoService;

    public function __construct(
        RfcProveedorService $rfcService,
        DatosGeneralesService $datosGeneralesService,
        DomicilioService $domicilioService,
        ActividadesService $actividadesService,
        ConstitucionService $constitucionService,
        AccionistasService $accionistasService,
        ApoderadoService $apoderadoService,
        ArchivosService $archivosService,
        ContactoService $contactoService
    ) {
        $this->rfcService = $rfcService;
        $this->datosGeneralesService = $datosGeneralesService;
        $this->domicilioService = $domicilioService;
        $this->actividadesService = $actividadesService;
        $this->constitucionService = $constitucionService;
        $this->accionistasService = $accionistasService;
        $this->apoderadoService = $apoderadoService;
        $this->archivosService = $archivosService;
        $this->contactoService = $contactoService;
    }

    public function guardarTramite(Request $request): Tramite
    {
        return DB::transaction(function () use ($request) {
            // 1. Crear o actualizar proveedor
            $proveedor = $this->crearProveedor($request);
            
            // 2. Crear trámite
            $tramite = $this->crearTramite($proveedor, $request);
            
            // 3. Guardar datos generales
            $this->datosGeneralesService->guardar($tramite, $proveedor, $request);
            
            // 4. Guardar contacto
            $this->contactoService->guardar($tramite, $proveedor, $request);
            
            // 5. Guardar domicilio
            $this->domicilioService->guardar($tramite, $proveedor, $request);
            
            // 6. Guardar actividades económicas
            $this->actividadesService->guardar($tramite, $request);
            
            // 7. Si es persona moral, guardar datos adicionales
            if ($this->esPersonaMoral($request->rfc)) {
                $this->constitucionService->guardar($tramite, $proveedor, $request);
                $this->accionistasService->guardar($tramite, $proveedor, $request);
                $this->apoderadoService->guardar($tramite, $proveedor, $request);
            }
            
            // 8. Guardar archivos
            $this->archivosService->guardar($tramite, $proveedor, $request);
            
            return $tramite;
        });
    }

    private function crearProveedor(Request $request): Proveedor
    {
        $rfc = $request->rfc;
        $accion = $this->rfcService->determinarAccion($rfc);
        
        if ($accion['accion'] === 'crear_nuevo') {
            return Proveedor::create([
                'usuario_id' => auth()->id(),
                'pv_numero' => $this->rfcService->generarNumeroProveedor($rfc),
                'rfc' => $rfc,
                'tipo_persona' => $this->rfcService->determinarTipoPersona($rfc),
                'estado_padron' => 'pendiente',
                'fecha_alta_padron' => now(),
                'fecha_vencimiento_padron' => now()->addYear(),
            ]);
        }
        
        // Actualizar proveedor existente
        $proveedor = $accion['proveedor_activo'];
        $proveedor->update([
            'estado_padron' => 'pendiente',
            'fecha_alta_padron' => now(),
            'fecha_vencimiento_padron' => now()->addYear(),
        ]);
        
        return $proveedor;
    }

    private function crearTramite(Proveedor $proveedor, Request $request): Tramite
    {
        return Tramite::create([
            'user_id' => auth()->id(),
            'proveedor_id' => $proveedor->id,
            'constancia_path' => session('constancia_path'),
            'constancia_name' => session('constancia_name'),
            'tipo_tramite' => $this->determinarTipoTramite($proveedor),
            'estado' => 'pendiente',
        ]);
    }

    private function determinarTipoTramite(Proveedor $proveedor): string
    {
        $proveedoresExistentes = $proveedor->tramites()->count();
        
        if ($proveedoresExistentes === 0) {
            return 'inscripcion';
        }
        
        return 'renovacion';
    }

    private function esPersonaMoral(string $rfc): bool
    {
        return $this->rfcService->determinarTipoPersona($rfc) === 'Moral';
    }
} 