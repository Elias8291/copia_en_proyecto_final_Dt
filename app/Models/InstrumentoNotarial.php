<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstrumentoNotarial extends Model
{
    protected $table = 'instrumentos_notariales';
    
    protected $fillable = [
        'numero_escritura',
        'numero_escritura_constitutiva',
        'fecha_constitucion',
        'nombre_notario',
        'estado_id',
        'numero_notario',
        'numero_registro_publico',
        'fecha_inscripcion'
    ];

    protected $casts = [
        'fecha_constitucion' => 'date',
        'fecha_inscripcion' => 'date'
    ];

    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }

    public function apoderadosLegales(): HasMany
    {
        return $this->hasMany(ApoderadoLegal::class);
    }

    public function datosConstitutivos(): HasMany
    {
        return $this->hasMany(DatoConstitutivo::class);
    }
} 