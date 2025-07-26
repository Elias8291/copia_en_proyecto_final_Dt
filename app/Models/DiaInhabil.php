<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DiaInhabil extends Model
{
    use HasFactory;

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
     * Verificar si una fecha específica es inhábil
     */
    public static function esInhabil(Carbon $fecha): bool
    {
        return self::where('activo', true)
            ->where('fecha_inicio', '<=', $fecha->format('Y-m-d'))
            ->where('fecha_fin', '>=', $fecha->format('Y-m-d'))
            ->exists();
    }

    /**
     * Verificar si una fecha es hábil (no es inhábil y no es fin de semana)
     */
    public static function esHabil(Carbon $fecha): bool
    {
        // Verificar si es fin de semana (sábado = 6, domingo = 0)
        if ($fecha->dayOfWeek === 0 || $fecha->dayOfWeek === 6) {
            return false;
        }

        // Verificar si es día inhábil
        return !self::esInhabil($fecha);
    }

    /**
     * Obtener el próximo día hábil desde una fecha dada
     */
    public static function proximoDiaHabil(Carbon $fecha): Carbon
    {
        $fechaActual = $fecha->copy();

        // Si la fecha actual es hábil, retornarla
        if (self::esHabil($fechaActual)) {
            return $fechaActual;
        }

        // Buscar el próximo día hábil
        do {
            $fechaActual->addDay();
        } while (!self::esHabil($fechaActual));

        return $fechaActual;
    }

    /**
     * Obtener el próximo día hábil con hora específica
     */
    public static function proximoDiaHabilConHora(Carbon $fecha, int $hora = 9, int $minuto = 0): Carbon
    {
        $diaHabil = self::proximoDiaHabil($fecha);
        return $diaHabil->setTime($hora, $minuto, 0);
    }

    /**
     * Obtener todos los días inhábiles activos
     */
    public static function obtenerDiasInhabilesActivos()
    {
        return self::where('activo', true)
            ->orderBy('fecha_inicio')
            ->get();
    }

    /**
     * Scope para días activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para un rango de fechas
     */
    public function scopeEnRango($query, Carbon $fechaInicio, Carbon $fechaFin)
    {
        return $query->where(function ($q) use ($fechaInicio, $fechaFin) {
            $q->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
              ->orWhereBetween('fecha_fin', [$fechaInicio, $fechaFin])
              ->orWhere(function ($subQ) use ($fechaInicio, $fechaFin) {
                  $subQ->where('fecha_inicio', '<=', $fechaInicio)
                       ->where('fecha_fin', '>=', $fechaFin);
              });
        });
    }
} 