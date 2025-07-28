<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DiaInhabil extends Model
{
    protected $table = 'dias_inhabiles';
    
    protected $fillable = [
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'tipo',
        'observaciones',
        'activo'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activo' => 'boolean'
    ];

    /**
     * Verificar si una fecha es hábil
     */
    public static function esHabil(Carbon $fecha): bool
    {
        // Verificar si es sábado o domingo
        if ($fecha->isWeekend()) {
            return false;
        }

        // Verificar si está en días inhábiles
        $diaInhabil = self::where('activo', true)
            ->where('fecha_inicio', '<=', $fecha->format('Y-m-d'))
            ->where('fecha_fin', '>=', $fecha->format('Y-m-d'))
            ->exists();

        return !$diaInhabil;
    }

    /**
     * Obtener el próximo día hábil
     */
    public static function proximoDiaHabil(Carbon $fecha): Carbon
    {
        $diaHabil = $fecha->copy();
        
        while (!self::esHabil($diaHabil)) {
            $diaHabil->addDay();
        }
        
        return $diaHabil;
    }
} 