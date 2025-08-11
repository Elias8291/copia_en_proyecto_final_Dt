<?php

namespace App\Services\Tramites;

use App\Models\Proveedor;
use App\Models\RevisionTramite;
use App\Models\Tramite;
use App\Models\User;
use App\Services\Tramites\ConstitucionService;
use App\Services\Tramites\ContactoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $startTime = microtime(true);
        
        return DB::transaction(function () use ($request, $startTime) {
            Log::info('TramiteService: Iniciando creación de trámite completo', [
                'user_id' => auth()->id(),
                'tipo_tramite' => $request->tipo_tramite
            ]);

            // 1. Crear o obtener proveedor (operación rápida)
            $proveedor = $this->crearObtenerProveedor($request);
            $proveedorTime = microtime(true) - $startTime;

            // 2. Crear trámite base (operación rápida)
            $tramite = $this->crearTramiteBase($proveedor, $request);
            $tramiteTime = microtime(true) - $startTime;

            // 3. Crear revisión digital automática (operación rápida)
            $this->crearRevisionDigitalAutomatica($tramite);
            $revisionTime = microtime(true) - $startTime;

            // 4. Guardar secciones del trámite (optimizado)
            $this->guardarSeccionesUltraOptimizado($tramite, $proveedor, $request);
            $seccionesTime = microtime(true) - $startTime;

            Log::info('TramiteService: Trámite creado exitosamente', [
                'tramite_id' => $tramite->id,
                'proveedor_id' => $proveedor->id,
                'tiempos' => [
                    'proveedor' => round($proveedorTime, 3),
                    'tramite' => round($tramiteTime, 3),
                    'revision' => round($revisionTime, 3),
                    'secciones' => round($seccionesTime, 3),
                    'total' => round($seccionesTime, 3)
                ]
            ]);

            return $tramite;
        });
    }

    private function crearObtenerProveedor(Request $request): Proveedor
    {
        $rfc = $request->rfc ?: $request->rfc_hidden ?: $request->rfc_fallback;
        $tipoPersona = $request->tipo_persona ?: $request->tipo_persona_hidden ?: $request->tipo_persona_fallback;
        $tipoTramite = $request->tipo_tramite ?? 'Inscripcion';

        Log::info('TramiteService: Creando/obteniendo proveedor', [
            'rfc' => $rfc,
            'tipo_persona' => $tipoPersona,
            'tipo_tramite' => $tipoTramite,
            'user_id' => auth()->id()
        ]);

        // Usar el RfcProveedorService para gestionar el proveedor según el tipo de trámite
        $rfcProveedorService = app(\App\Services\RfcProveedorService::class);
        
        $datosProveedor = [
            'tipo_persona' => $tipoPersona,
            'usuario_id' => auth()->id(),
            'razon_social' => $request->razon_social ?: $request->razon_social_hidden ?: $request->razon_social_fallback,
            'nombre' => $request->nombre ?: $request->nombre_hidden ?: $request->nombre_fallback,
            'apellido_paterno' => $request->apellido_paterno ?: $request->apellido_paterno_hidden ?: $request->apellido_paterno_fallback,
            'apellido_materno' => $request->apellido_materno ?: $request->apellido_materno_hidden ?: $request->apellido_materno_fallback,
            'curp' => $request->curp ?: $request->curp_hidden ?: $request->curp_fallback,
            'email' => $request->email ?: $request->email_hidden ?: $request->email_fallback,
            'telefono' => $request->telefono ?: $request->telefono_hidden ?: $request->telefono_fallback,
        ];

        try {
            $resultadoGestion = $rfcProveedorService->gestionarProveedorPorTramite($rfc, $tipoTramite, $datosProveedor);
            
            Log::info('TramiteService: Proveedor gestionado exitosamente', [
                'accion' => $resultadoGestion['accion'],
                'proveedor_id' => $resultadoGestion['proveedor']->id,
                'numero_proveedor' => $resultadoGestion['numero_proveedor'],
                'fecha_registro' => $resultadoGestion['fecha_registro'],
                'fecha_vencimiento' => $resultadoGestion['fecha_vencimiento']
            ]);

            return $resultadoGestion['proveedor'];
            
        } catch (\Exception $e) {
            Log::error('TramiteService: Error al gestionar proveedor', [
                'rfc' => $rfc,
                'tipo_tramite' => $tipoTramite,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    private function crearRevisionDigitalAutomatica(Tramite $tramite): void
    {
        // Buscar un revisor digital disponible
        $revisorDigital = User::role('Revisor Digital')->first();

        if (!$revisorDigital) {
            Log::warning('TramiteService: No se encontró revisor digital disponible', [
                'tramite_id' => $tramite->id
            ]);
            // Si no hay revisor digital, crear la revisión sin revisor asignado
            // (se asignará cuando un revisor digital inicie la revisión)
            $revisorDigital = null;
        }

        // Crear el registro de revisión digital
        RevisionTramite::create([
            'tramite_id' => $tramite->id,
            'tipo_revision' => 'Digital',
            'revisor_id' => $revisorDigital ? $revisorDigital->id : null,
            'estado' => 'Pendiente',
            'fecha_inicio' => now(),
            'intento' => 1
        ]);

        Log::info('TramiteService: Revisión digital creada automáticamente', [
            'tramite_id' => $tramite->id,
            'revisor_id' => $revisorDigital ? $revisorDigital->id : null
        ]);
    }

    private function crearTramiteBase(Proveedor $proveedor, Request $request): Tramite
    {
        return Tramite::create([
            'proveedor_id' => $proveedor->id,
            'tipo_tramite' => $request->tipo_tramite ?? 'Inscripcion',
            'status' => 'Revision_Digital',
            'fecha_inicio' => now(),
            'correcciones_count' => 0,
            'paso_actual' => 1,
        ]);
    }

    private function guardarSeccionesUltraOptimizado(Tramite $tramite, Proveedor $proveedor, Request $request): void
    {
        Log::info('TramiteService: Iniciando guardado ultra optimizado de secciones', [
            'tramite_id' => $tramite->id,
            'tipo_persona' => $proveedor->tipo_persona
        ]);

        // Procesar archivos primero (operación más lenta) para evitar bloqueos
        if ($request->hasFile('documentos')) {
            Log::info('TramiteService: Procesando archivos', ['tramite_id' => $tramite->id]);
            $this->archivosService->guardar($tramite, $proveedor, $request);
        }

        // Procesar todos los datos básicos en una sola operación
        $this->procesarDatosBasicosUltraOptimizado($tramite, $proveedor, $request);

        // Procesar datos específicos según tipo de persona
        if ($proveedor->tipo_persona === 'Moral') {
            $this->procesarDatosMoralesUltraOptimizado($tramite, $proveedor, $request);
        }

        Log::info('TramiteService: Secciones guardadas exitosamente', [
            'tramite_id' => $tramite->id
        ]);
    }

    private function procesarDatosBasicosUltraOptimizado(Tramite $tramite, Proveedor $proveedor, Request $request): void
    {
        // Procesar datos básicos de manera ultra eficiente
        $this->datosGeneralesService->guardar($tramite, $proveedor, $request);
        $this->contactoService->guardar($tramite, $proveedor, $request);
        $this->domicilioService->guardar($tramite, $proveedor, $request);
        $this->actividadesService->guardar($tramite, $request);
    }

    private function procesarDatosMoralesUltraOptimizado(Tramite $tramite, Proveedor $proveedor, Request $request): void
    {
        // Constitución (siempre requerida para personas morales)
        $this->constitucionService->guardar($tramite, $proveedor, $request);

        // Procesar accionistas solo si están presentes
        if ($request->filled('accionistas')) {
            $this->accionistasService->guardar($tramite, $proveedor, $request);
        }

        // Procesar apoderado solo si está presente
        if ($request->filled('nombre_apoderado')) {
            $this->apoderadoService->guardar($tramite, $proveedor, $request);
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

    /**
     * Actualizar un trámite existente con correcciones
     */
    public function actualizarTramite(Tramite $tramite, Request $request): Tramite
    {
        return DB::transaction(function () use ($tramite, $request) {
            Log::info('TramiteService: Iniciando actualización de trámite', [
                'tramite_id' => $tramite->id,
                'user_id' => auth()->id()
            ]);

            // 1. Actualizar datos generales usando el servicio específico
            $this->datosGeneralesService->actualizar($tramite, $request);

            // 2. Actualizar domicilio usando el servicio específico
            $this->domicilioService->actualizar($tramite, $request);

            // 3. Actualizar actividades económicas usando el servicio específico
            $this->actividadesService->actualizar($tramite, $request);

            // 4. Actualizar datos específicos según tipo de persona
            if ($tramite->proveedor->tipo_persona === 'Moral') {
                $this->constitucionService->actualizar($tramite, $request);
                $this->accionistasService->actualizar($tramite, $request);
                $this->apoderadoService->actualizar($tramite, $request);
            }

            // 5. Actualizar archivos si se proporcionaron nuevos
            if ($request->hasFile('archivos')) {
                $this->archivosService->actualizar($tramite, $request);
            }

            // 6. Cambiar estado del trámite a pendiente para nueva revisión
            $tramite->update([
                'status' => 'Pendiente',
                'observaciones' => null,  // Limpiar observaciones anteriores
                'correcciones_count' => $tramite->correcciones_count + 1
            ]);

            Log::info('TramiteService: Trámite actualizado exitosamente', [
                'tramite_id' => $tramite->id,
                'correcciones_count' => $tramite->correcciones_count
            ]);

            return $tramite->fresh();
        });
    }
}
