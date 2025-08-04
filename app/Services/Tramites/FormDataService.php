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
            \Log::info('Iniciando guardado de trámite en FormDataService', [
                'tipo_tramite' => $request->tipo_tramite,
                'rfc' => $request->rfc ?: $request->rfc_hidden,
                'has_files' => $request->hasFile('documentos'),
                'files_count' => $request->hasFile('documentos') ? count($request->file('documentos')) : 0,
                'has_accionistas' => $request->has('accionistas'),
                'has_apoderado' => $request->filled('nombre_apoderado'),
                'has_constitucion' => $request->filled('numero_escritura_constitutiva'),
                'all_request_keys' => array_keys($request->all())
            ]);
            
            try {
                // 1. Crear o actualizar proveedor
                \Log::info('Paso 1: Creando/actualizando proveedor');
                $proveedor = $this->crearProveedor($request);
                \Log::info('Proveedor creado/actualizado', ['proveedor_id' => $proveedor->id]);
                
                // 2. Crear trámite
                \Log::info('Paso 2: Creando trámite');
                $tramite = $this->crearTramite($proveedor, $request);
                \Log::info('Trámite creado', ['tramite_id' => $tramite->id]);
                
                // 3. Guardar datos generales
                \Log::info('Paso 3: Guardando datos generales');
                $this->datosGeneralesService->guardar($tramite, $proveedor, $request);
                
                // 4. Guardar contacto
                \Log::info('Paso 4: Guardando contacto');
                $this->contactoService->guardar($tramite, $proveedor, $request);
                
                // 5. Guardar domicilio
                \Log::info('Paso 5: Guardando domicilio');
                $this->domicilioService->guardar($tramite, $proveedor, $request);
                
                // 6. Guardar actividades económicas
                \Log::info('Paso 6: Guardando actividades económicas');
                $this->actividadesService->guardar($tramite, $request);
                
                // 7. Si es persona moral, guardar datos adicionales
                $rfc = $request->rfc ?: $request->rfc_hidden;
                $tipoPersona = $this->rfcService->determinarTipoPersona($rfc);
                
                \Log::info('FormDataService: Tipo de persona determinado', [
                    'tipo_persona' => $tipoPersona,
                    'rfc' => $rfc
                ]);
                
                if ($tipoPersona === 'Moral') {
                    \Log::info('Paso 7: Guardando datos de persona moral');
                    
                    // Guardar constitución - verificar si hay datos de constitución
                    if ($request->filled('numero_escritura_constitutiva') || $request->filled('fecha_constitucion')) {
                        \Log::info('Guardando datos de constitución');
                        $this->constitucionService->guardar($tramite, $proveedor, $request);
                    } else {
                        \Log::info('No hay datos de constitución para guardar');
                    }
                    
                    // Guardar accionistas - verificar si hay accionistas
                    if ($request->has('accionistas') && is_array($request->accionistas) && count($request->accionistas) > 0) {
                        \Log::info('Guardando accionistas', ['count' => count($request->accionistas)]);
                        $this->accionistasService->guardar($tramite, $proveedor, $request);
                    } else {
                        \Log::info('No hay accionistas para guardar');
                    }
                    
                    // Guardar apoderado legal - verificar si hay datos de apoderado
                    if ($request->filled('nombre_apoderado') || $request->filled('rfc_apoderado')) {
                        \Log::info('Guardando apoderado legal');
                        $this->apoderadoService->guardar($tramite, $proveedor, $request);
                    } else {
                        \Log::info('No hay datos de apoderado para guardar');
                    }
                } else {
                    \Log::info('Paso 7: Es persona física, no se guardan datos adicionales');
                }
                
                // 8. Guardar archivos
                \Log::info('Paso 8: Guardando archivos');
                
                // Verificar si hay archivos para guardar
                if ($request->hasFile('documentos') && $request->file('documentos')) {
                    $documentos = $request->file('documentos');
                    $archivosCount = count($documentos);
                    
                    \Log::info('Archivos encontrados para guardar', [
                        'count' => $archivosCount,
                        'files' => array_keys($documentos)
                    ]);
                    
                    $this->archivosService->guardar($tramite, $proveedor, $request);
                } else {
                    \Log::info('No se encontraron archivos para guardar');
                }
                
                \Log::info('Trámite guardado exitosamente');
                return $tramite;
                
            } catch (\Exception $e) {
                \Log::error('Error en FormDataService', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        });
    }

    private function crearProveedor(Request $request): Proveedor
    {
        $rfc = $request->rfc ?: $request->rfc_hidden;
        $tipoTramite = $request->tipo_tramite ?? $this->determinarTipoTramite($request);
        
        // Usar la nueva lógica según el tipo de trámite
        $accion = $this->rfcService->determinarAccionPorTipoTramite($rfc, strtolower($tipoTramite));
        
        \Log::info('FormDataService: Determinando acción para proveedor', [
            'rfc' => $rfc,
            'tipo_tramite' => $tipoTramite,
            'accion' => $accion['accion'],
            'motivo' => $accion['motivo']
        ]);
        
        switch ($accion['accion']) {
            case 'crear_nuevo':
                // Crear un nuevo proveedor
                \Log::info('FormDataService: Creando nuevo proveedor');
                return Proveedor::create([
                    'usuario_id' => auth()->id(),
                    'pv_numero' => $this->rfcService->generarNumeroProveedor($rfc),
                    'rfc' => $rfc,
                    'tipo_persona' => $this->rfcService->determinarTipoPersona($rfc),
                    'estado_padron' => 'pendiente',
                    'fecha_alta_padron' => now(),
                    'fecha_vencimiento_padron' => now()->addYear(),
                ]);
                
            case 'renovar_activo':
            case 'renovar_vencido':
            case 'actualizar_existente':
                // Usar el proveedor existente
                $proveedor = $accion['proveedor_activo'];
                \Log::info('FormDataService: Actualizando proveedor existente', [
                    'proveedor_id' => $proveedor->id
                ]);
                
                $proveedor->update([
                    'tipo_persona' => $this->rfcService->determinarTipoPersona($rfc),
                    'estado_padron' => 'pendiente',
                    'fecha_alta_padron' => now(),
                    'fecha_vencimiento_padron' => now()->addYear(),
                ]);
                
                return $proveedor;
                
            case 'tramite_pendiente':
                // Usar el proveedor con trámite pendiente
                $proveedor = $accion['proveedor_activo'];
                \Log::info('FormDataService: Usando proveedor con trámite pendiente', [
                    'proveedor_id' => $proveedor->id
                ]);
                
                return $proveedor;
                
            default:
                throw new \Exception('No se puede procesar el trámite: ' . $accion['motivo']);
        }
    }

    public function crearTramite(Proveedor $proveedor, Request $request): Tramite
    {
        \Log::info('FormDataService: Creando trámite', [
            'proveedor_id' => $proveedor->id,
            'request_data' => $request->all()
        ]);
        
        // Obtener el tipo de trámite del request o determinar basado en el proveedor
        $tipoTramite = $request->tipo_tramite ?? $this->determinarTipoTramite($request);
        
        \Log::info('FormDataService: Tipo de trámite determinado', [
            'tipo_tramite' => $tipoTramite
        ]);
        
        return Tramite::create([
            'proveedor_id' => $proveedor->id,
            'tipo_tramite' => $tipoTramite,
            'status' => 'Pendiente',
            'fecha_inicio' => now(),
            'correcciones_count' => 0,
            'paso_actual' => 1,
        ]);
    }

    private function determinarTipoTramite(Request $request): string
    {
        // Por ahora, determinar basado en el proveedor existente
        $rfc = $request->rfc ?: $request->rfc_hidden;
        $proveedorExistente = Proveedor::where('usuario_id', auth()->id())
            ->where('rfc', $rfc)
            ->first();
        
        if (!$proveedorExistente) {
            return 'Inscripcion';
        }
        
        // Si el proveedor está vencido, es renovación
        if (!$this->rfcService->proveedorEstaActivo($proveedorExistente)) {
            return 'Renovacion';
        }
        
        // Si tiene trámites pendientes, usar el tipo del trámite pendiente
        $tramitePendiente = $proveedorExistente->tramites()
            ->where('status', 'Pendiente')
            ->first();
            
        if ($tramitePendiente) {
            return $tramitePendiente->tipo_tramite;
        }
        
        // Por defecto, es actualización
        return 'Actualizacion';
    }

    private function esPersonaMoral(string $rfc): bool
    {
        return $this->rfcService->determinarTipoPersona($rfc) === 'Moral';
    }
} 