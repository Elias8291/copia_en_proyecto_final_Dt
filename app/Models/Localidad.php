<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Localidad extends Model
{
    protected $table = 'localidades';
    
    protected $fillable = [
        'nombre',
        'municipio_id'
    ];

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }
} 