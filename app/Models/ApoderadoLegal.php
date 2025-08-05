<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApoderadoLegal extends Model
{
    protected $table = 'apoderado_legal';
    
    protected $fillable = [
        'instrumento_notarial_id',
        'nombre_apoderado',
        'rfc',
        'numero_escritura_constitutiva_poder',
        'numero_registro_publico_poder',
        'fecha_inscripcion_poder',
        'tramite_id',
        'proveedor_id',
        'status'
    ];

    public function instrumentoNotarial(): BelongsTo
    {
        return $this->belongsTo(InstrumentoNotarial::class);
    }

    public function tramite(): BelongsTo
    {
        return $this->belongsTo(Tramite::class);
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }
} 