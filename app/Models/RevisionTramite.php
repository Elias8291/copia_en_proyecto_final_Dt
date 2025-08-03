<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevisionTramite extends Model
{
    protected $table = 'revisiones_tramite';
    
    protected $fillable = [
        'tramite_id',
        'tipo_revision',
        'revisor_id',
        'estado',
        'observaciones',
        'fecha_inicio',
        'fecha_fin',
        'intento'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'intento' => 'integer'
    ];

    public function tramite(): BelongsTo
    {
        return $this->belongsTo(Tramite::class);
    }

    public function revisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revisor_id');
    }
} 