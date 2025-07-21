<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    // Nombre de la tabla
    protected $table = 'sectores';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
    ];
}
