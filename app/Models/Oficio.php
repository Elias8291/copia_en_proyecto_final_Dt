<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Oficio extends Model
{
    use HasFactory;

    protected $table = 'oficios';

    protected $fillable = [
        'tramite_id',
        'numero_oficio',
        'fecha_oficio',
        'url_documento',
        'descripcion'
    ];

    protected $casts = [
        'fecha_oficio' => 'date',
    ];

    // Relaciones
    public function tramite()
    {
        return $this->belongsTo(Tramite::class);
    }

    // Métodos
    public function getFechaFormateadaAttribute()
    {
        return $this->fecha_oficio->format('d/m/Y');
    }
}
