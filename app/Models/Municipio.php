<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Municipio extends Model
{
    protected $table = 'municipios';
    
    protected $fillable = [
        'nombre',
        'estado_id'
    ];

    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }

    public function localidades(): HasMany
    {
        return $this->hasMany(Localidad::class);
    }
} 