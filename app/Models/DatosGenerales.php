<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosGenerales extends Model
{
    use HasFactory;

    protected $table = 'datos_generales';

    protected $fillable = [
        'tramite_id',
        'rfc',
        'curp',
        'razon_social',
        'pagina_web',
        'telefono',
    ];

    public function tramite()
    {
        return $this->belongsTo(Tramite::class);
    }
} 