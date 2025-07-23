<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApoderadoLegal extends Model
{
    use HasFactory;

    protected $table = 'apoderado_legal';

    protected $fillable = [
        'instrumento_notarial_id',
        'nombre_apoderado',
        'rfc',
        'tramite_id',
    ];

    public function instrumentoNotarial()
    {
        return $this->belongsTo(InstrumentoNotarial::class);
    }

    public function tramite()
    {
        return $this->belongsTo(Tramite::class);
    }
} 