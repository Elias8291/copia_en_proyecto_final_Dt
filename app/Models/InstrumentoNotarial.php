<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstrumentoNotarial extends Model
{
    use HasFactory;

    protected $table = 'instrumentos_notariales';

    protected $fillable = [
        'numero_escritura',
        'numero_escritura_constitutiva',
        'fecha_constitucion',
        'nombre_notario',
        'entidad_federativa',
        'numero_notario',
        'numero_registro_publico',
        'fecha_inscripcion',
    ];

    protected $casts = [
        'fecha_constitucion' => 'date',
        'fecha_inscripcion' => 'date',
    ];

    public function datosConstitutivos()
    {
        return $this->hasMany(DatosConstitutivos::class);
    }

    public function apoderadosLegales()
    {
        return $this->hasMany(ApoderadoLegal::class);
    }
} 