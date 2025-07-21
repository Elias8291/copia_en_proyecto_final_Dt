<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActividadEconomica extends Model
{
    use HasFactory;

    protected $table = 'actividades_economicas';

    protected $fillable = [
        'sector_id',
        'nombre',
        'codigo_scian',
        'descripcion',
        'fuente',
        'estado_validacion',
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }
}
