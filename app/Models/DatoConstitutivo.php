<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DatoConstitutivo extends Model
{
    protected $table = 'datos_constitutivos';
    
    protected $fillable = [
        'instrumento_notarial_id',
        'proveedor_id',
        'tramite_id',
        'status'
    ];

    public function instrumentoNotarial(): BelongsTo
    {
        return $this->belongsTo(InstrumentoNotarial::class);
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function tramite(): BelongsTo
    {
        return $this->belongsTo(Tramite::class);
    }
} 