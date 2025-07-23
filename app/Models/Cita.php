<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'tramite_id',
        'proveedor_id',
        'fecha_cita',
        'tipo_cita',
        'estado',
        'atendido_por'
    ];

    protected $casts = [
        'fecha_cita' => 'datetime',
    ];

    // Relaciones
    public function tramite()
    {
        return $this->belongsTo(Tramite::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function atendidoPor()
    {
        return $this->belongsTo(User::class, 'atendido_por');
    }

    // Scopes para filtros
    public function scopeTipoCita($query, $tipo)
    {
        return $query->where('tipo_cita', $tipo);
    }

    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    public function scopeFechaEntre($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_cita', [$fechaInicio, $fechaFin]);
    }
}