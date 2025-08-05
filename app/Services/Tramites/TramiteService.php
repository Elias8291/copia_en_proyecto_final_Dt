<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\Tramites\ContactoService;
use App\Services\Tramites\ConstitucionService;

class TramiteService
{
    private DatosGeneralesService $datosGeneralesService;
    private DomicilioService $domicilioService;
    private ActividadesService $actividadesService;
    private AccionistasService $accionistasService;
    private ApoderadoService $apoderadoService;
    private ArchivosService $archivosService;
    private ConstanciaService $constanciaService;
    private ContactoService $contactoService;
    private ConstitucionService $constitucionService;

    public function __construct(
        DatosGeneralesService $datosGeneralesService,
        DomicilioService $domicilioService,
        ActividadesService $actividadesService,
        AccionistasService $accionistasService,
        ApoderadoService $apoderadoService,
        ArchivosService $archivosService,
        ConstanciaService $constanciaService,
        ContactoService $contactoService,
        ConstitucionService $constitucionService
    ) {
        $this->datosGeneralesService = $datosGeneralesService;
        $this->domicilioService = $domicilioService;
        $this->actividadesService = $actividadesService;
        $this->accionistasService = $accionistasService;
        $this->apoderadoService = $apoderadoService;
        $this->archivosService = $archivosService;
        $this->constanciaService = $constanciaService;
        $this->contactoService = $contactoService;
        $this->constitucionService = $constitucionService;
    }

    public function crearTramiteCompleto(Request $request): Tramite
    {
        return DB::transaction(function () use ($request) {
            Log::info('TramiteService: Iniciando creación de trámite completo', [
                'user_id' => auth()->id(),
                'tipo_tramite' => $request->tipo_tramite
            ]);

            // 1. Crear o obtener proveedor
            $proveedor = $this->crearObtenerProveedor($request);
            
            // 2. Crear trámite base
            $tramite = $this->crearTramiteBase($proveedor, $request);
            
            // 3. Guardar secciones del trámite
            $this->guardarSecciones($tramite, $proveedor, $request);

            Log::info('TramiteService: Trámite creado exitosamente', [
                'tramite_id' => $tramite->id,
                'proveedor_id' => $proveedor->id
            ]);

            return $tramite;
        });
    }

    private function crearObtenerProveedor(Request $request): Proveedor
    {
        $rfc = $request->rfc ?: $request->rfc_hidden;
        $tipoPersona = $request->tipo_persona ?: $request->tipo_persona_hidden;

        $proveedor = Proveedor::where('usuario_id', auth()->id())
            ->where('rfc', $rfc)
            ->first();

        if (!$proveedor) {
            $proveedor = Proveedor::create([
                'usuario_id' => auth()->id(),
                'pv_numero' => 'PV-' . time(),
                'rfc' => $rfc,
                'tipo_persona' => $tipoPersona,
                'estado_padron' => 'pendiente',
                'fecha_alta_padron' => now(),
                'fecha_vencimiento_padron' => now()->addYear(),
            ]);
        }

        return $proveedor;
    }

    private function crearTramiteBase(Proveedor $proveedor, Request $request): Tramite
    {
        return Tramite::create([
            'proveedor_id' => $proveedor->id,
            'tipo_tramite' => $request->tipo_tramite ?? 'Inscripcion',
            'status' => 'Pendiente',
            'fecha_inicio' => now(),
            'correcciones_count' => 0,
            'paso_actual' => 1,
        ]);
    }

    private function guardarSecciones(Tramite $tramite, Proveedor $proveedor, Request $request): void
    {
        // Datos generales (siempre se guardan)
        $this->datosGeneralesService->guardar($tramite, $proveedor, $request);
        $this->contactoService->guardar($tramite, $proveedor, $request);
        
        // Domicilio (siempre se guarda)
        $this->domicilioService->guardar($tramite, $proveedor, $request);
        
        // Actividades económicas (siempre se guardan)
        $this->actividadesService->guardar($tramite, $request);
        
        // Solo para personas morales
        if ($proveedor->tipo_persona === 'Moral') {
            // Constitución (siempre requerida para personas morales)
            $this->constitucionService->guardar($tramite, $proveedor, $request);
            
            if ($request->filled('accionistas')) {
                $this->accionistasService->guardar($tramite, $proveedor, $request);
            }
            
            if ($request->filled('nombre_apoderado')) {
                $this->apoderadoService->guardar($tramite, $proveedor, $request);
            }
        }
        
        // Archivos (siempre se procesan)
        if ($request->hasFile('documentos')) {
            $this->archivosService->guardar($tramite, $proveedor, $request);
        }
    }

    public function verificarConstanciaCargada(): bool
    {
        return $this->constanciaService->verificarCargada();
    }

    public function obtenerDatosConstancia(): array
    {
        return $this->constanciaService->obtenerDatos();
    }
} 