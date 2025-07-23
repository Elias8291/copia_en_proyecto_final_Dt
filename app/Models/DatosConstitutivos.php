<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosConstitutivos extends Model
{
    use HasFactory;

    protected $table = 'datos_constitutivos';

    protected $fillable = [
        'instrumento_notarial_id',
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