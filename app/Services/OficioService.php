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
            $tramite->load(['proveedor', 'datosGenerales']);

            // Generar URL si no se proporciona
            if (!$url) {
                $url = $this->generarUrlOficio($tramite);
            }

            // Crear el oficio
            $oficio = Oficio::crearOficioConProveedor($tramite, $url);

            Log::info('Oficio generado exitosamente', [
                'oficio_id' => $oficio->id,
                'numero_oficio' => $oficio->numero_oficio,
                'tramite_id' => $tramite->id,
                'proveedor_id' => $tramite->proveedor_id,
                'url' => $url
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
     * Generar URL para el oficio
     */
    private function generarUrlOficio(Tramite $tramite): string
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

        Log::info('URL de oficio generada', [
            'tramite_id' => $tramite->id,
            'url' => $url,
            'nombre_archivo' => $nombreArchivo
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
     * Generar contenido del oficio
     */
    public function generarContenidoOficio(Tramite $tramite): string
    {
        $proveedor = $tramite->proveedor;
        $datosGenerales = $tramite->datosGenerales()->latest()->first();
        
        $razonSocial = $datosGenerales ? $datosGenerales->razon_social : $proveedor->razon_social;
        $rfc = $proveedor->rfc;
        $numeroProveedor = $proveedor->pv_numero;
        $fecha = Carbon::now()->format('d/m/Y');

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
} 