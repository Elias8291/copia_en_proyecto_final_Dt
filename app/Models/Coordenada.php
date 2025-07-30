<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coordenada extends Model
{
    use HasFactory;

    protected $table = 'coordenadas';

    protected $fillable = [
        'latitud',
        'longitud',
    ];

    protected $casts = [
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
    ];

    /**
     * Relación con direcciones
     */
    public function direcciones(): HasMany
    {
        return $this->hasMany(Direccion::class, 'coordenadas_id');
    }

    /**
     * Obtener coordenadas formateadas
     */
    public function getCoordenadasFormateadasAttribute(): string
    {
        return "{$this->latitud}, {$this->longitud}";
    }

    /**
     * Verificar si las coordenadas son válidas
     */
    public function sonValidas(): bool
    {
        return $this->latitud >= -90 && $this->latitud <= 90 &&
               $this->longitud >= -180 && $this->longitud <= 180;
    }
} 