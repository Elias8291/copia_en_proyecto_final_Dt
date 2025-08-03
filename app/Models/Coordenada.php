<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coordenada extends Model
{
    protected $table = 'coordenadas';
    
    protected $fillable = [
        'latitud',
        'longitud'
    ];

    protected $casts = [
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8'
    ];

    public function direcciones(): HasMany
    {
        return $this->hasMany(Direccion::class);
    }
} 