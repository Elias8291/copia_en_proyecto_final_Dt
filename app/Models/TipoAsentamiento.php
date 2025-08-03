<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoAsentamiento extends Model
{
    protected $table = 'tipos_asentamiento';
    
    protected $fillable = [
        'nombre'
    ];

    public function asentamientos(): HasMany
    {
        return $this->hasMany(Asentamiento::class);
    }
} 