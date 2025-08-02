<?php

namespace App\Services\Core;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

/**
 * Servicio base para respuestas HTTP reutilizables
 * Responsabilidad: Formateo consistente de respuestas entre controladores
 */
class BaseResponseService
{
    /**
     * Crea respuesta de éxito estandarizada
     */
    public function respuestaExito(
        array $datos, 
        string $mensaje = 'Operación exitosa',
        string $titulo = 'Éxito',
        ?string $redirect = null
    ): JsonResponse|RedirectResponse {
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $mensaje,
                'data' => $datos
            ]);
        }

        $response = redirect($redirect ?? back())->with([
            'success' => true,
            'success_title' => $titulo,
            'success_message' => $mensaje,
            'success_accept_text' => 'Aceptar'
        ]);

        if ($redirect) {
            $response->with('success_redirect', $redirect);
        }

        return $response;
    }

    /**
     * Crea respuesta de error estandarizada
     */
    public function respuestaError(
        string $mensaje, 
        string $titulo = 'Error',
        int $codigo = 400,
        ?array $datos = null
    ): JsonResponse|RedirectResponse {
        if (request()->ajax() || request()->wantsJson()) {
            $response = [
                'success' => false,
                'message' => $mensaje
            ];

            if ($datos) {
                $response['data'] = $datos;
            }

            return response()->json($response, $codigo);
        }

        return redirect()->back()->with([
            'error' => true,
            'error_title' => $titulo,
            'error_message' => $mensaje,
            'error_button_text' => 'Entendido'
        ]);
    }

    /**
     * Respuesta específica para operaciones de documentos
     */
    public function respuestaDocumento(array $resultado): JsonResponse
    {
        $statusCode = $resultado['success'] ? 200 : 500;
        return response()->json($resultado, $statusCode);
    }

    /**
     * Respuesta específica para cambios de estado
     */
    public function respuestaCambioEstado(
        array $resultado, 
        string $rutaExito = null,
        string $rutaError = null
    ): JsonResponse|RedirectResponse {
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($resultado);
        }

        if ($resultado['success']) {
            return redirect($rutaExito ?? back())->with([
                'success' => true,
                'success_title' => $resultado['titulo'] ?? 'Éxito',
                'success_message' => $resultado['mensaje'] ?? 'Operación exitosa',
                'success_accept_text' => 'Ir al listado',
                'success_redirect' => $rutaExito
            ]);
        } else {
            return redirect($rutaError ?? back())->with([
                'error' => true,
                'error_title' => $resultado['titulo'] ?? 'Error',
                'error_message' => $resultado['mensaje'] ?? 'Error en la operación',
                'error_button_text' => 'Entendido'
            ]);
        }
    }

    /**
     * Respuesta para operaciones de citas
     */
    public function respuestaCita(array $resultado): JsonResponse|RedirectResponse
    {
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($resultado);
        }

        if ($resultado['success']) {
            return redirect()->back()->with('success', $resultado['mensaje']);
        } else {
            return redirect()->back()->with('error', $resultado['mensaje']);
        }
    }

    /**
     * Respuesta para aprobación de trámites
     */
    public function respuestaAprobacion(array $resultado, string $rutaRedirect = null): JsonResponse|RedirectResponse
    {
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $resultado['message'],
                'pv_asignado' => $resultado['pv_asignado'] ?? null,
                'fecha_vencimiento' => $resultado['fecha_vencimiento'] ?? null,
                'numero_oficio' => $resultado['numero_oficio'] ?? null
            ]);
        }

        $mensajeExito = $resultado['message'];
        if ($resultado['pv_asignado'] ?? false) {
            $mensajeExito .= "\nPV Asignado: {$resultado['pv_asignado']}";
        }
        if ($resultado['fecha_vencimiento'] ?? false) {
            $mensajeExito .= "\nFecha de vencimiento: {$resultado['fecha_vencimiento']}";
        }
        if (isset($resultado['numero_oficio'])) {
            $mensajeExito .= "\nNúmero de Oficio: {$resultado['numero_oficio']}";
        }
        $mensajeExito .= "\n\nSe ha enviado una notificación al usuario.";

        return redirect($rutaRedirect ?? route('revision.index'))
            ->with('success', $mensajeExito)
            ->with('success_title', 'Trámite Aprobado')
            ->with('success_message', $mensajeExito)
            ->with('success_redirect', $rutaRedirect ?? route('revision.index'))
            ->with('success_accept_text', 'Aceptar');
    }

    /**
     * Respuesta para operaciones con paginación
     */
    public function respuestaPaginada(
        $items, 
        array $datosAdicionales = [],
        string $vista = null
    ): array|\Illuminate\View\View {
        $datos = array_merge([
            'items' => $items,
            'total' => method_exists($items, 'total') ? $items->total() : $items->count(),
            'per_page' => method_exists($items, 'perPage') ? $items->perPage() : null,
            'current_page' => method_exists($items, 'currentPage') ? $items->currentPage() : 1
        ], $datosAdicionales);

        if ($vista) {
            return view($vista, $datos);
        }

        return $datos;
    }

    /**
     * Log de respuesta para debugging
     */
    public function logRespuesta(string $operacion, array $resultado, array $contexto = []): void
    {
        $nivel = $resultado['success'] ? 'info' : 'error';
        
        Log::$nivel("Respuesta de {$operacion}", array_merge([
            'success' => $resultado['success'],
            'mensaje' => $resultado['mensaje'] ?? $resultado['message'] ?? 'Sin mensaje',
            'timestamp' => now()
        ], $contexto));
    }

    /**
     * Respuesta para formularios con validación
     */
    public function respuestaFormulario(
        array $resultado,
        string $rutaExito = null,
        bool $mantenerInput = true
    ): JsonResponse|RedirectResponse {
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($resultado);
        }

        if ($resultado['success']) {
            $response = redirect($rutaExito ?? $resultado['redirect'] ?? route('tramites.exito'));
            
            if (isset($resultado['tramite_id'])) {
                $response->with('tramite_id', $resultado['tramite_id']);
            }
            
            return $response->with('success', $resultado['message']);
        } else {
            $response = redirect()->back();
            
            if ($mantenerInput) {
                $response->withInput();
            }
            
            return $response->with('error', $resultado['message']);
        }
    }
}