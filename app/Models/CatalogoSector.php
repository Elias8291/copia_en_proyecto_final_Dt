<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CatalogoSector extends Model
{
    protected $table = 'catalogo_sectores';

    protected $fillable = [
        'nombre',
    ];

    public function actividades(): HasMany
    {
        return $this->hasMany(CatalogoActividad::class, 'sector_id');
    }
}
