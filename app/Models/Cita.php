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
        'user_id',
        'fecha_cita',
        'tipo_cita',
        'estado',
        'atendido_por',
        'observaciones',
        'motivo'
    ];

    protected $casts = [
        'fecha_cita' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function atendidoPor()
    {
        return $this->belongsTo(User::class, 'atendido_por');
    }

    public function tramite()
    {
        return $this->belongsTo(Tramite::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }
} 