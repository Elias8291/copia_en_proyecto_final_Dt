<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Archivo extends Model
{
    protected $table = 'archivos';
    
    protected $fillable = [
        'proveedor_id',
        'tramite_id',
        'nombre_original',
        'nombre_archivo',
        'ruta',
        'extension',
        'tamaño',
        'catalogo_archivo_id',
        'status',
        'comentario_revision',
        'observaciones_documento',
        'revisado_por',
        'fecha_revision'
    ];

    protected $casts = [
        'tamaño' => 'integer',
        'fecha_revision' => 'datetime'
    ];

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function tramite(): BelongsTo
    {
        return $this->belongsTo(Tramite::class);
    }

    public function catalogoArchivo(): BelongsTo
    {
        return $this->belongsTo(CatalogoArchivo::class);
    }

    public function revisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'Pendiente' => 'Pendiente',
            'Aprobado' => 'Aprobado',
            'Rechazado' => 'Rechazado',
            default => 'Desconocido'
        };
    }

    public function scopePendientes($query)
    {
        return $query->where('status', 'Pendiente');
    }

    public function scopeAprobados($query)
    {
        return $query->where('status', 'Aprobado');
    }

    public function scopeRechazados($query)
    {
        return $query->where('status', 'Rechazado');
    }
} 