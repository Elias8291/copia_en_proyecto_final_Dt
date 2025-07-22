<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $usuario_id
 * @property string|null $pv_numero
 * @property string $rfc
 * @property string $tipo_persona
 * @property string $estado_padron
 * @property \Carbon\Carbon|null $fecha_alta_padron
 * @property \Carbon\Carbon|null $fecha_vencimiento_padron
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
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
        'fecha_alta_padron',
        'fecha_vencimiento_padron'
    ];

    protected $casts = [
        'fecha_alta_padron' => 'date',
        'fecha_vencimiento_padron' => 'date',
        'estado_padron' => 'string',
        'tipo_persona' => 'string',
    ];

    protected $attributes = [
        'estado_padron' => 'Pendiente',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function tramites(): HasMany
    {
        return $this->hasMany(Tramite::class, 'proveedor_id');
    }

    // Scope activos removido - columna es_activo no existe en la tabla actual

    public function scopePorEstado($query, string $estado)
    {
        return $query->where('estado_padron', $estado);
    }

    /**
     * Verifica si el proveedor está activo
     */
    public function estaActivo(): bool
    {
        return $this->estado_padron === 'Activo';
    }

    /**
     * Verifica si el proveedor está vencido
     */
    public function estaVencido(): bool
    {
        return $this->estado_padron === 'Vencido' || 
               ($this->fecha_vencimiento_padron && $this->fecha_vencimiento_padron->isPast());
    }
}
