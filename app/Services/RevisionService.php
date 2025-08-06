<?php

namespace App\Services;

use App\Models\Tramite;
use App\Models\RevisionTramite;
use App\Models\SeccionRevision;
use App\Models\Archivo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RevisionService
{
    /**
     * Procesar revisión digital con evaluación por secciones
     */
    public function procesarRevisionDigital(Tramite $tramite, array $data)
    {
        return DB::transaction(function () use ($tramite, $data) {
            $usuario = Auth::user();
            $tipoRevision = $data['tipo_revision'] ?? 'Digital';
            
            // 1. Crear o actualizar la revisión del trámite
            $revision = $this->crearOActualizarRevision($tramite, $tipoRevision, $usuario);
            
            // 2. Procesar secciones evaluadas
            $estadosSecciones = $this->procesarSecciones($tramite, $data['secciones'] ?? [], $usuario);
            
            // 3. Procesar archivos si hay comentarios/decisiones
            if (isset($data['archivos'])) {
                $this->procesarArchivos($tramite, $data['archivos'], $usuario);
            }
            
            // 4. Determinar estado final del trámite
            $estadoFinal = $this->determinarEstadoFinal($data['decision_final'] ?? null, $estadosSecciones);
            
            // 5. Actualizar trámite
            $this->actualizarTramite($tramite, $estadoFinal, $data['observaciones_generales'] ?? null);
            
            // 6. Finalizar revisión
            $revision->update([
                'estado' => 'Finalizada',
                'fecha_fin' => Carbon::now(),
                'observaciones' => $data['observaciones_generales'] ?? null
            ]);
            
            return [
                'success' => true,
                'revision' => $revision,
                'estado_tramite' => $estadoFinal,
                'message' => 'Revisión procesada exitosamente'
            ];
        });
    }
    
    /**
     * Procesar revisión presencial (enfocada en documentos)
     */
    public function procesarRevisionPresencial(Tramite $tramite, array $data)
    {
        return DB::transaction(function () use ($tramite, $data) {
            $usuario = Auth::user();
            
            // 1. Crear o actualizar la revisión del trámite
            $revision = $this->crearOActualizarRevision($tramite, 'Presencial', $usuario);
            
            // 2. Procesar evaluación de documentos
            if (isset($data['archivos'])) {
                $this->procesarArchivos($tramite, $data['archivos'], $usuario);
            }
            
            // 3. Crear sección especial para documentos presenciales
            SeccionRevision::updateOrCreate(
                [
                    'tramite_id' => $tramite->id,
                    'seccion' => 'documentos_presencial'
                ],
                [
                    'estado' => $data['decision_documentos'] ?? 'Pendiente',
                    'comentario' => $data['comentarios_presencial'] ?? null,
                    'revisado_por' => $usuario->id
                ]
            );
            
            // 4. Determinar estado final
            $estadoFinal = $data['decision'] === 'aprobado' ? 'Aprobado' : 'Rechazado';
            
            // 5. Actualizar trámite
            $this->actualizarTramite($tramite, $estadoFinal, $data['observaciones'] ?? null);
            
            // 6. Finalizar revisión
            $revision->update([
                'estado' => 'Finalizada',
                'fecha_fin' => Carbon::now(),
                'observaciones' => $data['observaciones'] ?? null
            ]);
            
            return [
                'success' => true,
                'revision' => $revision,
                'estado_tramite' => $estadoFinal,
                'message' => 'Revisión presencial procesada exitosamente'
            ];
        });
    }
    
    /**
     * Crear o actualizar revisión del trámite
     */
    private function crearOActualizarRevision(Tramite $tramite, string $tipoRevision, User $usuario)
    {
        return RevisionTramite::updateOrCreate(
            [
                'tramite_id' => $tramite->id,
                'tipo_revision' => $tipoRevision
            ],
            [
                'revisor_id' => $usuario->id,
                'fecha_inicio' => Carbon::now(),
                'estado' => 'En_Proceso'
            ]
        );
    }
    
    /**
     * Procesar secciones de la revisión
     */
    private function procesarSecciones(Tramite $tramite, array $secciones, User $usuario): array
    {
        $estadosSecciones = [];
        
        foreach ($secciones as $nombreSeccion => $datosSeccion) {
            $estado = $datosSeccion['decision'] ?? 'Pendiente';
            $comentario = $datosSeccion['comentario'] ?? null;
            
            SeccionRevision::updateOrCreate(
                [
                    'tramite_id' => $tramite->id,
                    'seccion' => $nombreSeccion
                ],
                [
                    'estado' => $estado,
                    'comentario' => $comentario,
                    'revisado_por' => $usuario->id
                ]
            );
            
            $estadosSecciones[$nombreSeccion] = $estado;
        }
        
        return $estadosSecciones;
    }
    
    /**
     * Procesar archivos con comentarios
     */
    private function procesarArchivos(Tramite $tramite, array $archivos, User $usuario)
    {
        foreach ($archivos as $archivoId => $datosArchivo) {
            $archivo = Archivo::where('id', $archivoId)
                             ->where('tramite_id', $tramite->id)
                             ->first();
            
            if ($archivo) {
                $archivo->update([
                    'status' => $datosArchivo['status'] ?? $archivo->status,
                    'comentario_revision' => $datosArchivo['comentario'] ?? null,
                    'revisado_por' => $usuario->id
                ]);
            }
        }
    }
    
    /**
     * Determinar estado final del trámite
     */
    private function determinarEstadoFinal(?string $decisionFinal, array $estadosSecciones): string
    {
        // Si hay decisión final explícita
        if ($decisionFinal) {
            return match($decisionFinal) {
                'aprobado' => 'Aprobado',
                'rechazado' => 'Rechazado',
                'agendar_cita' => 'Por_Cotejar',
                'correcciones' => 'Para_Correccion',
                default => 'En_Revision'
            };
        }
        
        // Evaluar automáticamente basado en secciones
        $secciones = array_values($estadosSecciones);
        
        if (empty($secciones)) {
            return 'En_Revision';
        }
        
        // Si todas las secciones están aprobadas
        if (array_filter($secciones, fn($estado) => $estado === 'Aprobado') === $secciones) {
            return 'Aprobado';
        }
        
        // Si hay alguna sección rechazada
        if (in_array('Rechazado', $secciones)) {
            return 'Para_Correccion';
        }
        
        // Por defecto, mantener en revisión
        return 'En_Revision';
    }
    
    /**
     * Actualizar el trámite con el nuevo estado
     */
    private function actualizarTramite(Tramite $tramite, string $estado, ?string $observaciones)
    {
        $updateData = [
            'status' => $estado,
            'observaciones' => $observaciones
        ];
        
        // Si se aprueba o rechaza, marcar fecha de finalización
        if (in_array($estado, ['Aprobado', 'Rechazado'])) {
            $updateData['fecha_finalizacion'] = Carbon::now();
        }
        
        $tramite->update($updateData);
    }
    
    /**
     * Obtener resumen de la revisión
     */
    public function obtenerResumenRevision(Tramite $tramite): array
    {
        $secciones = SeccionRevision::where('tramite_id', $tramite->id)->get();
        $archivos = Archivo::where('tramite_id', $tramite->id)
                          ->whereNotNull('comentario_revision')
                          ->get();
        
        return [
            'secciones_evaluadas' => $secciones->count(),
            'secciones_aprobadas' => $secciones->where('estado', 'Aprobado')->count(),
            'secciones_rechazadas' => $secciones->where('estado', 'Rechazado')->count(),
            'archivos_comentados' => $archivos->count(),
            'detalle_secciones' => $secciones->toArray(),
            'detalle_archivos' => $archivos->toArray()
        ];
    }
} 