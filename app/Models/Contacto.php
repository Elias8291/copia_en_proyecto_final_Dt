<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    use HasFactory;

    protected $table = 'contactos';

    protected $fillable = [
        'nombre_contacto',
        'cargo',
        'correo_electronico',
        'telefono',
        'tramite_id',
    ];

    public function tramite()
    {
        return $this->belongsTo(Tramite::class);
    }
} 