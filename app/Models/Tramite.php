<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Solicitante;
use App\Models\DocumentoSolicitante;
use App\Models\SeccionTramite;
use App\Models\RevisionSeccion;

class Tramite extends Model
{
    use HasFactory;

    protected $table = 'tramites';
    
    protected $fillable = [
        'solicitante_id', 
        'tipo', 
        'estado', 
        'fecha_inicio', 
        'fecha_finalizacion', 
        'fecha_limite_correcciones'
    ];

    protected $casts = [
        'tipo' => 'string',
        'estado' => 'string',
        'fecha_inicio' => 'date',
        'fecha_finalizacion' => 'date',
        'fecha_limite_correcciones' => 'date',
    ];

} 