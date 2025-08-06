<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeccionRevision extends Model
{
    use HasFactory;

    protected $table = 'secciones_revision';
    
    protected $fillable = [
        'tramite_id',
        'seccion',
        'estado',
        'comentario',
        'revisado_por'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Obtener el trámite al que pertenece esta sección
     */
    public function tramite(): BelongsTo
    {
        return $this->belongsTo(Tramite::class);
    }

    /**
     * Obtener el usuario que revisó esta sección
     */
    public function revisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    /**
     * Obtener el label del estado
     */
    public function getEstadoLabelAttribute(): string
    {
        return match($this->estado) {
            'Pendiente' => 'Pendiente',
            'Aprobado' => 'Aprobado',
            'Rechazado' => 'Rechazado',
            default => 'Desconocido'
        };
    }

    /**
     * Obtener el label de la sección
     */
    public function getSeccionLabelAttribute(): string
    {
        return match($this->seccion) {
            'datos_generales' => 'Datos Generales',
            'actividades' => 'Actividades Económicas',
            'domicilio' => 'Domicilio',
            'constitucion' => 'Constitución',
            'accionistas' => 'Accionistas',
            'apoderado' => 'Apoderado Legal',
            'archivos' => 'Archivos',
            'documentos' => 'Documentos',
            'documentos_presencial' => 'Documentos Presencial',
            default => ucfirst(str_replace('_', ' ', $this->seccion))
        };
    }

    /**
     * Scope para filtrar por trámite
     */
    public function scopeDelTramite($query, $tramiteId)
    {
        return $query->where('tramite_id', $tramiteId);
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeConEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }
} 