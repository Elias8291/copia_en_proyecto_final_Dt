<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DatoGeneral extends Model
{
    protected $table = 'datos_generales';
    
    protected $fillable = [
        'tramite_id',
        'curp',
        'razon_social',
        'pagina_web',
        'telefono',
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