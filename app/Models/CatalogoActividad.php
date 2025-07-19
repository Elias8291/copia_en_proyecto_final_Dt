<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatalogoActividad extends Model
{
    protected $table = 'catalogo_actividades';

    protected $fillable = [
        'sector_id',
        'codigo_scian',
        'nombre',
        'estado',
        'creada_por_usuario_id',
    ];

    protected $casts = [
        'estado' => 'string',
    ];

    public function sector(): BelongsTo
    {
        return $this->belongsTo(CatalogoSector::class, 'sector_id');
    }

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creada_por_usuario_id');
    }

    public function scopeAprobadas($query)
    {
        return $query->where('estado', 'Aprobada');
    }

    public function scopeBuscarPorNombre($query, string $termino)
    {
        return $query->where('nombre', 'LIKE', '%' . $termino . '%');
    }
} 