<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $fillable = [
        'usuario_id',
        'pv_numero',
        'rfc',
        'tipo_persona',
        'estado_padron',
        'es_activo',
        'fecha_alta_padron',
        'fecha_vencimiento_padron',
        'observaciones',
        'razon_social'
    ];

    protected $casts = [
        'fecha_alta_padron' => 'date',
        'fecha_vencimiento_padron' => 'date',
        'es_activo' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('es_activo', true);
    }
}
