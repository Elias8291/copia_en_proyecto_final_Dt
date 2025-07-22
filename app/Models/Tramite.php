<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tramite extends Model
{
    use HasFactory;

    protected $fillable = [
        'proveedor_id',
        'tipo_tramite',
        'estado',
        'fecha_inicio',
        'fecha_finalizacion',
        'observaciones',
        'procesado_por',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_finalizacion' => 'datetime',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function procesadoPor()
    {
        return $this->belongsTo(User::class, 'procesado_por');
    }

    public function datosProveedor()
    {
        return $this->hasOne(DatosProveedor::class);
    }
}
