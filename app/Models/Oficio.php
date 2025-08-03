<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Oficio extends Model
{
    protected $table = 'oficios';
    
    protected $fillable = [
        'numero_oficio',
        'fecha_oficio',
        'contenido',
        'tramite_id'
    ];

    protected $casts = [
        'fecha_oficio' => 'date'
    ];

    public function tramite(): BelongsTo
    {
        return $this->belongsTo(Tramite::class);
    }
} 