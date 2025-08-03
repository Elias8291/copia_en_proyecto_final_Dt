<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cita extends Model
{
    protected $table = 'citas';
    
    protected $fillable = [
        'tramite_id',
        'tipo_cita',
        'fecha_cita',
        'estado',
        'intento',
        'asignado_a'
    ];

    protected $casts = [
        'fecha_cita' => 'datetime',
        'intento' => 'integer'
    ];

    public function tramite(): BelongsTo
    {
        return $this->belongsTo(Tramite::class);
    }

    public function asignadoA(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asignado_a');
    }
} 