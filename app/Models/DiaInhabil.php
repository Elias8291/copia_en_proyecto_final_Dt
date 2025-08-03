<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaInhabil extends Model
{
    protected $table = 'dias_inhabiles';
    
    protected $fillable = [
        'fecha',
        'descripcion',
        'es_fijo'
    ];

    protected $casts = [
        'fecha' => 'date',
        'es_fijo' => 'boolean'
    ];
} 