<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RevisionController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $allowedPerPage = [5, 10, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $tramites = Tramite::with(['proveedor', 'revisadoPor', 'datosGenerales'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $tramites->appends($request->query());

        return view('revision.index', compact('tramites', 'perPage'));
    }

    public function show(Tramite $tramite)
    {
        $tramite->load([
            'proveedor',
            'revisadoPor',
            'datosGenerales',
            'datosConstitutivos',
            'apoderadoLegal',
            'contactos',
            'accionistas',
            'actividades.actividad',
            'archivos.catalogoArchivo'
        ]);

        return view('revision.show', compact('tramite'));
    }

    public function asignar(Request $request, Tramite $tramite)
    {
        $request->validate([
            'revisor_id' => 'required|exists:users,id'
        ]);

        try {
            $tramite->update([
                'revisado_por' => $request->revisor_id,
                'estado' => 'En_Revision'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Revisor asignado exitosamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar revisor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cambiarEstado(Request $request, Tramite $tramite)
    {
        $request->validate([
            'estado' => 'required|in:Pendiente,En_Revision,Aprobado,Rechazado,Por_Cotejar,Para_Correccion,Cancelado',
            'observaciones' => 'nullable|string|max:1000'
        ]);

        try {
            $updateData = [
                'estado' => $request->estado,
                'revisado_por' => Auth::id()
            ];

            if ($request->filled('observaciones')) {
                $updateData['observaciones'] = $request->observaciones;
            }

            // Si se aprueba o rechaza, marcar fecha de finalización
            if (in_array($request->estado, ['Aprobado', 'Rechazado', 'Cancelado'])) {
                $updateData['fecha_finalizacion'] = now();
            }

            $tramite->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado exitosamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado: ' . $e->getMessage()
            ], 500);
        }
    }

    public function pendientes()
    {
        $tramites = Tramite::with(['proveedor', 'datosGenerales'])
            ->where('estado', 'Pendiente')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('revision.pendientes', compact('tramites'));
    }

    public function enRevision()
    {
        $tramites = Tramite::with(['proveedor', 'revisadoPor', 'datosGenerales'])
            ->where('estado', 'En_Revision')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('revision.en-revision', compact('tramites'));
    }

    public function misRevisiones()
    {
        $tramites = Tramite::with(['proveedor', 'datosGenerales'])
            ->where('revisado_por', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('revision.mis-revisiones', compact('tramites'));
    }



    public function revisarDatos(Tramite $tramite)
    {
        try {
            // Cargar todas las relaciones necesarias del trámite
            $tramite->load([
                'proveedor',
                'revisadoPor',
                'datosGenerales',
                'datosConstitutivos',
                'apoderadoLegal',
                'contactos',
                'accionistas',
                'direcciones.estado',
                'actividades',
                'archivos.catalogoArchivo'
            ]);

            return view('revision.revisar-datos', compact('tramite'));
        } catch (\Exception $e) {
            Log::error('Error al cargar datos del trámite para revisión', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Error al cargar los datos del trámite: ' . $e->getMessage());
        }
    }

    public function servirDocumento($tramiteId, $archivoId, $filename)
    {
        try {
            // Buscar el trámite
            $tramite = Tramite::findOrFail($tramiteId);
            
            // Buscar el archivo específico del trámite
            $archivo = $tramite->archivos()->where('id', $archivoId)->first();
            
            if (!$archivo) {
                Log::error('Archivo no encontrado en BD', [
                    'tramite_id' => $tramiteId,
                    'archivo_id' => $archivoId
                ]);
                abort(404, 'Documento no encontrado');
            }

            // Construir posibles rutas del archivo
            $rutasPosibles = [
                storage_path('app/' . $archivo->ruta_archivo),
                storage_path('app/public/' . $archivo->ruta_archivo),
                public_path('storage/' . $archivo->ruta_archivo),
                public_path($archivo->ruta_archivo)
            ];
            
            $rutaCompleta = null;
            foreach ($rutasPosibles as $ruta) {
                if (file_exists($ruta)) {
                    $rutaCompleta = $ruta;
                    break;
                }
            }
            
            if (!$rutaCompleta) {
                Log::error('Archivo físico no encontrado en ninguna ubicación', [
                    'tramite_id' => $tramiteId,
                    'archivo_id' => $archivoId,
                    'ruta_bd' => $archivo->ruta_archivo,
                    'rutas_verificadas' => $rutasPosibles
                ]);
                abort(404, 'Archivo físico no encontrado');
            }

            // Obtener el tipo MIME del archivo
            $mimeType = mime_content_type($rutaCompleta) ?: 'application/octet-stream';
            
            // Retornar el archivo como respuesta
            return response()->file($rutaCompleta, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . ($archivo->nombre_original ?: $filename) . '"'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al servir documento', [
                'tramite_id' => $tramiteId,
                'archivo_id' => $archivoId,
                'filename' => $filename,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Error interno del servidor: ' . $e->getMessage()], 500);
        }
    }

    public function mostrarDocumento($archivoId)
    {
        try {
            // Buscar el archivo
            $archivo = \App\Models\Archivo::findOrFail($archivoId);
            
            // Verificar que el usuario tenga acceso al trámite
            $tramite = $archivo->tramite;
            if (!$tramite) {
                abort(404, 'Trámite no encontrado');
            }

            // Construir posibles rutas del archivo
            $rutasPosibles = [
                storage_path('app/' . $archivo->ruta_archivo),
                storage_path('app/public/' . $archivo->ruta_archivo),
                public_path('storage/' . $archivo->ruta_archivo),
                public_path($archivo->ruta_archivo)
            ];
            
            $rutaCompleta = null;
            foreach ($rutasPosibles as $ruta) {
                if (file_exists($ruta)) {
                    $rutaCompleta = $ruta;
                    break;
                }
            }
            
            if (!$rutaCompleta) {
                Log::error('Archivo físico no encontrado', [
                    'archivo_id' => $archivoId,
                    'ruta_bd' => $archivo->ruta_archivo,
                    'rutas_verificadas' => $rutasPosibles
                ]);
                abort(404, 'Archivo físico no encontrado');
            }

            // Obtener el tipo MIME del archivo
            $mimeType = mime_content_type($rutaCompleta) ?: 'application/octet-stream';
            
            // Retornar el archivo como respuesta
            return response()->file($rutaCompleta, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $archivo->nombre_original . '"'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al mostrar documento', [
                'archivo_id' => $archivoId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Error interno del servidor: ' . $e->getMessage()], 500);
        }
    }

    public function descargarDocumento($archivoId)
    {
        try {
            // Buscar el archivo
            $archivo = \App\Models\Archivo::findOrFail($archivoId);
            
            // Verificar que el usuario tenga acceso al trámite
            $tramite = $archivo->tramite;
            if (!$tramite) {
                abort(404, 'Trámite no encontrado');
            }

            // Construir posibles rutas del archivo
            $rutasPosibles = [
                storage_path('app/' . $archivo->ruta_archivo),
                storage_path('app/public/' . $archivo->ruta_archivo),
                public_path('storage/' . $archivo->ruta_archivo),
                public_path($archivo->ruta_archivo)
            ];
            
            $rutaCompleta = null;
            foreach ($rutasPosibles as $ruta) {
                if (file_exists($ruta)) {
                    $rutaCompleta = $ruta;
                    break;
                }
            }
            
            if (!$rutaCompleta) {
                Log::error('Archivo físico no encontrado para descarga', [
                    'archivo_id' => $archivoId,
                    'ruta_bd' => $archivo->ruta_archivo,
                    'rutas_verificadas' => $rutasPosibles
                ]);
                abort(404, 'Archivo físico no encontrado');
            }

            // Retornar el archivo como descarga
            return response()->download($rutaCompleta, $archivo->nombre_original);

        } catch (\Exception $e) {
            Log::error('Error al descargar documento', [
                'archivo_id' => $archivoId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Error interno del servidor: ' . $e->getMessage()], 500);
        }
    }

    public function toggleApproval(Request $request, $archivoId)
    {
        try {
            // Buscar el archivo
            $archivo = \App\Models\Archivo::findOrFail($archivoId);
            
            // Verificar que el usuario tenga acceso al trámite
            $tramite = $archivo->tramite;
            if (!$tramite) {
                return response()->json(['success' => false, 'message' => 'Trámite no encontrado'], 404);
            }

            // Actualizar el estado de aprobación
            $archivo->update([
                'aprobado' => $request->boolean('approved'),
                'revisado_por' => Auth::id(),
                'fecha_revision' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Estado de aprobación actualizado exitosamente',
                'approved' => $archivo->aprobado
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado de aprobación', [
                'archivo_id' => $archivoId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateDocumentStatus(Request $request, $archivoId)
    {
        try {
            $request->validate([
                'status' => 'required|in:aprobado,rechazado,pendiente',
                'comment' => 'nullable|string|max:1000'
            ]);

            // Buscar el archivo
            $archivo = \App\Models\Archivo::findOrFail($archivoId);
            
            // Verificar que el usuario tenga acceso al trámite
            $tramite = $archivo->tramite;
            if (!$tramite) {
                return response()->json(['success' => false, 'message' => 'Trámite no encontrado'], 404);
            }

            // Actualizar el estado del documento
            $updateData = [
                'aprobado' => $request->status === 'aprobado',
                'revisado_por' => Auth::id(),
                'fecha_revision' => now()
            ];

            if ($request->filled('comment')) {
                $updateData['observaciones'] = $request->comment;
            }

            $archivo->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Estado del documento actualizado exitosamente',
                'status' => $request->status
            ]);

        } catch (\Exception $e) {
            Log::error('Error al actualizar estado del documento', [
                'archivo_id' => $archivoId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addDocumentComment(Request $request, $archivoId)
    {
        try {
            $request->validate([
                'comment' => 'required|string|max:1000',
                'decision' => 'required|in:aprobar,rechazar,pendiente'
            ]);

            // Buscar el archivo
            $archivo = \App\Models\Archivo::findOrFail($archivoId);
            
            // Verificar que el usuario tenga acceso al trámite
            $tramite = $archivo->tramite;
            if (!$tramite) {
                return response()->json(['success' => false, 'message' => 'Trámite no encontrado'], 404);
            }

            // Obtener comentarios existentes
            $comentarios = json_decode($archivo->comentarios_revision ?? '[]', true);
            
            // Agregar nuevo comentario
            $nuevoComentario = [
                'usuario' => Auth::user()->name,
                'comentario' => $request->comment,
                'decision' => $request->decision,
                'fecha' => now()->format('d/m/Y H:i'),
                'usuario_id' => Auth::id()
            ];
            
            $comentarios[] = $nuevoComentario;
            
            // Actualizar el archivo con el nuevo comentario
            $updateData = [
                'comentarios_revision' => json_encode($comentarios),
                'revisado_por' => Auth::id(),
                'fecha_revision' => now()
            ];

            // Si hay una decisión, actualizar también el estado
            if ($request->decision !== 'pendiente') {
                $updateData['aprobado'] = $request->decision === 'aprobar';
                if ($request->decision !== 'pendiente') {
                    $updateData['observaciones'] = $request->comment;
                }
            }

            $archivo->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Comentario agregado exitosamente',
                'usuario' => Auth::user()->name,
                'comment' => $nuevoComentario
            ]);

        } catch (\Exception $e) {
            Log::error('Error al agregar comentario al documento', [
                'archivo_id' => $archivoId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }
}
