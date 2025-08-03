<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contacto extends Model
{
    protected $table = 'contactos';
    
    protected $fillable = [
        'nombre_contacto',
        'cargo',
        'correo_electronico',
        'telefono',
        'tramite_id',
        'proveedor_id',
        'status'
    ];

    public function tramite(): BelongsTo
    {
        return $this->belongsTo(Tramite::class);
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }
} 