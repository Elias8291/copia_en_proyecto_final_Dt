<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CatalogoArchivo extends Model
{
    protected $table = 'catalogo_archivos';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo_persona',
        'tipo_archivo',
        'es_visible'
    ];

    protected $casts = [
        'es_visible' => 'boolean'
    ];

    public function archivos(): HasMany
    {
        return $this->hasMany(Archivo::class);
    }
} 