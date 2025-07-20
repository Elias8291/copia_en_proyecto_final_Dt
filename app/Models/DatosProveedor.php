<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosProveedor extends Model
{
    use HasFactory;

    protected $table = 'datos_proveedores';

    protected $fillable = [
        'tramite_id',
        'pv',
        'rfc',
        'curp',
        'razon_social',
        'pagina_web',
        'telefono'
    ];

    public function tramite()
    {
        return $this->belongsTo(Tramite::class);
    }
}