<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sector extends Model
{
    protected $table = 'sectores';
    
    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion'
    ];

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class);
    }
} 