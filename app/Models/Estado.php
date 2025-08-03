<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estado extends Model
{
    protected $table = 'estados';
    
    protected $fillable = [
        'nombre',
        'pais_id'
    ];

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class);
    }

    public function municipios(): HasMany
    {
        return $this->hasMany(Municipio::class);
    }

    public function instrumentosNotariales(): HasMany
    {
        return $this->hasMany(InstrumentoNotarial::class);
    }

    public function direcciones(): HasMany
    {
        return $this->hasMany(Direccion::class);
    }
} 