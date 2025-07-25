<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisionSeccion extends Model
{
    use HasFactory;

    protected $table = 'revision_secciones';

    protected $fillable = [
        'tramite_id',
        'seccion',
        'comentario',
        'aprobado',
        'user_id'
    ];

    protected $casts = [
        'aprobado' => 'boolean'
    ];

    public function tramite()
    {
        return $this->belongsTo(Tramite::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getEstadoTextoAttribute()
    {
        if (is_null($this->aprobado)) {
            return 'Pendiente';
        }
        return $this->aprobado ? 'Aprobado' : 'Rechazado';
    }
}