<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sector extends Model
{
    use HasFactory;

    protected $table = 'sectores';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
    ];

    /**
     * Relación: Actividades económicas del sector
     */
    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class, 'sector_id');
    }

 
   
}
