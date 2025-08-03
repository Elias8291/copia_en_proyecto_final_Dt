<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeccionRevision extends Model
{
    protected $table = 'secciones_revision';
    
    protected $fillable = [
        'tramite_id',
        'seccion',
        'estado',
        'comentario',
        'revisado_por'
    ];

    public function tramite(): BelongsTo
    {
        return $this->belongsTo(Tramite::class);
    }

    public function revisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }
} 