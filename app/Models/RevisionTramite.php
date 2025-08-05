<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisionTramite extends Model
{
    use HasFactory;

    protected $table = 'revisiones_tramite';

    protected $fillable = [
        'tramite_id',
        'tipo_revision',
        'revisor_id',
        'estado',
        'observaciones',
        'fecha_inicio',
        'fecha_fin',
        'intento'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    // Relaciones
    public function tramite()
    {
        return $this->belongsTo(Tramite::class);
    }

    public function revisor()
    {
        return $this->belongsTo(User::class, 'revisor_id');
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'Pendiente');
    }

    public function scopeEnProceso($query)
    {
        return $query->where('estado', 'En_Proceso');
    }

    public function scopeFinalizadas($query)
    {
        return $query->where('estado', 'Finalizada');
    }

    // Métodos
    public function iniciarRevision()
    {
        $this->update([
            'estado' => 'En_Proceso',
            'fecha_inicio' => now()
        ]);
    }

    public function finalizarRevision($observaciones = null)
    {
        $this->update([
            'estado' => 'Finalizada',
            'fecha_fin' => now(),
            'observaciones' => $observaciones
        ]);
    }

    public function getTipoRevisionLabelAttribute()
    {
        return match($this->tipo_revision) {
            'Digital' => 'Revisión Digital',
            'Presencial' => 'Revisión Presencial',
            'Domiciliaria' => 'Revisión Domiciliaria',
            default => $this->tipo_revision
        };
    }

    public function getEstadoLabelAttribute()
    {
        return match($this->estado) {
            'Pendiente' => 'Pendiente',
            'En_Proceso' => 'En Proceso',
            'Finalizada' => 'Finalizada',
            default => $this->estado
        };
    }
} 