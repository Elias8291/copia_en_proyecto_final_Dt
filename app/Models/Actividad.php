<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Actividad extends Model
{
    protected $table = 'actividad';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'sector_id'
    ];

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(ActividadProveedor::class);
    }
} 