<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Direccion extends Model
{
    protected $table = 'direcciones';
    
    protected $fillable = [
        'proveedor_id',
        'tramite_id',
        'calle',
        'numero_exterior',
        'numero_interior',
        'colonia',
        'codigo_postal',
        'municipio',
        'asentamiento',
        'coordenada_id',
        'estado_id',
        'status'
    ];

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function tramite(): BelongsTo
    {
        return $this->belongsTo(Tramite::class);
    }

    public function coordenada(): BelongsTo
    {
        return $this->belongsTo(Coordenada::class);
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }
} 