<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asentamiento extends Model
{
    protected $table = 'asentamientos';
    
    protected $fillable = [
        'nombre',
        'tipo_asentamiento_id'
    ];

    public function tipoAsentamiento(): BelongsTo
    {
        return $this->belongsTo(TipoAsentamiento::class);
    }
} 