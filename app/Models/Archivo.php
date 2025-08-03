<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Archivo extends Model
{
    protected $table = 'archivos';
    
    protected $fillable = [
        'proveedor_id',
        'tramite_id',
        'nombre_original',
        'nombre_archivo',
        'ruta',
        'extension',
        'tamaño',
        'catalogo_archivo_id',
        'status'
    ];

    protected $casts = [
        'tamaño' => 'integer'
    ];

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function tramite(): BelongsTo
    {
        return $this->belongsTo(Tramite::class);
    }

    public function catalogoArchivo(): BelongsTo
    {
        return $this->belongsTo(CatalogoArchivo::class);
    }
} 