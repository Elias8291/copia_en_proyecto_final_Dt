<?php

namespace App\Services\Revision;

use App\Models\Archivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Servicio especializado para la gestión de documentos en revisiones
 * Responsabilidad: Manejar estados, comentarios y operaciones de documentos
 */
class RevisionDocumentosService
{
    /**
     * Actualiza el comentario (observaciones) de un documento
     */
    public function actualizarComentario(int $archivoId, ?string $comentario): array
    {
        try {
            $archivo = Archivo::findOrFail($archivoId);
            $archivo->observaciones = $comentario;
            $archivo->save();

            return [
                'success' => true,
                'message' => 'Comentario actualizado correctamente.',
                'comentario' => $archivo->observaciones,
            ];
        } catch (\Exception $e) {
            Log::error('Error al actualizar comentario de documento', [
                'archivo_id' => $archivoId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error al actualizar el comentario: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Actualiza el estado (aprobado) de un documento
     */
    public function actualizarEstado(int $archivoId, bool $aprobado): array
    {
        try {
            $archivo = Archivo::findOrFail($archivoId);
            $archivo->aprobado = $aprobado;
            $archivo->save();

            return [
                'success' => true,
                'message' => 'Estado actualizado correctamente.',
                'aprobado' => $archivo->aprobado,
            ];
        } catch (\Exception $e) {
            Log::error('Error al actualizar estado de documento', [
                'archivo_id' => $archivoId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error al actualizar el estado: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Actualiza comentario y estado de un documento en una sola operación
     */
    public function actualizarDocumentoCompleto(int $archivoId, ?string $comentario, bool $aprobado): array
    {
        try {
            $archivo = Archivo::findOrFail($archivoId);
            
            $archivo->update([
                'observaciones' => $comentario,
                'aprobado' => $aprobado,
                'cotejado_por' => Auth::id(),
                'fecha_cotejo' => now()
            ]);

            return [
                'success' => true,
                'message' => 'Documento actualizado correctamente.',
                'data' => [
                    'comentario' => $archivo->observaciones,
                    'aprobado' => $archivo->aprobado,
                    'fecha_cotejo' => $archivo->fecha_cotejo
                ]
            ];
            
        } catch (\Exception $e) {
            Log::error('Error al actualizar documento completo', [
                'archivo_id' => $archivoId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error al actualizar el documento: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtiene el estado y comentarios de un documento
     */
    public function obtenerEstadoDocumento(int $archivoId): array
    {
        try {
            $archivo = Archivo::findOrFail($archivoId);
            
            return [
                'success' => true,
                'data' => [
                    'aprobado' => $archivo->aprobado,
                    'observaciones' => $archivo->observaciones,
                    'fecha_cotejo' => $archivo->fecha_cotejo,
                    'cotejado_por' => $archivo->cotejado_por
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error al obtener estado de documento', [
                'archivo_id' => $archivoId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error al obtener el estado del documento: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verifica si un documento existe y devuelve su ruta para visualización
     */
    public function obtenerRutaDocumento(int $tramiteId, int $archivoId): ?string
    {
        try {
            $archivo = \App\Models\Tramite::findOrFail($tramiteId)
                ->archivos()
                ->where('id', $archivoId)
                ->firstOrFail();

            $rutas = [
                storage_path('app/' . $archivo->ruta_archivo),
                storage_path('app/public/' . $archivo->ruta_archivo)
            ];

            foreach ($rutas as $ruta) {
                if (file_exists($ruta)) {
                    return $ruta;
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error al obtener ruta de documento', [
                'tramite_id' => $tramiteId,
                'archivo_id' => $archivoId,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * Obtiene información completa de un documento para visualización
     */
    public function obtenerInformacionDocumento(int $archivoId): ?array
    {
        try {
            $archivo = Archivo::with('catalogoArchivo')->findOrFail($archivoId);

            return [
                'id' => $archivo->id,
                'nombre_original' => $archivo->nombre_original,
                'ruta_archivo' => $archivo->ruta_archivo,
                'aprobado' => $archivo->aprobado,
                'observaciones' => $archivo->observaciones,
                'fecha_cotejo' => $archivo->fecha_cotejo,
                'cotejado_por' => $archivo->cotejado_por,
                'catalogo' => $archivo->catalogoArchivo ? [
                    'nombre' => $archivo->catalogoArchivo->nombre,
                    'descripcion' => $archivo->catalogoArchivo->descripcion,
                    'obligatorio' => $archivo->catalogoArchivo->obligatorio
                ] : null
            ];
        } catch (\Exception $e) {
            Log::error('Error al obtener información de documento', [
                'archivo_id' => $archivoId,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * Obtiene resumen de estados de documentos de un trámite
     */
    public function obtenerResumenEstadosDocumentos(\App\Models\Tramite $tramite): array
    {
        $tramite->load('archivos.catalogoArchivo');
        
        $pendientes = [];
        $aprobados = [];
        $rechazados = [];
        
        foreach ($tramite->archivos as $archivo) {
            $nombreDocumento = $archivo->catalogoArchivo->nombre ?? 'Documento';
            
            if ($archivo->aprobado === null) {
                $pendientes[] = $nombreDocumento;
            } elseif ($archivo->aprobado === true) {
                $aprobados[] = $nombreDocumento;
            } else {
                $rechazados[] = $nombreDocumento;
            }
        }
        
        return [
            'pendientes' => $pendientes,
            'aprobados' => $aprobados,
            'rechazados' => $rechazados,
            'total' => $tramite->archivos->count(),
            'porcentaje_aprobados' => $tramite->archivos->count() > 0 
                ? round((count($aprobados) / $tramite->archivos->count()) * 100, 2) 
                : 0
        ];
    }
}